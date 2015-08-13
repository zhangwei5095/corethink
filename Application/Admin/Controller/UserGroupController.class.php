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
 * 部门控制器
 * @author jry <598821125@qq.com>
 */
class UserGroupController extends AdminController{
    /**
     * 部门列表
     * @author jry <598821125@qq.com>
     */
    public function index(){
        //搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|title'] = array($condition, $condition, '_multi'=>true); //搜索条件

        //获取所有部门
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list = D('UserGroup')->where($map)->order('sort asc, id asc')->select();

        //转换成树状列表
        $tree = new \Common\Util\Tree();
        $data_list = $tree->toFormatTree($data_list);

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('部门列表') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮
                ->addTopButton('resume')  //添加启用按钮
                ->addTopButton('forbid')  //添加禁用按钮
                ->addTopButton('delete')  //添加删除按钮
                ->setSearch('请输入ID/部门名称', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title_show', '标题')
                ->addTableColumn('icon', '图标', 'icon')
                ->addTableColumn('sort', '排序')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->addRightButton('edit')   //添加编辑按钮
                ->addRightButton('forbid') //添加禁用/启用按钮
                ->addRightButton('delete') //添加删除按钮
                ->display();
    }

    /**
     * 新增部门
     * @author jry <598821125@qq.com>
     */
    public function add(){
        if(IS_POST){
            $user_group_object = D('UserGroup');
            $_POST['menu_auth']= implode(',', I('post.menu_auth'));
            $_POST['category_auth']= implode(',', I('post.category_auth'));
            $data = $user_group_object->create();
            if($data){
                $id = $user_group_object->add();
                if($id){
                    $this->success('新增成功', U('index'));
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($user_group_object->getError());
            }
        }else{
            //获取现有部门
            $map['status'] = array('egt', 0);
            $tree = new \Common\Util\Tree();
            $all_group = $tree->toFormatTree(D('UserGroup')->where($map)->order('sort asc,id asc')->select());
            $all_group = array_merge(array(0 => array('id'=>0, 'title_show'=>'顶级部门')), $all_group);

            //获取栏目分类权限节点（系统权限节点直接使用AdminController里的__ALL_MENU_LIST__）
            $category_auth_list = array();
            $category_group_list = C('CATEGORY_GROUP_LIST');
            foreach($category_group_list as $key => $val){
                //获取当前分组下的分类
                $map['status'] = array('egt', 1);
                $map['group']  = array('eq', $key);
                $category_list = $tree->toFormatTree(D('Category')->where($map)->select());

                //构造权限列表
                $category_auth_list[$key]['title'] = $val;
                $category_auth_list[$key]['auth'] = $category_list;
            }

            $this->assign('all_group', $all_group);
            $this->assign('category_auth_list', $category_auth_list);
            $this->meta_title = '新增部门';
            $this->display('add_edit');
        }
    }

    /**
     * 编辑部门
     * @author jry <598821125@qq.com>
     */
    public function edit($id){
        if(IS_POST){
            $user_group_object = D('UserGroup');
            $_POST['menu_auth']= implode(',', I('post.menu_auth'));
            $_POST['category_auth']= implode(',', I('post.category_auth'));
            $data = $user_group_object->create();
            if($data){
                if($user_group_object->save()!== false){
                    $this->success('更新成功', U('index'));
                }else{
                    $this->error('更新失败');
                }
            }else{
                $this->error($user_group_object->getError());
            }
        }else{
            //获取部门信息
            $info = D('UserGroup')->find($id);
            $info['menu_auth'] = explode(',', $info['menu_auth']);
            $info['category_auth'] = explode(',', $info['category_auth']);

            //获取现有部门
            $map['status'] = array('egt', 0);
            $tree = new \Common\Util\Tree();
            $all_group = $tree->toFormatTree(D('UserGroup')->where($map)->order('sort asc,id asc')->select());
            $all_group = array_merge(array(0 => array('id'=>0, 'title_show'=>'顶级部门')), $all_group);

            //获取栏目分类权限节点（系统权限节点直接使用AdminController里的__ALL_MENU_LIST__）
            $category_auth_list = array();
            $category_group_list = C('CATEGORY_GROUP_LIST');
            foreach($category_group_list as $key => $val){
                //获取当前分组下的分类
                $map['status'] = array('egt', 1);
                $map['group']  = array('eq', $key);
                $category_list = $tree->toFormatTree(D('Category')->where($map)->select());

                //构造权限列表
                $category_auth_list[$key]['title'] = $val;
                $category_auth_list[$key]['auth'] = $category_list;
            }

            $this->assign('all_group', $all_group);
            $this->assign('category_auth_list', $category_auth_list);
            $this->assign('info', $info);
            $this->assign('meta_title', '编辑部门');
            $this->display('add_edit');
        }
    }
}
