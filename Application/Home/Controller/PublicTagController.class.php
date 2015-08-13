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
 * 后台标签控制器
 * @author jry <598821125@qq.com>
 */
class PublicTagController extends HomeController{
    /**
     * 标签列表
     * @author jry <598821125@qq.com>
     */
    public function index(){
        //获取所有标签
        $map['status'] = array('eq', '1'); //禁用和正常状态
        $tag_list = D('PublicTag')->where($map)->order('sort desc,id desc')->group('`group`')->select();
        $this->assign('tag_list', $tag_list);
        $this->assign('meta_title', '标签');
        $this->display();
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
                    $this->success('新增成功');
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($tag_object->getError());
            }
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
