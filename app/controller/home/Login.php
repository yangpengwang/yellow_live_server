<?php

namespace app\controller\home;

use app\controller\layout\Layout;
use think\Request;
use app\model\User as tableUser;


class Login extends Layout
{
    public function islogin()
    {
        //验签token值 返回存里面的数据
        $data = checkToken();
        //查询
        $user =  tableUser::where('id',$data->data->id)->find()->toArray();
        //去除无用的数据
        unset($user['user_pass'],$user['user_name']);
        $res = ['user'=>$user,'code'=>'200'];
        return $res;
    }

    
    //登录验证
    public function login(Request $request)
    {
        $data = $request->param();
        //查找是否有该用户
        $userName = trim($data['username']);
        $password = trim($data['password']);
        if($userName == ''){
            return json(['message'=>'请输入用户名','httpcode'=>422]);
        }

        // 数据库中查询该用户
        $user =  tableUser::where('user_name',$userName)->find();
       
        if($user){
            //如果有该用户
            //验证是否输入密码
            if($password == ''){
                return json(['message'=>'请输入密码','httpcode'=>422]);
            }
            //验证密码是否正确
            if(md5($password) == $user['user_pass']){
                $tokenData = ['id'=>$user->id];
                $token = setToken($tokenData);
                return json(['message'=>'登录成功','token'=>$token,'httpcode'=>200]);
            }else{
                return json(['message'=>'密码错误','httpcode'=>422]);
            }
        }else{
            return json(['message'=>'账号密码错误','httpcode'=>422]);
        }
      
    }
    
    //用户注册
    public function register(Request $request)
    {
        $data = $request->param();
        $userName = trim($data['username']);
        $password = trim($data['password']);
        $repassword = trim($data['repassword']);
        //查找是否有该用户
        if($userName == ''){
            return json(['message'=>'请输入用户名','httpcode'=>422]);
        }
        
        // 数据库中查询该用户
        $user =  tableUser::where('user_name',$userName)->find();
        
        if($user){
            return json(['message'=>'该账号已被注册','httpcode'=>422]);
        }else{
            if($password == ''){
                return json(['message'=>'请输入密码','httpcode'=>422]);
            }
            if($repassword == ''){
                return json(['message'=>'请输入确认密码','httpcode'=>422]);
            }
             //验证密码与确认密码是否相同
             if($password == $repassword){
                $newUser = tableUser::create([
                    'user_name' => $data['username'],
                    'user_pass' => md5($data['password']),
                    'name'      => '游客'.time(),
                    'user_img'       => makeGravatar($userName)
                ]);
                $tokenData = ['id'=>$newUser->id,'','name'=>$newUser->name];
                $token = setToken($tokenData);
                return json(['message'=>'注册并登录成功','token'=>$token,'httpcode'=>200]);
            }else{
                return json(['message'=>'密码错误','httpcode'=>422]);
            }
        }
    }
}
