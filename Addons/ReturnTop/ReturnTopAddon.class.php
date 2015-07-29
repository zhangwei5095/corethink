<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Addons\ReturnTop;
use Common\Controller\Addon;
/**
 * 返回顶部插件
 * @jry <598821125@qq.com>
 */
class ReturnTopAddon extends Addon{
    /**
     * 插件信息
     * @author jry <598821125@qq.com>
     */
    public $info = array(
        'name'=>'ReturnTop',
        'title'=>'返回顶部',
        'description'=>'返回顶部',
        'status'=>1,
        'author'=>'CoreThink',
        'version'=>'1.0'
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

    //实现的PageFooter钩子方法
    public function PageFooter($param){
        $addons_config = $this->getConfig();
        if($addons_config['status']){
            $this->assign('addons_config', $addons_config);
            $this->display($addons_config['theme']);
        }
    }
}
