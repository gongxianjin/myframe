<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/4
 * Time: 10:02
 */

namespace core\lib;
use core\lib\conf;

class mysql extends \medoo{

    protected $table = NULL; // 是model所控制的表

    private static $ins = NULL;

    public function __construct()
    {
        $database = conf::all('database');
        parent::__construct($database);
//        try{
//            parent::__construct($database['DSN'], $database['USERNAME'], $database['PASSWD']);
//        }catch (\PDOException $e){
//            dump($e->getMessage());
//        }
    }

    public static function getIns(){
        if(!(self::$ins instanceof self)){
            self::$ins = new self();
        }
        return self::$ins;
    }

    public function table($table) {
        $this->table = $table;
    }


}