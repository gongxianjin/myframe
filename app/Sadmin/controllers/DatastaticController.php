<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use core\lib\mysql;
use Sadmin\models\materialModel;
use core\lib\page;
use core\lib\Excel_XML;

class DatastaticController extends BaseadminController
{

    public function datastatic_init(){
        ignore_user_abort(TRUE);// 设定关闭浏览器也执行程序
        set_time_limit(0);      // 设定响应时间不限制，默认为30秒
        $database = new mysql();
        $query  = "drop table if EXISTS datastatic_temp;";
        $query .= "create table datastatic_temp as (
                        select xb.barcode,xb.materialsName,gf.phone,cp.provance,cp.city,cp.mobile_type,u.nickname,u.openid,u.ip,gf.createtime,gf.giftname
                              from xc_barcode as xb
                              left join giftrecord as gf on gf.barcode = xb.barcode
                              left join c_phone_location as cp on left(gf.phone,7) = cp.phone_header
                              left join user as u on  u.id = gf.userid where xb.barcode is not NULL
                  );";
//        echo $query;die;
        $database->query($query)->execute();
        header('Content-Type:text/html;charset=UTF-8');
        $this->redirect(__SADMIN__.'/Datastatic/index');
        exit;
    }

    public function index($pages = '',$mid='',$starttime='',$endtime=''){
        $where = '';
        $MaterailsForm = new materialModel();
        // 分页地址
        $url = __SADMIN__.'/Datastatic/index';
        $p = empty($pages) ? 1 : $pages;

//        if(!empty($mid)){
//            $res = $MaterailsForm->getKey(array('id'=>$mid));
//            $where .= " AND xb.materialsname='".$res['materialsName']."'";
//            $url.= '/mid/'.$mid;
//        }
//        if(!empty($starttime)){
//            $starttime_t = ($starttime).' 00:00:00';
//            $where .= " AND gf.createtime> '".$starttime_t."'";
//            $url .= '/starttime/'.$starttime;
//        }
//        if(!empty($endtime)){
//            $endtime_t = ($endtime).' 00:00:00';
//            $where .= " AND gf.createtime <'".$endtime_t."'";
//            $url .= '/endtime/'.$endtime;
//        }

        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where .= " AND materialsname='".$res['materialsName']."'";
            $url.= '/mid/'.$mid;
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where .= " AND createtime> '".$starttime_t."'";
            $url .= '/starttime/'.$starttime;
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where .= " AND createtime <'".$endtime_t."'";
            $url .= '/endtime/'.$endtime;
        }

        $database = new mysql();

        $sql_init = "show tables like 'datastatic_temp'";
        $res = $database->query($sql_init)->fetchColumn();
        if(empty($res)){
            $this->datastatic_init();
        }
        //筛选 分页
//        $sql = "select count(0) as cont
//                  from xc_barcode as xb
//	              left join giftrecord as gf on gf.barcode = xb.barcode
//	              left join c_phone_location as cp on left(gf.phone,7) = cp.phone_header
//	              left join user as u on  u.id = gf.userid where xb.barcode is not null ".$where;

        $sql = "select count(0) as cont
                  from datastatic_temp where barcode is not null ".$where;
        //获取信息总数
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query = "select * from datastatic_temp where barcode is not null ".$where."  limit {$firstRow},{$listPage}";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        $materialsnames = $MaterailsForm->getAll();
        $this->assign('materialsnames',$materialsnames);
        $this->assign('mid',$mid);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->render();
    }

    /**
     * 添加
     * Enter description here ...
     */
    public function export($mid='',$starttime='',$endtime=''){
        $where = '';
        $MaterailsForm = new materialModel();
        // 分页地址
//        if(!empty($mid)){
//            $res = $MaterailsForm->getKey(array('id'=>$mid));
//            $where .= " AND xb.materialsname='".$res['materialsName']."'";
//            $url.= '/mid/'.$mid;
//        }
//        if(!empty($starttime)){
//            $starttime_t = ($starttime).' 00:00:00';
//            $where .= " AND gf.createtime> '".$starttime_t."'";
//            $url .= '/starttime/'.$starttime;
//        }
//        if(!empty($endtime)){
//            $endtime_t = ($endtime).' 00:00:00';
//            $where .= " AND gf.createtime <'".$endtime_t."'";
//            $url .= '/endtime/'.$endtime;
//        }
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where .= " AND materialsname='".$res['materialsName']."'";
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where .= " AND createtime> '".$starttime_t."'";
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where .= " AND createtime <'".$endtime_t."'";
        }
        $database = new mysql();
