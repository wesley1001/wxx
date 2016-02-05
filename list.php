<?php
/**
 * Created by PhpStorm.
 * User: wangxiaofeng
 * Date: 11/1/15
 * Time: 8:10 PM
 */

require "medoo.php";
$database = new medoo([
// required
   'database_type' => 'mysql',
   'database_name' => 'jsm-crm',
   'server' => 'rds9h4kag49h14yy814g.mysql.rds.aliyuncs.com',
   'username' => 'bluestone',
   'password' => 'Xidryhm00Uijshc',
   'charset' => 'utf8',

// [optional]
   'port' => 3306,

// [optional] Table prefix
//'prefix' => 'PREFIX_',

// driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
   'option' => [
       PDO::ATTR_CASE => PDO::CASE_NATURAL
   ]
]);
if ($_GET["area"] === "town") {
$townAddress = $database->select("addr_list","*",[
       "area"=>["万发镇","金山乡","小城子镇","大房身乡","四棵树乡",
                   "叶赫镇","三家子乡","双河镇","刘家馆子镇","胜利乡",
                   "喇嘛甸镇","十家堡镇","团结乡","郭家店镇","沈洋镇",
                   "梨树镇","林海镇","孟家岭镇","东河镇","梨树乡","董家乡",
                   "小宽镇","榆树台镇","太平镇","白山乡","泉眼岭乡","霍家店园区",
                   "蔡家镇"]
   ]);}
if ($_GET["area"] === "suburb") {
$townAddress = $database->select("addr_list","*",[
       "area[!]"=>["万发镇","金山乡","小城子镇","大房身乡","四棵树乡",
                   "叶赫镇","三家子乡","双河镇","刘家馆子镇","胜利乡",
                   "喇嘛甸镇","十家堡镇","团结乡","郭家店镇","沈洋镇",
                   "梨树镇","林海镇","孟家岭镇","东河镇","梨树乡","董家乡",
                   "小宽镇","榆树台镇","太平镇","白山乡","泉眼岭乡","霍家店园区",
                   "蔡家镇"],
		"ORDER" => "area"
   ]);
   }
?>

<!DOCTYPE html>
<html lang="cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>地址列表</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/justified-nav.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>
    <![endif]-->
</head>
<body>
<div class="container-fluid">
    <h1 class="text-center">地址列表</h1>
    <div class="row">
        <div class="table-responsive">
            <table id="" class="table table-striped">
                <thead>
                <tr>
                    <th>子区域</th>
                    <th>地址</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($townAddress as $u) {
                    echo '<tr>';
                    echo '<td>' . $u["area"] . '</td>';
                    echo '<td>' . $u["addr"] . '</td>';
                    echo '</tr>';
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
