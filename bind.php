<?php
/**
 * Created by PhpStorm.
 * User: wangxiaofeng
 * Date: 11/1/15
 * Time: 8:10 PM
 */
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
    <title>绑定智能卡</title>

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
<div style="margin-top:50px"></div>
    <?php if (empty($_GET["open_id"])){
        echo "<span class='text-danger'>请在公众号里打开本页面.</span>";
        exit;
    } ?>
<form class="form-horizontal" method = "get" name = "myForm" action="bind_card.php" onsubmit="return checkCardNumber();">
<label class="control-label">请输入智能卡号</label>
<input id="cardNum" class="form-control" type="text" name="card_number"/>
<label class="control-label">再输入智能卡号</label>
<input id="repeat" class="form-control" type="text" name="card_number1"/>
<input type="hidden" name="open_id" value=<?php echo $_GET["open_id"] ?>/>
<input type="hidden" name="act" value="bind"/>
<button class="btn btn-default" style="margin-top:10px"  type="submit">绑定</button>
    <div id="info" class="text-danger"></div>
</form>
</div>
<script>
    function checkCardNumber() {
        var card = $("#cardNum").val();
        var repeat = $("#repeat").val();
        if (card !== repeat) {
            $("#info").html("两次输入的卡号不一致,请重新输入");
            $("#cardNum").focus();
            return false;
        }
    }
</script>
</body>
</html>
