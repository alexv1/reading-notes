<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-5-4
 * Time: 下午6:24
 */

class HttpRequest {
    public static function httpsGet($url, $params){
        $url = $url."?".http_build_query($params);
        $ch = curl_init() ;
        curl_setopt($ch , CURLOPT_URL , $url) ;
        curl_setopt($ch , CURLOPT_SSL_VERIFYPEER , false) ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;  //设置返回内容不回显
        curl_setopt($ch , CURLOPT_SSL_VERIFYHOST , false) ;
        $result = curl_exec($ch) ;
        curl_close($ch) ;
        return $result ;
    }

    public static function httpsPost($url, $params){
        //echo $url."#";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }

    public static function securityPost($url, $body, $usr , $pwd){
        //Initialize a cURL session
        $curl = curl_init();
        //Return the transfer as a string of the return value of curl_exec() instead of outputting it out directly
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //Set URL to fetch
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        //Force HTTP version 1.1
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml; charset=utf-8"));
        //Use NTLM for HTTP authentication
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        //Username:password to use for the connection
        curl_setopt($curl, CURLOPT_USERPWD, $usr.':'.$pwd);
        //Stop cURL from verifying the peer’s certification
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        //##curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        //Execute cURL session
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        //Close cURL session
        curl_close($curl);
        return $result;
    }

    public static function postFile($post_data, $post_url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $post_url);
        curl_setopt($curl, CURLOPT_POST, 1 );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_USERAGENT,"Mozilla/4.0");
        $result = curl_exec($curl);
        $error = curl_error($curl);
        return $error ? $error : $result;
    }

    public static function downloadFile($capture_url, $file_name){

        $url = preg_replace( '/(?:^[\'"]+|[\'"\/]+$)/', '', $capture_url );
        $hander = curl_init();
        $fp = fopen($file_name,'wb');
        curl_setopt($hander,CURLOPT_URL,$url);
        curl_setopt($hander,CURLOPT_FILE,$fp);
        curl_setopt($hander,CURLOPT_HEADER,0);
        curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($hander,CURLOPT_TIMEOUT,60);
        curl_exec($hander);
        curl_close($hander);
        fclose($fp);
        return true;
    }
} 