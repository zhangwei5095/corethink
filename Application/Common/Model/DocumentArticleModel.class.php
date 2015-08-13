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
 * 文档子模型－文章模型
 * @author jry <598821125@qq.com>
 */
class DocumentArticleModel extends Model{
    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '文档标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,127', '文档标题长度为1-127个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('title', '', '文档标题已经存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        array('content', 'require', '文章内容不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    );
}
