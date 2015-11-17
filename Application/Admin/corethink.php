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
        'name'        => 'Admin',
        'title'       => '系统',
        'icon'        => 'fa fa-cog',
        'icon_color'  => '#3CA6F1',
        'description' => 'CoreThink核心系统',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.corethink.cn',
        'version'     => '1.1.0',
        'beta'        => '3.0',
    ),

    // 后台菜单及权限节点配置
    'admin_menu' => array(
        '1' => array(
            'pid'   => '0',
            'title' => '系统',
            'icon'  => 'fa fa-cog',
            'level' => 'system',
        ),
        '2' => array(
            'pid'   => '1',
            'title' => '系统功能',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3' => array(
            'pid'   => '2',
            'title' => '系统设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'Admin/Config/group',
        ),
        '4' => array(
            'pid'   => '3',
            'title' => '修改设置',
            'url'   => 'Admin/Config/groupSave',
        ),
        '5' => array(
            'pid'   => '2',
            'title' => '导航管理',
            'icon'  => 'fa fa-map-signs',
            'url'   => 'Admin/Nav/index',
        ),
        '6' => array(
            'pid'   => '5',
            'title' => '新增',
            'url'   => 'Admin/Nav/add',
        ),
        '7' => array(
            'pid'   => '5',
            'title' => '编辑',
            'url'   => 'Admin/Nav/edit',
        ),
        '8' => array(
            'pid'   => '5',
            'title' => '设置状态',
            'url'   => 'Admin/Nav/setStatus',
        ),
        '9' => array(
            'pid'   => '2',
            'title' => '快捷链接',
            'icon'  => 'fa fa-link',
            'url'   => 'Admin/Link/index',
        ),
        '10' => array(
            'pid'   => '9',
            'title' => '新增',
            'url'   => 'Admin/Link/add',
        ),
        '11' => array(
            'pid'   => '9',
            'title' => '编辑',
            'url'   => 'Admin/Link/edit',
        ),
        '12' => array(
            'pid'   => '9',
            'title' => '设置状态',
            'url'   => 'Admin/Link/setStatus',
        ),
        '13' => array(
            'pid'   => '2',
            'title' => '配置管理',
            'icon'  => 'fa fa-cogs',
            'url'   => 'Admin/Config/index',
        ),
        '14' => array(
            'pid'   => '9',
            'title' => '新增',
            'url'   => 'Admin/Config/add',
        ),
        '15' => array(
            'pid'   => '9',
            'title' => '编辑',
            'url'   => 'Admin/Config/edit',
        ),
        '16' => array(
            'pid'   => '9',
            'title' => '设置状态',
            'url'   => 'Admin/Config/setStatus',
        ),
        '17' => array(
            'pid'   => '2',
            'title' => '上传管理',
            'icon'  => 'fa fa-upload',
            'url'   => 'Admin/Upload/index',
        ),
        '18' => array(
            'pid'   => '9',
            'title' => '上传文件',
            'url'   => 'Admin/Upload/upload',
        ),
        '19' => array(
            'pid'   => '9',
            'title' => '删除文件',
            'url'   => 'Admin/Upload/delete',
        ),
        '20' => array(
            'pid'   => '9',
            'title' => '设置状态',
            'url'   => 'Admin/Upload/setStatus',
        ),
        '21' => array(
            'pid'   => '9',
            'title' => '下载远程图片',
            'url'   => 'Admin/Upload/downremoteimg',
        ),
        '22' => array(
            'pid'   => '9',
            'title' => '文件浏览',
            'url'   => 'Admin/Upload/fileManager',
        ),
        '23' => array(
            'pid'   => '1',
            'title' => '账号管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '24' => array(
            'pid'   => '23',
            'title' => '用户管理',
            'icon'  => 'fa fa-users',
            'url'   => 'Admin/User/index',
        ),
        '25' => array(
            'pid'   => '24',
            'title' => '新增',
            'url'   => 'Admin/User/add',
        ),
        '26' => array(
            'pid'   => '24',
            'title' => '编辑',
            'url'   => 'Admin/User/edit',
        ),
        '27' => array(
            'pid'   => '24',
            'title' => '设置状态',
            'url'   => 'Admin/User/setStatus',
        ),
        '28' => array(
            'pid'   => '23',
            'title' => '用户组管理',
            'icon'  => 'fa fa-sitemap',
            'url'   => 'Admin/Group/index',
        ),
        '29' => array(
            'pid'   => '28',
            'title' => '新增',
            'url'   => 'Admin/Group/add',
        ),
        '30' => array(
            'pid'   => '28',
            'title' => '编辑',
            'url'   => 'Admin/Group/edit',
        ),
        '31' => array(
            'pid'   => '28',
            'title' => '设置状态',
            'url'   => 'Admin/Group/setStatus',
        ),
        '32' => array(
            'pid'   => '1',
            'title' => '扩展中心',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '33' => array(
            'pid'   => '32',
            'title' => '功能模块',
            'icon'  => 'fa fa-th-large',
            'url'   => 'Admin/Module/index',
        ),
        '34' => array(
            'pid'   => '33',
            'title' => '安装',
            'url'   => 'Admin/Module/install',
        ),
        '35' => array(
            'pid'   => '33',
            'title' => '卸载',
            'url'   => 'Admin/Module/uninstall',
        ),
        '36' => array(
            'pid'   => '33',
            'title' => '更新信息',
            'url'   => 'Admin/Module/updateInfo',
        ),
        '37' => array(
            'pid'   => '33',
            'title' => '设置状态',
            'url'   => 'Admin/Module/setStatus',
        ),
        '38' => array(
            'pid'   => '32',
            'title' => '前台主题',
            'icon'  => 'fa fa-adjust',
            'url'   => 'Admin/Theme/index',
        ),
        '39' => array(
            'pid'   => '38',
            'title' => '安装',
            'url'   => 'Admin/Theme/install',
        ),
        '40' => array(
            'pid'   => '38',
            'title' => '卸载',
            'url'   => 'Admin/Theme/uninstall',
        ),
        '41' => array(
            'pid'   => '38',
            'title' => '更新信息',
            'url'   => 'Admin/Theme/updateInfo',
        ),
        '42' => array(
            'pid'   => '38',
            'title' => '设置状态',
            'url'   => 'Admin/Theme/setStatus',
        ),
        '43' => array(
            'pid'   => '38',
            'title' => '切换主题',
            'url'   => 'Admin/Theme/setCurrent',
        ),
        '44' => array(
            'pid'   => '32',
            'title' => '插件管理',
            'icon'  => 'fa fa-th',
            'url'   => 'Admin/Addon/index',
        ),
        '45' => array(
            'pid'   => '44',
            'title' => '安装',
            'url'   => 'Admin/Addon/install',
        ),
        '46' => array(
            'pid'   => '44',
            'title' => '卸载',
            'url'   => 'Admin/Addon/uninstall',
        ),
        '47' => array(
            'pid'   => '44',
            'title' => '运行',
            'url'   => 'Admin/Addon/execute',
        ),
        '48' => array(
            'pid'   => '44',
            'title' => '设置',
            'url'   => 'Admin/Addon/config',
        ),
        '49' => array(
            'pid'   => '44',
            'title' => '后台管理',
            'url'   => 'Admin/Addon/adminList',
        ),
        '50' => array(
            'pid'   => '49',
            'title' => '新增数据',
            'url'   => 'Admin/Addon/adminAdd',
        ),
        '51' => array(
            'pid'   => '49',
            'title' => '编辑数据',
            'url'   => 'Admin/Addon/adminEdit',
        ),
        '52' => array(
            'pid'   => '49',
            'title' => '设置状态',
            'url'   => 'Admin/Addon/setStatus',
        )
    )
);
