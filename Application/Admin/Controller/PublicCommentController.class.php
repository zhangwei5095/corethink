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
 * 后台评论控制器
 * @author jry <598821125@qq.com>
 */
class PublicCommentController extends AdminController{
    /**
     * 评论列表
     * @author jry <598821125@qq.com>
     */
    public function index(){
        //搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|content'] = array($condition, $condition,'_multi'=>true);

        //获取所有评论
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list = D('PublicComment')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('sort desc,id desc')->select();
        $page = new \Common\Util\Page(D('PublicComment')->where($map)->count(), C('ADMIN_PAGE_ROWS'));

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('评论列表') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮
                ->addTopButton('resume')  //添加启用按钮
                ->addTopButton('forbid')  //添加禁用按钮
                ->addTopButton('delete')  //添加删除按钮
                ->setSearch('请输入ID/评论关键字', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('content', '评论')
                ->addTableColumn('ctime', '创建时间', 'time')
                ->addTableColumn('sort', '排序')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->addRightButton('edit')   //添加编辑按钮
                ->addRightButton('forbid') //添加禁用/启用按钮
                ->addRightButton('delete') //添加删除按钮
                ->display();
    }

    /**
     * 新增评论
     * @author jry <598821125@qq.com>
     */
    public function add(){
        if(IS_POST){
            $user_comment_object = D('PublicComment');
            $data = $user_comment_object->create();
            if($data){
                $id = $user_comment_object->add();
                if($id){
                    $this->success('新增成功', U('index'));
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($user_comment_object->getError());
            }
        }else{
            $user_comment_object = D('PublicComment');

            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增评论')  //设置页面标题
                    ->setPostUrl(U('add')) //设置表单提交地址
                    ->addFormItem('table', 'radio', '数据表', '数据表ID', $user_comment_object->model_type())
                    ->addFormItem('data_id', 'num', '数据ID', '数据ID')
                    ->addFormItem('content', 'textarea', '评论内容', '评论内容')
                    ->addFormItem('pictures', 'pictures', '图片列表', '图片列表')
                    ->addFormItem('rate', 'num', '评分', '评分')
                    ->addFormItem('pid', 'num', '父评论ID', '父评论ID')
                    ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                    ->display();
        }
    }

    /**
     * 编辑评论
     * @author jry <598821125@qq.com>
     */
    public function edit($id){
        if(IS_POST){
            $user_comment_object = D('PublicComment');
            $data = $user_comment_object->create();
            if($data){
                if($user_comment_object->save()!== false){
                    $this->success('更新成功', U('index'));
                }else{
                    $this->error('更新失败');
                }
            }else{
                $this->error($user_comment_object->getError());
            }
        }else{
            $user_comment_object = D('PublicComment');

            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑评论')  //设置页面标题
                    ->setPostUrl(U('edit')) //设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('table', 'radio', '数据表', '数据表ID', $user_comment_object->model_type())
                    ->addFormItem('data_id', 'num', '数据ID', '数据ID')
                    ->addFormItem('content', 'textarea', '评论内容', '评论内容')
                    ->addFormItem('pictures', 'pictures', '图片列表', '图片列表')
                    ->addFormItem('rate', 'num', '评分', '评分')
                    ->addFormItem('pid', 'num', '父评论ID', '父评论ID')
                    ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                    ->setFormData(D('PublicComment')->find($id))
                    ->display();
        }
    }
}
