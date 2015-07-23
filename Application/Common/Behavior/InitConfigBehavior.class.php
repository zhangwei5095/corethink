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

        //前缀设置避免冲突
        if(MODULE_NAME === 'Admin' || $controller_name[0] === 'Admin'){
            $config['DATA_CACHE_PREFIX'] = ENV_PRE.'Admin_'; //缓存前缀
            $config['SESSION_PREFIX']    = ENV_PRE.'Admin_'; //Session前缀
            $config['COOKIE_PREFIX']     = ENV_PRE.'Admin_'; //Cookie前缀
        }elseif(MODULE_NAME === 'Home' || $controller_name[0] === 'Home'){
            $config['DATA_CACHE_PREFIX'] = ENV_PRE.'Home_'; //缓存前缀
            $config['SESSION_PREFIX']    = ENV_PRE.'Home_'; //Session前缀
            $config['COOKIE_PREFIX']     = ENV_PRE.'Home_'; //Cookie前缀
        }
        C($config); //添加配置

        //读取数据库中的配置
        $config = S('DB_CONFIG_DATA');
        if(!$config){
            //获取所有系统配置
            $config = D('SystemConfig')->lists();

            if(MODULE_NAME === 'Admin' || $controller_name[0] === 'Admin'){
                //模板相关配置
                $config['TMPL_PARSE_STRING']['__PUBLIC__'] = __ROOT__.'/Public';
                $config['TMPL_PARSE_STRING']['__IMG__'] = __ROOT__.'/Application/Admin/View/Public/img';
                $config['TMPL_PARSE_STRING']['__CSS__'] = __ROOT__.'/Application/Admin/View/Public/css';
                $config['TMPL_PARSE_STRING']['__JS__']  = __ROOT__.'/Application/Admin/View/Public/js';
            }elseif(MODULE_NAME === 'Home' || $controller_name[0] === 'Home'){
                //模板相关配置
                $config['TMPL_PARSE_STRING']['__PUBLIC__'] = __ROOT__.'/Public';
                $config['TMPL_PARSE_STRING']['__IMG__'] = __ROOT__.'/Application/Home/View/'.$config['DEFAULT_THEME'].'/Public/img';
                $config['TMPL_PARSE_STRING']['__CSS__'] = __ROOT__.'/Application/Home/View/'.$config['DEFAULT_THEME'].'/Public/css';
                $config['TMPL_PARSE_STRING']['__JS__']  = __ROOT__.'/Application/Home/View/'.$config['DEFAULT_THEME'].'/Public/js';
            }

            S('DB_CONFIG_DATA', $config, 3600); //缓存配置
        }

        //除非是Home模块否则主题配置取消
        if(MODULE_NAME !== 'Home'){
            $config['DEFAULT_THEME'] = '';
        }
        C($config); //添加配置
    }
}
