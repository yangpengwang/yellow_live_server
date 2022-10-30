<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// 应用公共文件
 /**
 * @生成token
 * @param {*} $id
 * @return token
 */
function setToken($data) {
    $key = "aslfjhasgjgja";
    $token=array(
        "iss"=>$key,        //签发者 可以为空
        "aud"=>'',          //面象的用户，可以为空
        "iat"=>time(),      //签发时间
        "nbf"=>time(),    //在什么时候jwt开始生效  （这里表示签发后立即生效）
        "exp"=> time()+1*60*60*48, //token 过期时间1秒*60*60*48=两天
        "data"=>[ 
            'id'=>$data['id'],
            'name' => $data['name'],
            'code' => 200,
        ]
    );
    $keyId = "keyId";
    $jwt = JWT::encode($token, $key, "HS256",$keyId);  //根据参数生成了 token
    return $jwt;
}

// 验签token
function checkToken(){
    // 获取请求头header中的authorization（token值）
    $token = request() -> header('authorization');
    
    // 去除token值中的bearer+空格标识
    $token = str_replace('bearer ', '', $token);

    
    // return response($token);
    if($token === "undefined"){
        // abort终止操作，返回结果
        abort(json(['message' => '登陆状态失效，请重新登录', 'code' => 401]));
        // return response($code);
    }
    // key必须与生成token值得字符串相同
    $key = "aslfjhasgjgja";
    
    try {
        JWT::$leeway = 60;//当前时间减去60，把时间留点余地用于后面的操作
        $key = new Key($key, 'HS256');
        $decoded = JWT::decode($token, $key); //HS256方式，这里要和签发的时候对应
        return $decoded;
    } catch(\Exception $e) { //其他错误
        abort(json(['message' => '登陆状态失效，请重新登录', 'code' => 401]));
    }

}