<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Controller\CommonController;
/**
 * 后台公共控制器
 * 为什么要继承AdminController？
 * 因为AdminController的初始化函数中读取了顶部导航栏和左侧的菜单，
 * 如果不继承的话，只能复制AdminController中的代码来读取导航栏和左侧的菜单。
 * 这样做会导致一个问题就是当AdminController被官方修改后AdminController不会同步更新，从而导致错误。
 * 所以综合考虑还是继承比较好。
 * @author jry <598821125@qq.com>
 */
class AdminController extends CommonController{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize(){
        //登录检测
        if(!is_login()){ //还没登录跳转到登录页面
            $this->redirect('Admin/Public/login');
        }

        //权限检测
        if(!D('UserGroup')->checkMenuAuth()){
            $this->error('权限不足！');
        }

        //获取系统菜单导航
        $map['status'] = array('eq', 1);
        if(!C('DEVELOP_MODE')){ //是否开启开发者模式
            $map['dev'] = array('neq', 1);
        }
        $tree = new \Common\Util\Tree();
        $all_admin_menu_list = $tree->list_to_tree(D('SystemMenu')->where($map)->select()); //所有系统菜单

        //设置数组key为菜单ID
        foreach($all_admin_menu_list as $key => $val){
            $all_menu_list[$val['id']] = $val;
        }

        //获取功能模块的后台菜单列表
        $moule_list = D('SystemModule')->where(array('status' => 1))->select(); //获取所有安装并启用的功能模块
        $all_module_menu_list = array();
        foreach($moule_list as $key => $val){
            $menu_list_item = $tree->list_to_tree(json_decode($val['admin_menu'], true));
            $all_module_menu_list[] = $menu_list_item[0];
        }

        //设置数组key为菜单ID
        foreach($all_module_menu_list as &$menu){
            $new_all_module_menu_list[$menu['id']] = $menu;
        }

        //合并系统核心菜单与功能模块菜单
        if($new_all_module_menu_list){
            $all_menu_list += $new_all_module_menu_list;
        }

        $current_menu = D('SystemMenu')->getCurrentMenu(); //当前菜单
        if($current_menu){
            $parent_menu = D('SystemMenu')->getParentMenu($current_menu); //获取面包屑导航
            foreach($parent_menu as $key => $val){
                $parent_menu_id[] = $val['id'];
            }
            $side_menu_list = $all_menu_list[$parent_menu[0]['id']]['_child']; //左侧菜单
        }

        $this->assign('__ALL_MENU_LIST__', $all_menu_list); //所有菜单
        $this->assign('__SIDE_MENU_LIST__', $side_menu_list); //左侧菜单
        $this->assign('__PARENT_MENU__', $parent_menu); //当前菜单的所有父级菜单
        $this->assign('__PARENT_MENU_ID__', $parent_menu_id); //当前菜单的所有父级菜单的ID
        $this->assign('__CURRENT_ROOTMENU__', $parent_menu[0]['id']); //当前主菜单
        $this->assign('__USER__', session('user_auth')); //用户登录信息
        $this->assign('__CONTROLLER_NAME__', strtolower(CONTROLLER_NAME)); //当前控制器名称
        $this->assign('__ACTION_NAME__', strtolower(ACTION_NAME)); //当前方法名称
    }
}
