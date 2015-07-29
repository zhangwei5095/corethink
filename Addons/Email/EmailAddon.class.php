<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Addons\Email;
use Common\Controller\Addon;
/**
 * 邮件插件
 * @author jry <598821125@qq.com>
 */
class EmailAddon extends Addon{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name' => 'Email',
        'title' => '邮件插件',
        'description' => '实现系统发邮件功能',
        'status' => 1,
        'author' => 'CoreThink',
        'version' => '1.0'
    );

    /**
     * 插件安装方法
     * @author jry <598821125@qq.com>
     */
    public function install(){
        return true;
    }

    /**
     * 插件卸载方法
     * @author jry <598821125@qq.com>
     */
    public function uninstall(){
        return true;
    }
}
