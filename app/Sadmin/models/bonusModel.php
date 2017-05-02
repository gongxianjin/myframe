<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/8
 * Time: 15:13
 */

namespace Sadmin\models;

use core\lib\model;

class bonusModel extends model
{

    protected $table = 'xc_bonus_type';

    protected $pk = 'type_id';

    protected $fields = array('type_id','type_name','chargenum','sendnum','min_amount','max_amount','use_start_date','use_end_date','createtime','reverse','comment','flag');

    protected $_valid = array(
        array('type_id',1,'id不能为空','require'),
        array('type_name',1,'type_name不能为空','require'),
        array('chargenum',1,'chargenum不能为空','require'),
        array('sendnum',1,'sendnum不能为空','require'),
        array('createtime',1,'参加时间不能为空','require'),
    );

    protected $_auto = array(
        array('createtime','function','time'),
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
        return $this->db->delete($this->table,array($this->pk=>$id));
    }

    //修改一条
    public function setOne($id,$data)
    {
        return $this->db->update($this->table,$data,array($this->pk=>$id));
    }

}