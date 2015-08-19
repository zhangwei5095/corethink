<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
/**
 * 用户控制器
 * @author jry <598821125@qq.com>
 */
class UserController extends HomeController{
    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function index(){
        $usertype = I('get.usertype');
        if($usertype){
            $map['usertype'] = $usertype;
        }
        $user_list = D('User')->page(!empty($_GET["p"])?$_GET["p"]:1, 24)
                              ->where($map)->order('id desc')->select();
        $page = new \Common\Util\Page(D('User')->where($map)->count(), 24);
        $this->assign('page', $page->show());
        $this->assign('meta_title', '会员');
        $this->assign('user_list', $user_list);
        $this->display();
    }

    /**
     * 用户个人主页
     * @author jry <598821125@qq.com>
     */
    public function home(){
        $uid = I('get.uid');
        if(!$uid){
            $uid  = $this->is_login();
        }
        $user_info = D('User')->where($con)->find($uid);
        if($user_info['status'] !== '1'){
            $this->error('该用户不存在或已禁用');
        }
        $this->assign('meta_title', $user_info['username'].'的主页');
        $this->assign('user_info', $user_info);
        $this->display();
    }

    /**
     * 用户个人中心
     * @author jry <598821125@qq.com>
     */
    public function center(){
        $uid  = $this->is_login();
        $this->assign('meta_title', '个人中心');
        $this->display();
    }

    /**
     * 用户修改信息
     * @author jry <598821125@qq.com>
     */
    public function profile(){
        if(IS_POST){
            $user_object = D('User');
            $_POST['id'] = $this->is_login();
            $result = $user_object->update($_POST);
            if($result){
                $this->success('信息修改成功');
            }else{
                $this->error($user_object->getError());
            }
        }else{
            $user_object = D('User');
            $user_info = $user_object->find($this->is_login());

            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('修改'.$user_info['username'].'的信息')  //设置页面标题
                    ->setPostUrl(U('')) //设置表单提交地址
                    ->addFormItem('username', 'text', '用户名', '')
                    ->addFormItem('avatar', 'picture', '头像', '')
                    ->addFormItem('sex', 'radio', '性别', '', $user_object->user_sex())
                    ->addFormItem('age', 'num', '年龄', '')
                    ->addFormItem('birthday', 'date', '生日', '生日')
                    ->addFormItem('summary', 'text', '签名', '一句话介绍')
                    ->setFormData($user_info)
                    ->setTemplate('_Builder/formbuilder_user')
                    ->display();
        }
    }

