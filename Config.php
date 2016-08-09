<?php
namespace WeChat;

/**
 * 获取tp or myself 配置类
 */
class config
{

    static public $config_path ='';

    static protected $config ;

    static public function __callStatic($funcname, $arguments){
        if(isset(self::$$funcname)){
            return self::$$funcname;
        }
        self::set();

        return self::$config[$funcname];
    }

    static public function set($name='',$value=''){
        if ( !isset(self::$config) && config::config_path() && !isset(self::$$name) ){
            if( !is_file(config::config_path()) ){
                mkdir(dirname(config::config_path()), 0755, true);
                $config_str = file_get_contents(__DIR__.'/Src/config.tpl');
                file_put_contents(config::config_path(),$config_str);
            }
            self::$config = include config::config_path();
        }
        if( isset(self::$$name) && $value!='' ){
            self::$$name  = $value;
        }elseif ($value!=''){
            self::$config[$name] = $value;
        }
    }

    static public function all(){
        self::set();
        return self::$config;
    }
    static public function config_path(){
        if( empty(self::$config_path) ){
            $vendorDir  = dirname(dirname(dirname(__FILE__)));
            $baseDir    = dirname($vendorDir);
            self::$config_path = $baseDir.'/config/wechat.php';
        }
        return self::$config_path;
    }
}