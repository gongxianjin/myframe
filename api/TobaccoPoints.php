<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/8
 * Time: 13:24
 */

namespace api;

use core\lib\ImHttpRequest;

class TobaccoPoints
{
    const CyTobacco_API = 'http://m.cytobacco.com/MarketingPoints/upPoints.htm';

    public  function  Syncharge($phone,$giftrecordid,$giftrecordtime,$points,$ip,$activityKey,$activityName){
        $createtime = getMillisecond();
        if(self::verifyPhone($phone)){
            $params = array(
                "msgid"=>$giftrecordid,
                "createtime"=> "$giftrecordtime",
                "content"=>array(
                    "phone"=>$phone,
                    "point"=>$points,
                    "ipaddr"=>$ip,
                    "activityKey"=>"$activityKey",
                    "activityName"=>"$activityName",
                    "resTime"=>"$createtime")
            );
            $response = ImHttpRequest::request(self::CyTobacco_API,$params,'POST');
//            $data = self::formateData($response);
            if(isset($response)){
                return $response;
            }
        }
        return false;
    }

    /*
     * 校验手机号合法性
     * */
    public static function verifyPhone($phone = null){
        $ret = false;
        if($phone){
            if(preg_match('/^1[34578]{1}\d{9}/',$phone)){
                $ret = true;
            }
        }
        return $ret;
        //  return mb_convert_encoding($ret, 'utf-8', 'gbk');
    }

    /*
     *格式化API请求的数据
     * */
    public static function formateData($data = null){
        $ret = false;
        if($data){
            $data = mb_convert_encoding($data,'utf-8','gbk');
            preg_match_all("/(\\w+):'([^']+)/",$data,$res);
            $ret = array_combine($res[1],$res[2]);
        }
        return $ret;
    }

}