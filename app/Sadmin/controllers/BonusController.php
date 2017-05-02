<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use Sadmin\models\bonusModel;

class BonusController extends BaseadminController
{
    public function index(){
        $bonusForm = new bonusModel();
        $lists = $bonusForm->getAll();
        $this->assign('lists',$lists);
        $this->render();
    }

    public function edit($ids=''){
        $bonusForm = new bonusModel();
        $lists = $bonusForm->getOne($ids);
        if(!$lists){
            $this->redirect(__SADMIN__.'/Bonus/index');exit;
        }
        $this->assign('lists',$lists);
        $this->render();
    }


    public function save_edit($ids='',$type_name='',$chargenum='',$sendnum='',$min_amount='',$max_amount='',$use_start_date='',$use_end_date = ''){
        $bonusForm = new bonusModel();
        if(!empty($ids)){
            $lists = $bonusForm->getOne($ids);
            if(empty($lists)){
                $this->jsonMsg(502, '参数有误');
            }
        }else{
             $this->jsonMsg(501,'ids参数错误');
        }
        if($type_name == null || $chargenum == null  ||  $sendnum == null || $min_amount == null || $max_amount == null){
            $this->jsonMsg(501, '参数有误');
        }

        $save['type_name'] = $type_name;
        $save['chargenum'] = $chargenum;
        $save['sendnum'] = $sendnum;
        $save['min_amount'] = $min_amount;
        $save['max_amount'] = $max_amount;
        $save['use_start_date'] = strtotime($use_start_date)*1000;
        $save['use_end_date'] = strtotime($use_end_date)*1000;

        if($bonusForm->setOne($ids,$save)){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Bonus/index');
        }else{
            $this->jsonMsg(400, '操作失败');
        }

    }

    /**
     * 添加
     * Enter description here ...
     */
    public function add(){
        $bonusForm = new bonusModel();
        $lists = $bonusForm->getAll();
        $this->assign('lists',$lists);
        $this->render();
    }


    public function save_add($type_name='',$chargenum='',$sendnum='',$min_amount='',$max_amount='',$use_start_date='',$use_end_date = ''){

        $bonusForm = new bonusModel();

        if($type_name == null || $chargenum == null  ||  $sendnum == null || $min_amount == null || $max_amount == null){
            $this->jsonMsg(501, '参数有误');
        }

        //名称不能重复
        $data['type_name'] = $type_name;
        $Bonus = $bonusForm->getlists($data);
        if($Bonus){
            $this->jsonMsg(502, '奖品名称重复');
        }
        $save['type_id'] = create_uuid();
        $save['type_name'] = $type_name;
        $save['chargenum'] = $chargenum;
        $save['sendnum'] = $sendnum;
        $save['min_amount'] = $min_amount;
        $save['max_amount'] = $max_amount;
        $save['use_start_date'] = strtotime($use_start_date)*1000;
        $save['use_end_date'] = strtotime($use_end_date)*1000;
        $save['createtime'] = getMillisecond();

        if($bonusForm->add($save) == 0){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Bonus/index');
        }else{
            $this->jsonMsg(400, '操作失败');
        }

    }


    public function del($ids){

        if($ids == null){
            $this->jsonMsg(400,'参数错误');
        }
        $bonusForm = new bonusModel();
        $list = $bonusForm->getOne($ids);
        if(!$list){
            $this->jsonMsg(500,'没有找到数据');
        }

        if(!$bonusForm->delOne($ids)){
            $this->jsonMsg(251, '删除失败');
        }

        $this->success('操作成功.');
    }


}