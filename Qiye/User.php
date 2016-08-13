<?php
namespace WeChat\Qiye;
use WeChat\Constraint;
use WeChat\Src\Http;
/**
 * Created by PhpStorm.
 * User: 007
 * Date: 2016/8/12
 * Time: 15:11
 */
class User implements Constraint\User
{
    function __construct( $weixin ){
        $this->weixin = $weixin;
    }
    /**
     * 获取成员
     * @param $userid
     * @return mixed
     */
    public function get( $userid ){
        $url 		= 'https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token='.$this->weixin->gettoken().'&userid='.$userid;
        $res 		= file_get_contents($url);
        $arrTemp 	= json_decode( $res,true );
        if( $arrTemp['errcode']===0 ){
            // 请求缓存
            return $arrTemp;
        }else{
            die('获取成员信息失败');
        }
    }
    /**
     * 获取成员所有部门
     */
    public function department( $userid ){
        $info = $this->get( $userid );
        return $info['department'];
    }
    /**
     * 获取可见范围内的用户部门列表
     */
    public function visible_scop( $userid ){
        // 用户的部门
        $arr1 = $this->department( $userid );
        // 该应用的可见范围部门
        $arr2 = $this->weixin->department()->visible_scop();
        foreach($arr1 as $id){
            if( !empty($arr2[$id]) ){
                $return[$id] = $arr2[$id];
            }
        }
        return $return;
    }
}