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

class DrawdatastaticController extends BaseadminController
{
    public function index($td='',$md='',$mid='',$starttime='',$endtime=''){
        $Totalsends = 0;
        $Tdsends = 0;
        $Mdsends = 0;
        $Tdsendalls = 0;
        $Mdsendalls = 0;
        $MaterailsForm = new materialModel();
        $GiftForm = new giftModel();
        $chargerecordForm = new chargerecordModel();

        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $materialsName = $res['materialsName'];
        }else{
            $materialsName = '';
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
        }else{
            $starttime_t = '';
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
        }else{
            $endtime_t = '';
        }
//        if(!empty($td)){
//            $prize = $GiftForm->getlists(array('AND'=>array('price'=>$td,'gift_type'=>'2')));
//        }
//        if(!empty($md)){
//            $prize = $GiftForm->getlists(array('AND'=>array('price'=>$md,'gift_type'=>'3')));
//        }
        if(!empty($md) && !empty($td)){
            $prize = $GiftForm->getlists(array('AND'=>array('price'=>array($md,$td))));
        }else{
            $prize = $GiftForm->getAll();
        }
        if($prize){
            //获取奖品总数
            foreach($prize as $key=>$item){
                //每个的已发放份数
                $prize[$key]['every_sends'] = $this->get_this_totals($item['id'],$item['gift_type'],$materialsName,$starttime_t,$endtime_t);
                //总的已发放份数
                $Totalsends += $prize[$key]['every_sends'];
                //骄子币已发放份数
                $prize[$key]['everytd_sends'] = $this->get_this_totals($item['id'],2,$materialsName,$starttime_t,$endtime_t);
                //总的骄子币已发放份数
                $Tdsends += $prize[$key]['everytd_sends'];
                //总的骄子币已发放个数
                $Tdsendalls += $prize[$key]['everytd_sends']*$item['price'];
                //每个话费已发放份数
                $prize[$key]['everymd_sends'] = $this->get_this_totals($item['id'],3,$materialsName,$starttime_t,$endtime_t);
                //总的话费已发放份数
                $Mdsends += $prize[$key]['everymd_sends'];
                //总的话费已发放金额
                $Mdsendalls += $prize[$key]['everymd_sends']*$item['price'];
            }
        }

        if(!empty($starttime)){
            $starttime_md = strtotime($starttime).'000';
            $where_Td["AND"]['createtime[>]'] = $starttime_md;
            $where_Md["AND"]['createtime[>]'] = $starttime_md;
        }
        if(!empty($endtime)){
            $endtime_mb = strtotime($endtime).'000';
            $where_Td["AND"]['createtime[<]'] = $endtime_mb;
            $where_Md["AND"]['createtime[<]'] = $endtime_mb;
        }
        if(!empty($md) && !empty($td)){
            $where_Td["AND"]['point']   = array($md,$td);
            $where_Md["AND"]['point']   = array($md,$td);
        }

        //总的骄子币充值总数
        $where_Td["AND"]['type'] = 2;
        $where_Td["AND"]['flag'] = 0;
        $where_Td["AND"]['laststate'] =  array(1,2);
        $column = 'point';
        $totaltdchargeCn = $chargerecordForm->getcolumnSum($column,$where_Td);

        //总的话费充值金额
        $where_Md["AND"]['type'] = 3;
        $where_Md["AND"]['flag'] = 0;
        $where_Md["AND"]['laststate'] =  array(0);
        $column = 'point';
        $totalmdchargeCn = $chargerecordForm->getcolumnSum($column,$where_Md);
//        dump($chargerecordForm);die;
        $materialsnames = $MaterailsForm->getAll();
        $TdInfo = $GiftForm->getlists(array('gift_type'=>'2','GROUP'=>'price'));
        $MdInfo = $GiftForm->getlists(array('gift_type'=>'3','GROUP'=>'price'));
        $this->assign('materialsnames',$materialsnames);
        $this->assign('TdInfo',$TdInfo);
        $this->assign('MdInfo',$MdInfo);
        $this->assign('mid',$mid);
        $this->assign('td',$td);
        $this->assign('md',$md);
        $this->assign('Totalsends',$Totalsends);
        $this->assign('Tdsends',$Tdsends);
        $this->assign('Tdsendalls',$Tdsendalls);
        $this->assign('Mdsends',$Mdsends);
        $this->assign('Mdsendalls',$Mdsendalls);
        $this->assign('totaltdchargeCn',$totaltdchargeCn);
        $this->assign('totalmdchargeCn',$totalmdchargeCn);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->render();
    }

    private function get_this_totals($giftid,$type,$materialsName = '',$start_time = '',$end_time = ''){
        $database = new mysql();
        $where = '';
        if(!empty($materialsName)){
            $where .= " AND xb.materialsName='".$materialsName."'";
        }
        if(!empty($start_time)){
            $where .= " AND gf.createtime> '".$start_time."'";
        }
        if(!empty($end_time)){
            $where .= " AND gf.createtime <'".$end_time."'";
        }
        $sql = 'select count(*) from giftrecord as gf left join xc_barcode as xb on xb.barcode = gf.barcode '.
                " where '" . $giftid . "' = gf.giftid_real  and gf.type = '".$type.
                "'".$where;
//        echo $sql;die;
        $res = $database->query($sql)->fetchColumn();
        $database = null;
        return $res;
    }

    /**
     * 添加
     * Enter description here ...
     */
    public function export($td='',$md='',$mid='',$starttime='',$endtime=''){
        $Totalsends = 0;
        $Tdsends = 0;
        $Mdsends = 0;
        $Tdsendalls = 0;
        $Mdsendalls = 0;
        $MaterailsForm = new materialModel();
        $GiftForm = new giftModel();
        $chargerecordForm = new chargerecordModel();

        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $materialsName = $res['materialsName'];
        }else{
            $materialsName = '';
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
        }else{
            $starttime_t = '';
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
        }else{
            $endtime_t = '';
        }

