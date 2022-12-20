<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;


//前端登录注册
Route::group('login',function(){
    //登录
    Route::post('/login', 'home.login/login');
    //注册
    Route::post('/register', 'home.login/register');
    //验证码
    Route::get('/verify', 'home.login/verify');
    //是否登录
    Route::get('/islogin','home.login/islogin');
});

Route::group('user',function(){
    //申请直播
    Route::post('/applyLive','home.user/applyLive');
    //修改用户名称
    Route::post('/editUser','home.user/editUser');
    //修改用户头像
    Route::post('/editUserImg','home.user/editUserImg');
    //用户开启直播
    Route::post('/switchLive','home.user/switchLive');
    //是否开启直播
    Route::post('/isLive','home.user/isLive');

});

Route::group('live',function(){
    //获取正在直播或录播的用户
    Route::post('/getLiveUser','home.live/getLiveUser');
});




