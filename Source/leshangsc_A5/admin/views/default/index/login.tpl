<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>后台管理</title>

<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.pngfix.js"></script>
</head>
<body>

<div id="login_box">
	<div class="login">
    	<ul>
        	<form action="<{$url}>/login" enctype="multipart/form-data" name="form1" method='post'>
        	<li>用户：&nbsp;<input type="text" name="adm_name" class="input_box"/></li>
            <li>密码：&nbsp;<input type="password" name="adm_password" class="input_box"/></li>
            <li class="left">验证：&nbsp;<input type="text" name="code" class="input_code" onkeyup="if (this.value != this.value.toUpperCase())
this.value=this.value.toUpperCase();"></li>
			<li class="code left"><img src="<{$url}>/code" onClick="this.src='<{$url}>/code/'+Math.random()"></li>
            <li class="button clear"><input type="submit" value="登陆" class="login_button"/></li>
            </form>
        </ul>
    </div>
</div>
</body>
</html>