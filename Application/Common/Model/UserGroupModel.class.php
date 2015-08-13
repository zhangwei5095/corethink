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
/**
 * 部门模型
 * @author jry <598821125@qq.com>
 */
class UserGroupModel extends Model{
    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '部门名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,32', '部门名称长度为1-32个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
        array('title', '', '部门名称已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('menu_auth', 'require', '权限不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('ctime', NOW_TIME, self::MODEL_INSERT),
        array('utime', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 检查部门功能权限
     * @author jry <598821125@qq.com>
     */
    public function checkMenuAuth(){
        $current_menu = D('SystemMenu')->getCurrentMenu(); //当前菜单id
        $user_group = (int)D('User')->getFieldById(session('user_auth.uid'), 'group'); //获得当前登录用户信息
        if($user_group !== 1){
            $group_info = $this->find($user_group);
            $group_auth = explode(',', $group_info['menu_auth']); //获得当前登录用户所属部门的权限列表
            if(in_array($current_menu['id'], $group_auth)){
                return true;
            }
        }else{
            return true; //超级管理员无需验证
        }
        return false;
    }

    /**
     * 检查部门分类权限
     * @author jry <598821125@qq.com>
     */
    public function checkCategoryAuth($category_id){
        $user_group = (int)D('User')->getFieldById(session('user_auth.uid'), 'group'); //获得当前登录用户信息
        if($user_group !== 1){
            $group_info = $this->find($user_group);
            $group_auth = explode(',', $group_info['category_auth']); //获得当前登录用户所属部门的分类权限列表
            if(in_array($category_id, $group_auth)){
                return true;
            }
        }else{
            return true; //超级管理员无需验证
        }
        return false;
    }
}
