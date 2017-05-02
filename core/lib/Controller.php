<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/7
 * Time: 22:38
 */

namespace core\lib;


class Controller
{

    protected $_controller;
    protected $_action;
    protected $_view;

    //基本权限配置
    protected $oper_meta_set = array(

        //权限管理
        'Userauthority'=>array(
            'alias'=>'权限管理',
            'items'=>array(
                'index'=>'管理列表', 'add'=>'添加', 'edit'=>'编辑','del'=>'删除'
            ),
        ),

        //奖品管理
        'Prizeset'=>array(
            'alias'=>'奖品管理',
            'items'=>array(
                'index'=>'管理列表', 'add'=>'添加', 'edit'=>'编辑','del'=>'删除','save_edit'=>'保存'
            ),
        ),

        //骄子币管理
        'Tobaccopoints'=>array(
            'alias'=>'骄子币管理',
            'items'=>array(
                'index'=>'管理列表', 'export'=>'导出', 'chargesetting'=>'限制修改'
            ),
        ),

        //话费充值管理
        'Mobilepoints'=>array(
            'alias'=>'话费充值管理',
            'items'=>array(
                'index'=>'管理列表',  'export'=>'导出', 'chargesetting'=>'限制修改'
            ),
        ),

        //烟包规格管理
        'Material'=>array(
            'alias'=>'烟包规格管理',
            'items'=>array(
                'index'=>'管理列表', 'add'=>'添加', 'edit'=>'编辑','del'=>'删除'
            ),
        ),

        //用户管理
        'User'=>array(
            'alias'=>'用户管理',
            'items'=>array(
                'index'=>'管理列表',  'export'=>'导出', 'unlock'=>'解锁'
            ),
        ),

 	//报表管理
        'Export'=>array(
            'alias'=>'用户管理',
            'items'=>array(
                'index'=>'管理列表',  'exportv1giftrecord'=>'导出V1版烟包抽奖数据报表', 'exportv1barcodes'=>'导出V1版本接口规格查询报表','exportv1users'=>'导出V1充值归属地数据报表',
            ),
        ),

//        //报表管理
//        'Export'=>array(
//            'alias'=>'用户管理',
//            'items'=>array(
//                'index'=>'管理列表',  'exportv2giftrecord'=>'导出V2版烟包抽奖数据报表', 'exportv2barcodes'=>'导出V2版本接口规格查询报表','exportv2mobilebills'=>'导出V2版本充值数据报表',
//            ),
//        ),

        //原始数据统计
        'Datastatic'=>array(
            'alias'=>'原始数据',
            'items'=>array(
                'index'=>'抽奖数据','export'=>'抽奖数据导出',  'tobaccocharge'=>'骄子币充值数据','tobaccocharge_export'=>'骄子币充值数据导出', 'mobilecharge'=>'话费充值数据','mobilecharge_export'=>'话费充值数据导出'
            ),
        ),

        //用户数据统计
        'Userdatastatic'=>array(
            'alias'=>'用户数据统计',
            'items'=>array(
                'index'=>'用户数据','export'=>'用户数据导出', 'detail'=>'用户详情','detail_export'=>'用户详情导出'
            ),
        ),

        //参与数据统计
        'Joindatastatic'=>array(
            'alias'=>'参与数据统计',
            'items'=>array(
                'index'=>'总数统计','export'=>'总数统计导出',   'daystatic'=>'按日统计','daystatic_export'=>'按日统计导出',  'hourstatic'=>'按时统计','hourstatic_export'=>'按时统计导出'
            ),
        ),

        //抽奖数据统计
        'Drawdatastatic'=>array(
            'alias'=>'抽奖数据统计',
            'items'=>array(
                'index'=>'总数统计','export'=>'总数统计导出',   'daystatic'=>'按日统计','daystatic_export'=>'按日统计导出',  'hourstatic'=>'按时统计','hourstatic_export'=>'按时统计导出'
            ),
        ),

        //抽奖数据统计
        'Zonedatastatic'=>array(
            'alias'=>'区域数据统计',
            'items'=>array(
                'index'=>'总数统计','export'=>'总数统计导出',   'daystatic'=>'按日统计','daystatic_export'=>'按日统计导出'
            ),
        ),
    );

    // 构造函数，初始化属性，并实例化对应模型
    public function __construct($controller, $action)
    {
        //初始化
//        dump(get_class_methods($this));exit;
        if(method_exists($this,'_initialize'))
            $this->_initialize();
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_view = new View($controller, $action);
    }

    // 分配变量
    public function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }

    // 渲染视图
    public function render()
    {
        $this->_view->render();
    }

    /**
     * Action跳转(URL重定向） 支持指定模块和延时跳转
     * @access protected
     * @param string $url 跳转的URL表达式
     * @param array $params 其它URL参数
     * @param integer $delay 延时跳转的时间 单位为秒
     * @param string $msg 跳转提示信息
     * @return void
     */
    protected function redirect($url,$delay=0,$msg='') {
        redirect($url,$delay,$msg);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function success($message='',$jumpUrl='') {
        $this->dispatchJump($message,$jumpUrl);
    }


    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function error($message='',$jumpUrl='') {
        $this->dispatchJump($message,$jumpUrl);
    }


    private function dispatchJump($message,$jumpUrl=''){
        echo "<div style='width:400px;margin:100px auto;border:2px #D8D8D8 solid;'>
	    <div style='line-height:30px;height:30px;background:#DCDCDC;padding-left:5px;'>提示信息</div>
	    <div style='padding:10px;color:red;'>".$message."</div>";
        if($jumpUrl){
            echo "<div style='text-align:center;margin-bottom:10px;'><a href='$jumpUrl'>如果没有跳转，请点击这里</a></div>";
        }else{
            echo "<div style='text-align:center;margin-bottom:10px;'><a href='javascript:history.go(-1)'>如果没有跳转，请点击这里</a></div>";
        }
        echo "</div>";
    }


    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @return void
     */
    protected function ajaxReturn($data,$type='') {
        if(empty($type)) $type = 'JSON';
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET['jsonpcallback']) ? $_GET['jsonpcallback'] : 'jsonpcallback';
                exit($handler.'('.json_encode($data).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
        }
    }

    public function jsonMsg($code, $msg, $data='', $forwardUrl=''){
        $array = array(
            'code' => $code,
            'msg' => $msg,
        );
        if($data){
            $array['data'] = $data;
        }
        if($forwardUrl){
            $array['forward'] = $forwardUrl;
        }
        $this->ajaxReturn($array, 'json');
    }

}