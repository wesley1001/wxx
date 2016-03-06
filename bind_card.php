<?php
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
?>
<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css"/>
    <link href="css/justified-nav.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css"/>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <link type="text/css" href="css/custom.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/moment-with-locales.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>
    <!--[if lt IE 9]>
    <scrinput-group src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></scrinput-group>
    <scrinput-group src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></scrinput-group>
    <![endif]-->
    <script src="js/emulatetab.joelpurra.js"></script>
    <script src="js/plusastab.joelpurra.js"></script>
    <title></title>
</head>
<body>
<div class="container">

    <?php
    $open_id = $_GET["open_id"];
    $act = $_GET["act"];
    echo '<div style="margin-top:50px"></div>';
    if ($act === "bind") {
        $card_number = $_GET["card_number"];
        if (!is_string($card_number)) {
            echo "机顶盒编号错误。";
            return;
        }
        echo '<div style="margin-top:30px"></div>';

//判断是否已有绑定记录
        $result = $database->select("bind", "*", [
            "open_id" => $open_id
        ]);
        if (count($result) > 0) {
            echo '<span class="text-danger">对不起，此微信号码已绑定了其他的智能卡，请检查或解绑。</span>';
            return;
        }
//插入数据库
        $database->insert("bind", [
            "open_id" => $open_id,
            "card_num" => $card_number
        ]);
        echo '<span class="text-success">绑定成功。</span>';
    }

    if ($act === "unbind") {
        $card_number = $_GET["card_number"];
        $database->delete("bind", [
            "open_id" => $open_id
        ]);
        echo '<span class="text-success">解绑成功。</span>';
    }

    if ($act === "bindEmployee") {
        $name = $_GET["name"];
        $contact = $_GET["contact"];
        $dep = $_GET["dep"];
        //保存到用户表
        $result = $database->select("wxid", "*", [
            "open_id" => $open_id
        ]);
        if (count($result) > 0) {
            echo '<span class="text-danger">对不起，此微信号码已经注册，请检查。</span>';
            return;
        }
        $database->insert("wxid", [
            "open_id" => $open_id,
            "name" => $name,
            "contact" => $contact,
            "dep" => $dep,
            "privilege" => "0"
        ]);
        echo '<span class="text-success">注册成功</span>';
    }
    ?>
</div>
</body>
</html>

