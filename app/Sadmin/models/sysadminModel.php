<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/8
 * Time: 15:13
 */

namespace Sadmin\models;

use core\lib\model;

class sysadminModel extends model
{

    protected $table = 'xc_sysadmin';

    protected $pk = 'id';

    protected $fields = array('id','user','pwd','phone','is_sys_dl','pid','cityid','qyid','salt','tname','qq','email','action_list','role_id','add_time','last_login','start_time','end_time','last_ip');

    protected $_valid = array(
        array('id',1,'user_id不能为空','require'),
        array('add_time',1,'添加时间不能为空','require'),
        array('start_time',1,'权限开始时间不能为空','require')
    );

    protected $_auto = array(
        array('add_time','function','time'),
        array('start_time','function','time'),
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
        $res = $this->db->get($this->table,'*',$parm);
        return $res;
    }

    //根据Parm查询多条
    public function getlists($parm)
    {
        $res =  $this->db->select($this->table,'*',$parm);
        return $res;
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