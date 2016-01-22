<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Common\Model;
use Think\Model;
/**
 * 文档模型
 * @author jry <598821125@qq.com>
 */
class DocumentModel extends Model{
    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('doc_type', 'require', '文档类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('cid', 'require', '分类不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('cid', 'checkPostAuth', '该分类禁止投稿', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
        array('ctime', 'getCreateTime', self::MODEL_BOTH, 'callback'),
        array('utime', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 创建时间不写则取当前时间
     * @return int 时间戳
     * @author jry <598821125@qq.com>
     */
    protected function getCreateTime(){
        $ctime  = I('post.ctime');
        return $ctime ? strtotime($ctime) : NOW_TIME;
    }

    /**
     * 检查分类是否允许前台会员投稿
     * @return int 时间戳
     * @author jry <598821125@qq.com>
     */
    protected function checkPostAuth(){
        if(MODULE_NAME == 'Home'){
            $category_post_auth = D('Category')->getFieldById(I('post.cid'), 'post_auth');
            if(!$category_post_auth){
                return false;
            }
        }
        return true;
    }

    /**
     * 新增或更新一个文档
     * @author jry <598821125@qq.com>
     */
    public function update(){
        //解析数据类似复选框类型的数组型值
        foreach($_POST as $key => $val){
            if(is_array($val)){
                $_POST[$key] = implode(',', $val);
            }
        }
        //调用create方法构造数据
        $base_data = $this->create();
        if($base_data){
            //获取当前分类
            $cid = I('post.cid');
            $category_info = D('Category')->find($cid);
            $doc_type = D('DocumentType')->where(array('id' => $category_info['doc_type']))->getField('name');
            $document_table_object = D('Document'.ucfirst($doc_type));
            $extend_data = $document_table_object->create(); //子模型数据验证
            if(!$extend_data){
                $this->error = $document_table_object->getError();
            }
            if($extend_data){
                if(empty($base_data['id'])){ //新增基础内容
                    $base_id = $this->add();
                    if($base_id){
                        $extend_data['id'] = $base_id;
                        $extend_id = $document_table_object->add($extend_data);
                        if(!$extend_id){
                            $this->delete($base_id);
                            $this->error = '新增扩展内容出错！';
                            return false;
                        }
                        return $base_id;
                    }else{
                        $this->error = '新增基础内容出错！';
                        return false;
                    }
                }else{
                    $status = $this->save(); //更新基础内容
                    if($status){
                        $status = $document_table_object->save(); //更新基础内容
                        if(false === $status){
                            $this->error = '更新扩展内容出错！';
                            return false;
                        }
                        return $extend_data;
                    }else{
                        $this->error = '更新基础内容出错！';
                        return false;
                    }
                }
            }
        }
        return false;
    }

    /**
     * 获取文档列表
     * @author jry <598821125@qq.com>
     */
    public function getDocumentList($cid, $limit = 10, $order = null, $map = null, $child = false){
        //获取分类信息
        $category_object = D('Category');
        $category_info = $category_object->find($cid);

        //获取该分类绑定文档模型的主要字段
        $document_type_object = D('DocumentType');
        $document_type = $document_type_object->find($category_info['doc_type']);
        if (!$document_type) {
            $this->error = '文档模型错误';
        }

        // 获取相同文档类型的子分类
        if ($child) {
            $child_cate_ids = $category_object->where(array('pid' => $cid, 'doc_type' => $category_info['doc_type']))->getField('id', true);
            if ($child_cate_ids) {
                $cid_list[] = $cid;
                $cid_list = array_merge($cid_list, $child_cate_ids);
            }
        } else {
            $cid_list[] = $cid;
        }

        $con["cid"] = array("in", $cid_list);
        $con["status"] = array("eq", '1');
        if($map){
            $con = array_merge($con, $map);
        }
        if(!$order){
            $order = 'sort desc,'.C('DB_PREFIX').'document.id desc';
        }
        $document_table = C('DB_PREFIX').'document_'.strtolower($document_type['name']);
        $document_list = $this->page(!empty($_GET["p"]) ? $_GET["p"] : 1, $limit)
                              ->order($order)
                              ->join($document_table.' ON __DOCUMENT__.id = '.$document_table.'.id')
                              ->where($con)
                              ->select();
        return $document_list;
    }

    /**
     * 获取文章详情
     * @author jry <598821125@qq.com>
     */
    public function detail($id){
        //获取基础表信息
        $info = $this->find($id);
        if(!(is_array($info) || 1 !== $info['status'])){
            $this->error = '文档被禁用或已删除！';
            return false;
        }

        //根据文档模型获取扩展表的息
        $category_info = D('Category')->find($info['cid']);
        $doc_type = D('DocumentType')->where(array('id' => $category_info['doc_type']))->getField('name');
        $document_table_object = D('Document'.ucfirst($doc_type));
        $extend_data = $document_table_object->find($id);

        //基础信息与扩展信息合并
        if(is_array($extend_data)){
            $info = array_merge($info, $extend_data);
        }

        //获取上一篇和下一篇文档信息
        $info['previous'] = $this->getPreviousDocument($info);
        $info['next']     = $this->getNextDocument($info);
        return $info;
    }

    /**
     * 获取当前分类上一篇文档
     * @author jry <598821125@qq.com>
     */
    private function getPreviousDocument($info){
        // 获取文档信息
        $map['status'] = array('eq', 1);
        $map['id'] = array('lt', $info['id']);
        $map['cid'] = array('eq', $info['cid']);
        $previous = $this->where($map)->order('id desc')->find();

        // 获取扩展信息
        if ($previous) {
            $type = D('DocumentType')->find($previous['doc_type']);
            $main_field_name = D('DocumentAttribute')->getFieldById($type['main_field'], 'name');
            $previous['title'] = D('Document'.ucfirst($type['name']))->getFieldById($next['id'], $main_field_name);
        }

        if(!$previous){
            $previous['title'] = '没有了';
            $previous['href'] = '#';
        }else{
            $previous['href'] = U('Home/Document/detail', array('id' => $previous['id']));
        }
        return $previous;
    }

    /**
     * 获取当前分类下一篇文档
     * @author jry <598821125@qq.com>
     */
    private function getNextDocument($info){
        // 获取文档信息
        $map['status'] = array('eq', 1);
        $map['id'] = array('gt', $info['id']);
        $map['cid'] = array('eq', $info['cid']);
        $next = $this->where($map)->order('id asc')->find();

        // 获取扩展信息
        if ($next) {
            $type = D('DocumentType')->find($next['doc_type']);
            $main_field_name = D('DocumentAttribute')->getFieldById($type['main_field'], 'name');
            $next['title'] = D('Document'.ucfirst($type['name']))->getFieldById($next['id'], $main_field_name);
        }

        if(!$next){
            $next['title'] = '没有了';
            $next['href'] = '#';
        }else{
            $next['href'] = U('Home/Document/detail', array('id' => $next['id']));
        }
        return $next;
    }
}
