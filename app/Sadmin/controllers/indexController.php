<?php

/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/4
 * Time: 9:41
 */
namespace app\Sadmin\controllers;

use Sadmin\models\chargerecordModel;

class indexController extends BaseadminController
{

    public function index(){
        $title = '95万象通营销活动大数据管理系统';
        $POWER_Userauthority = false;
        $POWER_Prizeset = false;
        $POWER_Material = false;
        $POWER_Bonus = false;
        $POWER_Validformdate = false;
        $POWER_User = false;
        $POWER_Export = false;
        $POWER_Datastatic = false;
        $model = new chargerecordModel();
        $data = $model->getRow();
        if($this->checkOperModule('Userauthority')){
            $POWER_Userauthority = true;
        }
        if($this->checkOperModule('Prizeset')){
            $POWER_Prizeset = true;
        }
        if($this->checkOperModule('Material')){
            $POWER_Material = true;
        }
        if($this->checkOperModule('Bonus')){
            $POWER_Bonus = true;
        }
        if($this->checkOperModule('Validformdate')){
            $POWER_Validformdate = true;
        }
        if($this->checkOperModule('User')){
            $POWER_User = true;
        }
        if($this->checkOperModule('Export')){
            $POWER_Export = true;
        }
        if($this->checkOperModule('Datastatic')){
            $POWER_Datastatic = true;
        }
        $this->assign('POWER_Userauthority',$POWER_Userauthority);
        $this->assign('POWER_User',$POWER_User);
        $this->assign('POWER_Prizeset',$POWER_Prizeset);
        $this->assign('POWER_Material',$POWER_Material);
        $this->assign('POWER_Bonus',$POWER_Bonus);
        $this->assign('POWER_Validformdate',$POWER_Validformdate);
        $this->assign('POWER_Export',$POWER_Export);
        $this->assign('POWER_Datastatic',$POWER_Datastatic);
        $this->assign('data',$data);
        $this->assign('title',$title);
        $this->render();
    }



}