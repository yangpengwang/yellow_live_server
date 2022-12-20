<?php

namespace app\Common\AliOss;

use OSS\Core\OssException;
use OSS\OssClient;

class AliOss
{
    //oss初始化对象
    private $oss;

    //一些配置
    private $accessKeyId = 'LTAI5tEJN6SqwSmDPc9vsxUr';
    private $accessKeySecret = '5JpO4532BgbnbyEGANjFEYXYdZHnjY';
    private $endpoint = 'http://oss-cn-hangzhou.aliyuncs.com';

    //bucket名称
    private $bucket = 'yellow-live';

    //单例模式存放
    private static $instance;

    //单例模式入口函数
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * 构造方法
     * 初始化OSS
     * @access final private 为保护配置信息，构造函数不允许访问 且不可重载
     * @return void
     */
    final private function __construct()
    {

    }

    //禁止克隆
    private function __clone(){

    }
    //初始化Oss对象
    public function InitOss()
    {
        $this->oss = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        return $this->oss;
    }

    /**
     * @param $fileName  文件保存到OSS的路径包含名称
     * @param $filePath  本地的图片路径
     * @return void
     */
    public function uploadFile($fileName,$filePath){
        try {
           return $this->InitOss()->uploadFile($this->bucket,$fileName,$filePath);
        } catch (OssException $e){
            print $e->getMessage();
            return;
        }
    }

    /**
     * @param $filePath 删除的文件路径
     * @return void|null
     */
    public function deleteFile($filePath){
        try {
            return $this->InitOss()->deleteObject($this->bucket,$filePath);
        } catch (OssException $e){
            print $e->getMessage();
            return;
        }
    }

}