//        if(!empty($td)){
//            $prize = $GiftForm->getlists(array('AND'=>array('price'=>$td,'gift_type'=>'2')));
//        }
//        if(!empty($md)){
//            $prize = $GiftForm->getlists(array('AND'=>array('price'=>$md,'gift_type'=>'3')));
//        }
        if(!empty($md) && !empty($td)){
            $prize = $GiftForm->getlists(array('AND'=>array('price'=>array($md,$td),'gift_type'=>'2')));
        }else{
            $prize = $GiftForm->getAll();
        }

        if($prize){
            //获取奖品总数
            foreach($prize as $key=>$item){
                //每个的已发放份数
                $prize[$key]['every_sends'] = $this->get_this_totals($item['id'],$item['gift_type'],$materialsName,$starttime_t,$endtime_t);
                //总的已发放份数
                $Totalsends += $prize[$key]['every_sends'];

                //骄子币已发放份数
                $prize[$key]['everytd_sends'] = $this->get_this_totals($item['id'],2,$materialsName,$starttime_t,$endtime_t);
                //总的骄子币已发放份数
                $Tdsends += $prize[$key]['everytd_sends'];
                //总的骄子币已发放个数
                $Tdsendalls += $prize[$key]['everytd_sends']*$item['price'];

                //每个话费已发放份数
                $prize[$key]['everymd_sends'] = $this->get_this_totals($item['id'],3,$materialsName,$starttime_t,$endtime_t);
                //总的话费已发放份数
                $Mdsends += $prize[$key]['everymd_sends'];
                //总的话费已发放金额
                $Mdsendalls += $prize[$key]['everymd_sends']*$item['price'];
            }
        }

        if(!empty($starttime)){
            $starttime_md = strtotime($starttime).'000';
            $where_Td["AND"]['createtime[>]'] = $starttime_md;
            $where_Md["AND"]['createtime[>]'] = $starttime_md;
        }
        if(!empty($endtime)){
            $endtime_mb = strtotime($endtime).'000';
            $where_Td["AND"]['createtime[<]'] = $endtime_mb;
            $where_Md["AND"]['createtime[<]'] = $endtime_mb;
        }
        if(!empty($md) && !empty($td)){
            $where_Td["AND"]['point']   = array($md,$td);
            $where_Md["AND"]['point']   = array($md,$td);
        }

        //总的骄子币充值总数
        $where_Td["AND"]['type'] = 2;
        $where_Td["AND"]['flag'] = 0;
        $where_Td["AND"]['laststate'] =  array(1,2);
        $column = 'point';
        $totaltdchargeCn = $chargerecordForm->getcolumnSum($column,$where_Td);

        //总的话费充值金额
        $where_Md["AND"]['type'] = 3;
        $where_Md["AND"]['flag'] = 0;
        $where_Md["AND"]['laststate'] =  array(0);
        $column = 'point';
        $totalmdchargeCn = $chargerecordForm->getcolumnSum($column,$where_Md);

        if (!empty($prize)){
            $filename = date('Y-m-d');
            $datas[] = array('奖品发放份数','娇子币发放份数','娇子币发放个数','娇子币充值个数','话费发放份数','话费发放金额','话费充值金额');
            $datas[] = array($Totalsends,$Tdsends,$Tdsendalls,$totaltdchargeCn,$Mdsends,$Mdsendalls,$totalmdchargeCn);
        }
