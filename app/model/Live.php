<?php
namespace app\model;

use think\Model;

class Live extends Model
{
    public function isLive($id){
        $userLive = $this->where('user_id',$id)->find();
        if($userLive['is_live']){
            $data = [
                'hls_addr'=>$userLive['hls_addr'],
                'is_live'=>$userLive['is_live']
            ];
            return reponseMsg('您已经开启了直播','200',$data);
        }else{
            $data = [
                'is_live'=>$userLive['is_live']
            ];
            return reponseMsg('尚未开播','200',$data);
        }
    }

    //开关直播
    public function switchLive($id){
        //获取那个用户开启直播
        $userLive = $this->where('user_id',$id)->find();
        //判断用户是否申请为主播
        if($userLive['is_live'] == 1){
            $hls_addr = makeHlsAddr($userLive['live_roomId']);
            $userLive->hls_addr = $hls_addr;
            $userLive->is_live = 0;
            $live = $userLive->save();
            //更换hls_addr地址
            if($live){
                $data = ['is_live' => 0];
                return reponseMsg('关闭直播成功','200',$data);
            }
        }else{
            $userLive->is_live = 1;
            $live = $userLive->save();
            if($live){
                $data = [
                    'is_live' => 1,
                    'hls_addr' => $userLive['hls_addr']
                ];
                return reponseMsg('开启直播成功','200',$data);
            }

        }
    }


}