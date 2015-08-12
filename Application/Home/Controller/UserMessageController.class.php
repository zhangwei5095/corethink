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
 * 消息控制器
 * @author jry <598821125@qq.com>
 */
class UserMessageController extends HomeController{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }

    /**
     * 默认方法
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function index($type = 0){
        $map['type'] = array('eq', $type);
        $map['status'] = array('eq', 1);
        $map['to_uid'] = array('eq', is_login());
        $user_message_object = D('UserMessage');
        $message_list = $user_message_object->where($map)->order('sort desc,id desc')->select();
        $message_type = $user_message_object->message_type();
        foreach($message_type as $key => $val){
            $new_message_type[$key] = D('UserMessage')->newMessageCount($key);
        }
        $this->assign('message_list', $message_list);
        $this->assign('message_type', $message_type);
        $this->assign('new_message_type', $new_message_type);
        $this->assign('__CURRENT_MESSAGE_TYPE', $type);
        $this->assign('meta_title', "消息中心");
        $this->display();
    }

    /**
     * 查看消息
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function detail($id){
        $user_message_object = D('UserMessage');
        $user_message_info = $user_message_object->find($id);
        if(!$user_message_info){
            $this->error('该消息已禁用或不存在');
        }
        $map['id'] = array('eq', $id);
        $user_message_object->where($map)->setField('is_read', 1);
        $this->assign('user_message_info', $user_message_info);
        $this->assign('__CURRENT_MESSAGE_TYPE', $user_message_info['type']);
        $this->assign('meta_title', $user_message_info['title']);
        $this->display();
    }

    /**
     * 获取当前用户未读消息数量
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function newMessageCount($type = null){
        $data['status'] = 1;
        $data['new_message'] = D('UserMessage')->newMessageCount($type);
        $this->ajaxReturn($data);
    }

    /**
     * 设置当前用户所有未读消息为已读
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function readAll($type = null){
        $map['status']  = array('eq', 1);
        $map['to_uid']  = array('eq', is_login());
        $map['is_read'] = array('eq', 0);
        $map['id']      = array('in', I('ids'));
        if($type !== null){
            $map['type'] = array('eq', $type);
        }
        $result = D('UserMessage')->where($map)->setField('is_read', 1);
        if($result){
            $this->success('操作成功');
        }
    }
}
