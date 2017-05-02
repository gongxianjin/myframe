<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use Sadmin\models\validformdateModel;

class ValidformdateController extends BaseadminController
{
    public function index(){ 
        $ValidformdateForm = new validformdateModel();
        $lists = $ValidformdateForm->getlists(array("ORDER" => array('createtime'=>'DESC')));
        $this->assign('lists',$lists);
        $this->render();
    }

    public function edit($ids=''){
        $ValidformdateForm = new validformdateModel();
        $lists = $ValidformdateForm->getOne($ids);
        if(!$lists){
            $this->redirect(__SADMIN__.'/Validformdate/index');exit;
        }
        $this->assign('lists',$lists);
        $this->render();
    }


    public function save_edit($ids='',$starttime='',$endtime){
        $ValidformdateForm = new validformdateModel();
        if(!empty($ids)){
            $lists = $ValidformdateForm->getOne($ids);
            if(empty($lists)){
                $this->jsonMsg(502, '参数有误');
            }
        }else{
             $this->jsonMsg(501,'ids参数错误');
        }
        if($starttime==null  || $endtime==null){
            $this->jsonMsg(501, '参数有误');
        }

        $save['starttime'] = strtotime($starttime)*1000;
        $save['endtime'] = strtotime($endtime)*1000;

        if($ValidformdateForm->setOne($ids,$save)){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Validformdate/index');
        }else{
            $this->jsonMsg(400, '操作失败');
        }

    }

    /**
     * 添加
     * Enter description here ...
     */
    public function add(){
        $ValidformdateForm = new validformdateModel();
        $lists = $ValidformdateForm->getAll();
        $this->assign('lists',$lists);
        $this->render();
    }


    public function save_add($starttime='',$endtime){

        $ValidformdateForm = new validformdateModel();

        if($starttime==null  ||  $endtime == null){
            $this->jsonMsg(501, '参数有误');
        }
        if($starttime > $endtime){
            $this->jsonMsg(503, '时间有误');
        }
        //添加的开始时间不能小于所有的结束时间
        $where['endtime[>]'] =  $starttime;
        $ValidformdateInfo = $ValidformdateForm->getlists($where);
        if(!empty($ValidformdateInfo)){
            $this->jsonMsg('504','开始时间错误');
        }
        $save['starttime'] = $starttime;
        $save['endtime'] = $endtime;
        $save['createtime'] = time();
        if($ValidformdateForm->add($save)){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Validformdate/index');
        }else{
            $this->jsonMsg(400, '操作失败');
        }
    }


    public function del($ids){
        if($ids == null){
            $this->jsonMsg(400,'参数错误');
        }
        $ValidformdateForm = new validformdateModel();
        $list = $ValidformdateForm->getOne($ids);
        if(!$list){
            $this->jsonMsg(500,'没有找到数据');
        }

        if(!$ValidformdateForm->delOne($ids)){
            $this->jsonMsg(251, '删除失败');
        }

        $this->success('操作成功.');
    }


}