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
        $controller_name = explode('/', CONTROLLER_NAME); //获取ThinkPHP控制器分级时控制器名称
        if(sizeof($controller_name) === 2){
            C('DATA_CACHE_PREFIX', ENV_PRE.MODULE_NAME.'_'.$controller_name[0].'_');
        }else{
            C('DATA_CACHE_PREFIX', ENV_PRE.MODULE_NAME.'_');
        }

        //读取数据库中的配置
        $system_config = S('DB_CONFIG_DATA');
        if(!$system_config){
            //获取所有系统配置
            $system_config = D('SystemConfig')->lists();

            //不直接在config里配置这些参数而要在这里配置是为了支持功能模块的相关架构
            if(MODULE_NAME === 'Admin' || $controller_name[0] === 'Admin'){
                //Admin后台与模块后台标记
                $system_config['MODULE_MARK'] = 'Admin';

                //SESSION与COOKIE与前缀设置避免冲突
                $system_config['SESSION_PREFIX'] = ENV_PRE.'Admin_'; //Session前缀
                $system_config['COOKIE_PREFIX']  = ENV_PRE.'Admin_'; //Cookie前缀

                //当前模块模版参数配置
                $system_config['TMPL_PARSE_STRING'] = C('TMPL_PARSE_STRING'); //先取出配置文件中定义的否则会被覆盖
                $system_config['TMPL_PARSE_STRING']['__IMG__']  = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/_Resource/img';
                $system_config['TMPL_PARSE_STRING']['__CSS__']  = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/_Resource/css';
                $system_config['TMPL_PARSE_STRING']['__JS__']   = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/_Resource/js';
                $system_config['TMPL_PARSE_STRING']['__LIBS__'] = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/_Resource/libs';

                //错误页面模板
                $system_config['TMPL_ACTION_ERROR']   = APP_PATH.'Admin/View/_Think/error.html'; //错误跳转对应的模板文件
                $system_config['TMPL_ACTION_SUCCESS'] = APP_PATH.'Admin/View/_Think/success.html'; //成功跳转对应的模板文件
                $system_config['TMPL_EXCEPTION_FILE'] = APP_PATH.'Admin/View/_Think/exception.html'; //异常页面的模板文件
            }elseif(MODULE_NAME === 'Home' || $controller_name[0] === 'Home'){
                //Home前台与模块前台标记
                $system_config['MODULE_MARK'] = 'Home';

                //SESSION与COOKIE与前缀设置避免冲突
                $system_config['SESSION_PREFIX'] = ENV_PRE.'Home_'; //Session前缀
                $system_config['COOKIE_PREFIX']  = ENV_PRE.'Home_'; //Cookie前缀

                //获取当前主题的名称
                $current_theme = D('SystemTheme')->where(array('current' => 1))->order('id asc')->getField('name');
                $current_theme_path = APP_PATH.MODULE_NAME.'/View/'.$current_theme; //当前主题文件夹路径
                if(!is_dir($current_theme_path)){
                    $current_theme = 'default';
                }

                //公共模版参数配置
                $system_config['TMPL_PARSE_STRING'] = C('TMPL_PARSE_STRING'); //先取出配置文件中定义的否则会被覆盖
                $system_config['TMPL_PARSE_STRING']['__HOME_IMG__']  = __ROOT__.'/'.APP_PATH.'Home/View/'.$current_theme.'/_Resource/img';
                $system_config['TMPL_PARSE_STRING']['__HOME_CSS__']  = __ROOT__.'/'.APP_PATH.'Home/View/'.$current_theme.'/_Resource/css';
                $system_config['TMPL_PARSE_STRING']['__HOME_JS__']   = __ROOT__.'/'.APP_PATH.'Home/View/'.$current_theme.'/_Resource/js';
                $system_config['TMPL_PARSE_STRING']['__HOME_LIBS__'] = __ROOT__.'/'.APP_PATH.'Home/View/'.$current_theme.'/_Resource/libs';

                //错误页面模板
                $system_config['TMPL_ACTION_ERROR']   = APP_PATH.'Home/View/'.$current_theme.'/_Think/error.html'; //默认错误跳转对应的模板文件
                $system_config['TMPL_ACTION_SUCCESS'] = APP_PATH.'Home/View/'.$current_theme.'/_Think/success.html'; //默认成功跳转对应的模板文件
                $system_config['TMPL_EXCEPTION_FILE'] = APP_PATH.'Home/View/'.$current_theme.'/_Think/exception.html'; //异常页面的模板文件

                //模块功能主题路径特殊处理(需要加上控制器分级的名称)
                if($controller_name[0] === 'Home'){
                    $current_theme = 'Home/'.$current_theme;
                }
                $system_config['DEFAULT_THEME'] = $current_theme; //默认主题设为当前主题

                //当前模块模版参数配置
                $system_config['TMPL_PARSE_STRING']['__IMG__']  = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/'.$current_theme.'/_Resource/img';
                $system_config['TMPL_PARSE_STRING']['__CSS__']  = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/'.$current_theme.'/_Resource/css';
                $system_config['TMPL_PARSE_STRING']['__JS__']   = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/'.$current_theme.'/_Resource/js';
                $system_config['TMPL_PARSE_STRING']['__LIBS__'] = __ROOT__.'/'.APP_PATH.MODULE_NAME.'/View/'.$current_theme.'/_Resource/libs';
            }

            S('DB_CONFIG_DATA', $system_config, 3600); //缓存配置
        }

        C($system_config); //添加配置
    }
}
