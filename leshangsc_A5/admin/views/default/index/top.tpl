<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/top.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<title>top</title>

<script type="text/javascript">
$(function()
{
window.parent.frames["menu"].location.href =  "<{$url}>/left/left_id/1";
$(".nav ul li a").click(function(){
	$(".nav ul li").removeClass("selected");
	$(this).parent().addClass("selected");
	$(this).blur();
	window.parent.frames["menu"].location.href =  "<{$url}>/left/left_id/"+$(this).attr("id");
	})
})
</script>
</head>

<body>
<div class="sitename"></div>
<div class="v_line"></div>
<div class="nav">
	<ul>
    	<li class="selected"><a href="<{$app}>/index/main" target="main" id="1">首页</a></li>
        <li><a href="<{$app}>/module" target="main" id="2">功能管理</a></li>
        <li><a href="<{$app}>/product" target="main" id="3">商品管理</a></li>
        <li><a href="<{$app}>/user" target="main" id="4">用户管理</a></li>
        <li><a href="<{$app}>/config" target="main" id="5">系统管理</a></li>
    </ul>
</div>
<div class="admin_info">当前管理员:<{$admin.adm_name}></div>
</body>
</html>
