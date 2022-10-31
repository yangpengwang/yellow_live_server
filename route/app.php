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
Route::group('user',function(){
    //登录
    Route::post('/login', 'home.user/login');
    //注册
    Route::post('/register', 'home.user/register');
    //验证码
    Route::get('/verify', 'home.user/verify');
    //是否登录
    Route::get('/islogin','home.user/islogin');
});



