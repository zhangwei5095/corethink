$(function(){
    // 一次性初始化所有弹出框
    $('[data-toggle="popover"]').popover();

    // 设置ct-tab的宽度
    $('.ct-tab').width($(window).width()-373);

    // 图片lazyload
    $('img.lazy').lazyload({
        effect         : 'fadeIn',
        data_attribute : 'lazy',
        placeholder    : $('#corethink_home_img').val()+'/logo/default.gif'
    });

    // 打开新Tab
    $('body').delegate('#sidebar .open-tab', 'click', function() {
        var tab_url   = $(this).attr('href');
        var tab_name  = $(this).attr('tab-name');
        var is_open   = $('.ct-tab-content #' + tab_name).length;
        if(is_open !== 0){
            $('.ct-tab a[href=#' + tab_name + ']').tab('show');
        } else {
            var tab  = '<li class="new-add" style="position: relative;float:left;display: inline-block;"><a href="#'
                     + tab_name
                     + '" role="tab" data-toggle="tab">'
                     + $(this).html()
                     + '<button type="button" class="close" aria-label="Close">'
                     + '<span aria-hidden="true">&times;</span></button></a></li>';
            var tab_content = '<div role="tabpanel" class="new-add tab-pane fade" id="'
                            + tab_name
                            + '"><iframe name="#'
                            + tab_name
                            + '" class="iframe" src="'
                            + tab_url
                            +'"></iframe></div>';
            $('.ct-tab').width($('.ct-tab').width() + 60);
            $('.ct-tab').append(tab);
            $('.ct-tab-content').append(tab_content);
            $('.ct-tab a:last').tab('show');
            $(this).closest('li').addClass('active');
        }
        return false;
    });

    // 关闭标签时自动取消左侧导航的active状态
    $('body').delegate('.nav-close .close', 'click', function() {
        var id  = $(this).closest('a[data-toggle="tab"]').attr('href');
        var tab = id.split('#');
        $('a[tab-name=' + tab[1] + ']').closest('li').removeClass('active');
    });

    // 关闭所有标签
    $('body').delegate('.close-all', 'click', function() {
        $('.new-add').remove();
        $('.ct-tab a:first').tab('show');
    });

    // 双击刷新标签
    $('body').delegate('.ct-tab a', 'dblclick', function() {
        var id = $(this).attr('href');
        $(id+' .iframe').attr('src', $(id+' .iframe').attr('src'));
    });

    // TAB向左滚动
    $('body').delegate('#tab-left', 'click', function() {
        var left = $('.ct-tab').position().left;
        if (left < 0) {
            $('.ct-tab').animate({left:(left+480+'px')});
        }
    });

    // TAB向右滚动
    $('body').delegate('#tab-right', 'click', function() {
        var left = $('.ct-tab').position().left;
        if(($(window).width()-373)-(left+$('.ct-tab').width()) < 0){
            $('.ct-tab').animate({left:(left-480+'px')});
        }
    });

    // 检测更新
    if ($('.ct-update').length != 0) {
        // 检测更新
        $.ajax({
            url: $('input[name="check_version_url"]').val(),
            type: 'GET',
        }).done(function(data) {console.log();
            if (data.status == 1) {
                $('.version').html(data.info);
                if (data.sn_info) {
                    $('.sn_info').html(data.sn_info);
                }
            } else {
                $.alertMessager(data.info, 'danger');
            }
        });
    }
});
