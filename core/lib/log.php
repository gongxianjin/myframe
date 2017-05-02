<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/4
 * Time: 11:49
 */

namespace core\lib;
use core\lib\conf;
class log{

    static  $class;

    /*
     * 1.确定的日志存储方式
     * 2.写日志
     * */

    static public function init(){
        //确定存储方式
//        $drive = conf::get('DRIVE','log');
        $drive = conf::get('DRIVE','mysql');
        $class = '\core\lib\drive\log\\'.$drive;
        self::$class = new $class;
    }

    static public function log($name,$file = 'log'){
//        self::$class->log($name,$file);
    }

    static public function Chargelog($sql){
        self::$class->Chargelog($sql);
    }

}