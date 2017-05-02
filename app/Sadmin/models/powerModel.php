<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/8
 * Time: 15:13
 */

namespace Sadmin\models;

use core\lib\model;

class powerModel extends model
{

    protected $table = 'xc_power';

    protected $pk = 'id';

    protected $fields = array('id','c_name','c_alias','a_name','a_alias','is_show','order','pid');

    protected $_valid = array(
        array('id',1,'id不能为空','require'),
    );

    protected $_auto = array(
    );

    //查询一条
    public function getOne($id)
    {
        return $this->db->get($this->table,'*',array($this->pk => $id));
    }

    //查询任意一条
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