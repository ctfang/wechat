<?php
namespace WeChat;
/**
 * Created by PhpStorm.
 * User: 007
 * Date: 2016/8/9
 * Time: 10:32
 */
class WeChat
{
    static private  $instance   =  null;     //  操作实例

    static public function getConnect( $argument='wechat' )
    {
        $config = config::{$argument}();

        switch ($config['type']) {
            case 'qiye':
                $namespace = 'WeChat\\Qiye\\Connect';
                break;
            case 'fuwu':
                $namespace = 'WeChat\\Fuwu\\Connect';
                break;
            case 'dingyue':
                $namespace = 'WeChat\\Dingyue\\Connect';
                break;
            default:
                die('必须设置为公众号类型');
                break;
        }
        return  new $namespace( $config );
    }
}