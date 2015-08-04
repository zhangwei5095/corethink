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
return array(
    /**
     * 产品配置
     * 系统升级需要此配置
     * 免费版不允许更改，授权版可更改产品名称及公司名称
     */
    'PRODUCT_NAME'    => 'CoreThink',                  //产品名称
    'CURRENT_VERSION' => '1.0.1',                      //当前版本
    'WEBSITE_DOMAIN'  => 'http://www.corethink.cn',    //官方网址
    'UPDATE_URL'      => '/appstore/home/core/update', //官方更新网址
    'COMPANY_NAME'    => '南京科斯克网络科技有限公司',   //公司名称
    'COMPANY_EMAIL'   => 'admin@corethink.cn',         //公司邮箱
    'COMPANY_TEL'     => '15005173785',                //公司电话

    //产品简介
    'PRODUCT_INFO'    => 'CoreThink是一套的互联网+解决方案云框架。致力于搭建一个完善的企业级应用生态环境以适应不同行业用户的需求,第三方开发者可以将自己的功能模块发布到官方的应用商城，最终达到“Enterprise One Solution”的目标。',

    //公司简介
    'COMPANY_INFO'    => '南京科斯克网络科技有限公司(CoreThink)是一家新兴的互联网+项目 技术解决方案提供商。我们用敏锐的视角洞察IT市场的每一次变革,我们顶着时代变迁的浪潮站在了前沿,以开拓互联网行业新渠道为己任。',

    //数据库配置
    'DB_TYPE'   => $_SERVER[ENV_PRE.'DB_TYPE'] ? : 'mysql', // 数据库类型
    'DB_HOST'   => $_SERVER[ENV_PRE.'DB_HOST'] ? : '127.0.0.1', // 服务器地址
    'DB_NAME'   => $_SERVER[ENV_PRE.'DB_NAME'] ? : 'corethink', // 数据库名
    'DB_USER'   => $_SERVER[ENV_PRE.'DB_USER'] ? : 'root', // 用户名
    'DB_PWD'    => $_SERVER[ENV_PRE.'DB_PWD']  ? : '', // 密码
    'DB_PORT'   => $_SERVER[ENV_PRE.'DB_PORT'] ? : '3306', // 端口
    'DB_PREFIX' => $_SERVER[ENV_PRE.'DB_PREFIX'] ? : 'ct_', // 数据库表前缀

    //系统加密字符串
    'AUTH_KEY'  => '_XRORJs!qajdwqRCEnb`yLd=UK{g@wG"?Ufa(zWFoQU+=Bf<YSDnAY/m.C%gPgnV',

    //URL模式
    'URL_MODEL' => '3',

    //全局过滤配置
    'DEFAULT_FILTER' => '', //默认为htmlspecialchars

    //预先加载的标签库
    'TAGLIB_PRE_LOAD' => 'Home\\TagLib\\Corethink',

    //URL配置
    'URL_CASE_INSENSITIVE' => true, //不区分大小写

    //应用配置
    'DEFAULT_MODULE'     => 'Home',
    'MODULE_DENY_LIST'   => array('Common'),
    'MODULE_ALLOW_LIST'  => array('Home','Admin','Install'),
    'AUTOLOAD_NAMESPACE' => array('Addons' => THINK_ADDON_PATH), //扩展模块列表

    //表单类型
    'FORM_ITEM_TYPE' => array(
        'hidden'     => array('隐藏', 'varchar(32) NOT NULL'),
        'num'        => array('数字', 'int(11) UNSIGNED NOT NULL'),
        'text'       => array('字符串', 'varchar(128) NOT NULL'),
        'textarea'   => array('文本', 'varchar(256) NOT NULL'),
        'array'      => array('数组', 'varchar(32) NOT NULL'),
        'password'   => array('密码', 'varchar(64) NOT NULL'),
        'radio'      => array('单选按钮', 'varchar(32) NOT NULL'),
        'checkbox'   => array('复选框', 'varchar(32) NOT NULL'),
        'select'     => array('下拉框', 'varchar(32) NOT NULL'),
        'icon'       => array('图标', 'varchar(32) NOT NULL'),
        'date'       => array('日期', 'int(11) UNSIGNED NOT NULL'),
        'time'       => array('时间', 'int(11) UNSIGNED NOT NULL'),
        'picture'    => array('图片', 'int(11) UNSIGNED NOT NULL'),
        'pictures'   => array('图片(多图)', 'varchar(32) NOT NULL'),
        'file'       => array('文件', 'varchar(32) NOT NULL'),
        'files'      => array('多文件', 'varchar(32) NOT NULL'),
        'kindeditor' => array('编辑器', 'text'),
        'tags'       => array('标签', 'varchar(128) NOT NULL'),
        'board  '    => array('拖动排序', 'varchar(256) NOT NULL'),
    ),

    //栏目分类前台用户投稿权限
    'CATEGORY_POST_AUTH' => array(
        '1'  => '允许投稿',
        '0'  => '禁止投稿',
    ),

    //注册方式列表
    'REG_TYPE_LIST' => array(
        'admin'  => '后台',
        'username'  => '用户名',
        'email'  => '邮箱',
        'mobile'  => '手机号',
        'sns'  => '第三方',
    ),

    //前台用户类型
    'USER_TYPE_LIST' => array(
        '0'  => '个人',
        '1'  => '企业',
    ),

    //前台用户VIP等级
    'USER_VIP_LEVEL' => array(
        '0'  => '普通用户',
        '1'  => '普通VIP',
        '2'  => '高级VIP',
    ),

    //前台用户性别
    'USER_SEX_LIST' => array(
        '1'  => '男',
        '-1' => '女',
        '0'  => '保密',
    ),

    //插件类型
    'ADDON_TYPE_LIST' => array(
        '0'  => '系统插件',
        '1'  => '微＋插件',
    ),

    //评论及Digg数据表ID
    'TABLE_LIST' => array(
        '1'  => 'Document',
        '2'  => 'Category',
        '3'  => 'User',
    ),

    //Digg类型
    'DIGG_TYPE_LIST' => array(
        '1'  => 'good', //赞
        '2'  => 'bad',  //踩
        '3'  => 'mark', //收藏
    ),

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

    //如果数据表字段名采用大小写混合需配置此项
    'DB_PARAMS'  =>  array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
);
