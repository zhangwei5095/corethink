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
 * 文章控制器
 * @author jry <598821125@qq.com>
 */
class DocumentController extends HomeController{
    /**
     * 文档列表
     * @author jry <598821125@qq.com>
     */
    public function index($cid){
        //获取分类信息
        $map['cid'] = $cid;
        $category_info = D('Category')->find($cid);
        switch($category_info['doc_type']){
            case 1: //链接
                if(stristr($category_info['url'], 'http://')){
                    redirect($category_info['url']);
                }else{
                    $this->redirect($category_info['url']);
                }
                break;
            case 2: //单页
                $this->redirect('Category/detail/id/'.$category_info['id']);
                break;
            default :
                //获取文档公共属性信息
                $template = $category_info['index_template'] ? 'Document/'.$category_info['index_template'] : 'Document/index_default';
                $map['status'] = array('eq', 1);
                $document_list = D('Document')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))
                                              ->order('sort desc,id desc')->where($map)->select();
                $page = new \Common\Util\Page(D('Document')->where($map)->count(), C('ADMIN_PAGE_ROWS'));

                //如果当前分类下无文档则获取子分类文档
                if(!$document_list){
                    //获取当前分类的子分类ID列表
                    $child_cagegory_id_list = D('Category')->where(array('pid' => $cid))->getField('id',true);
                    if($child_cagegory_id_list){
                        $map['cid'] = array('in', $child_cagegory_id_list);
                        $document_list = D('Document')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))
                                                      ->order('sort desc,id desc')
                                                      ->where($map)
                                                      ->select();
                        $page = new \Common\Util\Page(D('Document')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
                    }
                }

                //获取该分类绑定文档模型的主要字段
                $document_type_object = D('DocumentType');
                $document_type_main_field = $document_type_object->getFieldById($category_info['doc_type'],'main_field');
                $document_type_main_field = D('DocumentAttribute')->getFieldById($document_type_main_field, 'name');

                //获取扩展表的信息
                foreach($document_list as &$doc){
                    $doc_type_name = $document_type_object->getFieldById($doc['doc_type'], 'name');
                    $temp = array();
                    $temp = D('Document'.ucfirst($doc_type_name))->find($doc['id']);
                    $doc = array_merge($doc, $temp);

                    //给文档主要字段赋值，如：文章标题、商品名称
                    $doc['main_field'] = $doc[$document_type_main_field];
                }

                $this->assign('__CURRENT_CATEGORY__', $category_info['id']);
                $this->assign('__CURRENT_CATEGORY_GROUP__', $category_info['group']);
                $this->assign('info', $category_info);
                $this->assign('volist', $document_list);
                $this->assign('page', $page->show());
                $this->meta_title = $category_info['title'].'列表';
                Cookie('__forward__', $_SERVER['REQUEST_URI']);
                $this->display($template);
        }
    }

    /**
     * 我的文档列表
     * @author jry <598821125@qq.com>
     */
    public function mydoc(){
        $uid = $this->is_login();

        //获取文档基础信息
        $map['uid'] = $uid;
        $map['status'] = array('egt', 0);
        $document_list = D('Document')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))
                                      ->order('sort desc,id desc')
                                      ->where($map)
                                      ->select();
        $page = new \Common\Util\Page(D('Document')->where($map)->count(), C('ADMIN_PAGE_ROWS'));

        //获取扩展表的信息
        //前台与后台查询文档列表不一样
        //因为前台没有指定分类ID所以只能通过先找到文档的分类ID再根据分类绑定的模型获取主要字段
        foreach($document_list as &$document){
            //合并基础信息与扩展信息
            $doc_type_info = D('DocumentType')->find($document['doc_type']);
            $document = array_merge($document, D('Document'.ucfirst($doc_type_info['name']))->find($document['id']));

            //给主要字段赋值
            $main_field_name = D('DocumentAttribute')->getFieldById($doc_type_info['main_field'], 'name');
            $document['main_field'] = $document[$main_field_name];
        }

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('我的文档') //设置页面标题
                ->addTopButton('resume') //添加启用按钮
                ->addTopButton('forbid') //添加禁用按钮
                ->addTopButton('recycle') //添加回收按钮
                ->addTableColumn('id', 'ID')
                ->addTableColumn('main_field', '标题')
                ->addTableColumn('ctime', '发布时间', 'time')
                ->addTableColumn('sort', '排序')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($document_list) //数据列表
                ->setTableDataPage($page->show())  //数据列表分页
                ->addRightButton('edit')   //添加编辑按钮
                ->addRightButton('forbid') //添加禁用/启用按钮
                ->addRightButton('recycle') //添加回收按钮
                ->setTemplate('_Builder/listbuilder_user')
                ->display();
    }

    /**
     * 新增文档
     * @author jry <598821125@qq.com>
     */
    public function add(){
        $this->is_login();

        if(I('get.doc_type')){
            $map['doc_type'] = I('get.doc_type');
            $category_info = D('Category')->where($map)->order('id asc')->find();
        }elseif(I('get.cid')){
            $category_info = D('Category')->find(I('get.cid'));
        }
        //获取当前分类
        if(!$category_info['post_auth']){
            $this->error('该分类禁止投稿');
        }
        $doc_type = D('DocumentType')->find($category_info['doc_type']);
        $field_sort = json_decode($doc_type['field_sort'], true);
        $field_group = parse_attr($doc_type['field_group']);

        //获取文档字段
        $map = array();
        $map['status'] = array('eq', '1');
        $map['show'] = array('eq', '1');
        $map['doc_type'] = array('in', '0,'.$category_info['doc_type']);
        $attribute_list = D('DocumentAttribute')->where($map)->select();

        //解析字段options
        $new_attribute_list = array();
        foreach($attribute_list as $attr){
            if($attr['name'] == 'cid'){
                $con = array();
                $con['group'] = $category_info['group'];
                $con['doc_type'] = $category_info['doc_type'];
                $attr['value'] = $category_info['id'];
                $attr['options'] = select_list_as_tree('Category', $con);
            }else{
                $attr['options'] = parse_attr($attr['options']);
            }
            $new_attribute_list[$attr['id']] = $attr;
        }

        //表单字段排序及分组
        if($field_sort){
            $new_attribute_list_sort = array();
            foreach($field_sort as $k1 => &$v1){
                $new_attribute_list_sort[0]['type'] = 'group';
                $new_attribute_list_sort[0]['options']['group'.$k1]['title'] = $field_group[$k1];
                foreach($v1 as $k2 => $v2){
                    $new_attribute_list_sort[0]['options']['group'.$k1]['options'][] = $new_attribute_list[$v2];
                }
            }
            $new_attribute_list = $new_attribute_list_sort[0]['options']['group1']['options'];
        }

        //使用FormBuilder快速建立表单页面。
        $builder = new \Common\Builder\FormBuilder();
        $builder->setMetaTitle('新增文章')  //设置页面标题
                ->setPostUrl(U('update')) //设置表单提交地址
                ->addFormItem('doc_type', 'hidden')
                ->setFormData(array('doc_type' => $category_info['doc_type']))
                ->setExtraItems($new_attribute_list)
                ->setTemplate('_Builder/formbuilder_user')
                ->display();
    }

    /**
     * 编辑文章
     * @author jry <598821125@qq.com>
     */
    public function edit($id){
        $this->is_login();
        //获取文档信息
        $document_info = D('Document')->detail($id);

        //获取当前分类
        $category_info = D('Category')->find($document_info['cid']);
        if(!$category_info['post_auth']){
            $this->error('该分类禁止投稿');
        }
        $doc_type = D('DocumentType')->find($category_info['doc_type']);
        $field_sort = json_decode($doc_type['field_sort'], true);
        $field_group = parse_attr($doc_type['field_group']);

        //获取文档字段
        $map = array();
        $map['status'] = array('eq', '1');
        $map['show'] = array('eq', '1');
        $map['doc_type'] = array('in', '0,'.$category_info['doc_type']);
        $attribute_list = D('DocumentAttribute')->where($map)->select();

        //解析字段options
        $new_attribute_list = array();
        foreach($attribute_list as $attr){
            if($attr['name'] == 'cid'){
                $con = array();
                $con['group'] = $category_info['group'];
                $con['doc_type'] = $category_info['doc_type'];
                $attr['options'] = select_list_as_tree('Category', $con);
            }else{
                $attr['options'] = parse_attr($attr['options']);
            }
            $new_attribute_list[$attr['id']] = $attr;
            $new_attribute_list[$attr['id']]['value'] = $document_info[$attr['name']];
        }

        //表单字段排序及分组
        if($field_sort){
            $new_attribute_list_sort = array();
            foreach($field_sort as $k1 => &$v1){
                $new_attribute_list_sort[0]['type'] = 'group';
                $new_attribute_list_sort[0]['options']['group'.$k1]['title'] = $field_group[$k1];
                foreach($v1 as $k2 => $v2){
                    $new_attribute_list_sort[0]['options']['group'.$k1]['options'][] = $new_attribute_list[$v2];
                }
            }
            $new_attribute_list = $new_attribute_list_sort[0]['options']['group1']['options'];
        }

        //使用FormBuilder快速建立表单页面。
        $builder = new \Common\Builder\FormBuilder();
        $builder->setMetaTitle('编辑文章')  //设置页面标题
                ->setPostUrl(U('update')) //设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->setExtraItems($new_attribute_list)
                ->setFormData($document_info)
                ->setTemplate('_Builder/formbuilder_user')
                ->display();
    }

    /**
     * 新增或更新一个文档
     * @author jry <598821125@qq.com>
     */
    public function update(){
        $this->is_login();

        //解析数据类似复选框类型的数组型值
        foreach($_POST as $key => $val){
            if(is_array($val)){
                $_POST[$key] = implode(',', $val);
            }
        }

        //新增或更新文档
        $document_object = D('Document');
        $result = $document_object->update();
        if(!$result){
            $this->error($document_object->getError());
        }else{
            if(is_array($result)){
                $message = '更新成功';
            }else{
                $message = '新增成功';
            }
            $this->success($message, Cookie('__forward__') ? : C('HOME_PAGE'));
        }
    }

    /**
     * 文章信息
     * @author jry <598821125@qq.com>
     */
    public function detail($id){
        $map['status'] = array('egt', 1); //正常、隐藏两种状态是可以访问的
        $info = D('Document')->where($map)->detail($id);
        if(!$info){
            $this->error('您访问的文档已禁用或不存在');
        }
        $result = D('Document')->where(array('id' => $id))->SetInc('view'); //阅读量加1

        //获取文档所属分类详细信息
        $category_info = D('Category')->find($info['cid']);

        //获取该分类绑定文档模型的主要字段
        $document_type_object = D('DocumentType');
        $document_type_main_field = $document_type_object->getFieldById($category_info['doc_type'],'main_field');
        $document_type_main_field = D('DocumentAttribute')->getFieldById($document_type_main_field, 'name');

        //给文档主要字段赋值，如：文章标题、商品名称
        $info['main_field'] = $info[$document_type_main_field];

        if($info['file']){
            $file_list = explode(',', $info['file']);
            foreach($file_list as &$file){
                $file = D('PublicUpload')->find($file);
            }
            $info['file_list'] = $file_list;
        }

        //设置文档显示模版
        $template = $category_info['detail_template'] ? 'Document/'.$category_info['detail_template'] : 'Document/detail_default';

        $this->assign('info', $info);
        $this->assign('__CURRENT_CATEGORY__', $category_info['id']);
        $this->assign('meta_title', $info['main_field']);
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->display($template);
    }
}
