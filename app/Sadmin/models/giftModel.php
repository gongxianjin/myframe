<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/8
 * Time: 15:13
 */

namespace Sadmin\models;

use core\lib\model;

class giftModel extends model
{

    protected $table = 'gift';

    protected $pk = 'id';

    protected $fields = array('id','giftname','price','total','gift_type','gift_attr','daymax','hourmax','rate','openid_totalmax','openid_daymax','order','phone_totalmax','phone_hourmax','order');

    protected $_valid = array(
        array('id',1,'id不能为空','require'),
        array('total',1,'total必须是整型值','number'),
        array('gift_type',0,'gift_type只能是1或2或3或4','in','1,2,3,4'),
        array('gift_attr',0,'gift_attr只能是1或2或3或4或5或6或7或8或9或10或11或12或13或14','in','1,2,3,4,5,6,7,8,9,10,11,12,13,14'),
        array('daymax',1,'daymax必须是整型值','number'),
        array('hourmax',1,'栏目id必须是整型值','number'),
        array('rate',0,'rate只能在0,1之间','between','0,1'),
        array('openid_totalmax',1,'openid_totalmax必须是整型值','number'),
        array('openid_hourmax',1,'openid_hourmax必须是整型值','number'),
        array('phone_totalmax',1,'phone_totalmax必须是整型值','number'),
        array('phone_hourmax',1,'phone_hourmax必须是整型值','number'),
        array('order',1,'order必须是整型值','number'),
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