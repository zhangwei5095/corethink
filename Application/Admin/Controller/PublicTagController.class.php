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
 * 后台标签控制器
 * @author jry <598821125@qq.com>
 */
class PublicTagController extends AdminController{
    /**
     * 标签列表
     * @author jry <598821125@qq.com>
     */
    public function index(){
        //搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|title'] = array($condition, $condition,'_multi'=>true);

        //获取所有标签
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list = D('PublicTag')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('sort desc,id desc')->select();
        $page = new \Common\Util\Page(D('PublicTag')->where($map)->count(), C('ADMIN_PAGE_ROWS'));

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('标签列表') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮
                ->addTopButton('resume')  //添加启用按钮
                ->addTopButton('forbid')  //添加禁用按钮
                ->addTopButton('delete')  //添加删除按钮
                ->setSearch('请输入ID/标签标题', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '标签')
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
     * 新增标签
     * @author jry <598821125@qq.com>
     */
    public function add(){
        if(IS_POST){
            $tag_object = D('PublicTag');
            $data = $tag_object->create();
            if($data){
                $id = $tag_object->add();
                if($id){
                    $this->success('新增成功', U('index'));
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($tag_object->getError());
            }
        }else{
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增标签') //设置页面标题
                    ->setPostUrl(U('add')) //设置表单提交地址
                    ->addFormItem('title', 'text', '标签名称', '标签名称')
                    ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                    ->display();
        }
    }

    /**
     * 编辑标签
     * @author jry <598821125@qq.com>
     */
    public function edit($id){
        if(IS_POST){
            $tag_object = D('PublicTag');
            $data = $tag_object->create();
            if($data){
                if($tag_object->save()!== false){
                    $this->success('更新成功', U('index'));
                }else{
                    $this->error('更新失败');
                }
            }else{
                $this->error($tag_object->getError());
            }
        }else{
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑标签') //设置页面标题
                    ->setPostUrl(U('edit')) //设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('title', 'text', '标签名称', '标签名称')
                    ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                    ->setFormData(D('PublicTag')->find($id))
                    ->display();
        }
    }

    /**
     * 搜索相关标签
     * @param string 搜索关键字
     * @return array 相关标签
     * @author jry <598821125@qq.com>
     */
    public function searchTags(){
        $map["title"] = array("like", "%".I('get.q')."%");
        $tags = D('PublicTag')->field('id,title')->where($map)->select();
        foreach($tags as $value){
            $data[] = array('id' => $value['title'], 'title'=> $value['title']);
        }
        echo json_encode($data);
    }
}
