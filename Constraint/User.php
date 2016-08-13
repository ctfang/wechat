<?php
namespace WeChat\Constraint;
/**
 * 约束加载下级目录
 * User: 007
 * Date: 2016/8/9
 * Time: 10:48
 */
interface User
{
    public function get( $userid );
    public function department( $userid );
    public function visible_scop( $userid );
}