<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
return array(
    'CONTROLLER_LEVEL'  => 2, //控制器分级

    // 路由配置
    'URL_ROUTER_ON'     => true,
    'URL_MAP_RULES'     => array(
        'index'         =>    'default/index', // 每个模块必须配置此项
    ),
    'URL_ROUTE_RULES'   => array(
        'u/:uid'        =>    'default/home',
    ),
);
