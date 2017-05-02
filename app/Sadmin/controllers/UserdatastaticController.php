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
use Sadmin\models\userModel;
use core\lib\page;
use core\lib\Excel_XML;

class UserdatastaticController extends BaseadminController
{
    public function index($pages = '',$tel=''){
        $where = '';
        // 分页地址
        $url = __SADMIN__.'/Userdatastatic/index';
        $p = empty($pages) ? 1 : $pages;
        if(!empty($tel)){
            $where .= " AND u.phone='".$tel."'";
            $url.= '/tel/'.$tel;
        }
        $database = new mysql();
        //筛选 分页
        $sql = "select count(0) as cont
                  from user as u
	              left join c_phone_location as cp on left(u.phone,7) = cp.phone_header
	              where u.phone is not null".$where;
        //获取信息总数
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query = "select u.phone,cp.provance,cp.city,cp.mobile_type,u.id
                    from user as u
	                left join c_phone_location as cp on left(u.phone,7) = cp.phone_header
	                where u.phone is not null ".$where." limit {$firstRow},{$listPage}";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
        foreach($lists as $key=>$item){

            $drawqurey = "select count(*) from drawlog where userid = '".$item['id']."'";
            $drawcount = $database->query($drawqurey)->fetchColumn();
            $lists[$key]['drawcount'] = $drawcount;

            $lottaryquery = "select count(*) from giftrecord where phone = '".$item['phone']."'";
            $lottarycount = $database->query($lottaryquery)->fetchColumn();
            $lists[$key]['lottarycount'] = $lottarycount;

            $barsumqurey = "select sum(xb.count) as cont from xc_barcode as xb left join giftrecord as gf on gf.barcode = xb.barcode where gf.phone = '".$item['phone']."'";
            $barsum = $database->query($barsumqurey)->fetchColumn();
            $lists[$key]['barsum'] = $barsum;

            $barcountqurey = "select count(DISTINCT gf.barcode) as cont from giftrecord  as gf  where gf.userid = '".$item['id']."'";
            $barcount = $database->query($barcountqurey)->fetchColumn();
            $lists[$key]['barcount'] = $barcount;
        }
//        dump($lists);die;
        $database = null;
        $this->assign('tel',$tel);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $this->render();
    }

    /**
     * Enter description here ...
     */
    public function export($tel=''){
        $where = '';
        if(!empty($tel)){
            $where .= " AND u.phone='".$tel."'";
        }
        $database = new mysql();
        $query = "select u.phone,cp.provance,cp.city,cp.mobile_type,u.id
                    from user as u
	                left join c_phone_location as cp on left(u.phone,7) = cp.phone_header
	                where u.phone is not null ".$where;
        $lists = $database->query($query)->fetchAll();
        foreach($lists as $key=>$item){
            $drawqurey = "select count(*) from drawlog where userid = '".$item['id']."'";
            $drawcount = $database->query($drawqurey)->fetchColumn();
            $lists[$key]['drawcount'] = $drawcount;

            $lottaryquery = "select count(*) from giftrecord where phone = '".$item['phone']."'";
            $lottarycount = $database->query($lottaryquery)->fetchColumn();
            $lists[$key]['lottarycount'] = $lottarycount;

            $barsumqurey = "select sum(xb.count) as cont from xc_barcode as xb left join giftrecord as gf on gf.barcode = xb.barcode where gf.phone = '".$item['phone']."'";
            $barsum = $database->query($barsumqurey)->fetchColumn();
            $lists[$key]['barsum'] = $barsum;

            $barcountqurey = "select count(DISTINCT gf.barcode) as cont from giftrecord  as gf  where gf.userid = '".$item['id']."'";
            $barcount = $database->query($barcountqurey)->fetchColumn();
            $lists[$key]['barcount'] = $barcount;
        }
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('用户手机号','省份','城市','运营商','抽奖次数','中奖次数','扫描烟包次数','扫描烟包个数');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['phone'],$l['provance'],$l['city'],$l['mobile_type'],$l['drawcount'],$l['lottarycount'],$l['barsum'],$l['barcount']);
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


