<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/8
 * Time: 15:13
 */

namespace Sadmin\models;

use core\lib\model;

class sysadminlogModel extends model
{

    protected $table = 'xc_sysadmin_log';

    protected $pk = 'id';

    protected $fields = array('log_id','log_time','user_id','log_info','ip_address');

    protected $_valid = array(
        array('log_id',1,'log_id不能为空','require'),
        array('log_time',1,'添加时间不能为空','require'),
        array('user_id',1,'user_id不能为空','require')
    );

    protected $_auto = array(
        array('log_time','function','time'),
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
        if(is_array($parm)){
            $keys = array_keys($parm);
            if(isset($keys[0])){
                if(in_array($keys[0],$this->fields)){
                    return $this->db->get($this->table,'*',$parm);
                }
            }
        }
        return false;
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