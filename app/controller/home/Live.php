<?php

namespace app\controller\home;

use app\controller\layout\Layout;
use app\model\Live as tableLive;
use app\Request;


class Live extends Layout
{
    public function getLiveUser(Request $request){

        $num = $request->param('num');

        //获取到开播的用户
        $tableLive = new TableLive;

        $data = $tableLive->where('is_live','1')->limit($num)->select();
        //如果没分类则随机获取
        return reponseMsg('获取主播成功','200',$data);
    }
}