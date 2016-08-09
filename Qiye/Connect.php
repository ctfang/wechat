<?php
namespace WeChat\Qiye;
use WeChat\Constraint\Middleware;
use WeChat\Src\Cache;
/**
 * Created by PhpStorm.
 * User: 007
 * Date: 2016/8/9
 * Time: 10:49
 */
class Connect implements Middleware
{
    public $type;
    public $appid;
    public $appsecret;
    public $token;
    public $agentid;

    /**
     * 设置基本变量
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $key=>$value){
            $this->$key = $value;
        }
    }

    /**
     * 加载下一目录方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments){
        $class = 'WeChat\\Qiye\\'.ucfirst(strtolower($name));
        if( class_exists( $class ) ){
            return new $class( $arguments );
        }else{
            die('找不到类'.$class);
        }
    }
    /**
     * 获取token
     * @return bool
     */
    public function gettoken()
    {
        $cacheName  = md5($this).'token';
        $str        = Cache::get($cacheName);
        if( $str ){
            return $str;
        }
        $corpId     = $this->appid;
        $Secret     = $this->appsecret;
        $prm        = "corpid=".$corpId."&corpsecret=".$Secret;
        $url        = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?";
        $res        = file_get_contents( $url.$prm );
        $arrTemp    = json_decode( $res,true );
        if( !$arrTemp['access_token'] ){
            die( 'ERR: '.$res );
        }
        Cache::set($cacheName,$arrTemp['access_token'],$arrTemp['expires_in']-100);
        return $arrTemp['access_token'];
    }
}