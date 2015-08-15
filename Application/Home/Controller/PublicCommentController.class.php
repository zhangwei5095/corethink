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
 * 评论控制器
 * @author jry <598821125@qq.com>
 */
class PublicCommentController extends HomeController{
    /**
     * 新增评论
     * @author jry <598821125@qq.com>
     */
    public function add(){
        if(IS_POST){
            $uid = $this->is_login();
            $public_comment_object = D('PublicComment');
            $data = $public_comment_object->create();
            if($data){
                $id = $public_comment_object->add();
                if($id){
                    //更新评论数
                    D($public_comment_object->model_type(I('post.table')))->where(array('id'=> (int)$data['data_id']))->setInc('comment');

                    //获取当前被评论文档的基础信息
                    $current_document_info = D($public_comment_object->model_type(I('post.table')))->find(I('post.data_id'));

                    //查看详情连接
                    $view_detail = '<a href="'.U('Document/detail', array('id' => $current_document_info['id'])).'"> 查看详情... </a>';

                    //当前发表评论的用户信息
                    $current_user_info = D('User')->find($uid);

                    //给评论用户用户名加上链接以便于直接点击
                    $current_username = '<a href="'.U('User/index', array('id' => $current_user_info['id'])).'">'.$current_user_info['username'].'</a>';

                    //如果是对别人的评论进行回复则获取被评论的那个人的UID以便于发消息骚扰他
                    if(I('post.pid')){
                        $previous_comment_uid = D('PublicComment')->getFieldById(I('post.pid'), 'uid');
                    }

                    //如果是Document的则发消息
                    if(I('post.table') === '1'){
                        //定义消息结构
                        $msg_data['title'] = $current_username.'回复了您！'.$view_detail;
                        $msg_data['type']  = 1;

                        //给文档作者发送消息
                        //自己给自己发表的文档评论时不发送 要求$current_document_info['uid'] !== $current_user_info['id']
                        if($current_document_info['uid'] !== $current_user_info['id']){
                            //给文档发表者发消息
                            $msg_data['to_uid'] = $current_document_info['uid'];
                            $result = D('UserMessage')->sendMessage($msg_data);
                        }

                        //给被回复者发送消息
                        //自己回复自己的评论时不发送 要求$current_document_info['uid'] !== $previous_comment_uid
                        //如果是对别人的评论进行回复则获取被评论的那个人的UID以便于发消息骚扰他
                        if(I('post.pid')){
                            $previous_comment_uid = D('PublicComment')->getFieldById(I('post.pid'), 'uid');
                            if($current_document_info['uid'] !== $previous_comment_uid){
                                $msg_data['to_uid'] = $previous_comment_uid;
                                $result = D('UserMessage')->sendMessage($msg_data);
                            }
                        }
                    }

                    $this->success('提交成功');
                }else{
                    $this->error('提交失败');
                }
            }else{
                $this->error($public_comment_object->getError());
            }
        }
    }
}
