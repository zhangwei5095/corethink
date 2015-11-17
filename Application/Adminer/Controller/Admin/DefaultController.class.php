<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Adminer\Controller\Admin;
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
        $this->assign('meta_title', '数据库模块');
        echo '<iframe style="width:100%;height:100%;border:0;" '
            .' src="'.__ROOT__.'/'.APP_PATH.'/'.MODULE_NAME
            .'/adminer-4.2.2.php'
            .'?server='.C('adminer_config.host')
            .'&db='.C('adminer_config.db')
            .'&username='.C('adminer_config.username')
            .'">';
    }
}