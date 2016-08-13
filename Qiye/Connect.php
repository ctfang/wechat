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
    public $encodingaeskey;
    static public $wxcpt;

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
            return new $class( $this );
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
    // 获取微信库对象
    public function wxcpt(){
        if( isset(self::$wxcpt) ){
            return self::$wxcpt;
        }
        // 引入微信库
        require_once dirname(__DIR__).'/Src/qiye/WXBizMsgCrypt.php';
        // 引入微信库
        self::$wxcpt = new \WXBizMsgCrypt($this->token, $this->encodingaeskey, $this->appid);
        return self::$wxcpt;
    }

    /**
     * @return mixed
     */
    public function getData() {
        $this->wxcpt 	= $this->wxcpt();
        // 获取参数
        $input['msg_signature']		=	$_GET['msg_signature'];
        $input['timestamp']		    =	$_GET['timestamp'];
        $input['nonce']		        =	$_GET['nonce'];
        if( $echostr = $_GET['echostr'] ){
            $errCode 		= $this->wxcpt->VerifyURL($input['msg_signature'], $input['timestamp'], $input['nonce'], $echostr, $sMsg);
            if ($errCode == 0) {
                // 验证URL成功，将sEchoStr返回
                echo $sMsg;		exit(0);
            } else {
                print_r($input);
                die('error');
            }
        }
        $input['msg_encrypt'] 		=   $GLOBALS["HTTP_RAW_POST_DATA"];
        // 实例化
        $this->wxcpt 	= $this->wxcpt();
        $sMsg 			= "";  // 解析之后的明文
        $errCode 		= $this->wxcpt->DecryptMsg($input['msg_signature'], $input['timestamp'], $input['nonce'], $input['msg_encrypt'], $sMsg);
        if ($errCode == 0) {
            // 解密成功，sMsg即为xml格式的明文
            $content = $sMsg;
        }else{
            die("ERR: " . $errCode . "\n\n");
        }
        $data = new \SimpleXMLElement ( $content );

        foreach ( $data as $key => $value ) {
            foreach($value as $k => $vo){
                $this->data [$key][$k] = strval ( $vo );
            }
            if(!is_array($this->data [$key])){
                $this->data [$key] = strval ( $value );
            }
        }
        return $this->data;
    }
}