<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
/**
 * 前台默认控制器
 * @author jry <598821125@qq.com>
 */
class IndexController extends HomeController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
        Cookie('__forward__', C('HOME_PAGE'));
        $this->assign('meta_title', "首页");
        $this->display();
    }

    /**
     * 单页类型
     * @author jry <598821125@qq.com>
     */
    public function page($id) {
        $nav_object = D('Admin/Nav');
        $con['id']     = $id;
        $con['status'] = 1;
        $info = $nav_object->where($con)->find();

        Cookie('__forward__', C('HOME_PAGE'));
        $this->assign('info', $info);
        $this->assign('meta_title', $info['title']);
        $this->display();
    }
}
