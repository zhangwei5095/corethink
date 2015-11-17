<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
use \Common\Util\Tree;
/**
 * 链接模型
 * @author jry <598821125@qq.com>
 */
class LinkModel extends Model {
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'admin_link';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('url', '0,255', '链接长度为0-25个字符', self::EXISTS_VALIDATE, 'length',self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('sort', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 导航类型
     * @author jry <598821125@qq.com>
     */
    public function nav_type($id) {
        $list['1'] = '快捷功能';
        $list['2'] = '热门网站';
        $list['3'] = '娱乐影音';
        $list['4'] = '购物消费';
        $list['5'] = '适用工具';
        return $id ? $list[$id] : $list;
    }
}
