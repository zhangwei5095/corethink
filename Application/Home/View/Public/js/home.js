$(function(){
    // iOS中WebAPP状态下点击链接会跳转到Safari浏览器新标签页的问题
    if (("standalone" in window.navigator) && window.navigator.standalone) {
        var noddy, remotes = false;
        document.addEventListener('click',
            function(event) {
                noddy = event.target;
                while (noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
                    noddy = noddy.parentNode;
                }
                if ('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes)) {
                    event.preventDefault();
                    document.location.href = noddy.href;
                }
            },
            false
        );
    }

    // 多条件筛选
    $('body').delegate('a.query-link', 'click', function() {
        var url = window.location.href;
        var data_name = $(this).attr('data-name');
        var data_value = $(this).attr('data-value');
        url = change_url_parameter(url, data_name, data_value);
        window.location.href = url;
    });

    // 一次性初始化所有弹出框
    $('[data-toggle="popover"]').popover();

    // 图片lazyload
    $('img.lazy').lazyload({
        effect         : 'fadeIn',
        data_attribute : 'lazy',
        placeholder    : $('#corethink_home_img').val()+'/logo/default.gif'
    });
});

/*
* 改变URL参数
* url 目标url
* arg 需要替换的参数名称
* arg_val 替换后的参数的值
* return url 参数替换后的url
*/
function change_url_parameter(destiny, par, par_value) {
    var pattern = par+'=([^&]*)';
    var replaceText = par+'='+par_value;
    if (destiny.match(pattern)) {
        var tmp='/('+ par+'=)([^&]*)/gi';
        tmp = destiny.replace(eval(tmp), replaceText);
        return (tmp);
    } else {
        if (destiny.match('[\?]')) {
            return destiny+'&'+ replaceText;
        } else {
            return destiny+'?'+replaceText;
        }
    }
    return destiny+'\n'+par+'\n'+par_value;
}
