<?php

namespace app\controller\home;

use app\controller\layout\Layout;
use think\Request;
use app\model\User as tableUser;
use app\model\Live as tableLive;
use app\Common\AliOss\AliOss;



class User extends Layout
{

    //申请直播
    public function applyLive(Request $request){
        //获取申请人的信息
        $data = $request->param();

        //验证姓名和身份证
        if(strlen(trim($data['idCard'])) == 18){

            //申请开播 改变状态
            $tableUser = new tableUser();
            $user = $tableUser->where('id',$data['id'])->find();
            $user->is_live = 1;
            $user->save();
            //添加到直播表里
            $roomId = uniqid($user->id,true);
            $addr = makeHlsAddr($roomId);
            tableLive::create(array(
                'live_title'    =>  $user->name.'的直播间',
                'live_img'      =>  makeGravatar($user->user_name),
                'live_roomId'   =>  $roomId,
                'hls_addr'      =>  $addr,
                'user_id'       =>  $user->id
            ));
            return reponseMsg('申请直播间成功','200');
        }else{
            return reponseMsg('身份证信息错误','422');
        }
    }

    //修改用户信息
    public function editUser(Request $request)
    {
        $data = $request->param();
        $tableUser = tableUser::find($data['id']);
        $user = $tableUser->allowField(['name'])->save(['name'=>$data['name']]);
        if($user){
            return reponseMsg('修改用户信息成功','200');
        }else{
            return reponseMsg('修改用户信息失败','422');
        }
    }

    //用户修改头像并上传OSS
    public function editUserImg(Request $request)
    {
        $userId = $request->param('id');
        $file = $request->file('file');

        if($file) {
            // 获取上传的图片格式
            $format = $file->getMime();
            //设置图片格式
            $format_array = ['image/jpg','image/jpeg','image/png'];
            if(in_array($format,$format_array)){
                //获取上传文件地址
                $format_name = $file->getPathname();
                //设置传入OSS上的文件地址
                $userImgName = $file->getInode().$userId.time().'.png';

                //上传OSS
                $data = AliOss::getInstance()->uploadFile('user_img/'.$userImgName,$format_name);
                //是否上传成功上传成功则进入
                if($data['info']['http_code'] == 200){
                    //查询用户信息
                    $tableUser = tableUser::find($userId);
                    //修改用户头像
                    $user = $tableUser->allowField(['user_img'])->save(['user_img'=>$data['info']['url']]);
                    //设置返回信息
                    $data = ['user_img' =>$data['info']['url']];
                    if($user){
                        return reponseMsg('修改头像成功','200',$data);
                    }else{
                        return reponseMsg('修改头像失败','422');
                    }
                }
            }else{
                return reponseMsg('上传的图片格式正确','422');
            }
        }else{
            return reponseMsg('请上传图片','422');
        }
    }

    //用户开启直播
    public function switchLive(Request $request)
    {
        //获取那个用户开启直播
        $userId = $request->param('id');
        $user = tableUser::find($userId);
        //判断用户是否申请为主播
        if($user['is_live'] == 1){
            //查询主播直播间
            $tableLive = new tableLive;
            return $tableLive->switchLive($userId);
        }else{
            return reponseMsg('您还未成为主播请成为主播后在进行开播','422');
        }
    }

    //用户是否开播
    public function isLive(Request $request){
        $userId = $request->param('id');
        $user = tableUser::find($userId);
        //判断用户是否申请为主播
        if($user['is_live'] == 1){
            //查询主播直播间
            $tableLive = new tableLive();
            return $tableLive->isLive($userId);
        }else{
            return reponseMsg('您还未成为主播请成为主播后在进行开播','200');
        }
    }
}