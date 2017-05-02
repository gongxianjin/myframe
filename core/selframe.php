<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/3
 * Time: 22:31
 */

namespace core;
use core\lib\conf;

class selframe{

    public static $classMap = array();
    public $assign = array();

    static public function run(){
        \core\lib\log::init();
        removeMagicQuotes();
        unregisterGlobals();
        // Session初始化
        if(!IS_CLI){
            session(array());
        }
        $route = new \core\lib\route(); 
        $ctrlClass = $route->ctl;
        if(!preg_match('/^[A-Za-z](\/|\w)*$/',$ctrlClass)){ // 安全检测
            throw new \Exception('控制器不符合规范'.$ctrlClass);
        }
        $action = $route->action;
        $admin = conf::get('ADMIN','route');
        $home = conf::get('HOME','route');
        if(strpos($_SERVER['REQUEST_URI'],$admin)){
            $ctrlfile = APP.'/'.$admin.'/controllers/'.$ctrlClass.'Controller.php';
            $newctrlClass = '\\'.MODULE.'\\'.$admin.'\controllers\\'.$ctrlClass.'Controller';
        }elseif(strpos($_SERVER['REQUEST_URI'],$home)){
            $ctrlfile = APP.'/'.$home.'/controllers/'.$ctrlClass.'Controller.php';
            $newctrlClass = '\\'.MODULE.'\\'.$home.'\controllers\\'.$ctrlClass.'Controller';
        }else{
            $ctrlfile = APP.'/'.$home.'/controllers/'.$ctrlClass.'Controller.php';
            $newctrlClass = '\\'.MODULE.'\\'.$home.'\controllers\\'.$ctrlClass.'Controller';
        } 
        if(is_file($ctrlfile)){
            include $ctrlfile;
            $ctrl = new $newctrlClass($ctrlClass, $action);
//            $ctrl->$action();
//            \core\lib\log::log('controller:'.$ctrlClass.'  '.'action:'.$action);
//            \core\lib\log::Chargelog('controller:'.$ctrlClass.'  '.'action:'.$action);
        }else{
	        echo '找不到控制器';
            throw new \Exception('找不到控制器'.$ctrlClass);
	        exit;
        }

        # 通过反射进行参数绑定调起类的方法
        try{
            # 方法,从路由获取的,类也是由路由获取的
            if(!preg_match('/^[A-Za-z](\w)*$/',$action)){
                // 非法操作
                throw new \ReflectionException();
            }
            //执行当前操作
            $method =   new \ReflectionMethod($ctrl, $action);
            if($method->isPublic() && !$method->isStatic()) {
                # 获取类的反射
                $class  =   new \ReflectionClass($ctrl);
                // 前置操作
                if($class->hasMethod('_before_'.$action)) {
                    #获取对应方法的反射
                    $before =   $class->getMethod('_before_'.$action);
                    if($before->isPublic()) {
                        $before->invoke($ctrl);
                    }
                }
                // URL参数绑定检测
                if($method->getNumberOfParameters()>0){
                    switch($_SERVER['REQUEST_METHOD']) {
                        case 'POST':
                            $vars    =  array_merge($_GET,$_POST);
                            break;
                        case 'PUT':
                            parse_str(file_get_contents('php://input'), $vars);
                            break;
                        default:
                            $vars  =  $_GET;
                    }
                    # 获取方法的参数的反射列表（多个参数反射组成的数组）
                    $params =  $method->getParameters();
                    $paramsBindType     =   0;// URL变量绑定的类型 0 按变量名绑定 1 按变量顺序绑定
                    # 循环参数反射
                    # 如果存在路由参数的名称和参数的名称一致，就压进params里面
                    # 如果存在默认值，就将默认值压进params里面
                    # 如果。。。没有如果了，异常
                    foreach ($params as $param){
                        $name = $param->getName();
                        if( 1 == $paramsBindType && !empty($vars) ){
                            $args[] =   array_shift($vars);
                        }elseif( 0 == $paramsBindType && isset($vars[$name])){
                            $args[] =   $vars[$name];
                        }elseif($param->isDefaultValueAvailable()){
                            # 是否存在默认值
                            $args[] =   $param->getDefaultValue();
                        }else{
                            throw new \Exception('参数错误或者未定义'.$name);
                        }
                    }
                    # 调用
                    $method->invokeArgs($ctrl,$args);
                }else{
                    // 类比 调用 $ctrl->$action();
                    $method->invoke($ctrl);
                }
                // 后置操作
                if($class->hasMethod('_after_'.$action)) {
                    $after =   $class->getMethod('_after_'.$action);
                    if($after->isPublic()) {
                        $after->invoke($ctrl);
                    }
                }
            }else{
                // 操作方法不是Public 抛出异常
                throw new \ReflectionException();
            }
        } catch (\ReflectionException $e) {
            // 方法调用发生异常后 引导到__call方法处理
            $method = new \ReflectionMethod($ctrl,'__call');
            $method->invokeArgs($ctrl,array($action,''));
        }

    }

    static public function load($class){
        //自动加载类 (代替require)
        // new \core\route();
        // $class = '\core\route'
        // ROOT.'/core/route.php';　
        if(isset($classMap[$class])){
            return true;
        }else{
            $class = str_replace('\\','/',$class);
            if(substr($class,-5) == "Model"){
                $file = ROOT.'/'.'app/'.$class.'.php';
            }else{
                $file = ROOT.'/'.$class.'.php';
            }
            if(is_file($file)){
                include $file;
                self::$classMap[$class] = $class;
            }else{
                return false;
            }
        }

    }

//    public function assign($name,$val){
//        $this->assign[$name] = $val;
//    }
//
//    public function display($file){
//        $admin = conf::get('ADMIN','route');
//        $home = conf::get('HOME','route');
//        if(strpos($_SERVER['REQUEST_URI'],$admin)){
//            $file = APP.'/'.$admin.'/views/'.$file;
//        }elseif(strpos($_SERVER['REQUEST_URI'],$home)){
//            $file = APP.'/'.$home.'/views/'.$file;
//        }else{
//            $file = APP.'/'.$home.'/views/'.$file;
//        }
//        if(is_file($file)){
//            extract($this->assign);
//            include $file;
//        }
//    }



}