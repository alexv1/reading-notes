<?php
/**
 * Created by PhpStorm.
 * User: xv
 * Date: 16/4/14
 * Time: 下午3:39
 */


class QiniuController extends BaseController {

    private $bucket = "reading";
    private $accessKey = '2F_CZlrY239hBw3VkRno0IJyuarP0uZ_a9iL59eS';
    private $secretKey = 'MnqUuIG4M-O01rPDs-MLKh5rG_gKJhXgHY_T84gb';

    public function uploadToken(){
        require_once(__DIR__.'/../lib/qiniu-sdk/autoload.php');

        $auth = new Qiniu\Auth($this->accessKey, $this->secretKey);
        $token = $auth->uploadToken($this->bucket);
        return json_encode(array(
            'uptoken' => $token
        ));
    }

    public function convertFile(){
        $input_vals = \Input::All();
        $fileUrl = isset($input_vals['file_url']) ? $input_vals['file_url'] : '';
        require_once(__DIR__.'/../lib/HttpRequest.php');
        require_once(__DIR__.'/../lib/qiniu-sdk/autoload.php');

//        $fileUrl = 'https://img1.doubanio.com/lpic/s28986179.jpg';
        //获取图片路径信息
        $pathinfo = pathinfo($fileUrl);
        //获取图片具体名称
        $picName=$pathinfo['basename'];

        $fileId = time();
        $storagePath = storage_path().'/tmp/';
        // 要上传文件的本地路径
        $localFile = $storagePath.$fileId.'-'.$picName;
        HttpRequest::downloadFile($fileUrl, $localFile);

        $auth = new Qiniu\Auth($this->accessKey, $this->secretKey);
        $token = $auth->uploadToken($this->bucket);

        // 上传到七牛后保存的文件名
        $key = $fileId.'-'.$picName;
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new Qiniu\Storage\UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $localFile);

        // 删除本地文件
        unlink($localFile);
        if ($err !== null) {
            return json_encode(array(
                'code' => 1,
                'ret' => json_decode($err)
            ));
        } else {
            return json_encode(array(
                'code' => 0,
                'key' => $key
            ));
        }


    }

}