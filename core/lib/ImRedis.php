<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/4
 * Time: 18:25
 */

namespace core\lib;


class ImRedis
{
    private static $redis;
    public static  function getRedis(){
        if(!(self::$redis instanceof \Redis)){
            self::$redis = new \Redis();
            self::$redis->connect('127.0.0.1',6379);
        }
        return self::$redis;
    }
}