    /**
     * 登陆
     * @author jry <598821125@qq.com>
     */
    public function login(){
        if(IS_POST){
            $username = I('username');
            $password = I('password');
            if(!$username){
                $this->error('请输入账号！');
            }
            if(!$password){
                $this->error('请输入密码！');
            }
            $user_object = D('User');
            $uid = $user_object->login($username, $password);
            if(0 < $uid){
                $this->success('登录成功！', Cookie('__forward__') ? : C('HOME_PAGE'));
            }else{
                $this->error($user_object->getError());
            }
        }else{
            if(is_login()){
                $this->error("您已登陆系统", Cookie('__forward__') ? : C('HOME_PAGE'));
            }
            $this->meta_title = '用户登录';
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
        $this->success('退出成功！', Cookie('__forward__') ? : C('HOME_PAGE'));
    }

    /**
     * 用户注册
     * @author jry <598821125@qq.com>
     */
    public function register(){
        if(IS_POST){
            if(!C('TOGGLE_USER_REGISTER')){
                $this->error('注册已关闭！');
            }
            $reg_type = I('post.reg_type');
            if(!in_array($reg_type, C('ALLOW_REG_TYPE'))){
                $this->error('该注册方式已关闭，请选择其它方式注册！');
            }
            switch($reg_type){
                case 'username': //用户名注册
                    $username = I('post.username');
                    //图片验证码校验
                    if(!$this->check_verify(I('post.verify'))){
                        $this->error('验证码输入错误！');
                    }
                    break;
                case 'email': //邮箱注册
                    $username = I('post.email');
                    $_POST['username'] = 'U'.NOW_TIME;
                    //验证码严格加盐加密验证
                    if(user_md5(I('post.verify'), $username) !== session('reg_verify')){
                        $this->error('验证码错误！');
                    }
                    break;
                case 'mobile': //手机号注册
                    $username = I('post.mobile');
                    $_POST['username'] = 'U'.NOW_TIME;
                    //验证码严格加盐加密验证
                    if(user_md5(I('post.verify'), $username) !== session('reg_verify')){
                        $this->error('验证码错误！');
                    }
                    break;
            }
            $password = I('post.password');
            $user_object = D('User');
            $data = $user_object->create();
            if($data){
                $id = $user_object->add();
                if($id){
                    session('reg_verify', null);
                    $uid = $user_object->login($username, $password);
                    $this->success('注册成功', U('User/profile'));
                }else{
                    $this->error('注册失败');
                }
            }else{
                $this->error($user_object->getError());
            }
        }else{
            if(is_login()){
                $this->error("您已登陆系统", Cookie('__forward__') ? : C('HOME_PAGE'));
            }
            $this->meta_title = '用户注册';
            $this->display();
        }
    }

    /**
     * 密码重置
     * @author jry <598821125@qq.com>
     */
    public function resetPassword(){
        if(IS_POST){
            $reg_type = I('post.reg_type');
            switch($reg_type){
                case 'email':
                    $username = I('post.email');
                    $condition['email'] = I('post.email');
                    break;
                case 'mobile':
                    $username = I('post.mobile');
                    $condition['mobile'] = I('post.mobile');
                    break;
            }
            //验证码严格加盐加密验证
            if(user_md5(I('post.verify'), $username) !== session('reg_verify')){
                $this->error('验证码错误！');
            }
            $user_object = D('User');
            $data = $user_object->create($_POST, 5); //调用自动验证
            if(!$data){
                $this->error($user_object->getError());
            }
            $result = $user_object->where($condition)->setField('password', $data['password']); //重置密码
            $uid = $user_object->login($username, I('post.password')); //自动登录
            if($uid){
                $this->success('密码重置成功', C('HOME_PAGE'));
            }else{
                $this->error('密码重置失败');
            }
        }else{
            $this->meta_title = '密码重置';
            $this->display();
        }
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

    /**
     * 邮箱验证码，用于注册
     * @author jry <598821125@qq.com>
     */
    public function sendMailVerify(){
        $user_object = D('User');
        $result = $user_object->create($_POST, 5); //调用自动验证
        if(!$result){
            $this->error($user_object->getError());
        }

        //生成验证码
        $reg_verify = \Org\Util\String::randString(6,1);
        session('reg_verify', user_md5($reg_verify, I('post.email')));

        //构造邮件数据
        $mail_data['receiver'] = I('post.email');
        $mail_data['subject']  = '邮箱验证';
        $mail_data['content'] = '少侠/女侠好：<br>听闻您正使用该邮箱'.I('post.email').'【注册/修改密码】，请在验证码输入框中输入：
        <span style="color:red;font-weight:bold;">'.$reg_verify.'</span>，以完成操作。<br>
        注意：此操作可能会修改您的密码、登录邮箱或绑定手机。如非本人操作，请及时登录并修改
        密码以保证帐户安全 （工作人员不会向您索取此验证码，请勿泄漏！)';

        //发送邮件
        if(send_mail($mail_data)){
            $this->success('发送成功，请登陆邮箱查收！');
        }else{
            $this->error('发送失败！');
        }
    }

    /**
     * 短信验证码，用于注册
     * @author jry <598821125@qq.com>
     */
    public function sendMobileVerify(){
        $user_object = D('User');
        $result = $user_object->create($_POST, 5); //调用自动验证
        if(!$result){
            $this->error($user_object->getError());
        }

        //生成验证码
        $reg_verify = \Org\Util\String::randString(6,1);
        session('reg_verify', user_md5($reg_verify, I('post.mobile')));

        //构造短信数据
        $msg_data['receiver'] = I('post.mobile');
        $msg_data['message'] = '短信验证码：'.$reg_verify;
        if(send_mobile_message($msg_data)){
            $this->success('发送成功，请查收！');
        }else{
            $this->error('发送失败！');
        }
    }
}
