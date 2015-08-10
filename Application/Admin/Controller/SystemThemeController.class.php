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
 * 主题控制器
 * @author jry <598821125@qq.com>
 */
class SystemThemeController extends AdminController{
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index(){
        $data_list = D('SystemTheme')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->getAll();
        $page = new \Common\Util\Page(D('SystemTheme')->count(), C('ADMIN_PAGE_ROWS'));

        //使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('主题列表') //设置页面标题
                ->addTableColumn('name', '名称')
                ->addTableColumn('title', '标题')
                ->addTableColumn('description', '描述')
                ->addTableColumn('developer', '开发者')
                ->addTableColumn('version', '版本')
                ->addTableColumn('ctime', '创建时间', 'date')
                ->addTableColumn('status', '状态')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->display();
    }

    /**
     * 安装主题
     * @author jry <598821125@qq.com>
     */
    public function install($name){
        //获取当前主题信息
        $config_file = realpath(APP_PATH.'Home/View/'.$name).'/'.D('SystemTheme')->install_file();
        if(!$config_file){
            $this->error('安装失败');
        }
        $config = include $config_file;
        $data = $config['info'];
        if($config['config']){
            $data['config'] = json_encode($config['config']);
        }

        //写入数据库记录
        $system_theme_object = D('SystemTheme');
        $data = $system_theme_object->create($data);
        if($data){
            $id = $system_theme_object->add();
            if($id){
                $this->success('安装成功', U('index'));
            }else{
                $this->error('安装失败');
            }
        }else{
            $this->error($system_theme_object->getError());
        }
    }

    /**
     * 卸载主题
     * @author jry <598821125@qq.com>
     */
    public function uninstall($id){
        //当前主题禁止卸载
        $system_theme_object = D('SystemTheme');
        $theme_info = $system_theme_object->find($id);
        if($theme_info['current'] === '1'){
            $this->error('我是当前主题禁止被卸载');
        }

        //只剩一个主题禁止卸载
        $count = $system_theme_object->count();
        if($count > 1){
            $result = $system_theme_object->delete($id);
            if($result){
                $this->success('卸载成功！');
            }else{
                $this->error('卸载失败', $system_theme_object->getError());
            }
        }else{
            $this->error('只剩一个主题禁止卸载');
        }
    }
    /**
     * 更新主题信息
     * @author jry <598821125@qq.com>
     */
    public function updateInfo($id){
        $system_theme_object = D('SystemTheme');
        $name = $system_theme_object->getFieldById($id, 'name');
        $config_file = realpath(APP_PATH.'Home/View/'.$name).'/'.D('SystemTheme')->install_file();
        if(!$config_file){
            $this->error('不存在安装文件');
        }
        $config = include $config_file;
        $data = $config['info'];
        if($config['config']){
            $data['config'] = json_encode($config['config']);
        }
        $data['id'] = $id;
        $data = $system_theme_object->create($data);
        if($data){
            $id = $system_theme_object->save();
            if($id){
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败');
            }
        }else{
            $this->error($system_theme_object->getError());
        }
    }

    /**
     * 切换主题
     * @author jry <598821125@qq.com>
     */
    public function setCurrent($id){
        $system_theme_object = D('SystemTheme');
        $theme_info = $system_theme_object->find($id);
        if($theme_info){
            //当前主题current字段置为1
            $map['id'] = array('eq', $id);
            $result1 = $system_theme_object->where($map)->setField('current', 1);
            if($result1){
                //其它主题current字段置为0
                $con['id'] = array('neq', $id);
                $result2 = $system_theme_object->where($con)->setField('current', 0);
                if($result2){
                    $this->success('前台主题设置成功！');
                }else{
                    $this->error('设置当前主题失败', $system_theme_object->getError());
                }
            }else{
                $this->error('设置当前主题失败', $system_theme_object->getError());
            }
        }else{
            $this->error('主题不存在');
        }
    }
}
