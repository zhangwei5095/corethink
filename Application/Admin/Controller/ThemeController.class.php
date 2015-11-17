<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
use Common\Util\Think\Page;
/**
 * 主题控制器
 * @author jry <598821125@qq.com>
 */
class ThemeController extends AdminController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
        $theme_object = D('Theme');
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $data_list = $theme_object
                   ->getAll();

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('主题列表')  // 设置页面标题
                ->addTableColumn('name', '名称')
                ->addTableColumn('title', '标题')
                ->addTableColumn('description', '描述')
                ->addTableColumn('developer', '开发者')
                ->addTableColumn('version', '版本')
                ->addTableColumn('create_time', '创建时间', 'time')
                ->addTableColumn('status', '状态')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)     // 数据列表
                ->display();
    }

    /**
     * 编辑导航
     * @author jry <598821125@qq.com>
     */
    public function edit($id) {
        if (IS_POST) {
            // 如果值是数组则转换成字符串，适用于复选框等类型
            if (is_array($_POST['module'])) {
                $_POST['module'] = implode(',', $_POST['module']);
            }
            $theme_object = D('Theme');
            $data = $theme_object->create();
            if ($data) {
                if ($theme_object->save($data)) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($theme_object->getError());
            }
        } else {
            $con['is_system'] = 0;
            $module_list = D('Module')->where($con)->getField('name', true);
            foreach ($module_list as $val) {
                $module_name_list[$val] = $val;
            }

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑主题')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('module', 'checkbox', '启用该主题的模块', '未勾选的将使用系统默认主题', $module_name_list)
                    ->setFormData(D('Theme')->find($id))
                    ->display();
        }
    }

    /**
     * 安装主题
     * @author jry <598821125@qq.com>
     */
    public function install($name) {
        // 获取当前主题信息
        $config_file = realpath('./Theme/'.$name).'/'
                     .D('Theme')->install_file();
        if (!$config_file) {
            $this->error('安装失败');
        }
        $config = include $config_file;
        $data = $config['info'];
        if ($config['config']) {
            $data['config'] = json_encode($config['config']);
        }

        // 写入数据库记录
        $theme_object = D('Theme');
        $data = $theme_object->create($data);
        if ($data) {
            $id = $theme_object->add($data);
            if ($id) {
                $this->success('安装成功', U('index'));
            } else {
                $this->error('安装失败');
            }
        } else {
            $this->error($theme_object->getError());
        }
    }

    /**
     * 卸载主题
     * @author jry <598821125@qq.com>
     */
    public function uninstall($id) {
        // 当前主题禁止卸载
        $theme_object = D('Theme');
        $result = $theme_object->delete($id);
        if ($result) {
            $this->success('卸载成功！');
        } else {
            $this->error('卸载失败', $theme_object->getError());
        }
    }

    /**
     * 更新主题信息
     * @author jry <598821125@qq.com>
     */
    public function updateInfo($id) {
        $theme_object = D('Theme');
        $name = $theme_object->getFieldById($id, 'name');
        $config_file = realpath('./Theme/'.$name).'/'
                     .D('Theme')->install_file();
        if (!$config_file) {
            $this->error('不存在安装文件');
        }
        $config = include $config_file;
        $data = $config['info'];
        if ($config['config']) {
            $data['config'] = json_encode($config['config']);
        }
        $data['id'] = $id;
        $data = $theme_object->create($data);
        if ($data) {
            $id = $theme_object->save($data);
            if ($id) {
                $this->success('更新成功', U('index'));
            } else {
                $this->error('更新失败');
            }
        } else {
            $this->error($theme_object->getError());
        }
    }

    /**
     * 切换主题
     * @author jry <598821125@qq.com>
     */
    public function setCurrent($id) {
        $theme_object = D('Theme');
        $theme_info = $theme_object->find($id);
        if ($theme_info) {
            // 当前主题current字段置为1
            $map['id'] = array('eq', $id);
            $result1 = $theme_object->where($map)->setField('current', 1);
            if ($result1) {
                // 其它主题current字段置为0
                $map = array();
                $map['id'] = array('neq', $id);
                if ($theme_object->where($map)->count() == 0) {
                    $this->success('前台主题设置成功！');
                }
                $con['id'] = array('neq', $id);
                $result2 = $theme_object->where($con)->setField('current', 0);
                if ($result2) {
                    $this->success('前台主题设置成功！');
                } else {
                    $this->error('设置当前主题失败', $theme_object->getError());
                }
            } else {
                $this->error('设置当前主题失败', $theme_object->getError());
            }
        } else {
            $this->error('主题不存在');
        }
    }
}
