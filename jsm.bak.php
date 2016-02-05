<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "fiowfwojfiowu4209u20329042o4j3k4");
traceHttp();
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	//exit;
        }
    }

public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
				$RX_TYPE = $postObj->MsgType;
                switch($RX_TYPE)
                {
                    case "text":
                        $resultStr = $this->handleText($postObj);
                        break;
                    case "event":
                        $resultStr = $this->handleEvent($postObj);
						echo $resultStr;
                        break;
                    default:
                        $resultStr = "Unknow msg type: ".$RX_TYPE;
                        break;
                }
                echo $resultStr;
        }else {
        	echo "";
        	exit;
        }
    }
    //---------- 返 回 数 据 ---------- //
	public function handleText($object){
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
		$title = "客户名单（条件：".$keyword."）";
		$description = "客户名单（条件：".$keyword."）";
		$picurl = "http://115.28.231.224/users.png";
		$url = "http://115.28.231.224/wxx/index.php?addr=".$keyword;
		$time=time();
        //格式化消息模板
        $resultStr = sprintf($textTpl,$fromUsername,$toUsername,
        $time,$msgType,$title,$description,$picurl,$url);
		return $resultStr;
		}

	public  function handleEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "感谢您关注【吉视传媒梨树分公司】"."\n".
				"微信号：jsmlishu"."\n".
				"本公众号面向有线电视客户，为客户提供服务。"."\n".
				"目前平台功能如下："."\n".
				"【1】 一周节目早知道，涵盖100多个电视台的节目。"."\n".
				"【2】 第一时间可以查询到我公司的优惠方案。"."\n".
				"【3】 查询所属地的维修员联系方式。"."\n".
				"【4】 提交您宝贵的意见和建议。"."\n".
				"【5】 绑定智能卡之后，可以实现到期日提醒功能。"."\n\n";
                break;
            default :
                $contentStr = "Unknow Event: ".$object->Event;
                break;
        }
        $resultStr = $this->responseText($object, $contentStr);
        return $resultStr;
    }
  	public function responseText($object, $content, $flag=0)
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
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
function traceHttp()
{
	logger("REMOTE_ADDR:".$_SERVER["REMOTE_ADDR"].((strpos($_SERVER["REMOTE_ADDR"],"101.226"))?" From WeiXin":" Unknown IP"));
	logger("QUERY_STRING:".$_SERVER["QUERY_STRING"]);
	}
function logger($content)
{
	file_put_contents("log.html",date('Y-m-d H:i:s ').$content."<br>",FILE_APPEND);
	}

?>
