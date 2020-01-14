<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Redis;

use App\Model\UserModel;
use App\User;

class TestController extends Controller
{
    public function test()
    {
        echo '<pre>';print_r($_SERVER);echo '</pre>';
    }


    // // 用户注册
    // public function reg(Request $request)
    // {
    //     echo '<pre>';print_r($request->input());echo '</pre>';

    //     // 验证用户名 验证email 验证手机号

    //     $pass1 = $request->input('pass1');
    //     $pass2 = $request->input('pass2');

    //     if($pass1 != $pass2){
    //         die('两次输入的密码不一致');
    //     }

    //     $password = password_hash($pass1,PASSWORD_BCRYPT);

    //     $data = [
    //         'email'      => $request->input('email'),
    //         'name'       => $request->input('name'),
    //         'password'      => $password,
    //         'mobile'      => $request->input('mobile'),
    //         'last_login'      => time(),
    //         'last_ip'      => $_SERVER['REMOTE_ADDR'],       // 获取远程IP
    //     ];

    //    $uid = UserModel::insertGetId($data);
    //    var_dump($uid);
    // }

     /**
     * 用户注册
     */
    public function reg0(Request $request)
    {
        echo '<pre>';print_r($request->input());echo '</pre>';
        //验证用户名 验证email 验证手机号
        $pass1 = $request->input('pass1');
        $pass2 = $request->input('pass2');
        if($pass1 != $pass2){
            die("两次输入的密码不一致");
        }
        $password = password_hash($pass1,PASSWORD_BCRYPT);
        $data = [
            'email'         => $request->input('email'),
            'name'          => $request->input('name'),
            'password'      => $password,
            'mobile'        => $request->input('mobile'),
            'last_login'    => time(),
            'last_ip'       => $_SERVER['REMOTE_ADDR'],     //获取远程IP
        ];
        $uid = UserModel::insertGetId($data);
        var_dump($uid);
    }



    // public function login(Request $request)
    // {
    //     $name = $request->input('name');
    //     $pass = $request->input('pass');

    //     // echo "pass：" . $pass;

    //     $u = UserModel::where(['name'=>$name])->first();
    //     // var_dump($u);

    //     if($u){
    //         // echo '<pre>';print_r($u->toArray());echo '</pre>';

    //         // 验证密码
    //         if(password_verify($pass,$u->password)){
    //             // 登录成功

    //             // echo '登陆成功';

    //             // 生成token
    //             $token = Str::random(32);
    //             // echo $token;

    //             $response = [
    //                 'error' => 0,
    //                 'msg'   => 'ok',
    //                 'data'  => [
    //                     'token' => $token
    //                 ]
    //             ];

    //             return $response;
    //         }else{
    //             // echo "密码不正确";

    //             $response = [
    //                 'error' => 400003,
    //                 'msg'   => '密码不正确',
    //             ];

    //         }
    //         // $res = password_verify($pass,$u->password);
    //         // var_dump($res);
    //     }else{
    //         // echo "没有此用户";
    //         $response = [
    //             'error' => 400004,
    //             'msg'   => '用户不存在',
    //         ];
    //     }

    //     return $response;

    // }

    /**
     * 用户登录接口
     * @param Request $request
     * @return array
     */
    public function login0(Request $request)
    {
        $name = $request->input('name');
        $pass = $request->input('pass');
        $u = UserModel::where(['name'=>$name])->first();
        if($u){
            //验证密码
            if( password_verify($pass,$u->password) ){
                // 登录成功
                //echo '登录成功';
                //生成token
                $token = Str::random(32);
                $response = [
                    'errno' => 0,
                    'msg'   => 'ok',
                    'data'  => [
                        'token' => $token
                    ]
                ];
            }else{
                $response = [
                    'errno' => 400003,
                    'msg'   => '密码不正确'
                ];
            }
        }else{
            $response = [
                'errno' => 400004,
                'msg'   => '用户不存在'
            ];
        }
        return $response;
    }

     /**
     * 获取用户列表
     * 2020年1月2日16:32:07
     */
    public function userList()
    {
        // $user_token = $_SERVER['HTTP_TOKEN'];
        // echo 'user_token: '.$user_token;echo '</br>';
        // $current_url = $_SERVER['REQUEST_URI'];
        // echo "当前URL: ".$current_url;echo '<hr>';
        // //echo '<pre>';print_r($_SERVER);echo '</pre>';
        // //$url = $_SERVER[''] . $_SERVER[''];
        // $redis_key = 'str:count:u:'.$user_token.':url:'.md5($current_url);
        // echo 'redis key: '.$redis_key;echo '</br>';
        // $count = Redis::get($redis_key);        //获取接口的访问次数
        // echo "接口的访问次数： ".$count;echo '</br>';
        // if($count >= 5){
        //     echo "请不要频繁访问此接口，访问次数已到上限，请稍后再试";
        //     Redis::expire($redis_key,15);
        //     die;
        // }
        // $count = Redis::incr($redis_key);
        // echo 'count: '.$count;


        $list = UserModel::all();
        echo '<pre>';print_r($list->toArray());echo '</pre>';


    }

    /**
     * APP注册
     * @return bool|string
     */
    public function reg()
    {
        //请求passport
        $url = 'http://passport.1905.com/api/user/reg';
        $response = UserModel::curlPost($url,$_POST);
        return $response;
    }
    /**
     * APP 登录
     */
    public function login()
    {
        //请求passport
        $url = 'http://passport.1905.com/api/user/login';
        $response = UserModel::curlPost($url,$_POST);
        return $response;
    }
    public function showData()
    {
        // 收到 token
        $uid = $_SERVER['HTTP_UID'];
        $token = $_SERVER['HTTP_TOKEN'];
        // 请求passport鉴权
        $url = 'http://passport.1905.com/api/auth';         //鉴权接口
        $response = UserModel::curlPost($url,['uid'=>$uid,'token'=>$token]);
        echo '<pre>';print_r($response);echo '</pre>';die;
        $status = json_decode($response,true);
        //处理鉴权结果
        if($status['errno']==0)     //鉴权通过
        {
            $data = "sdlfkjsldfkjsdlf";
            $response = [
                'errno' => 0,
                'msg'   => 'ok',
                'data'  => $data
            ];
        }else{          //鉴权失败
            $response = [
                'errno' => 40003,
                'msg'   => '授权失败'
            ];
        }
        return $response;
    }

    // public function fang()
    // {
    //     // $user_token = $_SERVER['HTTP_TOKEN'];
    //     // echo 'user_token: '.$user_token;echo '</br>';
    //     // $current_url = $_SERVER['REQUEST_URI'];
    //     // echo "当前URL: ".$current_url;echo '<hr>';
    //     // //echo '<pre>';print_r($_SERVER);echo '</pre>';
    //     // //$url = $_SERVER[''] . $_SERVER[''];
    //     // $redis_key = 'str:count:u:'.$user_token.':url:'.md5($current_url);
    //     // echo 'redis key: '.$redis_key;echo '</br>';
    //     // $count = Redis::get($redis_key);        //获取接口的访问次数
    //     // echo "接口的访问次数： ".$count;echo '</br>';
    //     // if($count >= 5){
    //     //     echo "请不要频繁访问此接口，访问次数已到上限，请稍后再试";
    //     //     Redis::expire($redis_key,15);
    //     //     die;
    //     // }
    //     // $count = Redis::incr($redis_key);
    //     // echo 'count: '.$count;


    //     $list = UserModel::all();
    //     echo '<pre>';print_r($list->toArray());echo '</pre>';


    // }




}
