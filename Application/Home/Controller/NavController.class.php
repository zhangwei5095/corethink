<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
/**
 * 前台导航控制器
 * @author jry <598821125@qq.com>
 */
class NavController extends HomeController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index($name) {
        // 获取导航信息
        $where['name'] = $name;
        $nav_info = D('Admin/Nav')->where($where)->find();

        // 处理不同导航类型
        switch ($nav_info['type']) {
            case 'link':
                if (!$nav_info['url']) {
                    redirect(C('HOME_PAGE'));
                }
                if (stristr($nav_info['url'], 'http://')) {
                    redirect($nav_info['url']);
                } else {
                    $this->redirect($category_info['url']);
                }
                break;
            case 'module': //模块
                $this->redirect(ucfirst($nav_info['value']).'/index');
                break;
        }
    }
}