    public function detail($pages = '',$tel='',$mid='',$starttime='',$endtime=''){
        $where = '';
        if(empty($tel)){
            $this->error('链接错误!');die;
        }
        $UserForm = new userModel();
        $MaterailsForm = new materialModel();
        // 分页地址
        $url = __SADMIN__.'/Userdatastatic/detail/tel/'.$tel;
        $p = empty($pages) ? 1 : $pages;
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where .= " AND xb.materialsName='".$res['materialsName']."'";
            $url.= '/mid/'.$mid;
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where .= " AND gf.createtime> '".$starttime_t."'";
            $url .= '/starttime/'.$starttime;
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where .= " AND gf.createtime <'".$endtime_t."'";
            $url .= '/endtime/'.$endtime;
        }
        $UserInfo = $UserForm->getKey(array('phone'=>$tel));
        $database = new mysql();
        //筛选 分页
        $sql = "select count(0) as cont from giftrecord as gf
	              left join xc_barcode as xb on gf.barcode = xb.barcode
	              left join user as u on u.phone = gf.phone
	              where gf.userid = "."'$UserInfo[id]'".$where;
        //获取信息总数
        $count = $database->query($sql)->fetchColumn();
        //每页显示的记录数
        $listPage = 8;
        //起始页
        $firstRow = $listPage*($p-1);
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $query = "select gf.barcode,xb.materialsName,u.nickname,u.openid,u.ip,gf.createtime,gf.giftname from giftrecord as gf
                    left join xc_barcode as xb on gf.barcode = xb.barcode
                    left join user as u on u.phone = gf.phone
                    where gf.userid = "."'$UserInfo[id]'".$where." limit {$firstRow},{$listPage}";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        $materialsnames = $MaterailsForm->getAll();
        $this->assign('materialsnames',$materialsnames);
        $this->assign('starttime',$starttime);
        $this->assign('endtime',$endtime);
        $this->assign('mid',$mid);
        $this->assign('tel',$tel);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->assign('lists',$lists);
        $this->render();
    }


    /**
     * 添加
     * Enter description here ...
     */
    public function detail_export($tel='',$mid='',$starttime='',$endtime=''){
        $where = '';
        if(empty($tel)){
            $this->error('链接错误!');die;
        }
        $UserForm = new userModel();
        $MaterailsForm = new materialModel();
        if(!empty($mid)){
            $res = $MaterailsForm->getKey(array('id'=>$mid));
            $where .= " AND xb.materialsName='".$res['materialsName']."'";
        }
        if(!empty($starttime)){
            $starttime_t = ($starttime).' 00:00:00';
            $where .= " AND gf.createtime> '".$starttime_t."'";
        }
        if(!empty($endtime)){
            $endtime_t = ($endtime).' 00:00:00';
            $where .= " AND gf.createtime <'".$endtime_t."'";
        }
        $UserInfo = $UserForm->getKey(array('phone'=>$tel));
        $database = new mysql();
        $query = "select gf.barcode,xb.materialsName,u.nickname,u.openid,u.ip,gf.createtime,gf.giftname from giftrecord as gf
                    left join xc_barcode as xb on gf.barcode = xb.barcode
                    left join user as u on u.phone = gf.phone
                    where gf.userid = "."'$UserInfo[id]'".$where." order by gf.createtime desc";
//        echo $query;die;
        $lists = $database->query($query)->fetchAll();
//        dump($lists);die;
        $database = null;
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('二维码ID','烟包规格','昵称','openID','IP','抽奖时间','奖品名称');
            foreach ($lists as $key=>$l) {
                $datas[] = array($l['barcode'],$l['materialsName'],$l['nickname'],$l['openid'],$l['ip'],$l['createtime'],$l['giftname']);
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