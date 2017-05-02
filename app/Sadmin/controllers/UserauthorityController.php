<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use core\lib\mysql;
use Sadmin\models\powerModel;
use Sadmin\models\sysadminModel;

class UserauthorityController extends BaseadminController
{
    public function index($sword = '') {
        //筛选
        $data = array();
        if($sword){
            $data = array(
                'phone'=>$sword
            );
            $this->assign('sword',$sword);
        }
        $SadminForm=new sysadminModel();
        $lists = $SadminForm->getlists($data);
        $this->assign('lists',$lists);
        $this->render();
    }

    public function edit($ids=''){
        $sys = new sysadminModel();
        $power = new powerModel();
        $user = $sys->getOne($ids);
        if(!$user){
            $this->redirect(__SADMIN__.'/Userauthority/index');exit;
        }
        $cname = $aname = array();
        $cname=explode("|",$user['action_list']);
        $powers = $power->getAll();
        $powers = $this->_find_parent($powers);
        $this->assign('sysadmin',$user);
        $this->assign('power',$powers);
        $this->assign('cname',$cname);
        $this->render();
    }


    public function save_edit($ids='',$tname='',$phone='',$oldpwd='',$newpwd='',$start_time='',$end_time,$power_id){

        $sys = new sysadminModel();
        if(!empty($ids)){
            $sysuser = $sys->getOne($ids);
            if(empty($sysuser)){
                $this->jsonMsg(502, '参数有误');
            }
        }else{
             $this->jsonMsg(501,'ids参数错误');
        }
        if($tname == null || $phone==null  || $start_time==null || $end_time==null|| $power_id== null){
            $this->jsonMsg(501, '参数有误');
        }

        if($oldpwd != null && $newpwd != null){
            if(!preg_match('/\w{8,12}/',$newpwd)){
                $this->jsonMsg(501, '新密码格式不正确');
            }
            if($sysuser['pwd']!=md5($oldpwd)){
                $this->jsonMsg(503, '密码有误');
            }
            if($oldpwd == $newpwd){
                $this->jsonMsg(504, '新旧密码不能一致');
            }
            $save["pwd"]=md5($newpwd);
        }

        $save['tname'] = $tname;
        $save['phone'] = $phone;
        $save['start_time'] = date_format(date_create($start_time),'Y-m-d');
        $save['end_time'] = date_format(date_create($end_time),'Y-m-d');
        $power_l='';
        foreach ($power_id as $k=>$power_i){
            $power_l .=$power_i.'|';
        }
        $power_l=substr($power_l,0,-1);
        $save['action_list'] = $power_l;
        if($sys->setOne($ids,$save)){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Userauthority/index');
        }else{
            $this->jsonMsg(400, '操作失败');
        }

    }

    /**
     * 添加
     * Enter description here ...
     */
    public function add(){
        $power = new powerModel();
        $powers = $power->getAll();
        $powers = $this->_find_parent($powers);
        $this->assign('power',$powers);
        $this->render();
    }


    public function save_add($tname='',$phone='',$newpwd='',$confirm_pwd,$start_time='',$end_time,$power_id){

        $sys = new sysadminModel();

        if($tname == null || $phone==null  ||  $newpwd == null || $confirm_pwd == null || $start_time==null || $end_time == null || $power_id == null){
            $this->jsonMsg(501, '参数有误');
        }
        //手机号码正确
        if(!preg_match("/^1[34578]\\d{9}$/", $phone)){
            $this->jsonMsg(502, '手机号码不符');
        }
        //手机号不能重复
        $usephone = $sys->get('xc_sysadmin','*',array('phone' => $phone));
        if($usephone){
            $this->jsonMsg(502, '手机号码重复');
        }
        if($newpwd != $confirm_pwd){
            $this->jsonMsg(503, '输入密码不一致');
        }
        //密码正确
        if(!preg_match('/\w{8,12}/', $newpwd)){
            $this->jsonMsg(504, '密码不符');
        }
        $save['tname'] = $tname;
        $save['phone'] = $phone;
        $save['pwd'] = md5($newpwd);
        $save['start_time'] = date_format(date_create($start_time),'Y-m-d');
        $save['end_time'] = date_format(date_create($end_time),'Y-m-d');
        $power_l='';
        foreach ($power_id as $k=>$power_i){
            $power_l .=$power_i.'|';
        }
        $power_l=substr($power_l,0,-1);
        $save['action_list'] = $power_l;
        if($sys->add($save)){
            $this->jsonMsg(200, '操作成功','',__SADMIN__.'/Userauthority/index');
        }else{
            $this->jsonMsg(400, '操作失败');
        }
    }


    public function del($ids){

        if($ids == null){
            $this->jsonMsg(400,'参数错误');
        }
        $sys = new sysadminModel();
        $user = $sys->getOne($ids);
        if(!$user){
            $this->jsonMsg(500,'没有找到数据');
        }

        if(!$sys->delOne($ids)){
            $this->jsonMsg(251, '删除失败');
        }

        $this->success('操作成功.');
    }

    /**
     * 组装权限数据
     * Enter description here ...
     * @param unknown_type $ar
     */
    public function _find_parent($ar) {
        $return = array();

        foreach($ar as $item){
            if(!isset($return[$item['c_name']])){
                $return[ $item['c_name'] ] = array(
                    'c_alias' => $item['c_alias'],
                    'item' => array(),
                );
            }
            $return[$item['c_name']]['item'][$item['a_name']] = $item['a_alias'];
        }

        return $return;
    }

    /**
     * 导入基本权限
     * Enter description here ...
     */
    public function  Initrole(){
        //清空xc_power表
        $database = new mysql();
        $sql = 'truncate xc_power';
        $database->query($sql)->execute();

        $data = array();
        foreach ($this->oper_meta_set as $key=>$val){
            foreach ($val['items'] as $k=>$item){
                $model = new powerModel();
                $data['c_name'] = $key;
                $data['c_alias']  = $val['alias'];
                $data['a_name'] = $k;
                $data['a_alias'] = $item;
                $model->add($data);
            }
        }
        header('Content-Type:text/html;charset=UTF-8');
        $this->redirect(__SADMIN__.'/Userauthority/index');
        exit;
    }


}