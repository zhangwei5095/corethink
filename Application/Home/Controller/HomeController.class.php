<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Common\Controller\CommonController;
/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用模块名
 * @author jry <598821125@qq.com>
 */
class HomeController extends CommonController {
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize() {
        // 系统开关
        if (!C('TOGGLE_WEB_SITE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }

        // 获取所有模块配置的用户导航
        $mod_con['status'] = 1;
        $_user_nav_main = array();
        $_user_nav_list = D('Admin/Module')->where($mod_con)->getField('user_nav', true);
        foreach ($_user_nav_list as $key => $val) {
            if ($val) {
                $val = json_decode($val, true);
                $_user_nav_main = array_merge($_user_nav_main, $val['main']);
            }
        }

        // 监听行为扩展
        \Think\Hook::listen('corethink_behavior');

        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        $this->assign('_new_message', cookie('_new_message')); // 获取用户未读消息数量
        $this->assign('_user_auth', session('user_auth'));     // 用户登录信息
        $this->assign('_user_nav_main', $_user_nav_main);      // 用户导航信息
    }

    /**
     * 用户登录检测
     * @author jry <598821125@qq.com>
     */
    protected function is_login() {
        //用户登录检测
        $uid = is_login();
        if ($uid) {
            return $uid;
        } else {
            if (IS_AJAX) {
                $return['status']  = 0;
                $return['info']    = '请先登录系统';
                $return['login'] = 1;
                $this->ajaxReturn($return);
            } else {
                redirect(U('User/Home/User/login', null, true, true));
            }
        }
    }

    /**
     * 模板显示 调用内置的模板引擎显示方法，
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     */
    protected function display($template='',$charset='',$contentType='',$content='',$prefix='') {
        $depr     = C('TMPL_FILE_DEPR');
        $template = str_replace(':', $depr, $template);
        if (C('CURRENT_THEME')) {
            // 分析模板文件规则
            $controller_name = explode('/', CONTROLLER_NAME);
            if ('' == $template) {
                // 如果模板文件名为空 按照默认规则定位
                if (sizeof($controller_name) === 2) {
                    $template = $controller_name[1] . $depr . ACTION_NAME;
                } else {
                    $template = $controller_name[0] . $depr . ACTION_NAME;
                }
            } else if (false === strpos($template, $depr)) { // 没有/
                $template = CONTROLLER_NAME . $depr . $template;
                if (sizeof($controller_name) === 2) {
                    $template = $controller_name[1] . $depr . $template;
                } else {
                    $template = $controller_name[0] . $depr . $template;
                }
            }

            // WAP版
            if (C('IS_WAP')) {
                $file = './Theme/'.C('CURRENT_THEME').$depr.MODULE_NAME.$depr.'wap'.$depr.$template.C('TMPL_TEMPLATE_SUFFIX');
            } else {
                $file = './Theme/'.C('CURRENT_THEME').$depr.MODULE_NAME.$depr.$template.C('TMPL_TEMPLATE_SUFFIX');
            }

            if (is_file($file)) {
                $template = $file;
            }
        } else {
            // WAP版
            if (C('IS_WAP')) {
                $controller_name = explode('/', CONTROLLER_NAME);
                if (sizeof($controller_name) === 2) {
                    if ('' == $template) {
                        // 如果模板文件名为空 按照默认规则定位
                        $template = $controller_name[0] . $depr . 'Wap' . $depr . $controller_name[1] . $depr . ACTION_NAME;
                    } else if (false === strpos($template, $depr)) {
                        $template = $controller_name[0] . $depr . 'Wap' . $depr . $controller_name[1] . $depr . $template;
                    }
                } else {
                    if ('' == $template) {
                        // 如果模板文件名为空 按照默认规则定位
                        $template = 'Wap' . $depr . $controller_name[0] . $depr . ACTION_NAME;
                    } else if (false === strpos($template, $depr)) { // 没有/
                        $template = 'Wap' . $depr . $controller_name[0] . $depr . $template;
                    }
                }
            }
        }
        $this->view->display($template,$charset,$contentType,$content,$prefix);
    }
}
