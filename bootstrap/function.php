<?php
/*
 * 将unicode 编码转换为utf8
 */
if (! function_exists('unicode2Utf8')) {
    function unicode2Utf8($str){
        if(!$str) return $str;
        $decode = json_decode($str);
        if($decode) return $decode;
        $str = '["' . $str . '"]';
        $decode = json_decode($str);
        if(count($decode) == 1){
            return $decode[0];
        }
        return $str;
    }
}

/**
 * uuid 生成器
 * @param string $prefix
 * @return string
 */
if (! function_exists('uuid')){
    function uuid($prefix = ''){
        $chars = md5(uniqid(mt_rand(), true));
        $uuid  = substr($chars,0,8) . '-';
        $uuid .= substr($chars,8,4) . '-';
        $uuid .= substr($chars,12,4) . '-';
        $uuid .= substr($chars,16,4) . '-';
        $uuid .= substr($chars,20,12);
        return $prefix.$uuid;
    }
}

if (! function_exists('getSn')){
    // http://www.cnblogs.com/mssql8/p/3805213.html
    function getSn(){
//        $year_code = array('A','B','C','D','E','F','G','H','I','J');
//        $sn = $year_code[intval(date('Y'))-2010].
//            strtoupper(dechex(date('m'))).date('d').
//            substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
//        return $sn;
        $sn = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return substr($sn, 2);
    }
}

/**
 * 截取UTF-8编码下字符串的函数
 * @param   string      $str        被截取的字符串
 * @param   int         $length     截取的长度
 * @param   bool        $append     是否附加省略号
 * @return  string
 */
if (! function_exists('subStr')){
    function subStr($str, $length = 0, $append = true)
    {
        $str = trim($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength)
        {
            return $str;
        }
        elseif ($length < 0)
        {
            $length = $strlength + $length;
            if ($length < 0)
            {
                $length = $strlength;
            }
        }

        if (function_exists('mb_substr'))
        {
            $newstr = mb_substr($str, 0, $length, "utf-8");
        }
        elseif (function_exists('iconv_substr'))
        {
            $newstr = iconv_substr($str, 0, $length, "utf-8");
        }
        else
        {
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr)
        {
            $newstr .= '...';
        }

        return $newstr;
    }
}

