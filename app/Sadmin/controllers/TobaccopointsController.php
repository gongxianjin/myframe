<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use Sadmin\models\chargerecordModel;
use Sadmin\models\configModel;
use core\lib\page;
use core\lib\Excel_XML;

class TobaccopointsController extends BaseadminController
{
    public function index($pages = '',$tpstate='',$starttime='',$endtime='',$tel=''){
        $p = empty($pages) ? 1 : $pages;
        $tpstate = empty($tpstate)?0:$tpstate;
        switch($tpstate){
            case 1:
                $data["AND"]['laststate'] = array(-1,-2);
                break;
            case 2:
                $data["AND"]['laststate'] = array(1,2);
                break;
            case 3:
                $data["AND"]['laststate'] = 5;
                break;
            case 4:
                $data["AND"]['laststate'] = array(3,4,6,7,9);
                break;
            case 5:
                $data["AND"]['laststate'] = 8;
                break;
            default:
                break;
        }
        $data["AND"]['type'] = 2;
        if($starttime != null && $endtime != null){
            $starttime_t = strtotime($starttime).'000';
            $endtime_t = strtotime($endtime).'000';
            $data["AND"]['createtime[<>]'] =  array($starttime_t,$endtime_t);
        }
        if($tel != null){
            $data["AND"]['phone'] = $tel;
        }
        //筛选 分页
        $chargerecord = new chargerecordModel();
        // 获取信息总数
        $count = $chargerecord->getCount($data);
        // 每页显示的记录数
        $listPage = 8;
        // 起始页
        $firstRow = $listPage*($p-1);
        // 分页地址
        $url = __SADMIN__.'/Tobaccopoints/index';
        if($tpstate){
            $url.= '/tpstate/'.$tpstate;
        }
        if($starttime && $endtime){
            $url.= '/starttime/'.$starttime.'/endtime/'.$endtime;
        }
        if($tel){
            $url.= '/tel/'.$tel;
        }
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $data['LIMIT']= array($firstRow,$listPage);
        $lists = $chargerecord->getlists($data);
        foreach($lists as $key=>$item){
            switch($item['laststate']){
                case -2:
                    $lists[$key]['state'] = '同号未充值';
                    break;
                case -1:
                    $lists[$key]['state'] = '未开始充值';
                    break;
                case 1:
                    $lists[$key]['state'] = '充值成功';
                    break;
                case 2:
                    $lists[$key]['state'] = '充值成功(未注册)';
                    break;
                case 3:
                    $lists[$key]['state'] = '手机号不正确';
                    break;
                case 4:
                    $lists[$key]['state'] = '充值数量不正确';
                    break;
                case 5:
                    $lists[$key]['state'] = '接口内部错误';
                    break;
                case 6:
                    $lists[$key]['state'] = '参数不正确';
                    break;
                case 7:
                    $lists[$key]['state'] = '验证未通过';
                    break;
                case 8:
                    $lists[$key]['state'] = '重复的充值';
                    break;
                case 9:
                    $lists[$key]['state'] = '充值ip不合法';
                    break;
                default:
                    $lists[$key]['state'] = '未开始充值';
                    break;
            }
        }
//        dump($chargerecord->log());die;
        //已充值总数
        $where["AND"]['type'] = 2;
        $where["AND"]['laststate'] =  array(1,2);
        $column = 'point';
        $totalchargeCn = $chargerecord->getcolumnSum($column,$where);
        $wheres["AND"]['type'] = 2;
        $wheres["AND"]['laststate'] =  array(1,2);
        $day_start = mktime(0,0,0)*1000;
        $day_end = get_next_day_time()*1000;
        $wheres["AND"]['createtime[<>]'] =  array($day_start,$day_end);
        $daytotalchargeCn = $chargerecord->getcolumnSum($column,$wheres);
    //	dump($chargerecord->log());die;
        $configForm = new configModel();
        //总充值上限
        $tobacco_charge_max_limit = $configForm->getOne('tobacco_charge_max_limit');
        //每日充值上限
        $tobacco_charge_day_max_limit = $configForm->getOne('tobacco_charge_day_max_limit');
        //每小时充值上限
        $tobacco_charge_hour_max_limit = $configForm->getOne('tobacco_charge_day_max_limit');
        $this->assign('totalchargeCn',$totalchargeCn);
        $this->assign('daytotalchargeCn',$daytotalchargeCn);
        $this->assign('tobacco_charge_max_limit',$tobacco_charge_max_limit['value']);
        $this->assign('tobacco_charge_day_max_limit',$tobacco_charge_day_max_limit['value']);
        $this->assign('tobacco_charge_hour_max_limit',$tobacco_charge_hour_max_limit['value']);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $this->assign('tpstate',$tpstate);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->assign('tel',$tel);
        $this->render();
    }

