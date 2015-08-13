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
class PublicUploadController extends HomeController{
    /**
     * 上传
     * @author jry <598821125@qq.com>
     */
    public function upload(){
        exit(D('PublicUpload')->upload());
    }

    /**
     * KindEditor编辑器下载远程图片
     * @author jry <598821125@qq.com>
     */
    public function downremoteimg(){
        exit(D('PublicUpload')->downremoteimg());
    }

    /**
     * KindEditor编辑器文件管理
     * @author jry <598821125@qq.com>
     */
    public function fileManager(){
        exit(D('PublicUpload')->fileManager());
    }
}