/*----------是否为合法邮箱-------------*/
if (! function_exists('checkEmail')) {
    function checkEmail($inAddress){
        return (ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+",$inAddress));
    }
}
/*----------时间辍转化成日期时间-------------*/
if (! function_exists('toDate')) {
    function toDate($format = 'Y-m-d H:i:s',$time='') {
        if (empty ( $time )) {
            return date($format);
        }
        $format = str_replace ( '#', ':', $format );
        return date ($format, $time );
    }
}

if (! function_exists('getWDay')) {
    function getWDay($inputStr){
        $day = parseDay($inputStr);
        return date("w", $day);
    }
}

if (! function_exists('parseDay')) {
    function parseDay($inputStr){
        $day = strtotime($inputStr);
        return $day;
    }
}

if (! function_exists('formatDayFromStr')) {
    function formatDayFromStr($inputStr){
        $day = strtotime($inputStr);
        return date('Y-m-d',$day);
    }
}

if (! function_exists('getNowForFileName')) {
    function getNowForFileName(){
        return date('YmdHi', time());
    }
}

if (! function_exists('getMonth')) {
    function getMonth(){
        return date('Y-m', time());
    }
}

if (! function_exists('getYear')) {
    function getYear(){
        return date('Y', time());
    }
}
if (! function_exists('getLastNMonth')) {
    function getLast6Month(){
        $lastday = date('Y-m-01', strtotime('-11 month'));
        return $lastday;
    }
}

if (! function_exists('formatDayMinuteFromStr')) {
    function formatDayMinuteFromStr($inputStr){
        $day = strtotime($inputStr);
        return date('Y-m-d H:i',$day);
    }
}

if (! function_exists('formatDayHourFromStr')) {
    function formatDayHourFromStr($inputStr){
        $day = strtotime($inputStr);
        return date('Y-m-d H',$day).':00';
    }
}

if (! function_exists('getUserName')) {
    function getUserName(){
        $token = Cookie::get('backend_token');
        $name = '路人甲';
        if(empty($token)){
            return $name;
        }else{
            $obj = json_decode(base64_decode($token));

            if(!empty($obj)){
                if(!empty($obj->real_name)){
                    $name = $obj->real_name;
                }
            }
            return $name;
        }
    }

}

if (! function_exists('getIntValue')) {
    function getIntValue($inputStr){
        $t = 0;
        if(!empty($obj)){
            $t = $obj;
        }
        return $t;
    }
}

if (! function_exists('wrapTextToHtml')) {
    function wrapTextToHtml($text){
        return str_replace(array("\r\n", "\n", "\r"), "<br>", $text);
    }
}

if (! function_exists('tagToHtml')) {
    function tagToHtml($text){
        $strs = '';
        foreach(explode(' ',$text) as $tag){
            if(!empty($tag)){
                $strs = $strs.'<a href="../search/tag?q='.$tag.'">'.$tag.'</a>&nbsp;&nbsp;';
            }
        }
        return $strs;
    }
}

if (! function_exists('tagToShortHtml')) {
    function tagToShortHtml($text){
        $strs = '';
        $tags = explode(' ',$text);
        $len = count($tags) > 5 ? 5 : count($tags);
        for($i = 0; $i < $len; $i++){
            $tag = $tags[$i];
            if(!empty($tag)){
                $strs = $strs.'<a href="../search/tag?q='.$tag.'">'.$tag.'</a>&nbsp;&nbsp;';
            }
        }
        return $strs;
    }
}


if (! function_exists('tagToHtmlLimit')) {
    function tagToHtmlLimit($text, $len=4){
        $strs = '';
        $array = explode(' ',$text);
        $limit = (count($array) > $len) ? $len : count($array);
        for($i=0; $i<$limit; $i++){
            $tag = $array[$i];
            if(!empty($tag)){
                $strs = $strs.'<a href="../search/tag?q='.$tag.'">'.toHtmlLimit($tag,8).'</a>&nbsp;&nbsp;';
            }
        }
        if(count($array) > $len){
            $strs = $strs.'...';
        }
        return $strs;
    }
}

if (! function_exists('toHtmlLimit')) {
    function toHtmlLimit($text, $limit){
        if(mb_strlen($text) > $limit){
            return mb_substr($text, 0 , $limit).'...';
        }
        return $text;
    }
}

if (! function_exists('tagToInput')) {
    function tagToInput($text){
        $strs = '';
        $array = explode(' ',$text);
        for($i=0; $i<count($array); $i++){
            $tag = $array[$i];
            if(!empty($tag)){
                if($i != count($array) -1) {
                    $strs = $strs.$tag.',';
                } else{
                    $strs = $strs.$tag;
                }
            }
        }
        return $strs;
    }
}

if (! function_exists('pinyinSort')) {
    function pinyinSort($array){
        if(!isset($array) || !is_array($array)) {
            return $array;
        }
        foreach($array as $k=>$v) {
            $array[$k] = iconv('UTF-8', 'GBK//IGNORE',$v);
        }
        asort($array);
        foreach($array as $k=>$v) {
            $array[$k] = iconv('GBK', 'UTF-8//IGNORE', $v);
        }
        return $array;
    }
}

if (! function_exists('filterTitle')) {
    function filterTitle($text){
        $index = strrpos($text, ":");
        if($index != false){
            return trim(substr($text, 0, $index));
        }

        $index = strrpos($text, "：");
        if($index != false){
            return trim(substr($text, 0, $index));
        }

        $index = strrpos($text, ".");
        if($index != false){
            return trim(substr($text, 0, $index));
        }

        return $text;
    }
}
