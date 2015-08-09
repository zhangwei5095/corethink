// gulp 安裝命令
// @author   Jason 封仁杰 <solidzoro@live.com>
// @lastdate 2015-08-05 15:51:03
//
// npm install gulp autoprefixer strftime gulp-less gulp-autoprefixer gulp-concat gulp-jade gulp-jshint gulp-uglify gulp-jshint gulp-coffee gulp-header gulp-rename gulp-sourcemaps gulp-minify-css gulp-imports gulp-livereload gulp-imports gulp-watch --save-dev



//
//
// init module
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
// ADMIN
// --------------------------------------------
var ADMIN = {
    ROOT: 'Application/Admin',
    VIEW: 'Application/Admin/View',
};

// SCRIPT ADMIN
var admin_script_files = [
    ADMIN.VIEW + "/_Resource/js/admin.js",
];

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
        .pipe(gulp.dest('Public/js/'));
});

// STYLE ADMIN
var admin_style_files = [
    ADMIN.VIEW + "/_Resource/less/admin.less",
];

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
        .pipe(livereload());
});



//
//
// 监听
// --------------------------------------------
gulp.task('watching', function() {

    // TASK
    gulp.watch(admin_script_files, ['admin_script_module']);
    gulp.watch([
        ADMIN.ROOT + '/**/*.less',
        'Application/Common/**/*.less',
        'Public/libs/**/*.less'
    ], ['admin_style_module']);

    // LIVERELOAD
    livereload.listen();
    gulp.watch([
        'index.php',
        ADMIN.ROOT + '/**/*.php',
        ADMIN.ROOT + '/**/*.less',
        'Application/Common/**/*.less',
        'Public/libs/**/*.less',
    ], function(event) {
        livereload.changed(event.path);
    });
});



//
//
// 运行
// --------------------------------------------
gulp.task('default', [
    // 后端
    'admin_script_module',
    'admin_style_module',
    // 监听
    'watching'
]);