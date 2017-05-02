<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/17
 * Time: 17:55
 */

namespace app\Sadmin\controllers;

use Sadmin\models\userModel;
use core\lib\page;
use core\lib\Excel_XML;

class UserController extends BaseadminController
{
    public function index($pages = '' ,$sword = '') {
        $p = empty($pages) ? 1 : $pages;
        //筛选
        $data = array();
        if($sword){
            $data = array(
                'phone'=>$sword
            );
            $this->assign('sword',$sword);
        }
        //筛选 分页
        $userForm=new userModel();
        // 获取信息总数
        $count = $userForm->getCount($data);
        // 每页显示的记录数
        $listPage = 8;
        // 起始页
        $firstRow = $listPage*($p-1);
        // 分页地址
        $url = __SADMIN__.'/User/index';
        if($sword){
            $url.= '/sword/'.$sword;
        }
        $url .= '/pages/';
        $page = new Page($count,$p,$listPage,$url);
        $show = $page->show();
        $data['LIMIT']= array($firstRow,$listPage);
        $lists = $userForm->getlists($data);
        $this->assign('sword',$sword);
        $this->assign('lists',$lists);
        $this->assign('page',$page);
        $this->assign('show',$show);
        $this->render();
    }


    public function unlock($id){
        if($id == null){
            $this->jsonMsg(400,'参数错误');
        }
        $userForm = new userModel();
        $user = $userForm->getOne($id);
        if(!$user){
            $this->jsonMsg(500,'没有找到数据');
        }

        if(!$userForm->setOne($id,array('disabled'=>null))){ 
            $this->jsonMsg(251, '解锁失败');
        }

        $this->jsonMsg(200, '解锁成功');
    }


    /**
     * 导出
     * Enter description here ...
     */
    public function export($sword = ''){
        $data = array();
        if($sword != null){
            $data["AND"]['phone'] = $sword;
        }
        //筛选 分页
        $userForm=new userModel();
        $lists = $userForm->getlists($data);
        if (!empty($lists)) {
            $filename = date('Y-m-d_') . count($lists);
            $datas[] = array('编号','头像','微信ID','昵称','性别','手机号','参与时间','是否封号','封号原因','IP');
            foreach ($lists as $key=>$l) {
                $datas[] = array($key+1,$l['headimg'],$l['openid'],$l['nickname'],$l['sex'],$l['phone'],date('Y-m-d H:i:s',$l['createtime']),$l['disabled'],$l['disabledreason'],$l['ip']);
            }
        }
        if (empty($datas)){
            $this->error('暂无数据导出');
            exit;
        }else{
            $xls = new Excel_XML('UTF-8', false, $filename);
            $xls->addArray($datas);
            $xls->generateXML($filename);
        }
    }




}