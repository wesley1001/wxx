<?php
/**
 * wechat php test
 */

//define your token
require "medoo.php";
define("TOKEN", "fiowfwojfiowu4209u20329042o4j3k4");
//traceHttp();
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            //exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = $postObj->MsgType;
            switch ($RX_TYPE) {
                case "text":
                    $database = new medoo([
                        'database_type' => 'mysql',
                        'database_name' => 'jsm-crm',
                        'server' => 'rds9h4kag49h14yy814g.mysql.rds.aliyuncs.com',
                        'username' => 'bluestone',
                        'password' => 'Xidryhm00Uijshc',
                        'charset' => 'utf8',
                        'port' => 3306,
                        'option' => [
                            PDO::ATTR_CASE => PDO::CASE_NATURAL
                        ]
                    ]);
                    $keyword = trim($postObj->Content);
                    $userDetails = $database->select("stop_users", "*", [
                        "card_num" => $keyword
                    ]);
                    $content = "客户名称：" . $userDetails[0]["username"] . "\n安装地址：" . trim($userDetails[0]["addr"]) . "\n联系电话：" . $userDetails[0]["phone1"] . "\n联系电话2：" . $userDetails[0]["phone2"] . "\n产品到期日：" . $userDetails[0]["end_date"];
                    $resultStr = $this->responseText($postObj,$content);
                    break;
                case "event":
                    $resultStr = $this->handleEvent($postObj);
                    break;
                default:
                    $resultStr = "Unknow msg type: " . $RX_TYPE;
                    break;
            }
            echo $resultStr;
        } else {
            echo "";
            exit;
        }
    }

    //---------- 返 回 数 据 ---------- //
    public function handleText($object)
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

        $msgType = "news"; //消息类型
        $title = "点击进行绑定智能卡操作";
        $description = "将微信号与智能卡绑定，可以方便提醒客户业务到期时间";
		$picurl = "http://115.28.231.224/users.png";
        $url = "http://115.28.231.224/wxx/bind.php?open_id=" . $fromUsername;
		$time=time();
        //格式化消息模板
        $resultStr = sprintf($textTpl,$fromUsername,$toUsername,
        $time,$msgType,$title,$description,$picurl,$url);
		return $resultStr;
    }

    public function handleEvent($object)
    {
        $contentStr = "";
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
                }
                if ($object->EventKey == "service") {
                    $contentStr = "请拨打全省统一客服电话：96633.";
                }
                if ($object->EventKey == "bind") {
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

        $msgType = "news"; //消息类型
        $title = "点击进行绑定智能卡操作";
        $description = "将微信号与智能卡绑定，可以方便提醒客户业务到期时间";
		$picurl = "http://115.28.231.224/users.png";
        $url = "http://115.28.231.224/wxx/bind.php?open_id=" . $fromUsername;
		$time=time();
                }
				if ($object->EventKey == "unbind") {
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

        $msgType = "news"; //消息类型
        $title = "点击进行解绑智能卡操作";
        $description = "";
		$picurl = "http://115.28.231.224/users.png";
        $url = "http://115.28.231.224/wxx/unbind.php?open_id=" . $fromUsername;
		$time=time();
                }
                break;
            default :
                $contentStr = "Unknow Event: " . $object->Event;
                break;
        }
		if ($object->EventKey != "bind") {
        $resultStr = $this->responseText($object, $contentStr);}
		if ($object->EventKey == "bind" || $object->EventKey == "unbind") {
		$resultStr = sprintf($textTpl,$fromUsername,$toUsername,
        $time,$msgType,$title,$description,$picurl,$url);}
	   return $resultStr;
    }

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

function traceHttp()
{
    logger("REMOTE_ADDR:" . $_SERVER["REMOTE_ADDR"] . ((strpos($_SERVER["REMOTE_ADDR"], "101.226")) ? " From WeiXin" : " Unknown IP"));
    logger("QUERY_STRING:" . $_SERVER["QUERY_STRING"]);
}

function logger($content)
{
    file_put_contents("log.html", date('Y-m-d H:i:s ') . $content . "<br>", FILE_APPEND);
}

?>
