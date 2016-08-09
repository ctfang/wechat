<?php
namespace WeChat\Src;
use WeChat\config;
/**
 * Created by PhpStorm.
 * User: 007
 * Date: 2016/8/9
 * Time: 14:26
 */
class Cache
{
    /**
     * @param $name
     * @param $value
     * @param int $expiration
     */
    static public function set($name,$value,$expiration=0)
    {
        $cache_file_path = config::cache_path();
        if( !file_exists($cache_file_path) ) mkdir($cache_file_path, 0755, true);
        $data = ['data'=>$value,'expiration'=>$expiration+time()];
        $str  = serialize($data);
        file_put_contents($cache_file_path.'/'.$name,$str);
    }
    static public function get($name){
        $file_path       = config::cache_path().'/'.$name;
        if( !file_exists($file_path) ) return false;
        $str    = file_get_contents($file_path);
        $data   = unserialize($str);
        if( $data['expiration']<=time() ){
            unlink($file_path);
            return false;
        }
        return $data['data'];
    }
    static public function delete($name){
        $file_path       = config::cache_path().'/'.$name;
        if( !file_exists($file_path) ) return true;
        unlink($file_path);
    }
}