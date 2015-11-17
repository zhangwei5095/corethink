<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Addons\XiaMiFM;
use Common\Controller\Addon;
/**
 * 虾米音乐电台
 * @author Moobusy
 */
class XiaMiFMAddon extends Addon{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'        => 'XiaMiFM',
        'title'       => '虾米音乐电台',
        'description' => '虾米音乐电台',
        'status'      => 1,
        'author'      => 'Moobusy',
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
    public function AdminIndex($param){
        $config = $this->getConfig();
        if ($config['onuse']==1) {
            $this->display('index');
        }
    }
}
