<?php
namespace WeChat\Constraint;
/**
 * 约束加载下级目录
 * User: 007
 * Date: 2016/8/9
 * Time: 10:48
 */
interface Middleware
{
//    public $type;
//    public $appid;
//    public $appsecret;
//    public $token;
//    public $agentid;
    public function __construct(array $config);
    public function gettoken();
    public function __call($name, $arguments);
}