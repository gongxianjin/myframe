<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use core\lib\model;
use core\lib\mysql;

use Sadmin\models\giftModel;
use Sadmin\models\materialModel;

class PrizesetController extends BaseadminController
{
    public function index(){
        //筛选
        $Totalprizes = 0;
        $Totalsends = 0;
        $gift = new giftModel();
        $MaterialForm = new materialModel();
        $prize = $gift->getAll();
        if($prize){
            //获取奖品总数
            foreach($prize as $key=>$item){
                $Totalprizes += $item['total'];
                //每个的已发放个数
                $prize[$key]['every_sends'] = $this->get_this_totals($item['id'],$item['gift_type']);
                //每天已发放最大个数
                $prize[$key]['everyday_sends'] = $this->get_everyday_max_totals($item['id'],$item['gift_type']);
                //每小时已发放最大个数
//                $prize[$key]['everyhour_sends'] = $this->get_everyhour_max_totals($item['id'],$item['gift_type']);
                //每个手机号已发放最大个数
                $prize[$key]['everymobile_sends'] = $this->get_moiblemaxgifts($item['id'],$item['gift_type']);
                //总的已发放个数
                $Totalsends += $prize[$key]['every_sends'];
                switch($item['gift_type']){
                    case 1:
                        $prize[$key]['gift_type_name'] = '实物';
                        break;
                    case 2:
                        $prize[$key]['gift_type_name'] = '娇子币';
                        break;
                    case 3:
                        $prize[$key]['gift_type_name'] = '话费';
                        break;
                    case 4:
                        $prize[$key]['gift_type_name'] = '红包';
                        break;
                    default:
                        break;
                }
                $MaterialInfo = $MaterialForm->getOne($item['gift_attr']);
                $prize[$key]['gift_attr_name']  = $MaterialInfo['materialsName'];
            }
        } 
        $database = new mysql();
        // 抽奖人数
        $sql = "select count(DISTINCT userid) from giftrecord ";
        $userCn = $database->query($sql)->fetchColumn();

        //抽奖次数
//        $sql = "select count(id) from giftrecord ";
//        $prizeCn = $admin_db->getOne($sql);

        //中奖人数
        $sql = "select count(id) from giftrecord where giftid is not NULL";
        $giftCn = $database->query($sql)->fetchColumn();

        $this->assign('Totalprizes',$Totalprizes);
        $this->assign('Totalsends',$Totalsends);
        $this->assign('userCn',$userCn);
        $this->assign('giftCn',$giftCn);
        $this->assign('prize',$prize);
        $this->render();
    }


    private function get_everyday_max_totals($giftid,$type) {
        $database = new mysql();
        $sql = 'select count(*) as a from giftrecord ' . " where '" . $giftid . "' = giftid_real and type = '".$type."' GROUP BY  left(createtime,10) order by a desc limit 1 ";
        $res = $database->query($sql)->fetchColumn();
        $database = null;
        return $res;
    }

    private function get_everyhour_max_totals($giftid,$type) {
        $database = new mysql();
        $sql = 'select count(*) as a from giftrecord'." where '" . $giftid . "' = giftid_real  and type = '".$type."' GROUP BY  left(createtime,10) order by a desc limit 1 ";
        $res = $database->query($sql)->fetchColumn();
        $database = null;
        return $res;
    }

    private function get_moiblemaxgifts($giftid,$type){
        $database = new mysql();
        $sql = 'select count(*) as a from giftrecord'." where '" . $giftid . "' = giftid_real  and   type = '".$type."' GROUP BY phone order by a desc limit 1";
        $res = $database->query($sql)->fetchColumn();
        $database = null;
        return $res;
    }

    private function get_this_totals($giftid,$type){
        $database = new mysql();
        $sql = 'select count(*) from giftrecord'." where '" . $giftid . "' = giftid_real  and type = '".$type."'";
        $res = $database->query($sql)->fetchColumn();
        $database = null;
        return $res;
    }


    public function edit($ids=''){
        $gift = new giftModel();
        $prize = $gift->getOne($ids);
        if(!$prize){
            $this->redirect(__SADMIN__.'/Prizeset/index');exit;
        }
        $MaterialForm = new materialModel();
        $mlists = $MaterialForm->getAll();
        $this->assign('mlist',$mlists);
        $this->assign('prize',$prize);
        $this->render();
    }


