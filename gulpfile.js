// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
//
//
// 这是gulp的配置文件，这个文件决定了使用gulp时会对哪些less、js进行压缩编译处理
// gulpjs是一个前端构建工具，与gruntjs相比，gulpjs无需写一大堆繁杂的配置参数，API也非常简单，
// 学习起来很容易，而且gulpjs使用的是nodejs中stream来读取和操作数据，其速度更快。如果你还没有
// 使用过前端构建工具，或者觉得gruntjs太难用的话，那就尝试一下gulp吧。
//
//
//
// 安装并使用gulp
// 1、首先要安装node.js
// 2、将gulp安装到全局环境下，让你可以存取gulp的CLI。
//    $ npm install gulp -g
// 3、接著，需要在项目中进行安装
//    $ npm install gulp --save-dev
// 4、安装gulp插件以完成各种任务
//    $ sudo npm install gulp strftime gulp-less gulp-minify-css gulp-sourcemaps gulp-autoprefixer gulp-uglify  gulp-header gulp-rename gulp-livereload gulp-imports gulp-watch --save-dev
// 5、开启gulp大法
//    $ cd [你的项目目录]
//    $ gulp
//
//
//
// 初始化需要用的插件
// 呼！看起来比Grunt有更多的事要做，对吧？
// Gulp插件跟Grunt插件有些许差异–它被设计成做一件事并且做好一件事。
// 例如；Grunt的imagemin利用快取来避免重複压缩已经压缩好的图片。
// 在Gulp中，这必须透过一个快取插件来达成，当然，快取插件也可以拿来快取其他东西。
// 这让建构过程中增加了额外的弹性层面。蛮酷的，哼？
// --------------------------------------------
var gulp       = require('gulp');
var strftime   = require('strftime');
var less       = require('gulp-less');
var minifyCss  = require('gulp-minify-css');
var sourcemaps = require('gulp-sourcemaps');
var prefix     = require('gulp-autoprefixer');
var imports    = require('gulp-imports');
var uglify     = require('gulp-uglify');
var header     = require('gulp-header');
var rename     = require('gulp-rename');
var livereload = require('gulp-livereload');
//
//
//
// 定义项目相关路径
// --------------------------------------------
var CORETHINK = {
    APP  : 'Application/',
    PUB  : 'Public/',
    LIB  : 'Public/libs/',
    CSS  : 'Public/css/',
    JS   : 'Public/js/',
    GULP : 'Application/Admin/View/_Resource/gulp/',
    COMMON : {
        ROOT : 'Application/Common/'
    },
    ADMIN : {
        ROOT : 'Application/Admin/',
        GULP : 'Application/Admin/View/_Resource/gulp/'
    },
    HOME : {
        ROOT : 'Application/Home/',
        GULP : 'Application/Home/View/default/_Resource/gulp/'
    }
};
//
//
//
var corethink_admin_script_files = [CORETHINK.ADMIN.GULP + "admin.js"];    //加载后台所有需要压缩js文件
var corethink_admin_style_files  = [CORETHINK.ADMIN.GULP + "admin.less"];  //加载后台所有需要编译的less或者css文件
var corethink_home_script_files  = [CORETHINK.HOME.GULP  + "home.js"];     //加载前台所有需要压缩js文件
var corethink_home_style_files   = [CORETHINK.HOME.GULP  + "home.less"];   //加载前台所有需要编译的less或者css文件
var banner = '/*! ---- builder on ' + strftime('%F %T') + ' by corethink ---- */\n\n'; //包含当前时间的附加头部信息
//
//
//
// 压缩并在/Public/js下生成后台admin.min.js文件
gulp.task('corethink_admin_script_task', function() {
    gulp
        .src(corethink_admin_script_files) //设置需要处理的js文件列表
        .pipe(imports())                   //导入需要处理的js文件
        .pipe(header(banner))              //在文件开始添加编译信息
        .pipe(gulp.dest(CORETHINK.JS))     //输出未压缩版本至该目录下
        .pipe(uglify())                    //代码混淆
        .pipe(rename({suffix: '.min'}))    //产生一个压缩版的.min版本
        .pipe(gulp.dest(CORETHINK.JS))     //输出压缩版的.min版本至该目录下
        .pipe(livereload());               //动态加载，有文件变动即自动重新编译压缩
});
//
// 压缩并在/Public/css下生成后台admin.min.css文件
gulp.task('corethink_admin_style_task', function() {
    gulp
        .src(corethink_admin_style_files) //设置需要处理的样式文件列表
        .pipe(less())                     //编译less为css
        .pipe(prefix('last 2 version', 'ie 8', 'ie 9')) //自动加上css3前缀
        .pipe(sourcemaps.init())          //调用gulp-sourcemaps的初始化api来处理接收的文件流（方便后续编译出.map文件）
        .pipe(rename('admin.css'))        //重新命名
        .pipe(header(banner))             //在文件开始添加编译时间等信息
        .pipe(sourcemaps.write())         //调用gulp-sourcemaps的写入api，额外输出.map文件流
        .pipe(gulp.dest(CORETHINK.CSS))   //输出未压缩版本至该目录下
        .pipe(minifyCss())                //产生一个压缩版的.min版本
        .pipe(rename({suffix: '.min'}))   //重新命名
        .pipe(gulp.dest(CORETHINK.CSS))   //输出压缩版的.min版本至该目录下
        .pipe(livereload());              //动态加载，有文件变动即自动重新编译压缩
});
//
//
//
// 压缩并在/Public/js下生成后台home.min.js文件
gulp.task('corethink_home_script_task', function() {
    gulp        
        .src(corethink_home_script_files) //设置需要处理的js文件列表
        .pipe(imports())                  //导入需要处理的js文件
        .pipe(header(banner))             //在文件开始添加编译时间等信息
        .pipe(gulp.dest(CORETHINK.JS))    //输出未压缩版本至该目录下
        .pipe(uglify())                   //代码混淆
        .pipe(rename({suffix: '.min'}))   //产生一个压缩版的.min版本
        .pipe(gulp.dest(CORETHINK.JS))    //输出压缩版的.min版本至该目录下
        .pipe(livereload());              //动态加载，有文件变动即自动重新编译压缩
});
//
// 压缩并在/Public/css下生成后台homen.min.css文件
gulp.task('corethink_home_style_task', function() {
    gulp
        .src(corethink_home_style_files) //设置需要处理的样式文件列表
        .pipe(less())                    //编译less为css
        .pipe(prefix('last 2 version', 'ie 8', 'ie 9')) //自动加上css3前缀
        .pipe(sourcemaps.init())         //调用gulp-sourcemaps的初始化api来处理接收的文件流（方便后续编译出.map文件）
        .pipe(rename('home.css'))        //重新命名
        .pipe(header(banner))            //在文件开始添加编译时间等信息
        .pipe(sourcemaps.write())        //调用gulp-sourcemaps的写入api，额外输出.map文件流
        .pipe(gulp.dest(CORETHINK.CSS))  //输出未压缩版本至该目录下
        .pipe(minifyCss())               //产生一个压缩版的.min版本
        .pipe(rename({suffix: '.min'}))  //重新命名
        .pipe(gulp.dest(CORETHINK.CSS))  //输出压缩版的.min版本至该目录下
        .pipe(livereload());             //动态加载，有文件变动即自动重新编译压缩
});
//
//
//
// 监听，有文件变动即自动重新编译压缩
// --------------------------------------------
gulp.task('watching', function() {
    //监控后台脚本
    gulp.watch([
        CORETHINK.COMMON.ROOT + '**/*.js',
        CORETHINK.ADMIN.ROOT  + '**/*.js',
        CORETHINK.LIB         + '**/*.js'
    ], ['corethink_admin_script_task']);

    //监控后台样式
    gulp.watch([
        CORETHINK.COMMON.ROOT + '**/*.*ss',
        CORETHINK.ADMIN.ROOT  + '**/*.*ss',
        CORETHINK.LIB         + '**/*.*ss',
    ], ['corethink_admin_style_task']);

    //监控前台脚本
    gulp.watch([
        CORETHINK.COMMON.ROOT + '**/*.js',
        CORETHINK.HOME.ROOT   + '**/*.js',
        CORETHINK.LIB         + '**/*.js'
    ], ['corethink_home_script_task']);

    //监控前台样式
    gulp.watch([
        CORETHINK.COMMON.ROOT + '**/*.*ss',
        CORETHINK.HOME.ROOT   + '**/*.*ss',
        CORETHINK.LIB         + '**/*.*ss',
    ], ['corethink_home_style_task']);

    livereload.listen();

    //文件变动监控，发现有文件变动即显示在控制台
    gulp.watch([
        CORETHINK.APP + '**/*.*ss',
        CORETHINK.APP + '**/*.js',
        CORETHINK.LIB + '**/*.*ss',
        CORETHINK.LIB + '**/*.js'
    ], function(event) {
        livereload.changed(event.path);
    });
});
//
//
//
// 预设任务
// 输入gulp命令时会直接执行的任务
// --------------------------------------------
gulp.task('default', [
    'corethink_admin_script_task',
    'corethink_admin_style_task',
    'corethink_home_script_task',
    'corethink_home_style_task',
    'watching'
]);
