<?php

/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/9
 * Time: 16:31
 */

namespace Sadmin\models;

use core\lib\model;

class configModel extends model
{
    protected $table = 'config';

    protected $pk = 'name';

    protected $fields = array('name','value','comment');

    //查询一条
    public function getOne($name)
    {
        return $this->db->get($this->table,'*',array('name'=>$name));
    }

    public function getRow()
    {
        return $this->db->get($this->table,'*');
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
    public function setOne($name,$data)
    {
        return $this->db->update($this->table,$data,array('name'=>$name));
    }

}