    public function save_edit($ids='',$total='',$daymax='',$hourmax='',$rate='',$openid_totalmax='',$openid_hourmax='',$phone_totalmax='',$phone_hourmax='',$order=''){
        $gift = new giftModel();
        if(!empty($ids)){
            $prize = $gift->getOne($ids);
            if(empty($prize)){
                $this->jsonMsg(502, '参数有误');
            }
        }else{
             $this->jsonMsg(501,'ids参数错误');
        }
        if($total == null || $daymax==null  || $hourmax==null || $rate==null|| $openid_totalmax== null || $openid_hourmax==null || $phone_totalmax == null || $phone_hourmax == null || $order == null){
            $this->jsonMsg(501, '参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$total)){
            $this->jsonMsg(502, 'total参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$daymax)){
            $this->jsonMsg(502, 'daymax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$hourmax)){
            $this->jsonMsg(502, 'hourmax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$openid_totalmax)){
            $this->jsonMsg(502, 'openid_totalmax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$openid_hourmax)){
            $this->jsonMsg(502, 'openid_hourmax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$phone_totalmax)){
            $this->jsonMsg(502, 'phone_totalmax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$phone_hourmax)){
            $this->jsonMsg(502, 'phone_hourmax参数有误');
        }

        $save["id"] = $ids;
        $save["total"] = $total;
        $save["daymax"] = $daymax;
        $save["hourmax"] = $hourmax;
        $save["openid_totalmax"] = $openid_totalmax;
        $save["openid_hourmax"] = $openid_hourmax;
        $save["phone_totalmax"] = $phone_totalmax;
        $save["phone_hourmax"] = $phone_hourmax;
        $save["order"] = $order;
        $save["rate"] = $rate;

        if(!$gift->_validate($save)){
            $this->jsonMsg(503, '参数有误');
        }
        if($gift->setOne($ids,$save)){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Prizeset/index');
        }else{
            $this->jsonMsg(400, '操作失败');
        }

    }

    /**
     * 添加
     * Enter description here ...
     */
    public function add(){
        $MaterialForm = new materialModel();
        $mlists = $MaterialForm->getAll();
        $this->assign('mlist',$mlists);
        $this->render();
    }


    public function save_add($gift_attr='',$gift_type='',$giftname='',$price='',$total='',$daymax='',$hourmax='',$rate='',$openid_totalmax='',$openid_hourmax='',$phone_totalmax='',$phone_hourmax='',$order=''){
        $gift = new giftModel();
        if($gift_attr==null||$gift_type==null||$giftname==null||$price==null||$total == null || $daymax==null  || $hourmax==null || $rate==null|| $openid_totalmax== null || $openid_hourmax==null || $phone_totalmax == null || $phone_hourmax == null || $order == null){
            $this->jsonMsg(501, '参数有误');
        }
        if(!preg_match("/[1,2,3,4,5,6,7,8,9,10,11,12,13]/",$gift_attr)){
            $this->jsonMsg(502, 'gift_attr参数有误');
        }
        if(!preg_match("/[1,2,3,4]/",$gift_type)){
            $this->jsonMsg(502, 'gift_type参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$price)){
            $this->jsonMsg(502, 'price参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$total)){
            $this->jsonMsg(502, 'total参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$daymax)){
            $this->jsonMsg(502, 'daymax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$hourmax)){
            $this->jsonMsg(502, 'hourmax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$openid_totalmax)){
            $this->jsonMsg(502, 'openid_totalmax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$openid_hourmax)){
            $this->jsonMsg(502, 'openid_hourmax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$phone_totalmax)){
            $this->jsonMsg(502, 'phone_totalmax参数有误');
        }
        if(!preg_match("/^[1-9][0-9]*$/",$phone_hourmax)){
            $this->jsonMsg(502, 'phone_hourmax参数有误');
        }

        $save["id"] = create_uuid();
        $save["gift_attr"] = $gift_attr;
        $save["gift_type"] = $gift_type;
        $save["giftname"] = $giftname;
        $save["price"] = $price;
        $save["total"] = $total;
        $save["daymax"] = $daymax;
        $save["hourmax"] = $hourmax;
        $save["openid_totalmax"] = $openid_totalmax;
        $save["openid_hourmax"] = $openid_hourmax;
        $save["phone_totalmax"] = $phone_totalmax;
        $save["phone_hourmax"] = $phone_hourmax;
        $save["order"] = $order;
        $save["rate"] = $rate;

        if(!$gift->_validate($save)){
            $this->jsonMsg(503, $gift->getErr());
        }
        if($gift->add($save) == 0){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Prizeset/index');
        }else{
            $this->jsonMsg(400, '操作失败');
        }

    }


    public function del($ids){

        if($ids == null){
            $this->jsonMsg(400,'参数错误');
        }
        $gift = new giftModel();
        $prize = $gift->getOne($ids);
        if(!$prize){
            $this->jsonMsg(500,'没有找到数据');
        }

        if(!$gift->delOne($ids)){
            $this->jsonMsg(251, '删除失败');
        }

        $this->success('操作成功.');
    }



}