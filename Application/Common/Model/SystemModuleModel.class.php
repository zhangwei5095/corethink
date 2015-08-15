<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Common\Model;
use Think\Model;
use Think\Storage;
/**
 * 功能模块模型
 * @author jry <598821125@qq.com>
 */
class SystemModuleModel extends Model{
    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('name', 'require', '模块名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '', '该模块已存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        array('title', 'require', '模块标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('description', 'require', '模块描述不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('developer', 'require', '模块开发者不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('version', 'require', '模块版本不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('admin_menu', 'require', '模块菜单节点不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('ctime', NOW_TIME, self::MODEL_INSERT),
        array('utime', NOW_TIME, self::MODEL_BOTH),
        array('sort', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_find(&$result, $options){
        //如果应用文件夹下不存在该模块对应的目录则将该模块状态记为损坏:-2
        if(!file_exists(realpath(APP_PATH.$result['name']))){
            $con['id'] = $result['id'];
            $this->where($con)->setField('status', -2);
        }
    }

    /**
     * 查找后置操作
     * @author jry <598821125@qq.com>
     */
    protected function _after_select(&$result, $options){
        foreach($result as &$record){
            $this->_after_find($record, $options);
        }
    }

    /**
     * 安装描述文件名
     * @author jry <598821125@qq.com>
     */
    public function install_file(){
        return 'corethink.php';
    }

    /**
     * 获取模块列表
     * @param string $addon_dir
     * @author jry <598821125@qq.com>
     */
    public function getAll(){
        //获取除了Common等系统模块外的用户模块（文件夹下必须有$install_file定义的安装描述文件）
        $dirs = array_map('basename', glob(APP_PATH.'*', GLOB_ONLYDIR));
        foreach($dirs as $dir){
            $config_file = realpath(APP_PATH.$dir).'/'.$this->install_file();
            if(Storage::has($config_file)){
                $module_dir_list[] = $dir;
                $temp_arr = include $config_file;
                $temp_arr['info']['status'] = -1; //未安装
                $module_list[$temp_arr['info']['name']] = $temp_arr['info'];
            }
        }

        //获取系统已经安装的模块信息
        $installed_module_list = $this->field(true)->order('sort asc,id desc')->select();
        if($installed_module_list){
            foreach($installed_module_list as &$module){
                $module_list[$module['name']] = $module;
            }
            //系统已经安装的模块信息与文件夹下模块信息合并
            $module_list = array_merge($module_list, $module_list);
        }

        foreach($module_list as &$val){
            switch($val['status']){
                case '-2': //损坏
                    $val['status'] = '<span class="text-danger">损坏</span>';
                    $val['right_button']  = '<a class="label label-danger ajax-get" href="'.U('setStatus', array('status' => 'delete', 'ids' => $val['id'])).'">删除记录</a>';
                    break;
                case '-1': //未安装
                    $val['status'] = '<i class="fa fa-download text-success"></i>';
                    $val['right_button']  = '<a class="label label-success ajax-get" href="'.U('install?name='.$val['name']).'">安装</a>';
                    break;
                case '0': //禁用
                    $val['status'] = '<i class="fa fa-ban text-danger"></i>';
                    $val['right_button'] .= '<a class="label label-info ajax-get" href="'.U('updateInfo?id='.$val['id']).'">更新菜单</a> ';
                    $val['right_button'] .= '<a class="label label-success ajax-get" href="'.U('setStatus', array('status' => 'resume', 'ids' => $val['id'])).'">启用</a> ';
                    $val['right_button'] .= '<a class="label label-danger ajax-get" href="'.U('uninstall', array('id' => $val['id'])).'">卸载</a> ';
                    break;
                case '1': //正常
                    $val['status'] = '<i class="fa fa-check text-success"></i>';
                    $val['right_button'] .= '<a class="label label-info ajax-get" href="'.U('updateInfo?id='.$val['id']).'">更新菜单</a> ';
                    $val['right_button'] .= '<a class="label label-warning ajax-get" href="'.U('setStatus', array('status' => 'forbid', 'ids' => $val['id'])).'">禁用</a> ';
                    $val['right_button'] .= '<a class="label label-danger ajax-get" href="'.U('uninstall', array('id' => $val['id'])).'">卸载</a> ';
                    break;
            }
        }
        return $module_list;
    }
}
