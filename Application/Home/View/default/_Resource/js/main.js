$(function(){
    $('img.lazy').lazyload({effect: 'fadeIn'}); //图片lazyload
    $('[data-toggle="popover"]').popover(); //一次性初始化所有弹出框
});
