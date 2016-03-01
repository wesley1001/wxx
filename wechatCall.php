<?php

/**
 * Created by PhpStorm.
 * User: wangxiaofeng
 * Date: 2/5/16
 * Time: 10:16 AM
 */
class wechatCall
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
        }
    }


    //---------- 返 回 数 据 ---------- //

    public function handleEvent($object)
    {
        $contentStr = "";
        $resultStr = "";
        switch ($object->Event) {
            case "subscribe":
                $contentStr = "感谢您关注【吉视传媒梨树分公司】" . "\n" .
                    "微信号：jsmlishu" . "\n" .
                    "本公众号面向有线电视客户，为客户提供服务。" . "\n" .
                    "目前平台功能如下：" . "\n" .
                    "【1】 业务介绍。" . "\n" .
                    "【2】 查询最新的优惠促销活动。" . "\n" .
                    "【3】 可以查询收视到期日。" . "\n" .
                    "【4】 绑定智能卡之后，可以实现到期日提醒功能。" . "\n\n";
                break;
            case "CLICK":
                if ($object->EventKey == "V1001_TODAY_MUSIC") {
                    $contentStr = "本功能春节后上线";
                    $resultStr = $this->responseText($object, $contentStr);
                }
                if ($object->EventKey == "service") {
                    $contentStr = "请拨打全省统一客服电话：96633.";
                    $resultStr = $this->responseText($object, $contentStr);
                }
                if ($object->EventKey == "bind") {
                    $url = "http://115.28.231.224/wxx/bind.php?open_id=" . $object->FromUserName;
                    $resultStr = $this->responseNews($object, "点击进行绑定智能卡操作", "", "http://115.28.231.224/users.png", $url);
                }
                if ($object->EventKey == "unbind") {
                    $url = "http://115.28.231.224/wxx/unbind.php?open_id=" . $object->FromUserName;
                    $resultStr = $this->responseNews($object, "点击进行解绑智能卡操作", "", "http://115.28.231.224/users.png", $url);
                }
                if ($object->EventKey == "bindEmployee") {
                    $url = "http://115.28.231.224/wxx/bind-employee.php?open_id=" . $object->FromUserName;
                    $resultStr = $this->responseNews($object, "点击进行员工注册", "", "http://115.28.231.224/users.png", $url);
                }
                break;
            default :
                $contentStr = "Unknow Event: " . $object->Event;
                $resultStr = $this->responseText($object, $contentStr);
                break;
        }
        return $resultStr;
    }


    //发送文本消息
    public function responseText($object, $content, $flag = 0)
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }

    //发送图文消息
    public function responseNews($object, $title, $description, $picurl, $url)
    {
        $fromUsername = $object->FromUserName; //获取发送方帐号（OpenID）
        $toUsername = $object->ToUserName; //获取接收方账号
        $keyword = trim($object->Content); //获取消息内容
        //返回消息模板
        $textTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<ArticleCount>1</ArticleCount>
		<Articles>
		<item>
		<Title><![CDATA[%s]]></Title>
		<Description><![CDATA[%s]]></Description>
		<PicUrl><![CDATA[%s]]></PicUrl>
		<Url><![CDATA[%s]]></Url>
		</item>
		</Articles>
		</xml>";
        $msgType = "news";
        $time = time();
        //格式化消息模板
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername,
            $time, $msgType, $title, $description, $picurl, $url);
        return $resultStr;
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}