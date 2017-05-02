<?php

/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/9
 * Time: 16:31
 */

namespace Sadmin\models;

use core\lib\model;

class chargelogmodel extends model
{
    protected $table = 'chargelog';

    protected $pk = 'id';

    protected $fields = array('id','sendtime','statecode','statedesc','msgid','restime','chargerecordid','paramheader','paramjson','url','flag','comment','reverse');

    protected $_valid = array(
        array('id',1,'uuid不能为空','require'),
        array('sendtime',1,'发送时间不能为空','require')
    );

    protected $_auto = array(
        array('sendtime','function','time'),
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