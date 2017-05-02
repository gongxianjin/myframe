<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/15
 * Time: 15:33
 */

namespace app\Sadmin\controllers;

use  Sadmin\models\sysadminModel;
use  Sadmin\models\sysadminlogModel;


class PublicController extends  \core\lib\Controller
{
    public function login() { 
        $this->render();
    }

    public function CheckLogin($phone='',$code,$password=''){ 
        if(empty($phone)) {
            $this->error('帐号必须！');
        }
        if(empty($code)) {
            $this->error('验证码必须！');
        }
//        if($code != $_SESSION['code']){
//            $this->error('验证码错误!');
//        }
        if(empty($password)){
            $this->error('密码必须！');
        } 
        $sysadmin = new sysadminModel();
        $sysadminlog = new sysadminlogModel();
        $map['AND']['phone'] = $phone;
        $map['AND']['pwd'] = md5($password);
        $pass = $sysadmin->getKey($map);
        if($pass){
            $data['log_time'] = time();
            $data['user_id'] = $pass['id'];
            $data['log_info'] = $pass['user'].'登录系统';
            $data['ip_address'] = get_client_ip();
            $sysadminlog->add($data);
            $_SESSION['SADMIN_ID']=$pass['id'];
            $_SESSION["SADMIN_PHONE"]=$pass['phone'];
            $_SESSION["SADMIN_USER"]=$pass['tname'];
            $this->success("登录成功",__SADMIN__.'/index/index');
        }else{
            $this->error("此登录帐号或密码错误！");
        }
    }

    // 用户登出
    public function logout()
    {
        if(isset($_SESSION['SADMIN_ID'])) {
            unset($_SESSION['SADMIN_ID']);
            unset($_SESSION["SADMIN_PHONE"]);
            unset($_SESSION["SADMIN_USER"]);
            $this->success('登出成功！');
        }else {
            $this->error('已经登出！');
        }
    }


}