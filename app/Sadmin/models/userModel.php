<?php

/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/9
 * Time: 16:31
 */

namespace Sadmin\models;

use core\lib\model;

class userModel extends model
{
    protected $table = 'user';

    protected $pk = 'id';

    protected $fields = array('id','openid','noticed','phone','nickname','disabled','disabledreason','sex','headimg','createtime','ip','country','province','city','lat','lng','user_points','lastlogintime','logincounts','comment','reverse');

    protected $_valid = array(
        array('id',1,'uuid不能为空','require'),
        array('createtime',1,'参加时间不能为空','require'),
    );

    protected $_auto = array(
        array('createtime','function','time'),
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
        return $this->insert($this->table,$data);
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

    public function getCount($data = ''){
        return $this->db->count($this->table,$data);
    }

    public function getcolumnSum($column,$data = ''){
        return $this->db->sum($this->table,$column,$data);
    }

}