<?php
declare (strict_types = 1);

namespace app\http;

use think\worker\Server;
use Workerman\Lib\Timer;


class Worker extends Server
{
    protected $socket = "websocket://0.0.0.0:2345";
    protected static $heartbeat_time = 55;


    //设置定时器 定时监听心跳
    public function onWorkerStart($worker){
        Timer::add(10, function()use($worker){
            $time_now = time();
            foreach($worker->connections as $connection) {
                // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                if (empty($connection->lastMessageTime)) {
                    $connection->lastMessageTime = $time_now;
                    continue;
                }
                // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                if ($time_now - $connection->lastMessageTime > self::$heartbeat_time) {
                    $connection->close();
                }
                
            }
        });
    }

    //接收数据的时候触发
    public function onMessage($connection,$data){
         // 给connection临时设置一个lastMessageTime属性，用来记录上次收到消息的时间
        $connection->lastMessageTime = time();
        $arr = json_decode($data);

        //表示绑定
        if($arr->type == 'bind' && $arr->roomId){
            // 没验证的话把第一个包当做uid（这里为了方便演示，没做真正的验证）
            $connection->uid = $arr->uid;
            /* 保存uid到connection的映射，这样可以方便的通过uid查找connection，
            * 实现针对特定uid推送数据
            */
            $this->worker->uidConnections[$arr->roomId][$connection->uid] = $connection;
            $users = $this->worker->uidConnections[$arr->roomId];
            foreach($users as $conn){
                $conn->send($data);
            }
        }

        //直播聊天
        if($arr->type == 'text' && $arr->mode == 'group'){
            //获取房间内的用户
            $users = $this->worker->uidConnections[$arr->roomId];
            foreach($users as $conn){
                $conn->send($data);
            }
        }

    }

  


}
