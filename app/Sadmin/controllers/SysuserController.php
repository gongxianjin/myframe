<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 14:44
 */

namespace app\Sadmin\controllers;

use  Sadmin\models\sysadminModel;

class SysuserController extends BaseadminController
{
    function selfuser(){
        $FromUser = new sysadminModel();
        $admin_user   =  $FromUser->getOne($_SESSION['SADMIN_ID']);
        $this->assign('adminuser',$admin_user);
        $this->render();
    }

    function save_selfuser($id='',$oldpwd='',$newpwd='',$password_confirm=''){
        $model = new sysadminModel();
        if($id == null || $oldpwd==null || $newpwd==null || $password_confirm==null){
            $this->jsonMsg(501, '参数有误');
        }
        if($newpwd != $password_confirm){
            $this->jsonMsg(501, '输入密码不一致');
        }
        if(!preg_match('/\w{8,12}/',$newpwd)){
            $this->jsonMsg(501, '新密码格式不正确');
        }
        $sysuser = $model->getOne($id);
        if(empty($sysuser)){
            $this->jsonMsg(502, '参数有误');
        }
        if($sysuser['pwd']!=md5($oldpwd)){
            $this->jsonMsg(503, '密码有误');
        }
        if($oldpwd == $newpwd){
            $this->jsonMsg(504, '新旧密码不能一致');
        }
        $save["pwd"]=md5($newpwd);
        if($model->setOne($id,$save)){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Sysuser/selfuser');
        }else{
            $this->jsonMsg(400, '操作失败');
        }
    }

}