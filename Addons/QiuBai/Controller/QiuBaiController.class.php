<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <ijry@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Addons\QiuBai\Controller;
use Home\Controller\AddonController;
class QiuBaiController extends AddonController {
    //获取糗事百科列表
    public function getList(){
        if (!extension_loaded('curl')) {
            $this->error('糗事百科插件需要开启PHP的CURL扩展');
        }
        $lists = S('QiuBai_content');
        if (!$lists) {
            $config = \Common\Controller\Addon::getConfig('QiuBai');
            $content = \Org\Net\Http::fsockopenDownload('http://www.qiushibaike.com');
            if ($content) {
                $regex = "/<div class=\"content\".*?>.*?--(.*?)--.*?<\/div>/ism";
                preg_match_all($regex, $content, $match);
                $lists = array_map(function($a, $b) {
                    return array('time' => time_format($a), 'content' => $b);
                }, $match[1], $match[0]);
                S('QiuBai_content',$lists,$config['cache_time']);
            }
        }
        if ($lists) {
            $this->success('成功', '', array('data'=>$lists));
        } else {
            $this->error('获取糗事百科列表失败');
        }
        $this->assign('qiubai_list', $lists);
    }
}