    /**
     * 添加
     * Enter description here ...
     */
    public function export($pages = '',$tpstate='',$starttime='',$endtime='',$tel=''){
        $p = empty($pages) ? 1 : $pages;
        $tpstate = empty($tpstate)?0:$tpstate;
        switch($tpstate){
            case 1:
                $data["AND"]['laststate'] = array(-1,-2);
                break;
            case 2:
                $data["AND"]['laststate'] = array(1,2);
                break;
            case 3:
                $data["AND"]['laststate'] = 5;
                break;
            case 4:
                $data["AND"]['laststate'] = array(3,4,6,7,9);
                break;
            case 5:
                $data["AND"]['laststate'] = 8;
                break;
            default:
                break;
        }
        $data["AND"]['type'] = 2;
        if($starttime != null && $endtime != null){
            $starttime_t = strtotime($starttime).'000';
            $endtime_t = strtotime($endtime).'000';
            $data["AND"]['createtime[<>]'] =  array($starttime_t,$endtime_t);
        }
        if($tel != null){
            $data["AND"]['phone'] = $tel;
        }
        //筛选 分页
        $chargerecord = new chargerecordModel();
        // 每页显示的记录数
//        $listPage = 8;
        // 起始页
//        $firstRow = $listPage*($p-1);
//        $data['LIMIT']= array($firstRow,$listPage);
        $lists = $chargerecord->getlists($data);
//        dump($chargerecord->log());die;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('编号','手机号','昵称','USERID','IP','中奖时间','骄子币面值');
            foreach ($lists as $key=>$l) {
                $datas[] = array($key+1,$l['phone'],$l['usernickname'],$l['userid'],$l['ipaddr'],date('Y-m-d H:i:s',$l['createtime']/1000),$l['point']);
            }
        }
        if (empty($datas)){
            $this->error('暂无数据导出');
            exit;
        }else{
            $xls = new Excel_XML('UTF-8', false, $filename);
            $xls->addArray($datas);
            $xls->generateXML($filename);
        }

    }

    public function chargesetting($tobacco_charge_max_limit,$tobacco_charge_day_max_limit,$tobacco_charge_hour_max_limit){
        $configForm = new configModel();
        if($tobacco_charge_max_limit == null || $tobacco_charge_day_max_limit==null  || $tobacco_charge_hour_max_limit==null){
            $this->jsonMsg(501, '参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$tobacco_charge_max_limit)){
            $this->jsonMsg(502, 'tobacco_charge_max_limit参数有误');
        }
        $save['value'] = $tobacco_charge_max_limit;
        $res1 = $configForm->setOne('tobacco_charge_max_limit',$save);

        if(!preg_match("/^[1-9][0-9]*$/",$tobacco_charge_day_max_limit)){
            $this->jsonMsg(502, 'tobacco_charge_day_max_limit参数有误');
        }
        $save['value'] = $tobacco_charge_day_max_limit;
        $res2 = $configForm->setOne('tobacco_charge_day_max_limit',$save);
        if(!preg_match("/^[1-9][0-9]*$/",$tobacco_charge_hour_max_limit)){
            $this->jsonMsg(502, 'tobacco_charge_hour_max_limit参数有误');
        }
        $save['value'] = $tobacco_charge_hour_max_limit;
        $res3 = $configForm->setOne('tobacco_charge_hour_max_limit',$save);

        if($res1 || $res2 || $res3){
            $this->jsonMsg(200, '更新成功');
        }else{
            $this->jsonMsg(400, '更新失败');
        }

    }

}