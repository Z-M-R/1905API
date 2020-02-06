<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    // echo "api.1905.com";die;
    return view('welcome');
});


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/info',function(){
    phpinfo();
});

Route::get('/test/pay','TestController@alipay');    // 沙箱支付测试

Route::get('/test/ascii','TestController@ascii');    // 加密
Route::get('/test/dec','TestController@dec');    // 解密


Route::get('/test/alipay/return','Alipay\PayController@aliReturn');
Route::post('/test/alipay/notify','Alipay\PayController@notify');

// 接口
Route::get('/api/test','Api\TestController@test');

Route::post('/api/user/reg','Api\TestController@reg');  // 用户注册
Route::post('/api/user/login','Api\TestController@login');  // 用户登录
Route::get('/api/show/data','Api\TestController@showData')->middleware('fangshua');     //获取数据接口
Route::get('/api/user/list','Api\TestController@userList')->middleware('filter');  // 用户登录

//curl测试
Route::get('/test/curl1','Test\CurlController@curl1');
Route::post('/test/curl2','Test\CurlController@curl2');
Route::post('/test/curl3','Test\CurlController@curl3');
Route::post('/test/curl4','Test\CurlController@curl4');
//
Route::get('/test/rsa1','TestController@rsa1');

//用户管理
Route::get('/user/addkey','User\IndexController@addSSHKey1');
Route::post('/user/addkey','User\IndexController@addSSHKey2');
//解密数据
Route::get('/user/decrypt/data','User\IndexController@decrypt1');
Route::post('/user/decrypt/data','User\IndexController@decrypt2');

//签名测试
Route::get('/sign1','TestController@sign1');
Route::get('/test/get/signonlie','Sign\IndexController@signOnline');
Route::post('/test/post/signonlie','Sign\IndexController@signOnline1');
Route::get('/test/get/sign1','Sign\IndexController@sign1');
Route::post('/test/post/sign2','Sign\IndexController@sign2');


//
Route::get('/sign2','TestController@sign2');

Route::get('/test/postman','Api\TestController@postman');
//Route::get('/test/postman1','Api\TestController@postman1');
//Route::get('/test/postman1','Api\TestController@postman1')->middleware('filter');
Route::get('/test/postman1','Api\TestController@postman1')->middleware('filter','checktoken');
