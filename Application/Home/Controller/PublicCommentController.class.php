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
            $user_comment_object = D('PublicComment');
            $data = $user_comment_object->create();
            if($data){
                $id = $user_comment_object->add();
                if($id){
                    //更新评论数
                    D($user_comment_object->model_type(I('post.table')))->where(array('id'=> (int)$data['data_id']))->setInc('comment');

                    //获取当前被评论文档的详细信息
                    $current_document_info = D($user_comment_object->model_type(I('post.table')))->find(I('post.data_id'));

                    //给文档标题加上链接以便于直接点击
                    $current_document_title = '<a href="'.U($user_comment_object->model_type(I('post.table')).'/detail', array('id' => $current_document_info['id'])).'">'.$current_document_info['title'].'</a>';

                    //当前发表评论的用户信息
                    $current_user_info = D('User')->find($uid);

                    //给评论用户用户名加上链接以便于直接点击
                    $current_username = '<a href="'.U('User/index', array('id' => $current_user_info['id'])).'">'.$current_user_info['username'].'</a>';

                    //如果是对别人的评论进行回复则获取被评论的那个人的UID以便于发消息骚扰他
                    if(I('post.pid')){
                        $previous_comment_uid = D('PublicComment')->getFieldById(I('post.pid'), 'id');
                    }

                    //如果是Document的则发消息
                    if(I('post.table') === '1'){
                        //发送消息，以下集中特殊情况不发送
                        //自己给自己发表的文档评论 要求$current_document_info['uid'] !== $current_user_info['id']
                        //自己回复自己的评论 要求$current_document_info['uid'] === $previous_comment_uid
                        if($current_document_info['uid'] !== $current_user_info['id']){
                            if(I('post.pid') && $current_document_info['uid'] !== $previous_comment_uid){
                                $result = D('UserMessage')->sendMessage($current_username.'在'.$current_document_title.'中回复了您！', '' , $previous_comment_uid, 1);
                            }
                            $result = D('UserMessage')->sendMessage($current_username.'在'.$current_document_title.'中回复了您！', '' , $current_document_info['uid'], 1);
                        }
                    }

                    $this->success('提交成功');
                }else{
                    $this->error('提交失败');
                }
            }else{
                $this->error($user_comment_object->getError());
            }
        }
    }
}
