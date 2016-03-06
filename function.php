<?php
/**
 * Created by PhpStorm.
 * User: wangxiaofeng
 * Date: 2/5/16
 * Time: 10:12 AM
 */
function getAccessToken($appId,$appSecret) {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("access_token.json"));
    if ($data->expire_time < time()) {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
        //$res = json_decode(httpGet($url));   //改用file_get_contents 注意需要环境支持 谢谢
        $res = json_decode(file_get_contents($url));


        $access_token = $res->access_token;
        if ($access_token) {
            $data->expire_time = time() + 7000;
            $data->access_token = $access_token;
            $fp = fopen("access_token.json", "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    } else {
        $access_token = $data->access_token;
    }
    return $access_token;
}



// 工具函数 //
/* 使用curl来post一个json数据 */
// CURLOPT_SSL_VERIFYPEER,CURLOPT_SSL_VERIFYHOST - 在做https中要用到
// CURLOPT_RETURNTRANSFER - 不以文件流返回，带1
function post_json($url,$json){
    $post_url = $url;
    $post_data =$json;
    $ch = curl_init();//初始化
    curl_setopt($ch, CURLOPT_TIMEOUT, '30');//超时时间
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Keep-Alive: 300','Connection: keep-alive')) ;
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; GTB7.4; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.2)');
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_URL,$post_url);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


    $contents = curl_exec($ch);

    if(curl_errno($ch)){}

    return  $contents;
}
