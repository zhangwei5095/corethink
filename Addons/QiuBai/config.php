<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <ijry@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
return array(
    'title'=>array(
        'title'=>'显示标题:',
        'type'=>'text',
        'value'=>'糗事百科',
    ),
    'display'=>array(
        'title'=>'是否显示:',
        'type'=>'radio',
        'options'=>array(
            '1'=>'显示',
            '0'=>'不显示'
        ),
        'value'=>'1'
    ),
    'cache_time'=>array(
        'title'=>'缓存采集时间:',
        'type'=>'text',
        'value'=>'60',
        'tip'=>'（单位 秒）'
    )
);
