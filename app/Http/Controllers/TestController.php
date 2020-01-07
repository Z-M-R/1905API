<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function alipay()
    {
        $ali_gateway = 'https://openapi.alipaydev.com/gateway.do';  //支付网关
        // 公共请求参数
        $appid = '2016101100657582';
        $method = 'alipay.trade.page.pay';
        $charset = 'utf-8';
        $signtype = 'RSA2';
        $sign = '';
        $timestamp = date('Y-m-d H:i:s');
        $version = '0.01';
        $return_url = 'http://1905api.zmrzzj.com/test/alipay/return';   //支付宝同步通知地址
        $notify_url = 'http://1905api.zmrzzj.com/test/alipay/notify';   //支付宝异步通知地址
        $biz_content = '';

        // 请求参数
        $out_trade_no = time() . rand(1111,9999);
        $product_code = 'FAST_INSTANT_TRADE_PAY';
        $total_amount = 0.01;
        $subject = '测试订单' . $out_trade_no;

        $request_param = [
            'out_trade_no' => $out_trade_no,
            'product_code' => $product_code,
            'total_amount' => $total_amount,
            'subject'      => $subject
        ];

        $param = [
            'app_id'    =>  $appid,
            'method'    =>  $method,
            'charset'   =>  $charset,
            'sign_type' =>  $signtype,
            'timestamp' =>  $timestamp,
            'version'   =>  $version,
            'notify_url'=>  $notify_url,
            'return_url'=>  $return_url,
            'biz_content'=> json_encode($request_param)
        ];
       
        // echo '<pre>';print_r($param);echo '</pre>';

        // // 字典序排序
        ksort($param);
        
        // // 2 拼接 key1=value1&key2=value2...
        $str = "";
        foreach($param as $k=>$v)
        {
            $str .= $k . '=' . $v . '&';
        }
        // echo 'str：'.$str;die;

        $str = rtrim($str,'&');
        //echo 'str: '.$str;echo '</br>';echo '<hr>';
        // 3 计算签名   https://docs.open.alipay.com/291/106118
        $key = storage_path('keys/app_priv');
        $priKey = file_get_contents($key);
        $res = openssl_get_privatekey($priKey);
        //var_dump($res);echo '</br>';
        openssl_sign($str, $sign, $res, OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);
        $param['sign'] = $sign;
        // 4 urlencode
        $param_str = '?';
        foreach($param as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $param_str = rtrim($param_str,'&');
        $url = $ali_gateway . $param_str;
        //  echo '<pre>';print_r($url);echo '</pre>';die;
        //发送GET请求
        //echo $url;die;
        header("Location:".$url);
    }

    //加密
    public function ascii()
    {
        $char = 'Hello World';
        $length = strlen($char);
        echo $length;echo '</br>';

        $pass = "";
        for($i=0;$i<$length;$i++)
        {
            echo $char[$i] . '>>>' . ord($char[$i]);echo '</br>';
            $ord = ord($char[$i]) + 3;
            $chr = chr($ord);
            echo $char[$i] . '>>>' . $ord . '>>>' . $chr;echo '<hr>';
            $pass .= $chr;
        }

        echo '</br>';
        echo $pass;
    }

    public function dec()
    {
        $enc = 'Khoor#Zruog';
        echo "密文：" . $enc;echo '<hr>';
        $length = strlen($enc);


        $str = "";
        for($i=0;$i<$length;$i++)
        {
            $ord = ord($enc[$i]) - 3;
            $chr = chr($ord);
            echo $ord . '>>>' . $chr;echo '</br>';
            $str .= $chr;
        }

        echo "解密：" . $str;

    }
}
