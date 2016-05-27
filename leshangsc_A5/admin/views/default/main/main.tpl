<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<title>main</title>
</head>

<body>
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;后台管理系统首页</div></div>
<div class="info red_l">
	<ul>
    	<li>欢迎使用乐尚商城后台管理系统</li>
    	<li>系统当前时间：<{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}></li>
        <li>操作提示：请在菜单选择需要操作的内容</li>
        <li>数据库使用：<{$dbsize}></li>
        <li>数据库版本：<{$dbversion}></li>
        <li>友情提示：本软件可更换皮肤，如需更多皮肤，请联系QQ:15919572</li>
        <li>邮箱：15919572@qq.com</li>
		<li>乐尚商城交流群：307867520</li>
    </ul>

</div>
</body>
</html>
