<?php
namespace app\controller\home;

use app\BaseController;
use think\Request;
use think\captcha\facade\Captcha;


class User extends BaseController
{
    public function login(Request $request){
        $data = $request->param();
        var_dump($data);
    }

    public function verify()
    {
        return Captcha::create();    
    }
}
