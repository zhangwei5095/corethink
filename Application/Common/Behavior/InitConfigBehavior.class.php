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
class InitConfigBehavior extends Behavior{
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content){
        //安装模式下直接返回
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;

        //系统主页地址配置
        $config['HOME_PAGE'] = 'http://'.$_SERVER['HTTP_HOST'].__ROOT__;

        //获取ThinkPHP控制器分级时控制器名称
        $controller_name = explode('/', CONTROLLER_NAME);

        /**
         * 前缀设置避免冲突
         * 用于将各个模块的缓存、Cookie等数据分开防止冲突
         */
         $config['DATA_CACHE_PREFIX'] = ENV_PRE.MODULE_NAME.'_'; //数据缓存前缀
        if(MODULE_NAME === 'Admin' || $controller_name[0] === 'Admin'){
            $config['SESSION_PREFIX']    = ENV_PRE.'Admin_'; //Session前缀
            $config['COOKIE_PREFIX']     = ENV_PRE.'Admin_'; //Cookie前缀
        }elseif(MODULE_NAME === 'Home' || $controller_name[0] === 'Home'){
            $config['SESSION_PREFIX']    = ENV_PRE.'Home_'; //Session前缀
            $config['COOKIE_PREFIX']     = ENV_PRE.'Home_'; //Cookie前缀
        }
        C($config); //添加配置

        //读取数据库中的配置
        $system_config = S('DB_CONFIG_DATA');
        if(!$system_config){
            //获取所有系统配置
            $system_config = D('SystemConfig')->lists();

            if(MODULE_NAME === 'Admin' || $controller_name[0] === 'Admin'){
                //模板相关配置
                $system_config['TMPL_PARSE_STRING']['__PUBLIC__'] = __ROOT__.'/Public';
                $system_config['TMPL_PARSE_STRING']['__IMG__'] = __ROOT__.'/Application/Admin/View/Public/img';
                $system_config['TMPL_PARSE_STRING']['__CSS__'] = __ROOT__.'/Application/Admin/View/Public/css';
                $system_config['TMPL_PARSE_STRING']['__JS__']  = __ROOT__.'/Application/Admin/View/Public/js';
            }elseif(MODULE_NAME === 'Home' || $controller_name[0] === 'Home'){
                /**
                 * 获取系统所有主题名称并配置THEME_LIST
                 * 根据ThinkPHP规则如果开启自动侦测模板主题功能(TMPL_DETECT_THEME)
                 * 则必须配置THEME_LIST，否则TP会调用默认主题(DEFAULT_THEME)导致主题功能失效
                 */
                $system_theme_list = D('SystemTheme')->getfield('name', true);
                $system_config['THEME_LIST'] = implode(',', $system_theme_list);

                //从系统主题数据表获取当前主题的名称
                $current_theme = D('SystemTheme')->where(array('current' => 1))->order('id asc')->getField('name');
                if(MODULE_NAME === 'Home'){
                    $system_config['DEFAULT_THEME'] = $current_theme; //默认主题设为当前主题
                    cookie('think_template', $current_theme); //默认主题设为当前主题
                }

                //模板相关配置
                $system_config['TMPL_PARSE_STRING']['__PUBLIC__'] = __ROOT__.'/Public';
                $system_config['TMPL_PARSE_STRING']['__IMG__'] = __ROOT__.'/Application/Home/View/'.$current_theme.'/Public/img';
                $system_config['TMPL_PARSE_STRING']['__CSS__'] = __ROOT__.'/Application/Home/View/'.$current_theme.'/Public/css';
                $system_config['TMPL_PARSE_STRING']['__JS__']  = __ROOT__.'/Application/Home/View/'.$current_theme.'/Public/js';
            }

            S('DB_CONFIG_DATA', $system_config, 3600); //缓存配置
        }

        C($system_config); //添加配置
    }
}
