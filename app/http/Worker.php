<?php
declare (strict_types = 1);

namespace app\http;

use think\worker\Server;


class Worker extends Server
{
    protected $socket = "websocket://0.0.0.0:2345";
    // protected static $heartbeat_time = 50;
    // protected $client_id;



    //接收数据的时候触发
    public function onMessage($connection,$data){
        $arr = json_decode($data);
      
        //表示绑定
        if($arr->type == 'bind' && $arr->roomId){
            // 没验证的话把第一个包当做uid（这里为了方便演示，没做真正的验证）
            $connection->uid = $arr->uid;
            /* 保存uid到connection的映射，这样可以方便的通过uid查找connection，
            * 实现针对特定uid推送数据
            */
            $this->worker->uidConnections[$arr->roomId][$connection->uid] = $connection;
        }

        //群聊天
        if($arr->type == 'text' && $arr->mode == 'group'){
            //获取房间内的用户
            $users = $this->worker->uidConnections[$arr->roomId];
            
            foreach($users as $conn){
                $conn->send($data);
            }
        }

    }

    //断开连接的时候触发
    public function onClose($connection){
        // echo 'onClose';
    }

    //客户端连接发生错误的时候触发
    public function onError($connection){
        // echo 'onError';
    }
}
