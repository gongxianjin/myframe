<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/28
 * Time: 14:02
 */

namespace app\Sadmin\controllers;


use core\lib\conf;

use core\lib\model;

use Sadmin\models\chargerecordModel;
use Sadmin\models\configModel;
use Sadmin\models\chargeerrlogModel;

class MobileChargeController extends BaseadminController
{
    public function StartCronTab($secret = ''){
        //加密处理
        $arg = array(
            'company'=>'成都先讯物联网技术有限公司',
            'author'=>'victor'
        );
        $arg = join($arg);
        $str = md5($arg);
        $str = sha1($str);
        //str = '9cf4ca39ca6544c374b3924984b1266f6b4430e0';
        if($secret != $str){
            echo '非法入口';
            return;
        }else{
            $conf = conf::get('OPTION','log');
            $lockpath = $conf['MLOCKTAB'];
            if(file_exists($lockpath."lock.txt")){
                unlink($lockpath."lock.txt");
            } 
            $this->SynchronizedCharge();
        }
    }

    public function StopCronTab($secret = ''){
        //加密处理
        $arg = array(
            'company'=>'成都先讯物联网技术有限公司',
            'author'=>'victor'
        );
        $arg = join($arg);
        $str = md5($arg);
        $str = sha1($str);
        if($secret != $str){
            echo '非法入口';
            return;
        }
        $conf = conf::get('OPTION','log');
        $lockpath = $conf['MLOCKTAB'];
        if(!is_dir($lockpath)){
            if (!mkdir($lockpath,'0777',true)) {//0777
                die('Failed to create folders...');
            }
        }
        if(file_put_contents($lockpath."lock.txt", "This is a lock file.")){
            die('定时任务停止成功');
        }else{
            die('定时任务停止失败');
        }
    }

    public function Cronlog($message,$file='cron'){
        /*
         * 1 确定一下文件的存储位置
         * 新建目录
         * 2 写入日志
         * */
        //文件记录该定时任务是否在执行
        $conf = conf::get('OPTION','log');
        $cronpath = $conf['MCRONTAB']; 
        if(!is_dir($cronpath)){
            if (!mkdir($cronpath,'0777',true)) {//0777
                die('Failed to create folders...');
            }
        }
        // 写文件操作开始
//        $fp = fopen($this->path.'/'.$file.'.txt', "w");
//        if($fp)
//        {
//            $flag=fwrite($fp,date('Y-m-d H:i:s').json_encode($message).PHP_EOL);
//            if(!$flag)
//            {
//                echo "写入文件失败";
//            }
//        }
//        fclose($fp);
//        // 写文件操作结束
//        return $flag;  
        return file_put_contents($cronpath.$file.'.php',date('Y-m-d H:i:s').PHP_EOL.$message.PHP_EOL,FILE_APPEND);
    }


    public function excuteHandCharge($chargeRecordId){
        try{
            $ErrlogFrom = new chargeerrlogModel();
            if(empty($chargeRecordId)){
                $data['errlog_time'] = time();
                $data['errlog_code'] = 203;
                $data['errlog_info'] = '充值记录标示不能为空';
                $ErrlogFrom->add($data);
                throw new \Exception('错误：充值记录标示不能为空');
                return false;
            }
            $crmodel = new chargerecordModel();
            $res = $crmodel->getOne($chargeRecordId);
            if(empty($res)){
                $data['errlog_time'] = time();
                $data['errlog_code'] = 203;
                $data['errlog_info'] = '不合法的充值';
                $ErrlogFrom->add($data);
                throw new \Exception('错误：不合法的充值');
                return false;
            }
            if(!empty($res['laststate'])){
                if($res['laststate'] == 0){
                    $data['errlog_time'] = time();
                    $data['errlog_code'] = 203;
                    $data['errlog_info'] = "充值记录[".$chargeRecordId."]已充值成功，不用重复充值";
                    $ErrlogFrom->add($data);
                    throw new \Exception("充值记录[".$chargeRecordId."]已充值成功，不用重复充值");
                    return false;
                }
            }
            if($this->doRecordCharge($chargeRecordId,false)){
                $this->success('手动充值完成');
            }else{
                $this->error('手动充值失败');
            }
        }catch (\Exception $e){
            $e->getMessage();
        }
    }


