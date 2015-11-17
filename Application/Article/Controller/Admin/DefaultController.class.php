<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Article\Controller\Admin;
use Admin\Controller\AdminController;
/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class DefaultController extends AdminController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
        $this->assign('meta_title', '默认方法');
        $this->display();
    }
}