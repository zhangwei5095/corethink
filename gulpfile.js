// gulp 安裝命令
// @author     Jason 封仁杰 <solidzoro@live.com>
// @lastdate   2015-08-05 15:51:03
//
// npm install gulp autoprefixer strftime gulp-less gulp-autoprefixer gulp-concat gulp-jade gulp-jshint gulp-uglify gulp-jshint gulp-coffee gulp-header gulp-rename gulp-sourcemaps gulp-minify-css gulp-livereload gulp-watch --save-dev



//
//
// init module
// --------------------------------------------
var gulp = require('gulp');
var less = require('gulp-less');
// var minifyCSS = require('gulp-minify-css');
// var sourcemaps = require('gulp-sourcemaps');
var prefix = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var strftime = require('strftime');
var header = require('gulp-header');
var rename = require('gulp-rename');
var livereload = require('gulp-livereload');
// var jsincluder = require('gulp-jsincluder');



//
//
// mod module
// --------------------------------------------
var gulp_date_now = strftime('%F %T'); // mod current time
var gulp_comment_banner = '/*! * <%= date %> */\n\n'; // mod ASCII banner



//
//
// ADMIN
// --------------------------------------------
var ADMIN = {
    ROOT: 'Application/Admin',
    VIEW: 'Application/Admin/View',
    PUBLIC: 'Public/Admin',
    STATIC: 'Public/Static'
};

// SCRIPT ADMIN
var admin_script_files = [];

gulp.task('admin_script_module', function() {
    gulp
        .src(admin_script_files)
        .pipe(concat('app_admin_source.js'))
        .pipe(gulp.dest(ADMIN.PUBLIC + '/js/'))
        .pipe(uglify()).pipe(rename('app_admin.js'))
        .pipe(header(gulp_comment_banner, {
            date: gulp_date_now
        }))
        .pipe(gulp.dest(ADMIN.PUBLIC + '/js/'));
});

// STYLE ADMIN
var admin_style_files = [];

gulp.task('admin_style_module', function() {
    gulp
        .src(admin_style_files)
        .pipe(less())
        .pipe(prefix())
        .pipe(rename('style_admin.css'))
        .pipe(header(gulp_comment_banner, {
            date: gulp_date_now
        }))
        .pipe(gulp.dest(ADMIN.PUBLIC + '/css/'))
        .pipe(livereload());
});



//
//
// WATCHING
// --------------------------------------------
gulp.task('watching', function() {

    // TASK
    gulp.watch(admin_script_files, ['admin_script_module']);
    gulp.watch(ADMIN.ROOT + '**/*.less', ['admin_style_module']);

    // LIVERELOAD
    livereload.listen();

    gulp.watch([
        'index.php',
        ADMIN.ROOT + '**/*.php',
        ADMIN.ROOT + '**/*.less',
    ], function(event) {
        livereload.changed(event.path);
    });
});



//
//
// RUNING
// --------------------------------------------
gulp.task('default', [
    // 後端
    'admin_script_module',
    'admin_style_module',
    // 監聽
    'watching'
]);