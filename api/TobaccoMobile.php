<?php
/**
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/8
 * Time: 13:24
 */

namespace api;

use core\lib\ImHttpRequest;

class TobaccoMobile
{
    // 访问地址
    const SERVER_URL = 'http://v.sctobacco.com/cloud2.extser/entry/service.do';
    // 测试地址
    const TEST_SERVER_URL = 'http://yz2-m-stg.taiheiot.com/cloud2.extser/entry/service.do';

    // syscode
    private static $sysCode = 'scjw';
    // salt
    private static $salt = '=safijw==';
    //serviceFlg
    private  static $serviceFlg = 'teleChargeNoBarcode';


    public  function  Syncharge($mobile,$amount,$orderId,$retUri){
        $createtime = getMillisecond();
        $param = array(
            "amount"=>$amount,
            "mobile"=> "$mobile",
            "retUri"=> "$retUri",
            "orderId"=> "$orderId");
        $key = md5(self::$sysCode.$createtime.self::$salt);
        $reqjosn = json_encode($param);
        if(self::verifyPhone($mobile)){
            $params = array(
                "sysCode"=>self::$sysCode,
                "timer"=> "$createtime",
                "key"=> "$key",
                "serviceFlg"=> self::$serviceFlg
            );
            $params["params"] = "$reqjosn";
            $params['amount'] = "$amount";
            $params['mobile'] = "$mobile";
            $params['retUri'] = "$retUri";
            $params['orderId'] = "$orderId";
            $response = ImHttpRequest::request(self::SERVER_URL,$params,'GET');
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