<?php
declare (strict_types = 1);

namespace app\http;

use think\worker\Server;


class Worker extends Server
{
    protected $socket = "websocket://0.0.0.0:2345";
    protected static $heartbeat_time = 50;

    protected $client_id;

    //服务启动的时候触发
    public function onWorkerStart($worker){
        echo 'onWorkerStart';
    }

    // 建立连接的时候触发
    public function onConnect($connection){
        echo 'onConnect';
    }

    //接收数据的时候触发
    public function onMessage($connection,$data){
        echo 'onMessage';
        $connection->send(json_encode($data));
    }

    //断开连接的时候触发
    public function onClose($connection){
        echo 'onClose';
    }

    //客户端连接发生错误的时候触发
    public function onError($connection){
        echo 'onError';
    }
}
