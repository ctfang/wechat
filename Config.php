<?php
namespace WeChat;

/**
 * 获取tp or myself 配置类
 */
class Config
{

    static public $config_path ='./Application/Common/Conf/wechat.php';

    static public $cache_path ='./Runtime';

    static protected $config ;

    static public function __callStatic($funcname, $arguments){
        if(isset(self::$$funcname)){
            return self::$$funcname;
        }
        self::set();

        return self::$config[$funcname];
    }

    static public function set($name='',$value=''){
        if ( !isset(self::$config) && config::config_path() ){
            if( !is_file(config::config_path()) ){
                mkdir(dirname(config::config_path()), 0755, true);
                $config_str = file_get_contents('Src/config.tpl');
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
}