//        $query = "select xb.barcode,xb.materialsName,gf.phone,cp.provance,cp.city,cp.mobile_type,u.nickname,u.openid,u.ip,gf.createtime,gf.giftname
//          from xc_barcode as xb
//          left join giftrecord as gf on gf.barcode = xb.barcode
//          left join c_phone_location as cp on left(gf.phone,7) = cp.phone_header
//          left join user as u on  u.id = gf.userid where xb.barcode is not NULL ".$where." order by gf.createtime desc";
        $query = "select * from datastatic_temp where barcode is not null ".$where;
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('二维码ID','烟包规格','用户手机号','省份','城市','运营商','昵称','openID','IP','抽奖时间','奖品名称');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['barcode'],$l['materialsName'],$l['phone'],$l['provance'],$l['city'],$l['mobile_type'],$l['nickname'],$l['openid'],$l['ip'],$l['createtime'],$l['giftname']);
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


    public function tobaccocharge_init(){
        ignore_user_abort(TRUE);// 设定关闭浏览器也执行程序
        set_time_limit(0);      // 设定响应时间不限制，默认为30秒
        $database = new mysql();
        $query  = "drop table if EXISTS tobaccocharge_temp;";
        $query .= "create table tobaccocharge_temp as (
                      select xb.barcode,xb.materialsName,gf.phone,cp.provance,cp.city,cp.mobile_type,u.nickname,u.openid,u.ip,gf.giftname,cg.point,cg.createtime
                      from xc_barcode as xb
                      left join giftrecord as gf on gf.barcode = xb.barcode
                      left join c_phone_location as cp on left(gf.phone,7) = cp.phone_header
                      left join chargerecord  as cg on gf.id = cg.giftrecordid
                      left join user as u on u.id = gf.userid where xb.barcode is not NULL and cg.type = 2  and cg.flag = 0  order by gf.createtime desc
                  );";
        $database->query($query)->execute();
        header('Content-Type:text/html;charset=UTF-8');
        $this->redirect(__SADMIN__.'/Datastatic/tobaccocharge');
        exit;
    }


    public function tobaccocharge($pages = '',$mid='',$starttime='',$endtime=''){
        $where = '';
        $MaterailsForm = new materialModel();
        // 分页地址
        $url = __SADMIN__.'/Datastatic/tobaccocharge';
        $p = empty($pages) ? 1 : $pages;
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where .= " AND materialsName='".$res['materialsName']."'";
            $url.= '/mid/'.$mid;
        }
        if(!empty($starttime)){
            $starttime_t = strtotime($starttime).'000';
            $where .= " AND createtime> '".$starttime_t."'";
            $url .= '/starttime/'.$starttime;
        }
        if(!empty($endtime)){
            $endtime_t = strtotime($endtime).'000';
            $where .= " AND createtime <'".$endtime_t."'";
            $url .= '/endtime/'.$endtime;
        }
        $database = new mysql();

        $sql_init = "show tables like 'tobaccocharge_temp'";
        $res = $database->query($sql_init)->fetchColumn();
        if(empty($res)){
            $this->tobaccocharge_init();
        }
        //筛选 分页
        $sql = "select count(0) as cont
                  from tobaccocharge_temp where barcode is not null ".$where;
        //获取信息总数
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query = "select * from tobaccocharge_temp where barcode is not NULL ".$where." limit {$firstRow},{$listPage}";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        $materialsnames = $MaterailsForm->getAll();
        $this->assign('materialsnames',$materialsnames);
        $this->assign('mid',$mid);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->render();
    }


    /**
     * 添加
     * Enter description here ...
     */
    public function tobaccocharge_export($mid='',$starttime='',$endtime=''){
        $where = '';
        $MaterailsForm = new materialModel();
        // 分页地址
        $url = __SADMIN__.'/Datastatic/tobaccocharge';
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where .= " AND materialsName='".$res['materialsName']."'";
        }
        if(!empty($starttime)){
            $starttime_t = strtotime($starttime).'000';
            $where .= " AND createtime> '".$starttime_t."'";
        }
        if(!empty($endtime)){
            $endtime_t = strtotime($endtime).'000';
            $where .= " AND createtime <'".$endtime_t."'";
        }
        $database = new mysql();
        $query = "select *
          from tobaccocharge_temp where barcode is not NULL ".$where;
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('二维码ID','烟包规格','用户手机号','省份','城市','运营商','昵称','openID','IP','奖品名称','充值面额','充值时间');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['barcode'],$l['materialsName'],$l['phone'],$l['provance'],$l['city'],$l['mobile_type'],$l['nickname'],$l['openid'],$l['ip'],$l['giftname'],$l['point'],date('Y-m-d H:i:s',$l['createtime']/1000));
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


    public function mobilecharge_init(){
        ignore_user_abort(TRUE);// 设定关闭浏览器也执行程序
        set_time_limit(0);      // 设定响应时间不限制，默认为30秒
        $database = new mysql();
        $query  = "drop table if EXISTS mobilecharge_temp;";
        $query .= "create table mobilecharge_temp as (
                      select xb.barcode,xb.materialsName,gf.phone,cp.provance,cp.city,cp.mobile_type,u.nickname,u.openid,u.ip,gf.giftname,cg.point,cg.createtime
                      from xc_barcode as xb
                      left join giftrecord as gf on gf.barcode = xb.barcode
                      left join c_phone_location as cp on left(gf.phone,7) = cp.phone_header
                      left join chargerecord  as cg on gf.phone = cg.phone
                      left join user as u on  u.id = gf.userid
                      where xb.barcode is not NULL and gf.type = 3  and cg.flag = 0  order by gf.createtime desc
                  );";
        $database->query($query)->execute();
        header('Content-Type:text/html;charset=UTF-8');
        $this->redirect(__SADMIN__.'/Datastatic/mobilecharge');
        exit;
    }

    public function mobilecharge($pages = '',$mid='',$starttime='',$endtime=''){
        $where = '';
        $MaterailsForm = new materialModel();
        // 分页地址
        $url = __SADMIN__.'/Datastatic/mobilecharge';
        $p = empty($pages) ? 1 : $pages;
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where .= " AND materialsName='".$res['materialsName']."'";
            $url.= '/mid/'.$mid;
        }
        if(!empty($starttime)){
            $starttime_t = strtotime($starttime).'000';
            $where .= " AND createtime> '".$starttime_t."'";
            $url .= '/starttime/'.$starttime;
        }
        if(!empty($endtime)){
            $endtime_t = strtotime($endtime).'000';
            $where .= " AND createtime <'".$endtime_t."'";
            $url .= '/endtime/'.$endtime;
        }
        $database = new mysql();
        $sql_init = "show tables like 'mobilecharge_temp'";
        $res = $database->query($sql_init)->fetchColumn();
        if(empty($res)){
            $this->mobilecharge_init();
        }
        //筛选 分页
        $sql = "select count(0) as cont
                  from mobilecharge_temp  ".$where;
        //获取信息总数
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query = "select * from mobilecharge_temp ".$where." limit {$firstRow},{$listPage}";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        $materialsnames = $MaterailsForm->getAll();
        $this->assign('materialsnames',$materialsnames);
        $this->assign('mid',$mid);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->render();
    }

    public function mobilecharge_export($pages = '',$mid='',$starttime='',$endtime=''){
        $where = '';
        $MaterailsForm = new materialModel();
        // 分页地址
        $url = __SADMIN__.'/Datastatic/mobilecharge';
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where .= " AND materialsName='".$res['materialsName']."'";
            $url.= '/mid/'.$mid;
        }
        if(!empty($starttime)){
            $starttime_t = strtotime($starttime).'000';
            $where .= " AND createtime> '".$starttime_t."'";
            $url .= '/starttime/'.$starttime;
        }
        if(!empty($endtime)){
            $endtime_t = strtotime($endtime).'000';
            $where .= " AND createtime <'".$endtime_t."'";
            $url .= '/endtime/'.$endtime;
        }
        $database = new mysql();
        $query = "select * from mobilecharge_temp  where barcode is not NULL ".$where;
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('二维码ID','烟包规格','用户手机号','省份','城市','运营商','昵称','openID','IP','奖品名称','充值面额','充值时间');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['barcode'],$l['materialsName'],$l['phone'],$l['provance'],$l['city'],$l['mobile_type'],$l['nickname'],$l['openid'],$l['ip'],$l['giftname'],$l['point'],date('Y-m-d H:i:s',$l['createtime']/1000));
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