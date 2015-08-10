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
/**
 * 功能模块控制器
 * @author jry <598821125@qq.com>
 */
class SystemModuleController extends AdminController{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index(){
        $data_list = D('SystemModule')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->getAll();
        $page = new \Common\Util\Page(D('SystemModule')->count(), C('ADMIN_PAGE_ROWS'));

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('模块列表') //设置页面标题
                ->addTopButton('resume')  //添加启用按钮
                ->addTopButton('forbid')  //添加禁用按钮
                ->setSearch('请输入ID/标题', U('index'))
                ->addTableColumn('name', '名称')
                ->addTableColumn('title', '标题')
                ->addTableColumn('description', '描述')
                ->addTableColumn('developer', '开发者')
                ->addTableColumn('version', '版本')
                ->addTableColumn('ctime', '创建时间', 'date')
                ->addTableColumn('status', '状态', 'text')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->display();
    }

    /**
     * 安装模块
     * @author jry <598821125@qq.com>
     */
    public function install($name){
        //获取当前模块信息
        $config_file = realpath(APP_PATH.$name).'/'.D('SystemModule')->install_file();
        if(!$config_file){
            $this->error('安装失败');
        }
        $config = include $config_file;
        $data = $config['info'];
        if($config['admin_menu']){
            $data['admin_menu'] = json_encode($config['admin_menu']);
        }

        //安装数据库
        $sql_status = execute_sql_from_file(realpath(APP_PATH.$name).'/Sql/install.sql');
        if($sql_status){
            //写入数据库记录
            $system_module_object = D('SystemModule');
            $data = $system_module_object->create($data);
            if($data){
                $id = $system_module_object->add();
                if($id){
                    $this->success('安装成功', U('index'));
                }else{
                    $this->error('安装失败');
                }
            }else{
                $this->error($system_module_object->getError());
            }
        }else{
            $sql_status = execute_sql_from_file(realpath(APP_PATH.$name).'/Sql/uninstall.sql');
            $this->error('安装失败');
        }
    }

    /**
     * 卸载模块
     * @author jry <598821125@qq.com>
     */
    public function uninstall($id){
        $system_module_object = D('SystemModule');
        $name = $system_module_object->where($map)->getFieldById($id, 'name');
        $result = $system_module_object->delete($id);
        if($result){
            $sql_status = execute_sql_from_file(realpath(APP_PATH.$name).'/Sql/uninstall.sql');
            if($sql_status){
                $this->success('卸载成功！');
            }
        }else{
            $this->error('卸载失败');
        }
    }

    /**
     * 更新模块信息
     * @author jry <598821125@qq.com>
     */
    public function updateInfo($id){
        $system_module_object = D('SystemModule');
        $name = $system_module_object->getFieldById($id, 'name');
        $config_file = realpath(APP_PATH.$name).'/'.D('SystemModule')->install_file();
        if(!$config_file){
            $this->error('不存在安装文件');
        }
        $config = include $config_file;
        $data = $config['info'];
        if($config['admin_menu']){
            $data['admin_menu'] = json_encode($config['admin_menu']);
        }
        $data['id'] = $id;
        $data = $system_module_object->create($data);
        if($data){
            $id = $system_module_object->save();
            if($id){
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败');
            }
        }else{
            $this->error($system_module_object->getError());
        }
    }
}
