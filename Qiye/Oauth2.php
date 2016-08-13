<?php
namespace WeChat\Qiye;
use WeChat\Constraint;
use WeChat\Src\Http;
/**
 * Created by PhpStorm.
 * User: 007
 * Date: 2016/8/12
 * Time: 14:14
 */
class Oauth2 implements Constraint\Oauth2
{
    function __construct( $weixin ){
        $this->weixin = $weixin;
    }
    /**
     * 网页登陆入口
     */
    public function oauth(){
        return $this->authorize();
    }
    /**
     * 企业获取code
     */
    public function authorize(){
        $code = $_GET['code'];
        if( $_SESSION['authorize_data'] ){
            return $_SESSION['authorize_data'];
        }elseif( empty($code) ){
            // 第一次进入
            $redirect_uri  = urlencode( Http::newUrl() );
            $appid = $this->weixin->appid;
            $state = time();
            $_SESSION['authorize_state'] = $state;
            //前去获取授权
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=SCOPE&state='.$state.'#wechat_redirect';
            header('Location:'.$url); exit(0);
        }elseif( $_SESSION['authorize_state']!=$_GET['state'] ){
            die('防跨域攻击,state参数有误');
        }else{
            $arrTemp 	= $this->getUserId($code);
            $_SESSION['authorize_data'] = $arrTemp;
            if( isset($arrTemp['UserId']) ){
                return $arrTemp;
            }else{
                print_r($res);
                die('ERROR');
            }
        }
    }
    public function getUserId($code=''){
        if( $code ){
            $url 		= 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token='.$this->weixin->gettoken().'&code='.$code;
            $res 		= file_get_contents($url);
            $arrTemp 	= json_decode( $res,true );
            return $arrTemp;
        }else{
            return $_SESSION['authorize_data'];
        }
    }
}