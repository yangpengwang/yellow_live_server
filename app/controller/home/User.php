<?php
/*
 * @Author: yangpengwang 2254240747@qq.com
 * @Date: 2022-10-28 14:34:32
 * @LastEditors: yangpengwang 2254240747@qq.com
 * @LastEditTime: 2022-10-29 18:12:41
 * @FilePath: \tp\app\controller\home\User.php
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
namespace app\controller\home;

use app\BaseController;
use think\Request;
use think\captcha\facade\Captcha;


class User extends BaseController
{
    //登录验证
    public function login(Request $request){
        $data = $request->param();
        var_dump($data);
    }
    

    public function register(Request $request){
        $data = $request->param();
        var_dump($data);
    }
    
    //验证码
    public function verify()
    {
        return Captcha::create();    
    }
    
}
