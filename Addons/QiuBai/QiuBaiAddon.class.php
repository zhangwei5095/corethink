<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Addons\QiuBai;
use Common\Controller\Addon;
/**
 * 系统环境信息插件
 * @author thinkphp
 */
class QiuBaiAddon extends Addon {
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name' => 'QiuBai',
        'title' => '糗事百科',
        'description' => '读别人的糗事，娱乐自己',
        'status' => 1,
        'author' => 'CoreThink',
        'version'     => '1.1.0',
        'beta'        => '3.0',
    );

    /**
     * 插件所需钩子
     * @author jry <598821125@qq.com>
     */
    public $hooks = array(
        '0' => 'AdminIndex',
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

    /**
     * 实现的AdminIndex钩子方法
     * @author jry <598821125@qq.com>
     */
    public function AdminIndex($param) {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if ($config['display']) {
            $this->display('widget');
        }
    }
}
