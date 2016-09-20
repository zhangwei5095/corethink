/* 扩展OpenCMF对象 */
(function($){
    /**
     * 获取OpenCMF基础配置
     * @type {object}
     */
    var OpenCMF = window.OpenCMF;

    /* 基础对象检测 */
    OpenCMF || $.error("OpenCMF基础配置没有正确加载！");

    /**
     * 解析URL
     * @param  {string} url 被解析的URL
     * @return {object}     解析后的数据
     */
    OpenCMF.parse_url = function(url){
        var parse = url.match(/^(?:([a-z]+):\/\/)?([\w-]+(?:\.[\w-]+)+)?(?::(\d+))?([\w-\/]+)?(?:\?((?:\w+=[^#&=\/]*)?(?:&\w+=[^#&=\/]*)*))?(?:#([\w-]+))?$/i);
        parse || $.error("url格式不正确！");
        return {
            "scheme"   : parse[1],
            "host"     : parse[2],
            "port"     : parse[3],
            "path"     : parse[4],
            "query"    : parse[5],
            "fragment" : parse[6]
        };
    }

    OpenCMF.parse_str = function(str){
        var value = str.split("&"), vars = {}, param;
        for(val in value){
            param = value[val].split("=");
            vars[param[0]] = param[1];
        }
        return vars;
    }

    OpenCMF.parse_name = function(name, type){
        if(type){
            /* 下划线转驼峰 */
            name.replace(/_([a-z])/g, function($0, $1){
                return $1.toUpperCase();
            });

            /* 首字母大写 */
            name.replace(/[a-z]/, function($0){
                return $0.toUpperCase();
            });
        } else {
            /* 大写字母转小写 */
            name = name.replace(/[A-Z]/g, function($0){
                return "_" + $0.toLowerCase();
            });

            /* 去掉首字符的下划线 */
            if(0 === name.indexOf("_")){
                name = name.substr(1);
            }
        }
        return name;
    }

    //scheme://host:port/path?query#fragment
    OpenCMF.U = function(url, vars, suffix){
        var info = this.parse_url(url), path = [], param = {}, reg;

        /* 验证info */
        info.path || $.error("url格式错误！");
        url = info.path;

        /* 组装URL */
        if(0 === url.indexOf("/")){ //路由模式
            this.MODEL[0] == 0 && $.error("该URL模式不支持使用路由!(" + url + ")");

            /* 去掉右侧分割符 */
            if("/" == url.substr(-1)){
                url = url.substr(0, url.length -1)
            }
            url = ("/" == this.DEEP) ? url.substr(1) : url.substr(1).replace(/\//g, this.DEEP);
            url = "/" + url;
        } else { //非路由模式
            /* 解析URL */
            path = url.split("/");
            path = [path.pop(), path.pop(), path.pop()].reverse();
            path[1] || $.error("OpenCMF.U(" + url + ")没有指定控制器");

            if(path[0]){
                param[this.VAR[0]] = this.MODEL[1] ? path[0].toLowerCase() : path[0];
            }

            param[this.VAR[1]] = this.MODEL[1] ? this.parse_name(path[1]) : path[1];
            param[this.VAR[2]] = path[2].toLowerCase();

            url = "?" + $.param(param);
        }

        /* 解析参数 */
        if(typeof vars === "string"){
            vars = this.parse_str(vars);
        } else if(!$.isPlainObject(vars)){
            vars = {};
        }

        /* 解析URL自带的参数 */
        info.query && $.extend(vars, this.parse_str(info.query));

        if(vars){
            url += "&" + $.param(vars);
        }

        if(0 != this.MODEL[0]){
            url = url.replace("?" + (path[0] ? this.VAR[0] : this.VAR[1]) + "=", "/")
                     .replace("&" + this.VAR[1] + "=", this.DEEP)
                     .replace("&" + this.VAR[2] + "=", this.DEEP)
                     .replace(/(\w+=&)|(&?\w+=$)/g, "")
                     .replace(/[&=]/g, this.DEEP);

            /* 添加伪静态后缀 */
            if(false !== suffix){
                suffix = suffix || this.MODEL[2].split("|")[0];
                if(suffix){
                    url += "." + suffix;
                }
            }
        }

        url = this.APP + url;
        return url;
    }

    /* 设置表单的值 */
    OpenCMF.setValue = function(name, value){
        var first = name.substr(0,1), input, i = 0, val;
        if(value === "") return;
        if("#" === first || "." === first){
            input = $(name);
        } else {
            input = $("[name='" + name + "']");
        }

        if(input.eq(0).is(":radio")) { //单选按钮
            input.filter("[value='" + value + "']").each(function(){this.checked = true});
        } else if(input.eq(0).is(":checkbox")) { //复选框
            if(!$.isArray(value)){
                val = new Array();
                val[0] = value;
            } else {
                val = value;
            }
            for(i = 0, len = val.length; i < len; i++){
                input.filter("[value='" + val[i] + "']").each(function(){this.checked = true});
            }
        } else {  //其他表单选项直接设置值
            input.val(value);
        }
    }
})(jQuery);


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

    // 一次性初始化所有弹出框
    $('[data-toggle="popover"]').popover();

    // 图片lazyload
    $('img.lazy').lazyload({
        effect         : 'fadeIn',
        data_attribute : 'src',
        placeholder    : $('#corethink_home_img').val()+'/default/default.gif'
    });

    // 刷新验证码
    $("body").delegate('.reload-verify', 'click', function() {
        var verifyimg = $(this).attr("src");
        if (verifyimg.indexOf('?') > 0) {
            $(this).attr("src", verifyimg + '&random=' + Math.random());
        } else {
            $(this).attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
        }
    });

    //全选/反选/单选的实现
    $('body').delegate('.check-all', 'click', function() {
        $(".ids").prop("checked", this.checked);
    });

    $('body').delegate('.ids', 'click', function() {
        var option = $(".ids");
        option.each(function() {
            if (!this.checked) {
                $(".check-all").prop("checked", false);
                return false;
            } else {
                $(".check-all").prop("checked", true);
            }
        });
    });

    //搜索功能
    $('body').delegate('.search-btn', 'click', function() {
        var url = $(this).closest('form').attr('action');
        var query = $(this).closest('form').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g, '');
        query = query.replace(/(^&)|(\+)/g, '');
        if (url.indexOf('?') > 0) {
            url += '&' + query;
        } else {
            url += '?' + query;
        }
        window.location.href = url;
        return false;
    });

    //回车搜索
    $('body').delegate('.search-input', 'keydown', function(e) {
        if (e.keyCode === 13) {
            $(this).closest('form').find('.search-btn').click();
            return false;
        }
    });


    // 多条件筛选
    $('body').delegate('a.query-link', 'click', function() {
        var url = window.location.href;
        var data_name = $(this).attr('data-name');
        var data_value = $(this).attr('data-value');
        url = change_url_parameter(url, data_name, data_value);
        window.location.href = url;
        return false;
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
