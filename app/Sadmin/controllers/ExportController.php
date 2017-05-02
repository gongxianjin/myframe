<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use core\lib\mysql;
use core\lib\model;
use core\lib\Excel_XML;

class ExportController extends BaseadminController
{
    public function index() {
        $this->render();
    }

    /**
     * 导出
     * Enter description here ...
     */
    public function exportv1giftrecord($starttime='',$endtime=''){
        $data = array();
        if($starttime == null  ||  $endtime == null){
            $this->error('参数有误');die;
        }
        if($starttime >= $endtime){
            $this->error('时间有误');die;
        }
        $database = new mysql();
        $starttime = $starttime.'09:00:00';
        $endtime = $endtime.'09:00:00';
        $starttime = date('Y-m-d H:i:s',strtotime($starttime));
        $endtime = date('Y-m-d H:i:s',strtotime($endtime));
        $sql = "SELECT g.id,g.userid,g.giftname,g.barcode,g.createtime,g.phone,xb.materialsName,u.openid FROM giftrecord as g left join `xc_barcode` as xb on g.barcode = xb.barcode left join user as u on u.id = g.userid where  g.createtime  >  '$starttime'  and  g.createtime  < '$endtime'  order by g.createtime";
        $lists = $database->query($sql)->fetchAll();
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('编号','微信ID','奖品名称','二维码','手机号','参与时间','规格');
            foreach ($lists as $key=>$l) {
                $datas[] = array($key+1,$l['openid'],$l['giftname'],$l['barcode'],$l['phone'],$l['createtime'],$l['materialsName']);
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


    /**
     * 导出
     * Enter description here ...
     */
    public function exportv1barcodes($starttime='',$endtime=''){
        $data = array();
        if($starttime == null  ||  $endtime == null){
            $this->error('参数有误');die;
        }
        if($starttime >= $endtime){
            $this->error('时间有误');die;
        }
        $database = new mysql();
        $starttime = $starttime.'09:00:00';
        $endtime = $endtime.'09:00:00';
        $starttime = strtotime($starttime);
        $endtime = strtotime($endtime);
        $starttime = $starttime*1000;
        $endtime = $endtime*1000;
        $sql = "SELECT xb.*,FROM_UNIXTIME(xb.`timestamp`/1000, '%Y-%m-%d %H:%i:%s') as '日期' FROM `xc_barcode` as xb where (xb.`timestamp` BETWEEN $starttime AND $endtime ) order by xb.`timestamp`";
        $lists = $database->query($sql)->fetchAll();
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('编号','二维码','中奖情况','访问数','日期');
            foreach ($lists as $key=>$l) {
                $datas[] = array($key+1,$l['barcode'],$l['materialsName'],$l['isLotter'],$l['count'],$l['日期']);
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


    /**
     * 导出
     * Enter description here ...
     */
    public function exportv1users($starttime='',$endtime=''){
        $data = array();
        if($starttime == null  ||  $endtime == null){
            $this->error('参数有误');die;
        }
        if($starttime >= $endtime){
            $this->error('时间有误');die;
        }
        $database = new mysql();
        $starttime = $starttime.'09:00:00';
        $endtime = $endtime.'09:00:00';
        $starttime = date('Y-m-d H:i:s',strtotime($starttime));
        $endtime = date('Y-m-d H:i:s',strtotime($endtime));
        $sql = "SELECT u.phone,u.phoneowner,gf.giftname,gf.createtime FROM `user` as u inner join giftrecord as gf on u.id = gf.userid where gf.type = 3 and (gf.giftname = '5元话费') and gf.createtime < '$endtime'  and gf.createtime > '$starttime' ";
        $lists = $database->query($sql)->fetchAll();
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('编号','手机号','手机归属地','奖品名称','参与时间');
            foreach ($lists as $key=>$l) {
                $datas[] = array($key+1,$l['phone'],$l['phoneowner'],$l['giftname'],$l['createtime']);
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


    /**
     * 导出
     * Enter description here ...
     */
    public function exportv2giftrecord($starttime='',$endtime=''){  
        $data = array();
        if($starttime == null  ||  $endtime == null){
            $this->error('参数有误');die;
        }
        if($starttime >= $endtime){
            $this->error('时间有误');die;
        }
        $database = new mysql();
        $starttime = $starttime.'09:00:00';
        $endtime = $endtime.'09:00:00';
        $starttime = date('Y-m-d H:i:s',strtotime($starttime));
        $endtime = date('Y-m-d H:i:s',strtotime($endtime));
        $sql = "SELECT g.id,g.userid,g.giftname,g.barcode,g.createtime,g.phone,xb.materialsName,u.openid FROM giftrecord as g left join `xc_barcode` as xb on g.barcode = xb.barcode left join user as u on u.id = g.userid where  g.createtime  >  '$starttime'  and  g.createtime  < '$endtime'  order by g.createtime";
        $lists = $database->query($sql)->fetchAll();
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('编号','微信ID','奖品名称','二维码','手机号','参与时间','规格');
            foreach ($lists as $key=>$l) {
                $datas[] = array($key+1,$l['openid'],$l['giftname'],$l['barcode'],$l['phone'],$l['createtime'],$l['createtime'],$l['materialsName']);
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


    /**
     * 导出
     * Enter description here ...
     */
    public function exportv2barcodes($starttime='',$endtime=''){
        $data = array();
        if($starttime == null  ||  $endtime == null){
            $this->error('参数有误');die;
        }
        if($starttime >= $endtime){
            $this->error('时间有误');die;
        }
        $database = new mysql();
        $starttime = $starttime.'09:00:00';
        $endtime = $endtime.'09:00:00';
        $starttime = strtotime($starttime);
        $endtime = strtotime($endtime);
        $starttime = $starttime*1000;
        $endtime = $endtime*1000;
        $sql = "SELECT xb.*,FROM_UNIXTIME(xb.`timestamp`/1000, '%Y-%m-%d %H:%i:%s') as '日期' FROM `xc_barcode` as xb where (xb.`timestamp` BETWEEN $starttime AND $endtime ) order by xb.`timestamp`";
        $lists = $database->query($sql)->fetchAll();
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('编号','二维码','中奖情况','访问数','日期');
            foreach ($lists as $key=>$l) {
                $datas[] = array($key+1,$l['barcode'],$l['materialsName'],$l['isLotter'],$l['count'],$l['日期']);
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

    /**
     * 导出
     * Enter description here ...
     */
    public function exportv2mobilebills($starttime='',$endtime=''){
        $data = array();
        if($starttime == null  ||  $endtime == null){
            $this->error('参数有误');die;
        }
        if($starttime >= $endtime){
            $this->error('时间有误');die;
        }
        $database = new mysql();
        $starttime = $starttime.'09:00:00';
        $endtime = $endtime.'09:00:00';
        $starttime = strtotime($starttime);
        $endtime = strtotime($endtime);
        $sql = "select xmo.phone,cg.giftname,xmo.materialsName,u.phoneowner,FROM_UNIXTIME(xmo.createtime,'%Y-%m-%d %H:%i:%s') as 日期  from xc_mobilebills as xmo left join chargerecord as cg on xmo.chargerecordid = cg.id left join user as u on xmo.userid = u.id where xmo.giftrecordid is NULL and xmo.createtime BETWEEN  $starttime AND  $endtime ";
        $lists = $database->query($sql)->fetchAll();
        if (!empty($lists)){
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('编号','手机号','手机归属地','奖品名称','时间','规格');
            foreach ($lists as $key=>$l) {
                $datas[] = array($key+1,$l['phone'],$l['phoneowner'],$l['giftname'],$l['日期'],$l['materialsName']);
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



}