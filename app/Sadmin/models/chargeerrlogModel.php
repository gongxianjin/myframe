<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/8
 * Time: 15:13
 */

namespace Sadmin\models;

use core\lib\model;

class chargeerrlogModel extends model
{

    protected $table = 'xc_charge_errlog';

    protected $pk = 'errlog_id';

    protected $fields = array('errlog_id','errlog_time','errlog_code','errlog_info','comment','reverse');

    protected $_valid = array(
        array('errlog_id',1,'log_id不能为空','require'),
        array('errlog_time',1,'添加时间不能为空','require'),
    );

    protected $_auto = array(
        array('errlog_time','function','time'),
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