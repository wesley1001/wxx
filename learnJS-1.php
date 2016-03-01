<?php
/**
 * Created by PhpStorm.
 * User: wangxiaofeng
 * Date: 1/2/16
 * Time: 10:00 AM
 */

include("wechatCall.php");
require "medoo.php";

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

$open_id = 'om4epwW2GoPzrgTJHBZVRK7mW25k';

$result = $database->select("wxid","*",[
               "AND"=>[
                   "open_id"=>$open_id,
                  "privilege"=>'1'
              ]]);
var_dump($result);