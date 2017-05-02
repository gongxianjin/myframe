<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/4
 * Time: 11:52
 */

//数据库

namespace core\lib\drive\log;

use \Sadmin\models\giftlogModel;

class mysql{

    public function mysql($name){
        dump($name);
    }

    //充值记录的所有动作记录到数据库里面
    public function chargelog($params = array()){
        //检查参数
        $model = new giftlogModel();
        if(!$model->_validate($params)) {  // 自动检验
            $msg = implode('<br />',$model->getErr());
            dump($msg);
            exit;
        }
        $params = $model->_autoFill($params);
        $data = $model->_facade($params);  // 自动过滤
        if($model->add($data)){
            echo "记录完成";
        }
    }

}