//      dump($prize);die;
        if (empty($datas)){
            $this->error('暂无数据导出');
            exit;
        }else{
            $xls = new Excel_XML('UTF-8', false, $filename);
            $xls->addArray($datas);
            $xls->generateXML($filename);
        }

    }


    public function draw_daystatic_init($td='',$md='',$mid='',$starttime='',$endtime=''){
        ignore_user_abort(TRUE);// 设定关闭浏览器也执行程序
        set_time_limit(0);      // 设定响应时间不限制，默认为30秒
        $where_t = '';
        $where_td = '';
        $where_md = '';
        $MaterailsForm = new materialModel();
        if(!empty($td)){
            $where_td .= " AND price='".$td."'";
        }
        if(!empty($md)){
            $where_md .= " AND price ='".$md."'";
        }
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where_t .= " AND xb.materialsName='".$res['materialsName']."'";
        }else{
            $res['materialsName'] = '';
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
        $query  = "drop table if EXISTS draw_daystatic_temp;";
        $query .= "create table draw_daystatic_temp as (
                        select * from
                    (
                        select gf.day as dt1,count(*) as totalsends  from giftrecord as gf left join xc_barcode as xb on gf.barcode = xb.barcode
                                    where gf.giftid_real in (select id from gift where id is not null) and (gf.type = 2 or gf.type = 3)  ".$where_t." GROUP BY dt1
                    )as tb1
                    left join
                    (
                        select gf.day as dt2,count(*) as tdsends,sum(gf.chargenum) as tdsendalls from giftrecord as gf left join xc_barcode as xb on gf.barcode = xb.barcode
                                    where gf.giftid_real in (select id from gift where gift_type = 2 ".$where_td." ) and gf.type = 2  ".$where_t." GROUP BY dt2
                    )as tb2
                    on tb1.dt1 = tb2.dt2
                    left join
                    (
                        select gf.day as dt3,count(*) as mdsends,sum(gf.chargenum) as mdsendalls from giftrecord as gf left join xc_barcode as xb on gf.barcode = xb.barcode
                                    where gf.giftid_real in (select id from gift where gift_type = 3 ".$where_md.") and gf.type = 3 ".$where_t." GROUP BY dt3
                    )as tb3
                    on tb1.dt1 = tb3.dt3
                    left join
                    (
                        select sum(point) as totaltdchargeCn,FROM_UNIXTIME(createtime/1000,'%Y-%m-%d') as dt4 from chargerecord where type = 2 and flag = 0 and laststate in (1,2)  GROUP BY dt4
                    )as tb4
                    on tb1.dt1 = tb4.dt4
                    left join
                    (
                        select sum(point) as totalmdchargeCn,FROM_UNIXTIME(createtime/1000,'%Y-%m-%d') as dt5 from chargerecord where type = 3 and flag = 0 and laststate in (0)  GROUP BY dt5
                    )as tb5
                    on tb1.dt1 = tb5.dt5
                  );";
        $database->query($query)->execute();
        header('Content-Type:text/html;charset=UTF-8');
        $this->redirect(__SADMIN__.'/Drawdatastatic/daystatic');
        exit;
    }


    public function daystatic($pages = ''){
        $MaterailsForm = new materialModel();
        $GiftForm  = new giftModel();
        // 分页地址
        $url = __SADMIN__.'/Drawdatastatic/daystatic';
        $p = empty($pages) ? 1 : $pages;
        $database = new mysql();
        $sql_init = "show tables like 'draw_daystatic_temp'";
        $res = $database->query($sql_init)->fetchColumn();
        if(empty($res)){
            $this->draw_daystatic_init();
        }
        //筛选 分页
        $sql = "select count(*) from draw_daystatic_temp";
        //获取信息总数
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query =  "select * from draw_daystatic_temp limit {$firstRow},{$listPage}";
//      echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $materialsnames = $MaterailsForm->getAll(); 
        $TdInfo = $GiftForm->getlists(array('gift_type'=>'2','GROUP'=>'price'));
        $MdInfo = $GiftForm->getlists(array('gift_type'=>'3','GROUP'=>'price'));
        $this->assign('materialsnames',$materialsnames);
        $this->assign('TdInfo',$TdInfo);
        $this->assign('MdInfo',$MdInfo);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $database = null;
        $this->render();
    }


    /**
     * 添加
     * Enter description here ...
     */
    public function daystatic_export(){
        $database = new mysql();
        $query =  " select * from draw_daystatic_temp";
//      echo $query;die;
        $lists = $database->query($query)->fetchAll();
//      dump($lists);die;
        $database = null;
        if (!empty($lists)){
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('日期','奖品发放份数','娇子币发放份数','娇子币发放个数','话费发放份数','话费发放金额','话费充值金额');
            foreach($lists as $key=>$l){
                $datas[] = array($l['dt1'],$l['totalsends'],$l['tdsends'],$l['tdsendalls'],$l['mdsends'],$l['mdsendalls'],$l['totaltdchargeCn'],$l['totalmdchargeCn']);
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


    public function draw_hourstatic_init($td='',$md='',$mid='',$starttime='',$endtime=''){
        ignore_user_abort(TRUE);// 设定关闭浏览器也执行程序
        set_time_limit(0);      // 设定响应时间不限制，默认为30秒
        $where_t = '';
        $where_td = '';
        $where_md = '';
        $MaterailsForm = new materialModel();
        if(!empty($td)){
            $where_td .= " AND price='".$td."'";
        }
        if(!empty($md)){
            $where_md .= " AND price ='".$md."'";
        }
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where_t .= " AND xb.materialsName='".$res['materialsName']."'";
        }else{
            $res['materialsName'] = '';
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
        $query  = "drop table if EXISTS draw_hourstatic_temp;";
        $query .= "create table draw_hourstatic_temp as (
                        select * from
                    (
                        select gf.hour as dt1,count(*) as totalsends  from giftrecord as gf left join xc_barcode as xb on gf.barcode = xb.barcode
                                    where gf.giftid_real in (select id from gift where id is not null) and (gf.type = 2 or gf.type = 3)  ".$where_t." GROUP BY dt1
                    )as tb1
                    left join
                    (
                        select gf.hour as dt2,count(*) as tdsends,sum(gf.chargenum) as tdsendalls from giftrecord as gf left join xc_barcode as xb on gf.barcode = xb.barcode
                                    where gf.giftid_real in (select id from gift where gift_type = 2 ".$where_td." ) and gf.type = 2  ".$where_t." GROUP BY dt2
                    )as tb2
                    on tb1.dt1 = tb2.dt2
                    left join
                    (
                        select gf.hour as dt3,count(*) as mdsends,sum(gf.chargenum) as mdsendalls from giftrecord as gf left join xc_barcode as xb on gf.barcode = xb.barcode
                                    where gf.giftid_real in (select id from gift where gift_type = 3 ".$where_md.") and gf.type = 3 ".$where_t." GROUP BY dt3
                    )as tb3
                    on tb1.dt1 = tb3.dt3
                    left join
                    (
                        select sum(point) as totaltdchargeCn,FROM_UNIXTIME(createtime/1000,'%Y-%m-%d %H') as dt4 from chargerecord where type = 2 and flag = 0 and laststate in (1,2)  GROUP BY dt4
                    )as tb4
                    on tb1.dt1 = tb4.dt4
                    left join
                    (
                        select sum(point) as totalmdchargeCn,FROM_UNIXTIME(createtime/1000,'%Y-%m-%d %H') as dt5 from chargerecord where type = 3 and flag = 0 and laststate in (0)  GROUP BY dt5
                    )as tb5
                    on tb1.dt1 = tb5.dt5
                  );";
        $database->query($query)->execute();
        header('Content-Type:text/html;charset=UTF-8');
        $this->redirect(__SADMIN__.'/Drawdatastatic/hourstatic');
        exit;
    }


    public function hourstatic($pages = ''){
        $MaterailsForm = new materialModel();
        $GiftForm  = new giftModel();
        // 分页地址
        $url = __SADMIN__.'/Drawdatastatic/hourstatic';
        $p = empty($pages) ? 1 : $pages;
        $database = new mysql();
        $sql_init = "show tables like 'draw_hourstatic_temp'";
        $res = $database->query($sql_init)->fetchColumn();
        if(empty($res)){
            $this->draw_hourstatic_init();
        }
        //筛选 分页
        $sql = "select count(*) from draw_hourstatic_temp";
        //获取信息总数
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query =  " select * from draw_hourstatic_temp limit {$firstRow},{$listPage} ";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $materialsnames = $MaterailsForm->getAll();
        $TdInfo = $GiftForm->getlists(array('gift_type'=>'2','GROUP'=>'price'));
        $MdInfo = $GiftForm->getlists(array('gift_type'=>'3','GROUP'=>'price'));
        $this->assign('materialsnames',$materialsnames);
        $this->assign('TdInfo',$TdInfo);
        $this->assign('MdInfo',$MdInfo);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $database = null;
        $this->render();
    }

    public function hourstatic_export(){
        $database = new mysql();
        $query =  " select * from draw_hourstatic_temp";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('日期', '奖品发放份数','娇子币发放份数','娇子币发放个数','话费发放份数','话费发放金额','话费充值金额');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['dt1'],$l['totalsends'],$l['tdsends'],$l['tdsendalls'],$l['mdsends'],$l['mdsendalls'],$l['totaltdchargeCn'],$l['totalmdchargeCn']);
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