    public function doRecordCharge($chargeRecordId,$isAuto){
        try{
            $ErrlogFrom = new chargeerrlogModel();
            if(empty($chargeRecordId)){
                $data['errlog_time'] = time();
                $data['errlog_code'] = 204;
                $data['errlog_info'] = "充值记录标示不能为空";
                $ErrlogFrom->add($data);
                throw new \Exception('错误：充值记录标示不能为空');
                return false;
            }
            $crmodel = new chargerecordModel();
            $res = $crmodel->getOne($chargeRecordId); 
            if(empty($res)){
                $data['errlog_time'] = time();
                $data['errlog_code'] = 204;
                $data['errlog_info'] = "不合法的充值";
                throw new \Exception('错误：不合法的充值');
                return false;
            }
            if(!empty($res['laststate'])  && $res['laststate'] == 0){
                $data['errlog_time'] = time();
                $data['errlog_code'] = 204;
                $data['errlog_info'] = "充值记录[".$chargeRecordId."]已充值成功，不用重复充值";
                $ErrlogFrom->add($data);
                throw new \Exception("充值记录[".$chargeRecordId."]已充值成功，不用重复充值");
                return false;
            }
            //判断总充值上限
            $configForm = new configModel();
            $chargeMaxLimit = $configForm->getOne('mobile_charge_max_limit');
            if($chargeMaxLimit['value']>0){
                $where["AND"]['type'] = 2;
                $where["AND"]['laststate'] =  array(0);
                $column = 'point';
                $pointSum = $crmodel->getcolumnSum($column,$where);
                if($pointSum>=$chargeMaxLimit['value']){
                    $data['errlog_time'] = time();
                    $data['errlog_code'] = 204;
                    $data['errlog_info'] = "充值数量".$pointSum."超过上限".$chargeMaxLimit['value']."，进行充值拦截。";
                    $ErrlogFrom->add($data);
                    throw new \Exception("充值数量".$pointSum."超过上限".$chargeMaxLimit['value']."，进行充值拦截。");
                    return false;
                }
            }
            //判断每日充值上限
            $chargeDayMaxLimit = $configForm->getOne('mobile_charge_day_max_limit');
            if($chargeDayMaxLimit['value']>0){
                $where["AND"]['type'] = 2;
                $where["AND"]['laststate'] =  array(0);
                $day_start = mktime(0,0,0)*1000;
                $day_end = get_next_day_time()*1000;
                $where["AND"]['createtime[<>]'] = array($day_start,$day_end);
                $column = 'point';
                $pointDaySum = $crmodel->getcolumnSum($column,$where);
                if($pointDaySum >= $chargeDayMaxLimit['value']){
                    $data['errlog_time'] = time();
                    $data['errlog_code'] = 204;
                    $data['errlog_info'] = "每日充值数量".$pointDaySum."超过上限".$chargeDayMaxLimit['value']."，进行充值拦截。";
                    $ErrlogFrom->add($data);
                    throw new \Exception("每日充值数量".$pointDaySum."超过上限".$chargeDayMaxLimit['value']."，进行充值拦截。");
                    return false;
                }
            }
            //判断每日充值上限
            $chargeHourMaxLimit = $configForm->getOne('mobile_charge_hour_max_limit');
            if($chargeHourMaxLimit['value']>0){
                $where["AND"]['type'] = 2;
                $where["AND"]['laststate'] =  array(0);
                $hour_start = get_next_hour_time(1)*1000;
                $hour_end = get_next_hour_time(0)*1000;
                $where["AND"]['createtime[<>]'] = array($hour_start,$hour_end);
                $column = 'point';
                $pointHourSum = $crmodel->getcolumnSum($column,$where);
                if($pointHourSum >= $chargeHourMaxLimit['value']){
                    $data['errlog_time'] = time();
                    $data['errlog_code'] = 204;
                    $data['errlog_info'] = "每小时充值数量".$pointHourSum."超过上限".$chargeHourMaxLimit['value']."，进行充值拦截。";
                    $ErrlogFrom->add($data);
                    throw new \Exception("每小时充值数量".$pointHourSum."超过上限".$chargeHourMaxLimit['value']."，进行充值拦截。");
                    return false;
                }
            }
            //95_sczysmhdpt_161201 sczysm
            $response = \api\TobaccoMobile::Syncharge($res['phone'],$res['point'],$chargeRecordId,'');
            $response = json_decode($response,true);
            $comment = $isAuto?'自动充值':'手动充值';
            if($response){
                $res = array(
                    'activitykey'=>'95_zhongyan_fangweiyanzhen',
                    'activityname'=>'sczysm',
                    'laststate'=>$response['code'],
                    'laststatedesc'=>$response['msg'],
                    'lastchargetime'=>isset($response['data']['createdAt'])?$response['data']['createdAt']:'',
                    'respoints'=>isset($response['data']['amount'])?$response['data']['amount']:'',
                    'resmobile'=>isset($response['data']['mobile'])?$response['data']['mobile']:'',
                    'restime'=>getMillisecond(),
                    'flag'=>'0',
                    'comment'=>$comment
                );
                //返回值更新
                if($crmodel->setOne($chargeRecordId,$res)){
                    return true;
                }else{
                    return false;
                }
            }
        }catch (\Exception $e){
            $e->getMessage();
        }
    }

    public function SynchronizedCharge(){
        $conf = conf::get('OPTION','log');
        $lockpath = $conf['MLOCKTAB'];
        ignore_user_abort(TRUE);// 设定关闭浏览器也执行程序
        set_time_limit(0);      // 设定响应时间不限制，默认为30秒
        $interval = 10;          // 每间隔*秒运行
        $runBatchCount = 0;
        $runEachCount = 0;
        $start = 0;
        $size = 100; //每次查询的条数
        $messege = '';
        while(true)
        {
            // 设定定时任务终止条件
            if (file_exists($lockpath.'lock.txt'))
            {
                break;
            }
            //记录正确后执行crontab
            $messege .= '开始等待...'.PHP_EOL;
            sleep($interval);           // 每5秒钟执行一次
            $messege .= '开始处理下一批次...'.PHP_EOL;
            //从充值记录表中查出一条未充值的记录出来
            $crmodel = new chargerecordModel();
            $data = array(
                'AND'=>array(
                    'flag'=>array(-1),
                    'type'=>array(3)
                ),
                'ORDER'=>'id',
                'LIMIT'=>array($start,$size)
            );
            $charge = $crmodel->getlists($data); 
            if(empty($charge)){
                $messege = '';
                continue;
            }
            if($charge){
                //对用接口对其自动充值
                foreach($charge as $item){
                    $this->doRecordCharge($item['id'],true);
                    $runEachCount++;
                }
                $runBatchCount++;
            }
            $messege .= "已处理第".$runBatchCount."批次，共".$runEachCount."条数据";
            if(!$this->Cronlog($messege)){
			    die('Failed to create file...');
            }
        }
    }
}