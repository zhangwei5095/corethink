<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------

/**
 * CoreThink全局配置文件
 */
const THINK_ADDON_PATH = './Addons/';
$_config = array (
    /**
     * 产品配置
     * 系统升级需要此配置
     * 根据CoreThink用户协议：
     * 免费版您可以免费用于项目开发
     * 但不允许更改本产品后台的版权信息，请您尊重我们的劳动成果及知识产权，违者追究法律责任。
     * 为了您更加方便使用本系统，后台特别设置了当前项目开发团队名称：DEVELOP_TEAM允许您自由更改来展示您的信息
     * 商业授权版可更改所有的产品名称及公司名称，授权联系：admin@corethink.cn
     */

    'PRODUCT_NAME'    => 'CoreThink',                  //产品名称
    'CURRENT_VERSION' => '1.0.3',                      //当前版本
    'WEBSITE_DOMAIN'  => 'http://www.corethink.cn',    //官方网址
    'UPDATE_URL'      => '/appstore/home/core/update', //官方更新网址
    'COMPANY_NAME'    => '南京科斯克网络科技有限公司',   //公司名称
    'DEVELOP_TEAM'    => '南京科斯克网络科技有限公司',   //当前项目开发团队名称［未授权版仅允许修改此项］

    //产品简介
    'PRODUCT_INFO'    => 'CoreThink是一套的互联网+解决方案云框架。致力于搭建一个完善的企业级应用生态环境以适应不同行业用户的需求,第三方开发者可以将自己的功能模块发布到官方的应用商城，最终达到“Enterprise One Solution”的目标。',

    //公司简介
    'COMPANY_INFO'    => '南京科斯克网络科技有限公司(CoreThink)是一家新兴的互联网+项目技术解决方案提供商。我们用敏锐的视角洞察IT市场的每一次变革,我们顶着时代变迁的浪潮站在了前沿,以开拓互联网行业新渠道为己任。',

    //系统主页地址配置
    'HOME_PAGE'       => 'http://'.$_SERVER['HTTP_HOST'].__ROOT__,

    //URL模式
    'URL_MODEL' => '3',

    //全局过滤配置
    'DEFAULT_FILTER' => '', //TP默认为htmlspecialchars

    //预先加载的标签库
    'TAGLIB_PRE_LOAD' => 'Home\\TagLib\\Corethink',

    //URL配置
    'URL_CASE_INSENSITIVE' => true, //不区分大小写

    //应用配置
    'DEFAULT_MODULE'     => 'Home',
    'MODULE_DENY_LIST'   => array('Common'),
    'MODULE_ALLOW_LIST'  => array('Home','Admin','Install'),
    'AUTOLOAD_NAMESPACE' => array('Addons' => THINK_ADDON_PATH), //扩展模块列表

    //模板相关配置
    'TMPL_PARSE_STRING'  => array (
        '__PUBLIC__'     => __ROOT__.'/Public',
        '__ADMIN_IMG__'  => __ROOT__.'/'.APP_PATH.'Admin/View/_Resource/img',
        '__ADMIN_CSS__'  => __ROOT__.'/'.APP_PATH.'Admin/View/_Resource/css',
        '__ADMIN_JS__'   => __ROOT__.'/'.APP_PATH.'Admin/View/_Resource/js',
        '__ADMIN_LIBS__' => __ROOT__.'/'.APP_PATH.'Admin/View/_Resource/libs',
        '__HOME_IMG__'   => __ROOT__.'/'.APP_PATH.'Home/View/default/_Resource/img',
        '__HOME_CSS__'   => __ROOT__.'/'.APP_PATH.'Home/View/default/_Resource/css',
        '__HOME_JS__'    => __ROOT__.'/'.APP_PATH.'Home/View/default/_Resource/js',
        '__HOME_LIBS__'  => __ROOT__.'/'.APP_PATH.'Home/View/default/_Resource/libs',
    ),

    //文件上传默认驱动
    'UPLOAD_DRIVER' => 'Local',

    //文件上传相关配置
    'UPLOAD_CONFIG' => array(
        'mimes'    => '', //允许上传的文件MiMe类型
        'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制，默认为2M，后台配置会覆盖此值)
        'autoSub'  => true, //自动子目录保存文件
        'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './Uploads/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', //文件保存后缀，空则使用原后缀
        'replace'  => false, //存在同名是否覆盖
        'hash'     => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ),
);

//返回合并的配置
return array_merge (
    $_config, //系统全局默认配置
    include APP_PATH.'/Common/Conf/db.php', //包含数据库连接配置
    include APP_PATH.'/Common/Builder/config.php' //包含Builder配置
);
