<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/15
 * Time: 16:59
 */

namespace app\Sadmin\controllers;

use Sadmin\models\sysadminModel;

class BaseadminController extends \core\lib\Controller
{

    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        //加密处理
        $arg = array(
            'company'=>'成都先讯物联网技术有限公司',
            'author'=>'victor'
        );
        $arg = join($arg);
        $str = md5($arg);
        $str = sha1($str);
        $secret = isset($_GET['secret'])?$_GET['secret']:'';
        if($secret != $str){
            if(!$this->checkOperModule($controller, $action)){
                $this->error('权限不足');die;
            }
        }

    }

    protected function _initialize(){
//        $response = \api\TobaccoMobile::Syncharge('18628246831','2','2016112310222','');
//        $response = "{\"code\":\"0\",\"msg\":\"成功\",\"data\":{\"amount\":\"5\",\"createdAt\":\"20161121164939\",\"mobile\":\"13049836808\"}}";
//        $response = json_decode($response,true);
//        dump($response);die;

        //加密处理
        $arg = array(
            'company'=>'成都先讯物联网技术有限公司',
            'author'=>'victor'
        );
        $arg = join($arg);
        $str = md5($arg);
        $str = sha1($str);
        $secret = isset($_GET['secret'])?$_GET['secret']:'';
        if($secret != $str){
            if(empty($_SESSION['SADMIN_ID'])) {
                $this->redirect(__SADMIN__.'/Public/login');
            }
        }
    }

    // 检测单元操作权限
    public function checkOperModule($c, $a='index'){
        $sysrole = new sysadminModel();
        if(!$c || !$a) return false;

        $ingnore_controller = array('index');
        $ingnore_action = array('select', 'order');

        if(in_array($c, $ingnore_controller) || in_array($a, $ingnore_action)) return true;

        if($_SESSION['SADMIN_ID'] ==  1) return true;

        static $G_POWER;
        if(!$G_POWER){
            $G_POWER = $sysrole->getOne($_SESSION['SADMIN_ID']);
        }
        return $G_POWER && stristr($G_POWER['action_list'], $c.'-'.$a) ? true :false;
    }

}