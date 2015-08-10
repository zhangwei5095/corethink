// gulp 安裝命令
// @author   Jason 封仁杰 <solidzoro@live.com>
// @lastdate 2015-08-05 15:51:03
//
// npm install gulp autoprefixer strftime gulp-less gulp-autoprefixer gulp-concat gulp-jade gulp-jshint gulp-uglify gulp-jshint gulp-coffee gulp-header gulp-rename gulp-sourcemaps gulp-minify-css gulp-imports gulp-livereload gulp-imports gulp-watch --save-dev



//
//
// 初始化gulp插件
// --------------------------------------------
var gulp = require('gulp');
var less = require('gulp-less');
var minifyCss = require('gulp-minify-css');
var sourcemaps = require('gulp-sourcemaps');
var prefix = require('gulp-autoprefixer');
// var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var strftime = require('strftime');
var header = require('gulp-header');
var rename = require('gulp-rename');
var livereload = require('gulp-livereload');
var imports = require('gulp-imports');



//
//
// mod module
// --------------------------------------------
var gulp_date_now = strftime('%F %T'); // mod current time
var gulp_comment_banner = '/*! ---- * <%= date %> ---- */\n\n'; // mod ASCII banner



//
//
// 编译压缩后台相关样式与脚本
// --------------------------------------------
var ADMIN = {
    ROOT: 'Application/Admin',
    GULP: 'Application/Admin/View/_Resource/gulp/'
};

// 加载后台所有需要压缩js文件
var admin_script_files = [
    ADMIN.GULP + "admin.js",
];

// 压缩并在/Public/js下生成后台admin.min.js文件
gulp.task('admin_script_module', function() {
    gulp
        .src(admin_script_files)
        .pipe(imports())
        .pipe(header(gulp_comment_banner, {
            date: gulp_date_now
        }))
        .pipe(gulp.dest('Public/js/'))
        .pipe(uglify())
        .pipe(header(gulp_comment_banner, {
            date: gulp_date_now
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('Public/js/'))
        .pipe(livereload()); // 动态加载，有文件变动即自动重新编译压缩
});


// 加载后台所有需要编译的less或者css文件
var admin_style_files = [
    ADMIN.GULP + "admin.less",
];

// 压缩并在/Public/css下生成后台admin.min.css文件
gulp.task('admin_style_module', function() {
    gulp
        .src(admin_style_files)
        .pipe(less())
        .pipe(prefix('last 2 version', 'ie 8', 'ie 9'))
        .pipe(sourcemaps.init())
        .pipe(rename('admin.css'))
        .pipe(header(gulp_comment_banner, {
            date: gulp_date_now
        }))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('Public/css/'))
        .pipe(minifyCss())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('Public/css/'))
        .pipe(livereload()); // 动态加载，有文件变动即自动重新编译压缩
});



//
//
// 编译压缩前台相关样式与脚本
// --------------------------------------------
var HOME = {
    ROOT: 'Application/Home/',
    GULP: 'Application/Home/View/default/_Resource/gulp/'
};

// 加载前台所有需要压缩js文件
var home_script_files = [
    HOME.GULP + "home.js",
];

// 压缩并在/Public/js下生成后台home.min.js文件
gulp.task('home_script_module', function() {
    gulp
        .src(home_script_files)
        .pipe(imports())
        .pipe(header(gulp_comment_banner, {
            date: gulp_date_now
        }))
        .pipe(gulp.dest('Public/js/'))
        .pipe(uglify())
        .pipe(header(gulp_comment_banner, {
            date: gulp_date_now
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('Public/js/'))
        .pipe(livereload()); // 动态加载，有文件变动即自动重新编译压缩
});


// 加载前台所有需要编译的less或者css文件
var home_style_files = [
    HOME.GULP + "home.less",
];

// 压缩并在/Public/css下生成后台admin.min.css文件
gulp.task('home_style_module', function() {
    gulp
        .src(home_style_files)
        .pipe(less())
        .pipe(prefix('last 2 version', 'ie 8', 'ie 9'))
        .pipe(sourcemaps.init())
        .pipe(rename('home.css'))
        .pipe(header(gulp_comment_banner, {
            date: gulp_date_now
        }))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('Public/css/'))
        .pipe(minifyCss())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('Public/css/'))
        .pipe(livereload()); // 动态加载，有文件变动即自动重新编译压缩
});



//
//
// 监听，有文件变动即自动重新编译压缩
// --------------------------------------------
gulp.task('watching', function() {

    // TASK
    gulp.watch(admin_script_files, ['admin_script_module']);
    gulp.watch([
        ADMIN.ROOT + '/**/*.less',
        'Application/Common/**/*.less',
        'Public/libs/**/*.less'
    ], ['admin_style_module']);

    gulp.watch(home_script_files, ['home_script_module']);
    gulp.watch([
        HOME.ROOT + '/**/*.less',
        'Application/Common/**/*.less',
        'Public/libs/**/*.less'
    ], ['home_style_module']);

    // LIVERELOAD
    livereload.listen();
    gulp.watch([
        'index.php',
        ADMIN.ROOT + '/**/*.less',
        ADMIN.ROOT + '/**/*.js',
        HOME.ROOT + '/**/*.less',
        HOME.ROOT + '/**/*.js',
        'Application/Common/**/*.less',
        'Application/Common/**/*.js',
        'Public/libs/**/*.less',
        'Public/libs/**/*.js'
    ], function(event) {
        livereload.changed(event.path);
    });
});



//
//
// 运行
// --------------------------------------------
gulp.task('default', [
    // 后台
    'admin_script_module',
    'admin_style_module',
    // 前台
    'home_script_module',
    'home_style_module',
    // 监听
    'watching'
]);
