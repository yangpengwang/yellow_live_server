<?php

namespace app\controller\home;

use app\BaseController;
use think\Request;
use app\model\User as tableUser;


class User extends BaseController
{
    public function islogin(){
        //验签token值 返回存里面的数据
        $data = checkToken();
        return $data;
    }

    
    //登录验证
    public function login(Request $request){
        $data = $request->param();
       
        //查找是否有该用户
        if($data['username'] == ''){
            return json(['message'=>'请输入用户名','httpcode'=>422]);
        }

        // 数据库中查询该用户
        $user =  tableUser::where('user_name',$data['username'])->find();
        
        
        if($user){
            //如果有该用户
            //验证是否输入密码
            if($data['password'] == ''){
                return json(['message'=>'请输入密码','httpcode'=>422]);
            }
            //验证密码是否正确
            if($data['password'] == $user['user_pass']){
                $token = setToken($user);
                return json(['message'=>'验证成功','token'=>$token,'httpcode'=>200]);
            }else{
                return json(['message'=>'密码错误','httpcode'=>422]);
            }
        }
      
    }
    

    public function register(Request $request){
        $data = $request->param();
        var_dump($data);
    }

}
