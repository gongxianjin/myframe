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

class JoindatastaticController extends BaseadminController
{
    public function index($mid='',$starttime='',$endtime=''){
        $where = '';
        $wheret = '';
        $MaterailsForm = new materialModel();
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $wheret .= " AND materialsName='".$res['materialsName']."'";
            $where .= " AND xb.materialsName='".$res['materialsName']."'";
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where .= " AND gf.createtime> '".$starttime_t."'";
            $starttime_tb = strtotime($starttime).'000';
            $wheret .= " AND timestamp > '".$starttime_tb."'";
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where .= " AND gf.createtime <'".$endtime_t."'";
            $endtime_tb = strtotime($endtime).'000';
            $wheret .= " AND timestamp < '".$endtime_tb."'";
        }
        $database = new mysql();

        $sql = "select count(*) as barcnt,sum(count) as visitcnt from xc_barcode where barcode is not null".$wheret;
//      echo $sql;die;
        $list = $database->query($sql)->fetch();
        $query = "select count(distinct gf.userid) as lottaryuser,count(*) as lottarycnt,count(gf.phone) as drawcnt,count(distinct gf.phone) as drawuser from giftrecord as gf
                        left join xc_barcode as xb on xb.barcode = gf.barcode
                        where gf.barcode is not null ".$where;
//      echo $query;die;
        $lists = $database->query($query)->fetch();
//      dump($lists);die; 
        $materialsnames = $MaterailsForm->getAll();
        $this->assign('materialsnames',$materialsnames);
        $this->assign('mid',$mid);
        $this->assign('list',$list);
        $this->assign('lists',$lists);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
	$database = null;
        $this->render();
    }

    /**
     * 添加
     * Enter description here ...
     */
    public function export($mid='',$starttime='',$endtime=''){
        $where = '';
        $wheret = '';
        $MaterailsForm = new materialModel();
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $wheret .= " AND materialsName='".$res['materialsName']."'";
            $where .= " AND xb.materialsName='".$res['materialsName']."'";
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where .= " AND gf.createtime> '".$starttime_t."'";
            $starttime_tb = strtotime($starttime).'000';
            $wheret .= " AND timestamp > '".$starttime_tb."'";
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where .= " AND gf.createtime <'".$endtime_t."'";
            $endtime_tb = strtotime($endtime).'000';
            $wheret .= " AND timestamp < '".$endtime_tb."'";
        }
        $database = new mysql();
        $sql = "select count(*) as barcnt,sum(count) as visitcnt from xc_barcode where barcode is not null".$wheret;
//      echo $sql;die;
        $list = $database->query($sql)->fetch();
        $query = "select count(distinct gf.userid) as lottaryuser,count(*) as lottarycnt,count(gf.phone) as drawcnt,count(distinct gf.phone) as drawuser from giftrecord as gf
                        left join xc_barcode as xb on xb.barcode = gf.barcode
                        where gf.barcode is not null ".$where;
