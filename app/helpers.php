<?php

if (!function_exists('show_avatar')) {

    function show_avatar($avatar)
    {
        if (empty($avatar)) {
            $avatar = config('const.NO_AVATAR');
        } else {
            $avatar = config('config.qiniu.url') . $avatar;
        }

        return $avatar;
    }
}

if (!function_exists('show_photo')) {

    function show_photo($key)
    {
        return config('config.qiniu.url') . $key;
    }
}

if (!function_exists('show_date')) {

    function show_date($date)
    {
        $now = time();
        $time = strtotime($date);
        $diff = $now - $time;
        if ($diff < 60) {
            $showDate = '刚刚';
        } elseif ($diff < 3600) {
            $showDate = floor($diff / 60) . '分钟前';
        } elseif ($diff < 3600 * 24) {
            $showDate = floor($diff / 3600) . '小时前';
        } elseif ($diff < 3600 * 24 * 7) {
            $showDate = floor($diff / 86400) . '天前';
        } elseif ($diff < 3600 * 24 * 7 * 4) {
            $showDate = floor($diff / 604800) . '周前';
        } else {
            $showDate = date('Y-m-d', $time);
        }

        return $showDate;
    }
}

if (!function_exists('post_image')) {

    function post_image($content)
    {
        $postImage = '';
        if (preg_match_all('/<[img|IMG].*?src=[\'|\\"](.*?)[\'|\\"].*?[\/]?>/', $content, $matches)) {
            $size = isMobile() ? 9 : 6;
            $postImage = array_slice($matches[1], 0, $size);
        }

        return $postImage;
    }
}

if (!function_exists('post_excerpt')) {

    function post_excerpt($content)
    {
        $excerpt = '';
        if ($content != '') {
            $excerpt = strip_tags($content);
            if (strlen($excerpt) > 300) {
                $excerpt = str_limit($excerpt, 300);
            }
        }

        return $excerpt;
    }
}

//判断是否为手机
if (!function_exists('isMobile')) {

    function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
}

//获取IP地址
if (!function_exists('getIp')) {
    function getIp(){
        $onlineip='';
        if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
            $onlineip=getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
            $onlineip=getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
            $onlineip=getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
            $onlineip=$_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }
}




