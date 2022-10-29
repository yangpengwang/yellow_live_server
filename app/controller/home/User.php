<?php

namespace app\controller\home;

use app\BaseController;
use think\Request;
use \Firebase\JWT\JWT;
use app\model\User as tableUser;


class User extends BaseController
{
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
                $token = $this->setToken($user['id']);
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


    /**
     * @生成token
     * @param {*} $id
     * @return token
     */
    function setToken($password) {
        $key = "aslfjhasgjgja";
        $token=array(
            "iss"=>$key,        //签发者 可以为空
            "aud"=>'',          //面象的用户，可以为空
            "iat"=>time(),      //签发时间
            "nbf"=>time(),    //在什么时候jwt开始生效  （这里表示签发后立即生效）
            "exp"=> time()+1*60*60*48, //token 过期时间1秒*60*60*48=两天
            "data"=>[           //加入password，后期同样使用password进行比对
                'password'=>$password,
            ]
        );
        
        $jwt = JWT::encode($token, $key, "HS256");  //根据参数生成了 token
        return $jwt;
    }


}
