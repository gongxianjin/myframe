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


class MobilepointsController extends BaseadminController
{
    public function index($pages = '',$tpstate='',$starttime='',$endtime='',$tel=''){
        $p = empty($pages) ? 1 : $pages;
        $tpstate = empty($tpstate)?0:$tpstate;
        switch($tpstate){
            case 1:
                $data["AND"]['laststate'] = null;
                break;
            case 2:
                $data["AND"]['laststate'] = 0;
                break;
            case 3:
                $data["AND"]['laststate'] = array(202,1206,1211,1901);
                break;
            case 4:
                $data["AND"]['laststate'] = array(201,1101,1207,1213,1212,1203,1201);
                break;
            case 5:
                $data["AND"]['laststate'] = 1701;
                break;
            default:
                break;
        }
        $data["AND"]['type'] = 3;
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
        $url = __SADMIN__.'/Mobilepoints/index';
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
            if($item['laststate'] == ''){
                $lists[$key]['state'] = '未开始充值';
                $lists[$key]['laststate'] = 400;
            }else{
                switch($item['laststate']){
                    case 0:
                        $lists[$key]['state'] = '成功';
                        break;
                    case 201:
                        $lists[$key]['state'] = '无效的充值订单';
                        break;
                    case 202:
                        $lists[$key]['state'] = '订单充值失败';
                        break;
                    case 1101:
                        $lists[$key]['state'] = '参数缺失';
                        break;
                    case 1201:
                        $lists[$key]['state'] = '系统未授权';
                        break;
                    case 1206:
                        $lists[$key]['state'] = '超时请求';
                        break;
                    case 1211:
                        $lists[$key]['state'] = '授权过期';
                        break;
                    case 1212:
                        $lists[$key]['state'] = '系统未授权,没有调用接口权限';
                        break;
                    case 1213:
                        $lists[$key]['state'] = '话费面额错误';
                        break;
                    case 1701:
                        $lists[$key]['state'] = '订单号已经存在';
                        break;
                    case 1901:
                        $lists[$key]['state'] = '系统内部错误';
                        break;
                    default:
                        break;
                }
            }
        }
//        dump($chargerecord->log());die;
        //已充值总数
        $where["AND"]['type'] = 3;
        $where["AND"]['laststate'] =  array(0);
        $column = 'point';
        $totalchargeCn = $chargerecord->getcolumnSum($column,$where);
        $wheres["AND"]['type'] = 3;
        $wheres["AND"]['laststate'] =  array(0);
        $day_start = mktime(0,0,0)*1000;
        $day_end = get_next_day_time()*1000;
        $wheres["AND"]['createtime[<>]'] =  array($day_start,$day_end);
        $daytotalchargeCn = $chargerecord->getcolumnSum($column,$wheres);
        $configForm = new configModel();
        //总充值上限
        $mobile_charge_max_limit = $configForm->getOne('mobile_charge_max_limit');
        //每日充值上限
        $mobile_charge_day_max_limit = $configForm->getOne('mobile_charge_day_max_limit');
        //每小时充值上限
        $mobile_charge_hour_max_limit = $configForm->getOne('mobile_charge_hour_max_limit');
        $this->assign('totalchargeCn',$totalchargeCn);
        $this->assign('daytotalchargeCn',$daytotalchargeCn);
        $this->assign('mobile_charge_max_limit',$mobile_charge_max_limit['value']);
        $this->assign('mobile_charge_day_max_limit',$mobile_charge_day_max_limit['value']);
        $this->assign('mobile_charge_hour_max_limit',$mobile_charge_hour_max_limit['value']);
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
                $data["AND"]['laststate'] = null;
                break;
            case 2:
                $data["AND"]['laststate'] = 0;
                break;
            case 3:
                $data["AND"]['laststate'] = array(202,1206,1211,1901);
                break;
            case 4:
                $data["AND"]['laststate'] = array(201,1101,1207,1213,1212,1203,1201);
                break;
            case 5:
                $data["AND"]['laststate'] = 1701;
                break;
            default:
                break;
        }
        $data["AND"]['type'] = 3;
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
            $datas[] = array('编号','手机号','昵称','USERID','IP','中奖时间','话费面值');
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

    public function chargesetting($mobile_charge_max_limit,$mobile_charge_day_max_limit,$mobile_charge_hour_max_limit){
        $configForm = new configModel();
        if($mobile_charge_max_limit == null || $mobile_charge_day_max_limit==null  || $mobile_charge_hour_max_limit==null){
            $this->jsonMsg(501, '参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$mobile_charge_max_limit)){
            $this->jsonMsg(502, 'mobile_charge_max_limit参数有误');
        }
        $save['value'] = $mobile_charge_max_limit;
        $res1 = $configForm->setOne('mobile_charge_max_limit',$save);
        if(!preg_match("/^[1-9][0-9]*$/",$mobile_charge_day_max_limit)){
            $this->jsonMsg(502, 'mobile_charge_day_max_limit参数有误');
        }
        $save['value'] = $mobile_charge_day_max_limit;
        $res2 = $configForm->setOne('mobile_charge_day_max_limit',$save);
        if(!preg_match("/^[1-9][0-9]*$/",$mobile_charge_hour_max_limit)){
            $this->jsonMsg(502, 'mobile_charge_hour_max_limit参数有误');
        }
        $save['value'] = $mobile_charge_hour_max_limit;
        $res3 = $configForm->setOne('mobile_charge_hour_max_limit',$save);

        if($res1 || $res2 || $res3){
            $this->jsonMsg(200, '更新成功');
        }else{
            $this->jsonMsg(400, '更新失败');
        }

    }


}