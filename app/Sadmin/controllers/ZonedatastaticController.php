<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use core\lib\mysql;
use Sadmin\models\chargerecordModel;
use Sadmin\models\materialModel;
use Sadmin\models\giftModel;
use core\lib\page;
use core\lib\Excel_XML;

class ZonedatastaticController extends BaseadminController
{

    public function zonedatastatic_init($mid='',$provance='',$city='',$starttime='',$endtime=''){
        ignore_user_abort(TRUE);// 设定关闭浏览器也执行程序
        set_time_limit(0);      // 设定响应时间不限制，默认为30秒
        $where_t = '';
        $MaterailsForm = new materialModel();
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where_t .= " AND gf.createtime> '".$starttime_t."'";
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where_t .= " AND gf.createtime <'".$endtime_t."'";
        }
        $database = new mysql();
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where_t .= " AND xb.materialsName='".$res['materialsName']."'";
            $query  = "drop table if EXISTS zonedatastatic_temp;";
            $query .= "create table zonedatastatic_temp as (SELECT '全部' as provance,'全部' as city,xb.materialsName,sum(xb.count) as visitcnt,count(xb.barcode) as barcount,count(DISTINCT gf.userid) as lottorypcnt,
				    count(gf.id) as lottorycnt,count(DISTINCT gf.phone) as drawpcnt ,count(gf.phone) as drawcnt
                    FROM c_phone_location as cpl
                    LEFT JOIN giftrecord AS gf ON cpl.phone_header = left(gf.phone,7)
                    LEFT JOIN xc_barcode AS xb ON gf.barcode = xb.barcode
                    where xb.barcode is not null ".$where_t."
                    GROUP BY xb.materialsName
                  );";
        }else{
            $res['materialsName'] = '';
        }
        if(!empty($provance)){
            $where_t .= " AND cpl.provance='".urldecode($provance)."'";
            $query  = "drop table if EXISTS zonedatastatic_temp;";
            $query .= "create table zonedatastatic_temp as (SELECT cpl.provance,cpl.city,'全部' as materialsName,sum(xb.count) as visitcnt,count(xb.barcode) as barcount,count(DISTINCT gf.userid) as lottorypcnt,
				    count(gf.id) as lottorycnt,count(DISTINCT gf.phone) as drawpcnt ,count(gf.phone) as drawcnt
                    FROM c_phone_location as cpl
                    LEFT JOIN giftrecord AS gf ON cpl.phone_header = left(gf.phone,7)
                    LEFT JOIN xc_barcode AS xb ON gf.barcode = xb.barcode
                    where xb.barcode is not null ".$where_t."
                    GROUP BY cpl.provance
                  );";
        }
        if(!empty($city)){
            $where_t .= " AND cpl.city='".urldecode($city)."'";
            $query  = "drop table if EXISTS zonedatastatic_temp;";
            $query .= "create table zonedatastatic_temp as (SELECT cpl.provance,cpl.city,'全部' as materialsName,sum(xb.count) as visitcnt,count(xb.barcode) as barcount,count(DISTINCT gf.userid) as lottorypcnt,
				    count(gf.id) as lottorycnt,count(DISTINCT gf.phone) as drawpcnt ,count(gf.phone) as drawcnt
                    FROM c_phone_location as cpl
                    LEFT JOIN giftrecord AS gf ON cpl.phone_header = left(gf.phone,7)
                    LEFT JOIN xc_barcode AS xb ON gf.barcode = xb.barcode
                    where xb.barcode is not null ".$where_t."
                    GROUP BY cpl.city
                  );";
        }
        if(empty($mid) && empty($provance) && empty($city)){
            $query  = "drop table if EXISTS zonedatastatic_temp;";
            $query .= "create table zonedatastatic_temp as (SELECT cpl.provance,'全部' as city,'全部' as materialsName,sum(xb.count) as visitcnt,count(xb.barcode) as barcount,count(DISTINCT gf.userid) as lottorypcnt,
				    count(gf.id) as lottorycnt,count(DISTINCT gf.phone) as drawpcnt ,count(gf.phone) as drawcnt
                    FROM c_phone_location as cpl
                    LEFT JOIN giftrecord AS gf ON cpl.phone_header = left(gf.phone,7)
                    LEFT JOIN xc_barcode AS xb ON gf.barcode = xb.barcode
                    where xb.barcode is not null ".$where_t."
                    GROUP BY cpl.provance
                    );";
        }
        $database->query($query)->execute();
        header('Content-Type:text/html;charset=UTF-8');
        $this->redirect(__SADMIN__.'/Zonedatastatic/index');
        exit;
    }


    public function index($pages = ''){
        $where_t = '';
        $MaterailsForm = new materialModel();
        //分页地址
        $url = __SADMIN__.'/Zonedatastatic/index';
        $p = empty($pages) ? 1 : $pages;
        $database = new mysql();
        $sql_init = "show tables like 'zonedatastatic_temp'";
        $res = $database->query($sql_init)->fetchColumn();
        if(empty($res)){
            $this->zonedatastatic_init();
        }
        //筛选 分页
        $sql = "select count(*) FROM zonedatastatic_temp";
        //获取信息总数
//        dump($sql);die;
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query =  "select * from zonedatastatic_temp limit {$firstRow},{$listPage}";
//      echo $query;die;
        $lists = $database->query($query)->fetchAll();
        $sql01 = "select DISTINCT(provance) from c_phone_location";
        $provancelist = $database->query($sql01)->fetchAll();
        $sql02 = "select DISTINCT(city) from c_phone_location";
        $citylist = $database->query($sql02)->fetchAll();
//      dump($citylist);die;
        $database = null;
        $materialsnames = $MaterailsForm->getAll();
        $this->assign('materialsnames',$materialsnames);
        $this->assign('materialsName',$res['materialsName']);
        $this->assign('provancelist',$provancelist);
        $this->assign('citylist',$citylist);
        $this->assign('materialsName',$res['materialsName']);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $this->render();
    }


    /**
     * 添加
     * Enter description here ...
     */
    public function export(){
        $database = new mysql();
        $query =  "select * from zonedatastatic_temp";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('省份','城市','烟包规格','访问次数','烟包个数','抽奖人数','抽奖次数','中奖人数','中奖次数','娇子币发放份数','娇子币发放个数','娇子币充值个数','话费发放份数','话费发放金额','话费充值金额');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['provance'],$l['city'],'全部',$l['visitcnt'],$l['barcount'],$l['lottorypcnt'],$l['lottorycnt'],$l['drawpcnt'],$l['drawcnt'],$l['tbcnt'],$l['tbpoint'],$l['tbcharge'],$l['mbcnt'],$l['mbpoint'],$l['mbcharge']);
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


    public function daystatic($pages = '',$provance='',$city='',$mid='',$starttime='',$endtime=''){
        $where_t = '';
        $MaterailsForm = new materialModel();
        //分页地址
        $url = __SADMIN__.'/Zonedatastatic/daystatic';
        $p = empty($pages) ? 1 : $pages;
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where_t .= " AND xb.materialsName='".$res['materialsName']."'";
            $url.= '/mid/'.$mid;
        }else{
            $res['materialsName'] = '';
        }
        if(!empty($provance)){
            $where_t .= " AND cpl.provance='".urldecode($provance)."'";
            $url.= '/provance/'.urldecode($provance);
        }
        if(!empty($city)){
            $where_t .= " AND cpl.city='".urldecode($city)."'";
            $url.= '/city/'.urldecode($city);
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where_t .= " AND gf.createtime> '".$starttime_t."'";
            $url .= '/starttime/'.$starttime;
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where_t .= " AND gf.createtime <'".$endtime_t."'";
            $url .= '/endtime/'.$endtime;
        }
        $database = new mysql();
        //筛选 分页
        $sql = "select count(*) FROM c_phone_location as cpl
										LEFT JOIN user as u on left(u.phone,7) = cpl.phone_header
                    LEFT JOIN giftrecord AS gf ON u .phone = gf.phone
                    LEFT JOIN xc_barcode AS xb ON gf.barcode = xb.barcode
                    left join
                    (
                            SELECT count(*) as tbcnt,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 2 ".$where_t." GROUP BY cpl.provance
                    ) as tb1 on tb1.prov = cpl.provance
                    left join
                    (
                            SELECT sum(gf.chargenum) as tbpoint,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 2  ".$where_t."  GROUP BY cpl.provance
                    ) as tb2 on tb2.prov = cpl.provance
                    left join
                    (
                            SELECT sum(ch.point) as tbcharge,cpl.provance as prov from chargerecord as ch
                            left join giftrecord as gf on ch.phone = gf.phone
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where ch.type = 2 and ch.flag = 0  ".$where_t."  GROUP BY cpl.provance
                    ) as tb3 on tb3.prov = cpl.provance
                    left join
                    (
                            SELECT count(*) as mbcnt,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 3 ".$where_t."  GROUP BY cpl.provance
                    ) as tb4 on tb4.prov = cpl.provance
                    left join
                    (
                            SELECT sum(gf.chargenum) as mbpoint,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 3 ".$where_t."  GROUP BY cpl.provance
                    ) as tb5 on tb5.prov = cpl.provance
                    left join
                    (
                            SELECT sum(ch.point) as mbcharge,cpl.provance as prov from chargerecord as ch
                            left join giftrecord as gf on ch.phone = gf.phone
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where ch.type = 3 and ch.flag = 0 ".$where_t." GROUP BY cpl.provance
                    ) as tb6 on tb6.prov = cpl.provance
                    where xb.barcode is not null ".$where_t."
                    GROUP BY left(gf.createtime,10)";
        //获取信息总数
