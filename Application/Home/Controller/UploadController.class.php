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
 * 上传控制器
 * @author jry <598821125@qq.com>
 */
class UploadController extends HomeController{
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }

    /**
     * 上传
     * @author jry <598821125@qq.com>
     */
    public function upload(){
        exit(D('Admin/Upload')->upload());
    }

    /**
     * 下载
     * @author jry <598821125@qq.com>
     */
    public function download($token){
        if(empty($token)){
            $this->error('token参数错误！');
        }

        //解密下载token
        $file_md5 = \Think\Crypt::decrypt($token, user_md5(is_login()));
        if(!$file_md5){
            $this->error('下载链接已过期，请刷新页面！');
        }

        $upload_object = D('Admin/Upload');
        $file_id = $upload_object->getFieldByMd5($file_md5, 'id');
        if(!$upload_object->download($file_id)){
            $this->error($upload_object->getError());
        }
    }

    /**
     * KindEditor编辑器文件管理
     * @author jry <598821125@qq.com>
     */
    public function fileManager($only_image = true){
        exit(D('Admin/cUpload')->fileManager($only_image));
    }
}
