<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/4
 * Time: 16:11
 */

namespace api;

class QueryIP{

    const TAOBAO_API = 'http://ip.taobao.com/service/getIpInfo.php';

    public static function query($ip){
        if(self::verifyIP($ip)){
            $data = self::request(self::TAOBAO_API,array('ip'=>$ip));
            if(isset($data)){
                return $data['country'].$data['region'].$data['city'].$data['isp'];
            }
        }
    }

    /*
     * 校验IP合法性
     * */
    public static function verifyIP($IP = null){
        $ret = false;
        if($IP){
            // IP地址合法验证
            $long = sprintf("%u",ip2long($IP));
            $IP   = $long ? array($IP, $long) : 0;
            if($IP){
                $ret = true;
            }
        }
        return $ret;
        //  return mb_convert_encoding($ret, 'utf-8', 'gbk');
    }

    public static  function request($url,$params){
        $response = null;
        if($url){
            if(is_array($params) and count($params)){
                if(strrpos($url,'?')){
                    $url = $url.'&'.http_build_query($params);
                }else{
                    $url = $url.'?'.http_build_query($params);
                }
                $response = json_decode(file_get_contents($url));
                if((string)$response->code== '1'){
                    return false;
                }
                $response = (array)$response->data;
            }
        }
        return $response;
    }

}