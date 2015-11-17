<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think;
/**
 * ThinkPHP路由解析类
 */
class Route {
    
    // 路由检测
    public static function check(){
        $depr   =   C('URL_PATHINFO_DEPR');
        $regx   =   preg_replace('/\.'.__EXT__.'$/i','',trim($_SERVER['PATH_INFO'],$depr));
        // 分隔符替换 确保路由定义使用统一的分隔符
        if('/' != $depr){
            $regx = str_replace($depr,'/',$regx);
        }
        // URL映射定义（静态路由）
        $maps   =   C('URL_MAP_RULES');
        if(isset($maps[$regx])) {
            $var    =   self::parseUrl($maps[$regx]);
            $_GET   =   array_merge($var, $_GET);
            return true;                
        }        
        // 动态路由处理
        $routes =   C('URL_ROUTE_RULES');
        if(!empty($routes)) {
            foreach ($routes as $rule=>$route){
                if(is_numeric($rule)){
                    // 支持 array('rule','adddress',...) 定义路由
                    $rule   =   array_shift($route);
                }
                if(is_array($route) && isset($route[2])){
                    // 路由参数
                    $options    =   $route[2];
                    if(isset($options['ext']) && __EXT__ != $options['ext']){
                        // URL后缀检测
                        continue;
                    }
                    if(isset($options['method']) && REQUEST_METHOD != strtoupper($options['method'])){
                        // 请求类型检测
                        continue;
                    }
                    // 自定义检测
                    if(!empty($options['callback']) && is_callable($options['callback'])) {
                        if(false === call_user_func($options['callback'])) {
                            continue;
                        }
                    }                    
                }
                if(0===strpos($rule,'/') && preg_match($rule,$regx,$matches)) { // 正则路由
                    if($route instanceof \Closure) {
                        // 执行闭包
                        $result = self::invokeRegx($route, $matches);
                        // 如果返回布尔值 则继续执行
                        return is_bool($result) ? $result : exit;
                    }else{
                        return self::parseRegex($matches,$route,$regx);
                    }
                }else{ // 规则路由
                    $len1   =   substr_count($regx,'/');
                    $len2   =   substr_count($rule,'/');
                    if($len1>=$len2 || strpos($rule,'[')) {
                        if('$' == substr($rule,-1,1)) {// 完整匹配
                            if($len1 != $len2) {
                                continue;
                            }else{
                                $rule =  substr($rule,0,-1);
                            }
                        }
                        $match  =  self::checkUrlMatch($regx,$rule);
                        if(false !== $match)  {
                            if($route instanceof \Closure) {
                                // 执行闭包
                                $result = self::invokeRule($route, $match);
                                // 如果返回布尔值 则继续执行
                                return is_bool($result) ? $result : exit;
                            }else{
                                return self::parseRule($rule,$route,$regx);
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    // 检测URL和规则路由是否匹配
    private static function checkUrlMatch($regx,$rule) {
        $m1 = explode('/',$regx);
        $m2 = explode('/',$rule);
        $var = array();         
        foreach ($m2 as $key=>$val){
            if(0 === strpos($val,'[:')){
                $val    =   substr($val,1,-1);
            }
                
            if(':' == substr($val,0,1)) {// 动态变量
                if($pos = strpos($val,'|')){
                    // 使用函数过滤
                    $val   =   substr($val,1,$pos-1);
                }
                if(strpos($val,'\\')) {
                    $type = substr($val,-1);
                    if('d'==$type) {
                        if(isset($m1[$key]) && !is_numeric($m1[$key]))
                            return false;
                    }
                    $name = substr($val, 1, -2);
                }elseif($pos = strpos($val,'^')){
                    $array   =  explode('-',substr(strstr($val,'^'),1));
                    if(in_array($m1[$key],$array)) {
                        return false;
                    }
                    $name = substr($val, 1, $pos - 1);
                }else{
                    $name = substr($val, 1);
                }
                $var[$name] = isset($m1[$key])?$m1[$key]:'';
            }elseif(0 !== strcasecmp($val,$m1[$key])){
                return false;
            }
        }
        // 成功匹配后返回URL中的动态变量数组
        return $var;
    }

    // 解析规范的路由地址
    // 地址格式 [控制器/操作?]参数1=值1&参数2=值2...
    private static function parseUrl($url) {
        $var  =  array();
        if(false !== strpos($url,'?')) { // [控制器/操作?]参数1=值1&参数2=值2...
            $info   =  parse_url($url);
            $path   = explode('/',$info['path']);
            parse_str($info['query'],$var);
        }elseif(strpos($url,'/')){ // [控制器/操作]
            $path = explode('/',$url);
        }else{ // 参数1=值1&参数2=值2...
            parse_str($url,$var);
        }
        if(isset($path)) {
            $var[C('VAR_ACTION')] = array_pop($path);
            if(!empty($path)) {
                if (C('CONTROLLER_LEVEL') > 1 ){// 控制器层次
                    // 发现$paths的长度是偶数，说明URL省略了默认分级名，在这里加上
                    if (count($path) == 1 && C('DEFAULT_CONTROLLER_LEVEL')) {
                        array_unshift($path, C('DEFAULT_CONTROLLER_LEVEL'));
                    }
                    $var[C('VAR_CONTROLLER')] = implode('/', $path);
                } else {
                    $var[C('VAR_CONTROLLER')] = array_pop($path);
                }   
            }
            if(!empty($path)) {
                $var[C('VAR_MODULE')]  = array_pop($path);
            }
        }
        return $var;
    }

    // 解析规则路由
    // '路由规则'=>'[控制器/操作]?额外参数1=值1&额外参数2=值2...'
    // '路由规则'=>array('[控制器/操作]','额外参数1=值1&额外参数2=值2...')
    // '路由规则'=>'外部地址'
    // '路由规则'=>array('外部地址','重定向代码')
    // 路由规则中 :开头 表示动态变量
    // 外部地址中可以用动态变量 采用 :1 :2 的方式
    // 'news/:month/:day/:id'=>array('News/read?cate=1','status=1'),
    // 'new/:id'=>array('/new.php?id=:1',301), 重定向
    private static function parseRule($rule,$route,$regx) {
        // 获取路由地址规则
        $url   =  is_array($route)?$route[0]:$route;
        // 获取URL地址中的参数
        $paths = explode('/',$regx);
        // 解析路由规则
        $matches  =  array();
        $rule =  explode('/',$rule);
        foreach ($rule as $item){
            $fun    =   '';
            if(0 === strpos($item,'[:')){
                $item   =   substr($item,1,-1);
            }
            if(0===strpos($item,':')) { // 动态变量获取
                if($pos = strpos($item,'|')){ 
                    // 支持函数过滤
                    $fun  =  substr($item,$pos+1);
                    $item =  substr($item,0,$pos);                    
                }
                if($pos = strpos($item,'^') ) {
                    $var  =  substr($item,1,$pos-1);
                }elseif(strpos($item,'\\')){
                    $var  =  substr($item,1,-2);
                }else{
                    $var  =  substr($item,1);
                }
                $matches[$var] = !empty($fun)? $fun(array_shift($paths)) : array_shift($paths);
            }else{ // 过滤URL中的静态变量
                array_shift($paths);
            }
        }

        if(0=== strpos($url,'/') || 0===strpos($url,'http')) { // 路由重定向跳转
            if(strpos($url,':')) { // 传递动态参数
                $values = array_values($matches);
                $url = preg_replace_callback('/:(\d+)/', function($match) use($values){ return $values[$match[1] - 1]; }, $url);
            }
            header("Location: $url", true,(is_array($route) && isset($route[1]))?$route[1]:301);
            exit;
        }else{
            // 解析路由地址
            $var  =  self::parseUrl($url);
            // 解析路由地址里面的动态参数
            $values  =  array_values($matches);
            foreach ($var as $key=>$val){
                if(0===strpos($val,':')) {
                    $var[$key] =  $values[substr($val,1)-1];
                }
            }
            $var   =   array_merge($matches,$var);
            // 解析剩余的URL参数
            if(!empty($paths)) {
                preg_replace_callback('/(\w+)\/([^\/]+)/', function($match) use(&$var){ $var[strtolower($match[1])]=strip_tags($match[2]);}, implode('/',$paths));
            }
            // 解析路由自动传入参数
            if(is_array($route) && isset($route[1])) {
                if(is_array($route[1])){
                    $params     =   $route[1];
                }else{
                    parse_str($route[1],$params);
                }                
                $var   =   array_merge($var,$params);
            }
            $_GET   =  array_merge($var,$_GET);
        }
        return true;
    }

    // 解析正则路由
    // '路由正则'=>'[控制器/操作]?参数1=值1&参数2=值2...'
    // '路由正则'=>array('[控制器/操作]?参数1=值1&参数2=值2...','额外参数1=值1&额外参数2=值2...')
    // '路由正则'=>'外部地址'
    // '路由正则'=>array('外部地址','重定向代码')
    // 参数值和外部地址中可以用动态变量 采用 :1 :2 的方式
    // '/new\/(\d+)\/(\d+)/'=>array('News/read?id=:1&page=:2&cate=1','status=1'),
    // '/new\/(\d+)/'=>array('/new.php?id=:1&page=:2&status=1','301'), 重定向
    private static function parseRegex($matches,$route,$regx) {
        // 获取路由地址规则
        $url   =  is_array($route)?$route[0]:$route;
        $url   =  preg_replace_callback('/:(\d+)/', function($match) use($matches){return $matches[$match[1]];}, $url); 
        if(0=== strpos($url,'/') || 0===strpos($url,'http')) { // 路由重定向跳转
            header("Location: $url", true,(is_array($route) && isset($route[1]))?$route[1]:301);
            exit;
        }else{
            // 解析路由地址
            $var  =  self::parseUrl($url);
            // 处理函数
            foreach($var as $key=>$val){
                if(strpos($val,'|')){
                    list($val,$fun) = explode('|',$val);
                    $var[$key]    =   $fun($val);
                }
            }
            // 解析剩余的URL参数
            $regx =  substr_replace($regx,'',0,strlen($matches[0]));
            if($regx) {
                preg_replace_callback('/(\w+)\/([^\/]+)/', function($match) use(&$var){
                    $var[strtolower($match[1])] = strip_tags($match[2]);
                }, $regx);
            }
            // 解析路由自动传入参数
            if(is_array($route) && isset($route[1])) {
                if(is_array($route[1])){
                    $params     =   $route[1];
                }else{
                    parse_str($route[1],$params);
                }
                $var   =   array_merge($var,$params);
            }
            $_GET   =  array_merge($var,$_GET);
        }
        return true;
    }

    // 执行正则匹配下的闭包方法 支持参数调用
    static private function invokeRegx($closure, $var = array()) {
        $reflect = new \ReflectionFunction($closure);
        $params  = $reflect->getParameters();
        $args    = array();
        array_shift($var);
        foreach ($params as $param){
            if(!empty($var)) {
                $args[] = array_shift($var);
            }elseif($param->isDefaultValueAvailable()){
                $args[] = $param->getDefaultValue();
            }
        }
        return $reflect->invokeArgs($args);
    }

    // 执行规则匹配下的闭包方法 支持参数调用
    static private function invokeRule($closure, $var = array()) {
        $reflect = new \ReflectionFunction($closure);
        $params  = $reflect->getParameters();
        $args    = array();
        foreach ($params as $param){
            $name = $param->getName();
            if(isset($var[$name])) {
                $args[] = $var[$name];
            }elseif($param->isDefaultValueAvailable()){
                $args[] = $param->getDefaultValue();
            }
        }
        return $reflect->invokeArgs($args);
    }

    /**
     * 路由反向解析
     * @param  string $path  控制器/方法
     * @param  array $vars  url参数
     * @param  string $depr  分隔符
     * @param  string|true $suffix  url后缀
     * @return string|false
     */
    public static function reverse($path, &$vars, $depr, $suffix = true){
        $_rules = array();
        if ($rules = self::ruleCache()) {
            foreach ($rules as $i => $rules2) {
                foreach ($rules2 as $rule => $route) {
                    if (is_array($route) && is_string($route[0]) && '/' != substr($route[0], 0, 1)) {
                        $_rules[$i][$route[0]][$rule] = $route;
                    }
                }
            }
        }

        if (isset($_rules[0][$path])) { // 静态路由
            foreach ($_rules[0][$path] as $rule => $route) {
                $args = array_pop($route);
                if (count($vars) == count($args) && !empty($vars) && !array_diff($vars, $args)) {
                    return str_replace('/', $depr, $rule);
                }
            }
        }

        if (isset($_rules[1][$path])) {
            foreach ($_rules[1][$path] as $rule => $route) {
                $args = array_pop($route);
                $array = array();
                if (isset($route[2])) { // 路由参数检查
                    if (!checkOption($route[2], $suffix)) continue;
                }
                if ('/' != substr($rule, 0, 1)) { // 规则路由
                    foreach ($args as $key => $val) {
                        $flag = false;
                        if ($val[0] == 0) { // 静态变量值
                            $array[$key] = $key;
                            continue;
                        }
                        if (isset($vars[$key])) {
                            if (!empty($val[2])) { // 是否有过滤条件
                                if ($val[2] == 'int') {
                                    // 是否为数字
                                    if (!is_numeric($vars[$key]) || !is_int($vars[$key]+0)) break; 
                                } else {
                                    // 排除的名称
                                    if (in_array($vars[$key], $val[2])) break;
                                }
                            }
                            $flag = true;
                            $array[$key] = $vars[$key];
                        } elseif ($val[0] == 1) { // 如果是必选项
                            break; 
                        }
                    }
                    if (!empty($flag)) { // 匹配成功
                        foreach (array_keys($array) as $key) {
                            unset($vars[$key]);
                        }
                        return implode($depr, $array);
                    }
                } else { // 正则路由
                    $keys = !empty($args) ? array_keys($args) : array_keys($vars);
                    $str = preg_replace_callback('/\(.+\)/', function($match) use(&$vars, &$keys){ 
                        $k = array_shift($keys);
                        return isset($vars[$k]) ? $vars[$k] : ''; 
                    }, $rule);
                    $str = substr($str, 1, -1);
                    $str = rtrim(ltrim($str, '^'), '$');
                    $str = str_replace('\\', '', $str);
                    if (preg_match($rule, $str, $matches)) { // 匹配成功
                        if ($args) {
                            $keys = array_keys($args);
                        }
                        foreach ($keys as $key) {
                            unset($vars[$key]);
                        }
                        return str_replace('/', $depr, $str);
                    }
                }
            }
        }
        return false;
    }

    // 规则路由定义方法：
    // '路由规则'=>'[控制器/操作]?额外参数1=值1&额外参数2=值2...'
    // '路由规则'=>array('[控制器/操作]','额外参数1=值1&额外参数2=值2...')
    // '路由规则'=>'外部地址'
    // '路由规则'=>array('外部地址','重定向代码')
    // 路由规则中 :开头 表示动态变量
    // 外部地址中可以用动态变量 采用 :1 :2 的方式
    // 'news/:month/:day/:id'=>array('News/read?cate=1','status=1'),
    // 'new/:id'=>array('/new.php?id=:1',301), 重定向
    // 正则路由定义方法：
    // '路由正则'=>'[控制器/操作]?参数1=值1&参数2=值2...'
    // '路由正则'=>array('[控制器/操作]?参数1=值1&参数2=值2...','额外参数1=值1&额外参数2=值2...')
    // '路由正则'=>'外部地址'
    // '路由正则'=>array('外部地址','重定向代码')
    // 参数值和外部地址中可以用动态变量 采用 :1 :2 的方式
    // '/new\/(\d+)\/(\d+)/'=>array('News/read?id=:1&page=:2&cate=1','status=1'),
    // '/new\/(\d+)/'=>array('/new.php?id=:1&page=:2&status=1','301'), 重定向
    /**
     * 读取规则缓存
     * @param  boolean $update 是否更新
     * @return array
     */
    public static function ruleCache($update = false) {
        $module = defined('MODULE_NAME') ? '_'.MODULE_NAME : '';
        if ($update || !$_rules = S('url_route_rules'.$module)) {
            // 静态路由
            $_rules[0] = C('URL_MAP_RULES');
            if (!empty($_rules[0])) {
                foreach ($_rules[0] as $rule => $route) {
                    if (!is_array($route)) {
                        $route = array($route);
                    }
                    if (strpos($route[0], '?')) { // 分离参数
                        list($route[0], $args) = explode('?', $route[0], 2);
                        parse_str($args, $args);
                    } else {
                        $args = array();
                    }
                    if (!empty($route[1]) && is_string($route[1])) { // 额外参数
                        parse_str($route[1], $route[1]);
                    }
                    $route[] = $args;
                    $_rules[0][$rule] = $route;
                }
            }
            // 动态路由
            $_rules[1] = C('URL_ROUTE_RULES');
            if(!empty($_rules[1])) {
                foreach ($_rules[1] as $rule=>$route){
                    if (!is_array($route)) {
                        $route = array($route);
                    } elseif (is_numeric($rule)) {
                        // 支持 array('rule','adddress',...) 定义路由
                        $rule = array_shift($route);
                    }
                    if (!empty($route)) {
                        $args = array();
                        if (is_string($route[0])) {
                            if (0===strpos($route[0], '/') || 0===strpos($route[0], 'http')) { // 重定向
                                if (!isset($route[1])) {
                                    $route[1] = 301;
                                }
                            } else {
                                if (!empty($route[1]) && is_string($route[1])) { // 额外参数
                                    parse_str($route[1], $route[1]);
                                }
                                if (strpos($route[0], '?')) { // 分离参数
                                    list($route[0], $params) = explode('?', $route[0], 2);
                                    if (!empty($params)) {
                                        foreach (explode('&', $params) as $key => $val) {
                                            if (0 === strpos($val, ':')) { // 动态参数
                                                $val = substr($val, 1);
                                                $args[$key] = strpos($val, '|') ? explode('|', $val, 2) : array($val);
                                            } else {
                                                $route[1][$key] = $val;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if('/' != substr($rule, 0, 1)) { // 规则路由
                            foreach (explode('/', rtrim($rule, '$')) as $item){
                                $filter = $fun = '';
                                $type = 0;
                                if (0 === strpos($item, '[:')) { // 可选变量
                                    $type = 2;
                                    $item = substr($item, 1, -1);
                                }
                                if (0 === strpos($item, ':')) { // 动态变量获取
                                    $type = $type ?: 1;
                                    if($pos = strpos($item, '|')) { 
                                        // 支持函数过滤
                                        $fun  = substr($item, $pos+1);
                                        $item = substr($item, 1, $pos-1);
                                    }
                                    if ($pos = strpos($item, '^')) { // 排除项
                                        $filter = explode('-',substr($item, $pos+1));
                                        $item  = substr($item, 1, $pos-1);
                                    } elseif (strpos($item, '\\')) {
                                        if ('d' == substr($item,-1)) { // \d表示限制为数字
                                            $filter = 'int';
                                        }
                                        $item = substr($item, 1, -2);
                                    } else {
                                        $item = substr($item, 1);
                                    }
                                }
                                $args[$item] = array($type, $fun, $filter);
                            }
                        }
                        $route[] = $args;
                        $_rules[1][$rule] = $route;
                    } else {
                        unset($_rules[1][$rule]);
                    }
                }
            }
            S('url_route_rules'.$module, $_rules);
        }
        return $_rules;
    }
}