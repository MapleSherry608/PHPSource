<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/left.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$res}>/js/left.js"></script>
<title>index_left</title>

</head>

<body>
<div class="ver_line">当前系统版本：<{$version}></div>
<div class="left_nav grey_l">
	<ul>
        <li class="selected"><a href="<{$app}>/config" target="main">网站设置</a></li>
        <li><a href="<{$app}>/adminGroup" target="main">管理员分组</a></li>
        <li><a href="<{$app}>/admin/admin_list" target="main">管理员设置</a></li>
        <li><a href="<{$app}>/mails" target="main">邮件服务器</a></li>
		<li><a href="<{$app}>/mailRules" target="main">邮件规则</a></li>
        <li><a href="<{$app}>/cache" target="main">清除缓存</a></li>
        <li><a href="<{$app}>/cache/useless" target="main">无效图片管理</a></li>
		<li><a href="<{$app}>/code" target="main">在线模板编辑</a></li>
        <li><a href="<{$app}>/backup" target="main">数据备份</a></li>
        <li><a href="<{$app}>/update" target="main">在线升级</a></li>
    </ul>
</div>
</body>
</html>
