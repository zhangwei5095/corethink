<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
//模块信息配置
return array(
    //模块信息
    'info' => array(
        'name'        => 'Adminer',
        'title'       => '数据库(Adminer)',
        'icon'        => 'fa fa-coffee',
        'icon_color'  => '#3366CC',
        'description' => '数据库管理模块',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.corethink.cn',
        'version'     => '1.1.0',
        'beta'        => '3.0',
        'dependences' => array(
            'Admin'   => '1.1.0',
        )
    ),

    // 模块配置
    'config' => array(
        'host' => array(
            'title'  => '主机',
            'type'   =>'text',
            'value'  => '127.0.0.1',
        ),
        'db' => array(
            'title'  => '数据库名',
            'type'   =>'text',
            'value'  => 'corethink',
        ),
        'username' => array(
            'title'  => '用户名',
            'type'   =>'text',
            'value'  => 'root',
        ),
    ),

    //后台菜单及权限节点配置
    'admin_menu' => array(
        '1' => array(
            'pid'   => '0',
            'title' => '开发',
            'icon'  => 'fa fa-coffee',
        ),
        '2' => array(
            'pid'   => '1',
            'title' => '数据库管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3' => array(
            'pid'   => '2',
            'title' => '数据库配置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Adminer/Admin/Default/module_config',
        ),
        '4' => array(
            'pid'   => '2',
            'title' => '打开Adminer',
            'icon'  => 'fa fa-database',
            'url'   => 'Adminer/Admin/Default/index',
        )
    )
);
