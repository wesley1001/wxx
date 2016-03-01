<?php
/**
 * wechat php test
 */

include("wechatCall.php");
require "medoo.php";
//include("function.php");

//define database arg.
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

define("TOKEN", "fiowfwojfiowu4209u20329042o4j3k4");
//traceHttp();
$wechatObj = new wechatCall();
//valid the source.
$wechatObj->valid();

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
            $open_id = $postObj->FromUserName.'/';
            $result = $database->select("wxid","*",[
			"AND"=>[
                "open_id"=>$open_id,
				"privilege"=>'1'
            ]
			]);
            if (count($result) > 0) {
                $keyword = trim($postObj->Content);
                $userDetails = $database->select("stop_users", "*", [
                    "card_num" => $keyword
                ]);
                if (count($userDetails) > 0) {
				     $content = "客户名称：" . $userDetails[0]["username"] ."\n安装地址：" . trim($userDetails[0]["addr"]) ."\n联系电话：" . $userDetails[0]["phone1"] .  "\n联系电话2：" . $userDetails[0]["phone2"];  
				for ($i=0;$i<count($userDetails);$i++){
				$content .=	"\n\n产品名称：" . $userDetails[$i]["productname"] . 	"\n产品到期日：" . $userDetails[$i]["end_date"];
					}
                } else {
                    $content = "没有查询到此客户.请输入正确的智能卡号";
                }
            } else {
                $content = "非本公司员工或者处于审核期,不能使用后台服务.";
            }
            $resultStr = $wechatObj->responseText($postObj, $content);
            break;
        case "event":
            $resultStr = $wechatObj->handleEvent($postObj);
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


