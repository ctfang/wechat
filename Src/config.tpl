<?php
return array(
    // 默认微信配置
    'wechat'=>array(
        'type'      =>'公众号类型 qiye or fuwu or dingyue',
        'appid'     => '微信公众平台中的appid',
        'appsecret' => '微信公众平台中的secret',
        'token'     => '微信服务器对接您的服务器验证token',
        'agentid'   => '如果是企业化设置应用id',
    ),
    'cache_path'=>'./Runtime',// 缓存目录
);