//      echo $query;die;
        $lists = $database->query($query)->fetch();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('访问次数','烟包个数','抽奖人数','抽奖次数','中奖人数','中奖次数');
            $datas[] = array($list['visitcnt'],$list['barcnt'],$lists['lottaryuser'],$lists['lottarycnt'],$lists['drawcnt'],$lists['drawuser']);
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


    public function join_daystatic_init($mid='',$starttime='',$endtime=''){
        ignore_user_abort(TRUE);// 设定关闭浏览器也执行程序
        set_time_limit(0);      // 设定响应时间不限制，默认为30秒
        $where_t = '';
        $MaterailsForm = new materialModel();
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where_t .= " AND xb.materialsName='".$res['materialsName']."'";
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where_t .= " AND gf.createtime> '".$starttime_t."'";
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where_t .= " AND gf.createtime <'".$endtime_t."'";
        }
        $database = new mysql();
        $query  = "drop table if EXISTS join_daystatic_temp;";
        $query .= "create table join_daystatic_temp as (
                        SELECT
                            count(DISTINCT(xb.barcode)) as barcnt,
                            sum(xb.count) AS visitcnt,
                            count(DISTINCT gf.userid) AS lottaryuser,
                            count(*) AS lottarycnt,
                            count(gf.phone) AS drawcnt,
                            count(DISTINCT gf.phone) AS drawuser,
                            gf.day AS dst
                        FROM
                            giftrecord AS gf
                        LEFT JOIN xc_barcode AS xb ON xb.barcode = gf.barcode
                        WHERE
                            gf.barcode IS NOT NULL ".$where_t."
                        GROUP BY
                            dst
                  );";
        $database->query($query)->execute();
        header('Content-Type:text/html;charset=UTF-8');
        $this->redirect(__SADMIN__.'/Joindatastatic/daystatic');
        exit;
    }


    public function daystatic($pages = ''){
        $MaterailsForm = new materialModel();
        // 分页地址
        $url = __SADMIN__.'/Joindatastatic/daystatic';
        $p = empty($pages) ? 1 : $pages;
        $database = new mysql();
        $sql_init = "show tables like 'join_daystatic_temp'";
        $res = $database->query($sql_init)->fetchColumn();
        if(empty($res)){
            $this->join_daystatic_init();
        }
        //筛选 分页
        $sql = "SELECT  count(*) FROM join_daystatic_temp  ";
        //获取信息总数 
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query = "SELECT * FROM join_daystatic_temp limit {$firstRow},{$listPage}";
        //echo $query;die;
        $lists = $database->query($query)->fetchAll();
//      dump($lists);die;
        $database = null;
        $materialsnames = $MaterailsForm->getAll();
        $this->assign('materialsnames',$materialsnames);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $this->render();
    }


    /**
     * 添加
     * Enter description here ...
     */
    public function daystatic_export(){
        $database = new mysql();
        $query = "SELECT * FROM join_daystatic_temp";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('日期','访问次数','烟包个数','抽奖人数','抽奖次数','中奖人数','中奖次数');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['dst'],$l['visitcnt'],$l['barcnt'],$l['lottaryuser'],$l['lottarycnt'],$l['drawuser'],$l['drawcnt']);
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


    public function join_hourstatic_init($mid='',$starttime='',$endtime=''){
        ignore_user_abort(TRUE);// 设定关闭浏览器也执行程序
        set_time_limit(0);      // 设定响应时间不限制，默认为30秒
        $where_t = '';
        $MaterailsForm = new materialModel();
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where_t .= " AND xb.materialsName='".$res['materialsName']."'";
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where_t .= " AND gf.createtime> '".$starttime_t."'";
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where_t .= " AND gf.createtime <'".$endtime_t."'";
        }
        $database = new mysql();
        $query  = "drop table if EXISTS join_hourstatic_temp;";
        $query .= "create table join_hourstatic_temp as (
                        SELECT
                            count(DISTINCT(xb.barcode)) as barcnt,
                            sum(xb.count) AS visitcnt,
                            count(DISTINCT gf.userid) AS lottaryuser,
                            count(*) AS lottarycnt,
                            count(gf.phone) AS drawcnt,
                            count(DISTINCT gf.phone) AS drawuser,
                            gf.hour AS dst
                        FROM
                            giftrecord AS gf
                        LEFT JOIN xc_barcode AS xb ON xb.barcode = gf.barcode
                        WHERE
                            gf.barcode IS NOT NULL ".$where_t."
                        GROUP BY
                            dst
                  );";
        $database->query($query)->execute();
        header('Content-Type:text/html;charset=UTF-8');
        $this->redirect(__SADMIN__.'/Joindatastatic/hourstatic');
        exit;
    }

    public function hourstatic($pages = ''){
        $MaterailsForm = new materialModel();
        // 分页地址
        $url = __SADMIN__.'/Joindatastatic/hourstatic';
        $p = empty($pages) ? 1 : $pages;
        $database = new mysql();
        $sql_init = "show tables like 'join_hourstatic_temp'";
        $res = $database->query($sql_init)->fetchColumn();
        if(empty($res)){
            $this->join_hourstatic_init();
        }
        //筛选 分页
        $sql = "SELECT  count(*)   FROM  join_hourstatic_temp  ";
        //获取信息总数
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query = "SELECT  *  FROM  join_hourstatic_temp  limit  {$firstRow},{$listPage} ";
//        echo $query;die;
         $lists = $database->query($query)->fetchAll();
//        dump($lists);die; 
        $materialsnames = $MaterailsForm->getAll(); 
        $this->assign('materialsnames',$materialsnames);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $database = null;
        $this->render();
    }


    public function hourstatic_export(){
        $database = new mysql();
        $query = "SELECT    *   FROM  join_hourstatic_temp";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('日期','访问次数','烟包个数','抽奖人数','抽奖次数','中奖人数','中奖次数');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['dst'],$l['visitcnt'],$l['barcnt'],$l['lottaryuser'],$l['lottarycnt'],$l['drawuser'],$l['drawcnt']);
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