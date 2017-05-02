<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/4
 * Time: 11:52
 */

//文件系统

namespace core\lib\drive\log;

use core\lib\conf;

class file{

    public $path;#日志存储位置

    public function __construct()
    {
        $conf = conf::get('OPTION','log');
        $this->path = $conf['PATH'];
    }

    public function log($message,$file='log'){
        /*
         * 1 确定一下文件的存储位置
         * 新建目录
         * 2 写入日志
         * */
        if(!is_dir($this->path.date('YmdH'))){
            mkdir($this->path.date('YmdH'),'0777',true);
        }

        return file_put_contents($this->path.date('YmdH').'/'.$file.'.php',date('Y-m-d H:i:s').json_encode($message).PHP_EOL,FILE_APPEND);
    }

}