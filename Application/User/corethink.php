<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
// 模块信息配置
return array(
    // 模块信息
    'info' => array(
        'name'        => 'User',
        'title'       => '用户',
        'icon'        => 'fa fa-users',
        'icon_color'  => '#F9B440',
        'description' => '用户中心模块',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.corethink.cn',
        'version'     => '1.1.0',
        'beta'        => '3.0',
        'dependences' => array(
            'Admin'   => '1.1.0',
        )
    ),

    // 用户中心导航
    'user_nav' => array(
        'center' => array(
            '0' => array(
                'title' => '修改信息',
                'icon'  => 'fa fa-edit',
                'url'   => 'User/Home/Center/profile',
            ),
            '1' => array(
                'title' => '修改密码',
                'icon'  => 'fa fa-lock',
                'url'   => 'User/Home/Center/password',
            ),
            '2' => array(
                'title' => '消息中心',
                'icon'  => 'fa fa-envelope-o',
                'url'   => 'User/Home/Message/index',
            ),
        ),
        'main' => array(
            '0' => array(
                'title' => '个人中心',
                'icon'  => 'fa fa-tachometer',
                'url'   => 'User/Home/Center/index',
            ),
        ),
    ),

    // 模块配置
    'config' => array(
        'status' => array(
            'title'   => '是否开启',
            'type'    => 'radio',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value' => '1',
        ),
        'reg_toggle' => array(
            'title'   => '注册开关',
            'type'    => 'radio',
            'options' => array(
                '1'   => '开启',
                '0'   => '关闭',
            ),
            'value'   => '1',
        ),
        'allow_reg_type' => array(
            'title'   => '允许注册类型',
            'type'    =>'checkbox',
            'options' => array(
                'username' => '用户名注册',
                'email'    => '邮箱注册',
                'mobile'   => '手机注册',
            ),
            'value'=> array(
                '0' => 'username',
            ),
        ),
        'deny_username' => array(
            'title'   => '禁止注册的用户名',
            'type'    =>'textarea',
            'value'   => '',
        ),
        'user_protocol' => array(
            'title'   => '用户协议',
            'type'    =>'kindeditor',
            'value'=>'',
        ),
        'behavior' => array(
            'title'   => '行为扩展',
            'type'   =>'checkbox',
            'options'=> array(
                'User' => 'User',
            ),
            'value'  => array(
                '0'  => 'User',
            ),
        ),
    ),

    // 后台菜单及权限节点配置
    'admin_menu' => array(
        '1' => array(
            'pid'   => '0',
            'title' => '用户',
            'icon'  => 'fa fa-user',
        ),
        '2' => array(
            'pid'   => '1',
            'title' => '用户管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3' => array(
            'pid'   => '2',
            'title' => '用户设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'User/Admin/Default/module_config',
        ),
        '4' => array(
            'pid'   => '2',
            'title' => '用户统计',
            'icon'  => 'fa fa-area-chart',
            'url'   => 'User/Admin/Default/index',
        ),
        '5' => array(
            'pid'   => '2',
            'title' => '用户列表',
            'icon'  => 'fa fa-list',
            'url'   => 'User/Admin/User/index',
        ),
        '6' => array(
            'pid'   => '5',
            'title' => '新增',
            'url'   => 'User/Admin/User/add',
        ),
        '7' => array(
            'pid'   => '5',
            'title' => '编辑',
            'url'   => 'User/Admin/User/edit',
        ),
        '8' => array(
            'pid'   => '5',
            'title' => '设置状态',
            'url'   => 'User/Admin/User/setStatus',
        )
    )
);
