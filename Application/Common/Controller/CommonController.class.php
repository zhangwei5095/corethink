<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Common\Controller;
use Think\Controller;
/**
 * 所有模块公共控制器
 * @author jry <598821125@qq.com>
 */
class CommonController extends Controller{
    /**
     * 设置一条或者多条数据的状态
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = CONTROLLER_NAME){
        $ids    = I('request.ids');
        $status = I('request.status');
        if(empty($ids)){
            $this->error('请选择要操作的数据');
        }
        //特殊情况处理
        switch($model){
            case 'User':
                if(in_array(1, $ids, true) || 1 == $ids)
                    $this->error('不允许更改超级管理员状态');
                break;
            case 'UserGroup':
                if(in_array(1, $ids, true) || 1 == $ids)
                    $this->error('不允许更改超级管理员组状态');
                break;
        }
        $model_primary_key = D($model)->getPk();
        $map[$model_primary_key] = array('in',$ids);
        switch($status){
            case 'forbid' : //禁用条目
                $data = array('status' => 0);
                $this->editRow($model, $data, $map, array('success'=>'禁用成功','error'=>'禁用失败'));
                break;
            case 'resume' : //启用条目
                $data = array('status' => 1);
                $map  = array_merge(array('status' => 0), $map);
                $this->editRow($model, $data, $map, array('success'=>'启用成功','error'=>'启用失败'));
                break;
            case 'hide' : //隐藏条目
                $data = array('status' => 2);
                $map  = array_merge(array('status' => 1), $map);
                $this->editRow($model, $data, $map, array('success'=>'隐藏成功','error'=>'隐藏失败'));
                break;
            case 'show' : //显示条目
                $data = array('status' => 1);
                $map  = array_merge(array('status' => 2), $map);
                $this->editRow($model, $data, $map, array('success'=>'显示成功','error'=>'显示失败'));
                break;
            case 'recycle' : //移动至回收站
                $data['status'] = -1;
                $this->editRow($model, $data, $map, array('success'=>'成功移至回收站','error'=>'删除失败'));
                break;
            case 'restore' : //从回收站还原
                $data = array('status' => 1);
                $map  = array_merge(array('status' => -1), $map);
                $this->editRow($model, $data, $map, array('success'=>'恢复成功','error'=>'恢复失败'));
                break;
            case 'delete'  : //删除条目
                $result = D($model)->where($map)->delete();
                if($result){
                    $this->success('删除成功，不可恢复！');
                }else{
                    $this->error('删除失败');
                }
                break;
            default :
                $this->error('参数错误');
                break;
        }
    }

    /**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     * @param string $model 模型名称,供M函数使用的参数
     * @param array  $data  修改的数据
     * @param array  $map   查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                      url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     * @author jry <598821125@qq.com>
     */
    final protected function editRow($model, $data, $map, $msg){
        $id = array_unique((array)I('id',0));
        $id = is_array($id) ? implode(',',$id) : $id;
        //如存在id字段，则加入该条件
        $fields = M($model)->getDbFields();
        if(in_array('id',$fields) && !empty($id)){
            $where = array_merge(array('id' => array('in', $id )) ,(array)$where);
        }
        $msg = array_merge(array('success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg);
        if(M($model)->where($map)->save($data) !== false){
            $this->success($msg['success'], $msg['url'], $msg['ajax']);
        }else{
            $this->error($msg['error'], $msg['url'], $msg['ajax']);
        }
    }
}
