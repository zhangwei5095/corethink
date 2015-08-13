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

        //数据缓存前缀
        C('DATA_CACHE_PREFIX', ENV_PRE.MODULE_NAME.'_');

        //读取数据库中的配置
        $system_config = S('DB_CONFIG_DATA');
        if(!$system_config){
            //获取所有系统配置
            $system_config = D('SystemConfig')->lists();

            //SESSIONCOOKIE与前缀设置避免冲突
            //不直接在config里配置而要在这里配置是为了支持功能模块的相关架构
            $controller_name = explode('/', CONTROLLER_NAME); //获取ThinkPHP控制器分级时控制器名称
            if(MODULE_NAME === 'Admin' || $controller_name[0] === 'Admin'){
                $config['SESSION_PREFIX']    = ENV_PRE.'Admin_'; //Session前缀
                $config['COOKIE_PREFIX']     = ENV_PRE.'Admin_'; //Cookie前缀
            }elseif(MODULE_NAME === 'Home' || $controller_name[0] === 'Home'){
                $config['SESSION_PREFIX']    = ENV_PRE.'Home_'; //Session前缀
                $config['COOKIE_PREFIX']     = ENV_PRE.'Home_'; //Cookie前缀
            }

            //从系统主题数据表获取当前主题的名称
            $current_theme = D('SystemTheme')->where(array('current' => 1))->order('id asc')->getField('name');
            if(MODULE_NAME === 'Home' || $controller_name[0] === 'Home'){
                $current_theme_path = APP_PATH.MODULE_NAME.'/View/'.$current_theme; //当前主题文件夹路径
                if(is_dir($current_theme_path)){
                    //默认主题设为当前主题
                    $system_config['DEFAULT_THEME'] = $current_theme;
                    $system_config['TMPL_PARSE_STRING']['__HOME_IMG__']  = __ROOT__.'/'.APP_PATH.'Home/View/'.$current_theme.'/_Resource/img';
                    $system_config['TMPL_PARSE_STRING']['__HOME_CSS__']  = __ROOT__.'/'.APP_PATH.'Home/View/'.$current_theme.'/_Resource/css';
                    $system_config['TMPL_PARSE_STRING']['__HOME_JS__']   = __ROOT__.'/'.APP_PATH.'Home/View/'.$current_theme.'/_Resource/js';
                }else{
                    $system_config['DEFAULT_THEME'] = 'default'; //如果当前主题文件夹不存在则设为default
                }
            }

            S('DB_CONFIG_DATA', $system_config, 3600); //缓存配置
        }

        C($system_config); //添加配置
    }
}
