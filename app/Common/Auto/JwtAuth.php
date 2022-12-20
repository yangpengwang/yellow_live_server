<?php
namespace app\Common\AliOss\Auto;

use Cassandra\Cluster\Builder;

class JwtAuth
{
    private $token;

    private $iss = 'api.test.com';  //签发人

    private $aud = 'my_server_app'; //受众人

    private $uid;

    private $secretc = '$#aslfjhasgjgja';

    //单例模式JWT句柄
    private static $instance;

    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }

        return self::$instance;
    }

    //不让构造方法
    private function __construct()
    {
    }
    //不让克隆
    private function __clone(){

    }

    //获取token
    public function getToken()
    {
        return (string)$this->token;
    }

    //设置token
    public function setToken($token)
    {
        $this->token = $token;

        return this->token;
    }

    public function setUid($uid)
    {
        $this->$uid = $uid;

        return this->$uid;
    }

    //编码JWT token
    public function encode()
    {
        $time = time();
        $this->token = (new Builder())->setHeader('alg','HS256')
            ->setIssuer($this->iss)
            ->setAudience($this->aud)
            ->setIssuedAt($time)
            ->setExpiration($time + 1*60*60*48)
            ->set('uid',$this->uid)
            ->set(new Sha256(),$this->secretc)
            ->getToken();
        return $this;
    }



}