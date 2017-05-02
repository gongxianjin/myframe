<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/8/14
 * Time: 16:14
 */

//define('ROOT',str_replace('\\','/',dirname(__FILE__ )).'/');
define('ROOT',realpath('./'));
define('CORE',ROOT.'/core');
define('APP',ROOT.'/app');
define('MODULE','app');
define('DEBUG',true);
define('IS_CLI',PHP_SAPI=='cli'? 1   :   0);
define('__SADMIN_BASE__','/assets/Sadmin'); //公共调用文件路径
define('__SADMIN__','/Sadmin'); //公共调用文件路径

include "vendor/autoload.php";

if(DEBUG){
//    $whoops = new \Whoops\Run;
//    $errorTitle = '框架出错了';
//    $option = new \Whoops\Handler\PrettyPageHandler();
//    $option->setPageTitle($errorTitle);
//    $whoops->pushHandler($option);
//    $whoops->register();
    ini_set('display_error','On');
}else{
    ini_set('display_error','Off');
}
 
include CORE.'/common/function.php';

include CORE.'/selframe.php';

spl_autoload_register('\core\selframe::load');

\core\selframe::run();
