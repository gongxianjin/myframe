<?php
/**
 * http 请求模块
 *
 * Created by PhpStorm.
 * User: sun
 * Date: 2016/11/4
 * Time: 16:40
 */

namespace core\lib;
use core\lib\conf;


class ImHttpRequest
{
    public static  function request($url,$params,$method = 'GET'){
        $response = null;
        if($url){
            $method = strtoupper($method);
            if($method == 'POST'){
                //curl
                $parsedUrl = parse_url($url);
                if(isset($parsedUrl['port'])){
                    $host = $parsedUrl['host'].":".$parsedUrl['port'];
                }else{
                    $host = $parsedUrl['host'];
                }
                $ch = curl_init();
                if(is_array($params) and count($params)){
                    $reqjson = json_encode($params);
                    $msgid = $params['msgid'];
                    $str = $reqjson."CYZYGYGS_95".$msgid;
                    $msgtoken = md5($str);
                }
                $header = array(
                    "POST {$parsedUrl['path']} HTTP/1.1",
                    "Host:{$host}",
                    'content-type: multipart/form-data;boundary=---ABC',
//                    'Content-Length:'.strlen($reqjson),
                    "msgToken: {$msgtoken}"
                );
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"-----ABC\r\nContent-Disposition: form-data; name=\"reqjson\"\r\n\r\n$reqjson\r\n-----ABC");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                $response = curl_exec($ch);
                $err = curl_error($ch);
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($err) {
                    echo "cURL Error #:" . $err;
                    return false;
                }
                //调试信息：
//                static $i = 1;
//                echo "<h2>第{$i}轮请求</h2>";
//                echo "请求头信息：<br />", implode('<br />', $header), "<br /><br />";
//                echo "请求原始reqjson：<br />", htmlspecialchars($reqjson), "<br /><br />";
//                echo "服务器返回：<br />", htmlspecialchars($response), "<br /><br />";
//                echo "服务器返回HTTP状态码：<br />", $status, "<br /><br />";
//                echo "curl错误：<br />", $err;
//                echo "<h3>第{$i}轮请求结束</h3>";
//                $i++;
                //记录日志到数据库
                $res = json_decode($response,true);
                $logs = array(
                    'id'=>create_uuid(),
                    'sendtime'=> getMillisecond(),
                    'statecode'=> $res['state_code'],
                    'statedesc'=> $res['state_desc'],
                    'msgid'=>$msgid,
                    'restime'=>$res['curr_time'],
                    'paramheader'=>$msgtoken,
                    'paramjson'=>$reqjson,
                    'url'=>$url
                );
                \core\lib\log::Chargelog($logs);
            }else{
                if(is_array($params) and count($params)){
                    if(strrpos($url,'?')){
                        $url = $url.'&'.http_build_query($params);
                    }else{
                        $url = $url.'?'.http_build_query($params);
                    }
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => "GET",
                    ));
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                    //调试信息：
//                        static $i = 1;
//                        echo "<h2>第{$i}轮请求</h2>";
//                        echo "请求原始reqjson：<br />", htmlspecialchars($curl), "<br /><br />";
//                        echo "服务器返回：<br />", htmlspecialchars($response), "<br /><br />";
//                        echo "curl错误：<br />", $err;
//                        echo "<h3>第{$i}轮请求结束</h3>";
//                        $i++;
                    }
                } 
                //记录日志到数据库
                $res = json_decode($response,true);
                $logs = array(
                    'id'=>create_uuid(),
                    'sendtime'=> getMillisecond(),
                    'statecode'=> $res['code'],
                    'statedesc'=> $res['msg'],
                    'msgid'=>isset($res['data']['phone'])?$res['data']['phone']:'',
                    'restime'=>isset($res['data']['createdAt'])?$res['data']['createdAt']:'',
                    'paramheader'=>'',
                    'paramjson'=>$params,
                    'url'=>$url
                );
                \core\lib\log::Chargelog($logs);
            }
        }
        return $response;
    }

}