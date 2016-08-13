<?php
namespace WeChat\Src;
use WeChat\Config;
/**
 * Created by PhpStorm.
 * User: 007
 * Date: 2016/8/12
 * Time: 14:32
 */
class Http
{
    static public function newUrl(){
        $url = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
        $url .= $_SERVER['HTTP_HOST'];
        $url .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : urlencode($_SERVER['PHP_SELF']) . '?' . urlencode($_SERVER['QUERY_STRING']);
        return $url;
    }
}