<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use Sadmin\models\materialModel;

class MaterialController extends BaseadminController
{
    public function index(){
        $MaterialForm = new materialModel();
        $lists = $MaterialForm->getAll();
        $this->assign('lists',$lists);
        $this->render();
    }

    public function edit($ids=''){
        $MaterialForm = new materialModel();
        $lists = $MaterialForm->getOne($ids);
        if(!$lists){
            $this->redirect(__SADMIN__.'/Material/index');exit;
        }
        $this->assign('lists',$lists);
        $this->render();
    }


    public function save_edit($ids='',$materialsName='',$starttime='',$endtime){ 
        $MaterialForm = new materialModel();
        if(!empty($ids)){
            $lists = $MaterialForm->getOne($ids);
            if(empty($lists)){
                $this->jsonMsg(502, '参数有误');
            }
        }else{
             $this->jsonMsg(501,'ids参数错误');
        }
        if($materialsName == null || $starttime==null  || $endtime==null){
            $this->jsonMsg(501, '参数有误');
        }

        $save['materialsName'] = $materialsName;
        $save['starttime'] = strtotime($starttime)*1000;
        $save['endtime'] = strtotime($endtime)*1000;

        if($MaterialForm->setOne($ids,$save)){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Material/index');
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
        $lists = $MaterialForm->getAll();
        $this->assign('lists',$lists);
        $this->render();
    }


    public function save_add($materialsName='',$starttime='',$endtime){

        $MaterialForm = new materialModel();

        if($materialsName == null || $starttime==null  ||  $endtime == null){
            $this->jsonMsg(501, '参数有误');
        }

        //规格不能重复
        $data['materialsName'] = $materialsName;
        $materials = $MaterialForm->getlists($data);
        if($materials){
            $this->jsonMsg(502, '规格重复');
        }
        $save['materialsName'] = $materialsName;
        $save['starttime'] = strtotime($starttime)*1000;
        $save['endtime'] = strtotime($endtime)*1000;

        if($MaterialForm->add($save)){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Material/index');
        }else{
            $this->jsonMsg(400, '操作失败');
        }
    }


    public function del($ids){

        if($ids == null){
            $this->jsonMsg(400,'参数错误');
        }
        $MaterialForm = new materialModel();
        $list = $MaterialForm->getOne($ids);
        if(!$list){
            $this->jsonMsg(500,'没有找到数据');
        }

        if(!$MaterialForm->delOne($ids)){
            $this->jsonMsg(251, '删除失败');
        }

        $this->success('操作成功.');
    }


}