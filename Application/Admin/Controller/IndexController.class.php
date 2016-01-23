<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Util\Tree;
/**
 * 后台默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends AdminController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index(){
        // 获取所有模块信息及后台菜单
        $con['status'] = 1;
        $system_module_list = D('Module')->where($con)->order('sort asc, id asc')->select();
        $tree = new tree();
        $menu_list = array();
        foreach ($system_module_list as $key => &$module) {
            $temp = $tree->list_to_tree(json_decode($module['admin_menu'], true));
            $menu_list[$module['name']] = $temp[0];
            $menu_list[$module['name']]['id']   = $module['id'];
            $menu_list[$module['name']]['name'] = $module['name'];
        }

        // 如果模块顶级菜单配置了top字段则移动菜单至top所指的模块下边
        foreach ($menu_list as $key => &$value) {
            if ($value['top']) {
                if ($menu_list[$value['top']]) {
                    $menu_list[$value['top']]['_child'] = array_merge(
                        $menu_list[$value['top']]['_child'],
                        $value['_child']
                    );
                    unset($menu_list[$key]);
                }
            }
        }

        // 获取快捷链接
        $con = array();
        $con['status'] = 1;
        $link_list = D('Link')->where($con)->order('sort asc, id asc')->select();
        foreach ($link_list as $key => &$value) {
            if (!stristr($value['url'], 'http://') && !stristr($value['url'], 'https://')) {
                $value['url'] = U($value['url']);
            }
        }
        $link_list = $tree->list_to_tree($link_list);

        // 模板变量赋值
        $this->assign('_link_list', $link_list);  // 后台快捷链接
        $this->assign('_menu_list', $menu_list);  // 后台左侧菜单
        $this->assign('meta_title', "首页");
        $this->display();
    }

    /**
     * 删除缓存
     * @author jry <598821125@qq.com>
     */
    public function removeRuntime() {
        $file = new \Common\Util\File();
        $result = $file->del_dir(RUNTIME_PATH);
        if ($result) {
            $this->success("缓存清理成功");
        } else {
            $this->error("缓存清理失败");
        }
    }
}
