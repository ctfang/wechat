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
    
    public function __construct( $argument )
    {
        $config = config::all();
        
        print_r($config);
    }
}