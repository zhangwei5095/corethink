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
        $builder->title('主题列表')  //设置页面标题
                ->addField('name', '名称', 'text')
                ->addField('title', '标题', 'text')
                ->addField('description', '描述', 'text')
                ->addField('developer', '开发者', 'text')
                ->addField('version', '版本', 'text')
                ->addField('ctime', '创建时间', 'date')
                ->addField('status', '状态', 'text')
                ->addField('right_button', '操作', 'btn')
                ->dataList($data_list) //数据列表
                ->setPage($page->show())
                ->display();
    }

    /**
     * 安装主题
     * @author jry <598821125@qq.com>
     */
    public function install($name){
        //获取当前主题信息
        $config_file = realpath(APP_PATH.'Home/View/'.$name).'/corethink.php';
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
     * 更新主题信息
     * @author jry <598821125@qq.com>
     */
    public function updateInfo($id){
        $system_theme_object = D('SystemTheme');
        $name = $system_theme_object->getFieldById($id, 'name');
        $config_file = realpath(APP_PATH.'Home/View/'.$name).'/corethink.php';
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
     * 设置一条或者多条数据的状态
     * @author jry <598821125@qq.com>
     */
    public function setStatus($model = CONTROLLER_NAME){
        $ids    = I('request.ids');
        $status = I('request.status');
        if(empty($ids)){
            $this->error('请选择要操作的数据');
        }
        $map['id'] = array('eq',$ids);
        switch($status){
            case 'uninstall' : //卸载
                //当前主题禁止卸载
                $theme_info = D($model)->where($map)->find();
                if($theme_info['current'] === '1'){
                    $this->error('我是当前主题禁止被卸载');
                }

                //只剩一个主题禁止卸载
                $count = D($model)->count();
                if($count > 1){
                    $result = D($model)->where($map)->delete();
                    if($result){
                        $this->success('卸载成功！');
                    }else{
                        $this->error('卸载失败');
                    }
                }else{
                    $this->error('只剩一个主题禁止卸载');
                }
                break;
            case 'current' : //设为当前主题
                $theme_info = D($model)->where($map)->find();
                if($theme_info){
                    //当前主题current字段置为1
                    $result1 = D($model)->where($map)->setField('current', 1);
                    if($result1){
                        //其它主题current字段置为0
                        $con['id'] = array('neq', $ids);
                        $result2 = D($model)->where($con)->setField('current', 0);
                        if($result2){
                            $this->success('前台主题设置成功！');
                        }else{
                            $this->error('设置当前主题失败');
                        }
                    }else{
                        $this->error('设置当前主题失败');
                    }
                }else{
                    $this->error('主题不存在');
                }
                break;
            default :
                parent::setStatus($model);
                break;
        }
    }
}
