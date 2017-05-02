<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/4
 * Time: 15:58
 */

namespace api;

class QueryPhone
{

    const TAOBAO_API = 'http://tcc.taobao.com/cc/json/mobile_tel_segment.htm';

    public static function query($phone){
        if(self::verifyPhone($phone)){
            $response = self::request(self::TAOBAO_API,array('tel'=>$phone));
            $data = self::formateData($response);
            if(isset($data)){
                return $data['province'].$data['catName'];
            }
        }
    }

    public static function test($phone){
        dump($phone);
        QueryIP::query();
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

    public static  function request($url,$params){
        $response = null;
        if($url){
            if(is_array($params) and count($params)){
                if(strrpos($url,'?')){
                    $url = $url.'&'.http_build_query($params);
                }else{
                    $url = $url.'?'.http_build_query($params);
                }
                $response = file_get_contents($url);
            }
        }
        return $response;
    }


}