//        dump($sql);die;
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query =  "SELECT left(gf.createtime,10) as dt,cpl.provance,cpl.city,xb.materialsName,sum(xb.count) as visitcnt,count(xb.barcode) as barcount,count(DISTINCT gf.userid) as lottorypcnt,
				count(gf.id) as lottorycnt,count(DISTINCT gf.phone) as drawpcnt ,count(gf.phone) as drawcnt,tb1.tbcnt as tbcnt,
				tb2.tbpoint as tbpoint,tb3.tbcharge as tbcharge,tb4.mbcnt as mbcnt,tb5.mbpoint as mbpoint,tb6.mbcharge as mbcharge
                    FROM c_phone_location as cpl
										LEFT JOIN user as u on left(u.phone,7) = cpl.phone_header
                    LEFT JOIN giftrecord AS gf ON u .phone = gf.phone
                    LEFT JOIN xc_barcode AS xb ON gf.barcode = xb.barcode
                    left join
                    (
                            SELECT count(*) as tbcnt,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 2 ".$where_t." GROUP BY cpl.provance
                    ) as tb1 on tb1.prov = cpl.provance
                    left join
                    (
                            SELECT sum(gf.chargenum) as tbpoint,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 2   ".$where_t."  GROUP BY cpl.provance
                    ) as tb2 on tb2.prov = cpl.provance
                    left join
                    (
                            SELECT sum(ch.point) as tbcharge,cpl.provance as prov from chargerecord as ch
                            left join giftrecord as gf on ch.phone = gf.phone
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where ch.type = 2 and ch.flag = 0  ".$where_t."  GROUP BY cpl.provance
                    ) as tb3 on tb3.prov = cpl.provance
                    left join
                    (
                            SELECT count(*) as mbcnt,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 3 ".$where_t."  GROUP BY cpl.provance
                    ) as tb4 on tb4.prov = cpl.provance
                    left join
                    (
                            SELECT sum(gf.chargenum) as mbpoint,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 3 ".$where_t."  GROUP BY cpl.provance
                    ) as tb5 on tb5.prov = cpl.provance
                    left join
                    (
                            SELECT sum(ch.point) as mbcharge,cpl.provance as prov from chargerecord as ch
                            left join giftrecord as gf on ch.phone = gf.phone
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where ch.type = 3 and ch.flag = 0 ".$where_t." GROUP BY cpl.provance
                    ) as tb6 on tb6.prov = cpl.provance
                    where xb.barcode is not null ".$where_t."
                    GROUP BY left(gf.createtime,10)
                    limit {$firstRow},{$listPage}
                    ";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
        $sql01 = "select DISTINCT(provance) from c_phone_location";
        $provancelist = $database->query($sql01)->fetchAll();
        $sql02 = "select DISTINCT(city) from c_phone_location";
        $citylist = $database->query($sql02)->fetchAll();
