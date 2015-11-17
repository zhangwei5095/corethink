<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Common\Behavior;
use Think\Behavior;
defined('THINK_PATH') or exit();
/**
 * 根据不同情况读取数据库的配置信息并与本地配置合并
 * 本行为扩展很重要会影响核心系统前后台、模块功能及模版主题使用
 * 如非必要或者并不是十分了解系统架构不推荐更改
 * @author jry <598821125@qq.com>
 */
class InitConfigBehavior extends Behavior {
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content) {
        // 安装模式下直接返回
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;

        // 数据缓存前缀
        // 获取ThinkPHP控制器分级时控制器名称
        $controller_name = explode('/', CONTROLLER_NAME);
        if (sizeof($controller_name) === 2) {
            C('DATA_CACHE_PREFIX', ENV_PRE.MODULE_NAME.'_'.$controller_name[0].'_');
        } else {
            C('DATA_CACHE_PREFIX', ENV_PRE.MODULE_NAME.'_');
        }

        // 读取数据库中的配置
        $system_config = S('DB_CONFIG_DATA');
        if (!$system_config) {
            // 获取所有系统配置
            $system_config = D('Admin/Config')->lists();

            // 获取所有安装的模块配置
            $module_list = D('Admin/Module')->where(array('status' => '1'))->select();
            foreach ($module_list as $val) {
                $module_config[strtolower($val['name'].'_config')] = json_decode($val['config'], true);
                $module_config[strtolower($val['name'].'_config')]['module_name'] = $val['name'];
            }
            if ($module_config) {
                // 合并模块配置
                $system_config = array_merge($system_config, $module_config);

                // 加载模块标签库及行为扩展
                $system_config['TAGLIB_PRE_LOAD'] = explode(',', C('TAGLIB_PRE_LOAD'));  // 先取出配置文件中定义的否则会被覆盖
                foreach ($module_config as $key => $val) {
                    // 加载模块标签库
                    if ($val['taglib']) {
                        foreach ($val['taglib'] as $tag) {
                            $system_config['TAGLIB_PRE_LOAD'][] = $val['module_name'].'\\TagLib\\'.$tag.'';
                        }
                    }

                    // 加载模块行为扩展
                    if ($val['behavior']) {
                        foreach ($val['behavior'] as $bhv) {
                            \Think\Hook::add('corethink_behavior', $val['module_name'].'\\Behavior\\'.$bhv.'Behavior');
                        }
                    }
                }
                $system_config['TAGLIB_PRE_LOAD'] = implode(',', $system_config['TAGLIB_PRE_LOAD']);
            }

            // 获取当前主题的名称
            $current_theme = D('Admin/Theme')->where(array('current' => 1))->order('id asc')->find();

            // 当前模块模版参数配置
            $system_config['TMPL_PARSE_STRING'] = C('TMPL_PARSE_STRING');  // 先取出配置文件中定义的否则会被覆盖
            $system_config['TMPL_PARSE_STRING']['__IMG__']  = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/Public/img';
            $system_config['TMPL_PARSE_STRING']['__CSS__']  = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/Public/css';
            $system_config['TMPL_PARSE_STRING']['__JS__']   = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/Public/js';
            $system_config['TMPL_PARSE_STRING']['__LIBS__'] = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/Public/libs';
            if ($current_theme) {
                $current_theme['module'] = explode(',', $current_theme['module']);
                $current_theme['module'][] = 'Home';  // 强制支持主题对Home的支持

                // 一旦开启主题那么前台必须启用主题相关模版(后台无需支持主题)
                $theme_public_path = './Theme/'.$current_theme['name'].'/Home/Public';
                if (is_dir($theme_public_path)) {
                    $system_config['TMPL_PARSE_STRING']['__HOME_IMG__']   = __ROOT__.'/'.$theme_public_path.'/img';
                    $system_config['TMPL_PARSE_STRING']['__HOME_CSS__']   = __ROOT__.'/'.$theme_public_path.'/css';
                    $system_config['TMPL_PARSE_STRING']['__HOME_JS__']    = __ROOT__.'/'.$theme_public_path.'/js';
                    $system_config['TMPL_PARSE_STRING']['__HOME_LIBS__']  = __ROOT__.'/'.$theme_public_path.'/libs';
                }

                // 如果启用的主题配置里勾选了当前模块那么当前模块启用主题相关模版否则仍启用系统模版
                if (in_array(MODULE_NAME, $current_theme['module'])) {
                    $system_config['CURRENT_THEME'] = $current_theme['name'];  // 设置当前主题
                    $theme_public_path = './Theme/'.$current_theme['name'].'/'.MODULE_NAME.'/Public';
                    $theme_wap_path    = './Theme/'.$current_theme['name'].'/'.MODULE_NAME.'/wap';
                    if (is_dir($theme_public_path)) {
                        $system_config['TMPL_PARSE_STRING']['__IMG__']  = __ROOT__.'/'.$theme_public_path.'/img';
                        $system_config['TMPL_PARSE_STRING']['__CSS__']  = __ROOT__.'/'.$theme_public_path.'/css';
                        $system_config['TMPL_PARSE_STRING']['__JS__']   = __ROOT__.'/'.$theme_public_path.'/js';
                        $system_config['TMPL_PARSE_STRING']['__LIBS__'] = __ROOT__.'/'.$theme_public_path.'/libs';
                    }
                    // 判断是否含有wap专用模版
                    if (is_dir($theme_wap_path)) {
                        $system_config['HAS_WAP'] = 'wap';
                    }
                }
            } else {
                // 判断是否含有wap专用模版
                if (sizeof($controller_name) === 2) {
                    if (is_dir(APP_PATH.MODULE_NAME.'/View/Home/wap')) {
                        $system_config['HAS_WAP'] = 'wap';
                    }
                } else {
                
                    if (is_dir(APP_PATH.MODULE_NAME.'/View/wap')) {
                        $system_config['HAS_WAP'] = 'wap';
                    }
                }
            }

            // 不直接在config里配置这些参数而要在这里配置是为了支持功能模块的相关架构
            if (MODULE_NAME === 'Admin' || $controller_name[0] === 'Admin') {
                // Admin后台与模块后台标记
                $system_config['MODULE_MARK'] = 'Admin';

                // SESSION与COOKIE与前缀设置避免冲突
                $system_config['SESSION_PREFIX'] = ENV_PRE.'Admin_';  // Session前缀
                $system_config['COOKIE_PREFIX']  = ENV_PRE.'Admin_';  // Cookie前缀

                // 错误页面模板
                $system_config['TMPL_ACTION_ERROR']   = APP_PATH.'Admin/View/Public/think/error.html';      // 错误跳转对应的模板文件
                $system_config['TMPL_ACTION_SUCCESS'] = APP_PATH.'Admin/View/Public/think/success.html';    // 成功跳转对应的模板文件
                $system_config['TMPL_EXCEPTION_FILE'] = APP_PATH.'Admin/View/Public/think/exception.html';  // 异常页面的模板文件
            } else if (MODULE_NAME === 'Home' || $controller_name[0] === 'Home') {
                // Home前台与模块前台标记
                $system_config['MODULE_MARK'] = 'Home';

                // SESSION与COOKIE与前缀设置避免冲突
                $system_config['SESSION_PREFIX'] = ENV_PRE.'Home_';  // Session前缀
                $system_config['COOKIE_PREFIX']  = ENV_PRE.'Home_';  // Cookie前缀

                // 错误页面模板
                $system_config['TMPL_ACTION_ERROR']   = APP_PATH.'Home/View/Public/think/error.html';      // 错误跳转对应的模板文件
                $system_config['TMPL_ACTION_SUCCESS'] = APP_PATH.'Home/View/Public/think/success.html';    // 成功跳转对应的模板文件
                $system_config['TMPL_EXCEPTION_FILE'] = APP_PATH.'Home/View/Public/think/exception.html';  // 异常页面的模板文件
            }

            S('DB_CONFIG_DATA', $system_config, 3600);  // 缓存配置
        }

        C($system_config);  // 添加配置

        // 如果存在wap专用模版同时又确实是wap方式访问则设置WAP标记
        if (C('HAS_WAP') && is_wap()) {
            C('IS_WAP', C('HAS_WAP'));
        }

        // 检测系统授权
        if ($system_config['MODULE_MARK'] === 'Home' && (C('CT_USERNAME') !== 'trial')) {
            $sn_decode = \Think\Crypt::decrypt(C('CT_SN'), md5(sha1(C('CT_USERNAME'))));
            if ($sn_decode) {
                $sn_decode = explode('[ct]', base64_decode($sn_decode));
                $sn_decode_server = json_decode($sn_decode[0], true);
                if (!in_array($_SERVER['SERVER_NAME'], $sn_decode_server)) {
                    die('很抱歉，该域名未授权！请联系：<a href="http://www.corethink.cn">http://www.corethink.cn</a>');
                }
                if (md5(sha1(C('CT_USERNAME'))) !== $sn_decode[1]) {
                    die('很抱歉，该帐号未授权！请联系：<a href="http://www.corethink.cn">http://www.corethink.cn</a>');
                }
            } else {
                die('很抱歉，您的授权已过期！请联系：<a href="http://www.corethink.cn">http://www.corethink.cn</a>');
            }
        }
    }
}
