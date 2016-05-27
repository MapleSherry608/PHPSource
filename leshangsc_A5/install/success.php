<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>安装向导-乐尚商城</title>
<link href="css/global.css" rel="stylesheet" type="text/css" />
<link href="css/index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.pngfix.js"></script>
<?php
	$home="http://".$_SERVER['SERVER_NAME']."/".dirname(dirname($_SERVER["SCRIPT_NAME"]));
	$admin=$home."/admin.php"
?>
</head>

<body>
<div id="layout">
	<div class="top_line">
    	<div class="logo"></div>
        <div class="title">安装向导<br />www.5451CMS.com</div>
    </div>
    <div class="body_line">
    	<div class="title">安装成功</div>
		<div class="success">
        	您的程序已安装成功！<br /><a href="<?php echo $home ?>">进入首页</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $admin ?>">进入后台</a>
      	</div>
    </div>
</div>
</body>
</html>
