<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/3
 * Time: 22:35
 */

namespace core\lib;
    use core\lib\conf;
class route{

    public $ctl;
    public $action;
    public $admin;
    public $home;

    public function __construct()
    {
        //xx.com/index/index
        //xx.com/index.php/index/index
        /*
         * 1 隐藏index
         * 2 获取到url 参数部分
         * 3 返回对应控制器和方法
         * */
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
            $path = $_SERVER['REQUEST_URI'];
            // 使用“/”分割字符串，并保存在数组中
            $patharr = explode('/',trim($path,'/'));
            $this->admin = conf::get('ADMIN','route');
            $this->home = conf::get('HOME','route');

            if(isset($patharr[0]) && $patharr[0] == $this->admin){
                // 删除空的数组元素
                unset($patharr[0]);
                // 获取控制器名
                if(isset($patharr[1])){
                    $this->ctl = $patharr[1];
                    unset($patharr[1]);
                }
                // 获取动作名
                if(isset($patharr[2])){
                    $this->action = $patharr[2];
                    unset($patharr[2]);
                }else{
                    $this->action = conf::get('ACTION','route');
                }
                //url的多余部分转换为get参数
                //index/index/id/1 = id/1
                // 获取URL参数
                $count = count($patharr) + 3;
                $i = 2;
                while($i < $count){
                    if(isset($patharr[$i+2])){
                        $_GET[$patharr[$i+1]] = $patharr[$i+2];
                    }
                    $i = $i+2;
                }
            }else{
                if($patharr[0] == $this->home){
                    array_shift($patharr);
                }
                if(isset($patharr[0])){
                    $this->ctl = $patharr[0];
                    unset($patharr[0]);
                }
                if(isset($patharr[1])){
                    $this->action = $patharr[1];
                    unset($patharr[1]);
                }else{
                    $this->action = conf::get('ACTION','route');
                }
                //url的多余部分转换为get参数
                //index/index/id/1 = id/1
                $count = count($patharr) + 2;
                $i = 2;
                while($i < $count){
                    if(isset($patharr[$i+1])){
                        $_GET[$patharr[$i]] = $patharr[$i+1];
                    }
                    $i = $i+2;
                }
            }
        }else{
            $this->ctl = conf::get('CTRL','route');
            $this->action = conf::get('ACTION','route');
        }

    }
}