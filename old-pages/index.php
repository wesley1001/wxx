<?php
session_start();
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
date_default_timezone_set("Asia/Shanghai");
$date = date("Y-m-d",time(getdate())-(189*24*60*60));
$otherDate = date("Y-m-d",time(getdate())+(15*24*60*60));

$user = $database->select("stop_users","*",[
"OR #or"=>[
	"AND #first condition"=>[
		"addr[~]"=>$_GET["addr"],
		"productname[~]"=>"基本维护费"
		],
	"AND #second condition"=>[
		"addr[~]"=>$_GET["addr"],
		"end_date[<]"=>$otherDate,
		"productname[!~]"=>"基本维护费"
		]
	],
		"ORDER"=>"addr"
	]);
?>
<!DOCTYPE html>
<!-- saved from url=(0034)http://localhost/jsm-crm/index.php -->
<html lang="cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>梨树分公司网格小组客户管理系统</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/justified-nav.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../js/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="../js/jquery.dataTables.js"></script>
    <![endif]-->
    <script type="text/javascript" language="javascript" class="init">


        $(document).ready(function() {
            $('#example').DataTable({
                "paging": false,
                "ordering": true
            });
        } );


    </script>
</head>
<body>
<div class="container-fluid">
<?php 
if (count($user) < 1000) {
 ?>
<h1 class="text-center"><?php echo $_GET["addr"] ?>客户名单</h1>
    <div class="row">
        <div class="table-responsive">
            <table id="" class="table table-responsive">
                <thead>
                <tr>
                    <th>客户编码</th>
                    <th>客户姓名</th>
					<th>收视状态</th>
					<th>产品名称</th>
                    <th>产品到期日</th>
                    <th>联系电话</th>
                    <th>联系电话</th>
                    <th>客户地址</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($user as $u) {
				if ($u["end_date"] < $date) {
				echo '<tr class="info">';} else if ($u["end_date"] >=date('2016-01-01')) {
				echo '<tr class="danger">';} else {
				echo '<tr>';}
                    ?>
                        <td><?php echo $u["userid"] ?></td>
                        <td><?php echo $u["username"] ?></td>
						<td><?php echo $u["style"] ?></td>
                        <td><?php echo $u["productname"] ?></td>
                        <td><?php echo $u["end_date"] ?></td>
                        <td><?php echo $u["phone1"] ?></td>
                        <td><?php echo $u["phone2"] ?></td>
                        <td><?php echo $u["addr"] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } else {
	echo "抱歉，系统最多只能显示1000条记录，请缩小查询范围重试。";
} ?>
</div>
</body>
</html>
