<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台公共控制器
 * @author jry <598821125@qq.com>
 */
class PublicController extends Controller{
    /**
     * 后台登陆
     * @author jry <598821125@qq.com>
     */
    public function login(){
        if(IS_POST){
            $username = I('username');
            $password = I('password');
            //图片验证码校验
            if(!$this->check_verify(I('post.verify'))){
                $this->error('验证码输入错误！');
            }
            $map['group'] = array('egt', 1); //后台部门
            $user_object = D('User');
            $uid = $user_object->login($username, $password, $map);
            if(0 < $uid){
                $this->success('登录成功！', U('Admin/Index/index'));
            }else{
                $this->error($user_object->getError());
            }
        }else{
            $this->assign('meta_title', '用户登录');
            $this->assign('__CONTROLLER_NAME__', strtolower(CONTROLLER_NAME)); //当前控制器名称
            $this->assign('__ACTION_NAME__', strtolower(ACTION_NAME)); //当前方法名称
            $this->display();
        }
    }

    /**
     * 注销
     * @author jry <598821125@qq.com>
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
        $this->success('退出成功！', U('Public/login'));
    }

    /**
     * 图片验证码生成，用于登录和注册
     * @author jry <598821125@qq.com>
     */
    public function verify($vid = 1){
        $verify = new \Think\Verify();
        $verify->length = 4;
        $verify->entry($vid);
    }
    
    /**
     * 检测验证码
     * @param  integer $id 验证码ID
     * @return boolean 检测结果
     */
    function check_verify($code, $vid = 1){
        $verify = new \Think\Verify();
        return $verify->check($code, $vid);
    }
}
