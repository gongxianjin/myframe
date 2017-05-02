<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/7
 * Time: 22:44
 */

namespace core\lib;

use core\lib\conf;

class View
{

    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($controller, $action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
    }

    // 分配变量
    public function assign($name, $value)
    {
        $this->variables[$name] = $value;
    }

    // 渲染显示
    public function render()
    {
        extract($this->variables);
        $admin = conf::get('ADMIN','route');
        $home = conf::get('HOME','route');
        if(strpos($_SERVER['REQUEST_URI'],$admin)){
            $defaultHeader = APP.'/'.$admin.'/views/header.php';
            $defaultFooter = APP.'/'.$admin.'/views/footer.php';
            $defaultLayout = APP.'/'.$admin.'/views/layout.php';

            $controllerHeader = APP.'/'.$admin.'/views/' . strtolower($this->_controller) . '/header.php';
            $controllerFooter = APP.'/'.$admin.'/views/' . strtolower($this->_controller) . '/footer.php';
            $controllerLayout = APP.'/'.$admin.'/views/' . strtolower($this->_controller) . '/' . strtolower($this->_action) . '.php';

        }elseif(strpos($_SERVER['REQUEST_URI'],$home)){
            $defaultHeader = APP.'/'.$home.'/views/header.php';
            $defaultFooter = APP.'/'.$home.'/views/footer.php';
            $defaultLayout = APP.'/'.$home.'/views/layout.php';

            $controllerHeader = APP.'/'.$home.'/views/' . strtolower($this->_controller) . '/header.php';
            $controllerFooter = APP.'/'.$home.'/views/' . strtolower($this->_controller) . '/footer.php';
            $controllerLayout = APP.'/'.$home.'/views/' . strtolower($this->_controller) . '/' . strtolower($this->_action) . '.php';
        }else{
            $defaultHeader = APP.'/'.$home.'/views/header.php';
            $defaultFooter = APP.'/'.$home.'/views/footer.php';
            $defaultLayout = APP.'/'.$home.'/views/layout.php';

            $controllerHeader = APP.'/'.$home.'/views/' . strtolower($this->_controller) . '/header.php';
            $controllerFooter = APP.'/'.$home.'/views/' . strtolower($this->_controller) . '/footer.php';
            $controllerLayout = APP.'/'.$home.'/views/' . strtolower($this->_controller) . '/' . strtolower($this->_action) . '.php';
        }
        // 页头文件
        if (file_exists($controllerHeader)) {
            include ($controllerHeader);
        } else {
            include ($defaultHeader);
        }
        // 页内容文件
        if (file_exists($controllerLayout)) {
            include ($controllerLayout);
        } else {
            include ($defaultLayout);
        }

        // 页脚文件
        if (file_exists($controllerFooter)) {
            include ($controllerFooter);
        } else {
            include ($defaultFooter);
        }
    }

}