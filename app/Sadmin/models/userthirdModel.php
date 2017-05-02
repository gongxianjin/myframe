<?php

/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/9
 * Time: 16:31
 */

namespace Sadmin\models;

use core\lib\model;

class userthirdModel extends model
{
    protected $table = 'xc_user_third';

    protected $pk = 'uid';

    protected $fields = array('uid','type','typecode','typename','typesex','typelogo','addtime');

    protected $_valid = array(
        array('uid',1,'uuid不能为空','require'),
        array('addtime',1,'参加时间不能为空','require'),
    );

    protected $_auto = array(
        array('addtime','function','time'),
    );

    //查询一条
    public function getOne($id)
    {
        return $this->db->get($this->table,'*',array('id'=>$id));
    }

    public function getRow()
    {
        return $this->db->get($this->table,'*');
    }

    //根据key查询一条
    public function getKey($parm)
    {
        return $this->db->get($this->table,'*',$parm);
    }

    //根据Parm查询多条
    public function getlists($parm)
    {
        return $this->db->select($this->table,'*',$parm);
    }

    public function getAll(){
        $ret = $this->db->select($this->table,'*');
        return $ret;
    }

    //添加数据
    public function add($data)
    {
        return $this->db->insert($this->table,$data);
    }

    //删除一条
    public function delOne($id)
    {
        return $this->db->delete($this->table,array('id'=>$id));
    }

    //修改一条
    public function setOne($id,$data)
    {
        return $this->db->update($this->table,$data,array('id'=>$id));
    }

}