//        dump($citylist);die;
        $database = null;
        $materialsnames = $MaterailsForm->getAll();
        $this->assign('materialsnames',$materialsnames);
        $this->assign('materialsName',$res['materialsName']);
        $this->assign('provancelist',$provancelist);
        $this->assign('citylist',$citylist);
        $this->assign('provance',urldecode($provance));
        $this->assign('city',urldecode($city));
        $this->assign('mid',$mid);
        $this->assign('materialsName',$res['materialsName']);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->render();
    }

    public function daystatic_export($mid='',$provance='',$city='',$starttime='',$endtime=''){
        $where_t = '';
        $MaterailsForm = new materialModel();
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where_t .= " AND xb.materialsName='".$res['materialsName']."'";
        }else{
            $res['materialsName'] = '';
        }
        if(!empty($provance)){
            $where_t .= " AND cpl.provance='".urldecode($provance)."'";
        }
        if(!empty($city)){
            $where_t .= " AND cpl.city='".urldecode($city)."'";
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
        $query =  "SELECT left(gf.createtime,10) as dt,cpl.provance,cpl.city,xb.materialsName,sum(xb.count) as visitcnt,count(xb.barcode) as barcount,count(DISTINCT gf.userid) as lottorypcnt,
				count(gf.id) as lottorycnt,count(DISTINCT gf.phone) as drawpcnt ,count(gf.phone) as drawcnt,tb1.tbcnt as tbcnt,
				tb2.tbpoint as tbpoint,tb3.tbcharge as tbcharge,tb4.mbcnt as mbcnt,tb5.mbpoint as mbpoint,tb6.mbcharge as mbcharge
                    FROM c_phone_location as cpl
                    LEFT JOIN user as u on left(u.phone,7) = cpl.phone_header
                    LEFT JOIN giftrecord AS gf ON u .phone = gf.phone
                    LEFT JOIN xc_barcode AS xb ON gf.barcode = xb.barcode
                    left join
                    (
                            SELECT count(*) as tbcnt,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 2 ".$where_t." GROUP BY cpl.provance
                    ) as tb1 on tb1.prov = cpl.provance
                    left join
                    (
                            SELECT sum(gf.chargenum) as tbpoint,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 2   ".$where_t."  GROUP BY cpl.provance
                    ) as tb2 on tb2.prov = cpl.provance
                    left join
                    (
                            SELECT sum(ch.point) as tbcharge,cpl.provance as prov from chargerecord as ch
                            left join giftrecord as gf on ch.phone = gf.phone
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where ch.type = 2 and ch.flag = 0  ".$where_t."  GROUP BY cpl.provance
                    ) as tb3 on tb3.prov = cpl.provance
                    left join
                    (
                            SELECT count(*) as mbcnt,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 3 ".$where_t."  GROUP BY cpl.provance
                    ) as tb4 on tb4.prov = cpl.provance
                    left join
                    (
                            SELECT sum(gf.chargenum) as mbpoint,cpl.provance as prov from giftrecord as gf
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where gf.type = 3 ".$where_t."  GROUP BY cpl.provance
                    ) as tb5 on tb5.prov = cpl.provance
                    left join
                    (
                            SELECT sum(ch.point) as mbcharge,cpl.provance as prov from chargerecord as ch
                            left join giftrecord as gf on ch.phone = gf.phone
                            left join xc_barcode as xb on gf.barcode = xb.barcode
                            left join c_phone_location as cpl on cpl.phone_header = left(gf.phone,7)
                            where ch.type = 3 and ch.flag = 0 ".$where_t." GROUP BY cpl.provance
                    ) as tb6 on tb6.prov = cpl.provance
                    where xb.barcode is not null ".$where_t."
                    GROUP BY left(gf.createtime,10)
                    ";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('日期','省份','城市','烟包规格','访问次数','烟包个数','抽奖人数','抽奖次数','中奖人数','中奖次数','娇子币发放份数','娇子币发放个数','娇子币充值个数','话费发放份数','话费发放金额','话费充值金额');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['dt'],$l['provance'],$l['city'],$l['materialsName'],$l['visitcnt'],$l['barcount'],$l['lottorypcnt'],$l['lottorycnt'],$l['drawpcnt'],$l['drawcnt'],$l['tbcnt'],$l['tbpoint'],$l['tbcharge'],$l['mbcnt'],$l['mbpoint'],$l['mbcharge']);
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


    public function selCity($provance = ''){
        if($provance==''){
            $this->jsonMsg(501, '参数有误');
        }
        $database = new mysql();
        $sql = "select DISTINCT(city) from c_phone_location where provance = "."'$provance'";
        $lists = $database->query($sql)->fetchAll();
        $database = null;
        $this->jsonMsg(200, 'ok',$lists);
    }


}