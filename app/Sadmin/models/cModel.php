<?php

/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/7
 * Time: 15:44
 */

namespace Sadmin\models;

use core\lib\model;

class cModel extends model
{
    public $table = 'c';
    public function lists(){
        $ret = $this->db->select($this->table,'*');
        return $ret;
    }

}