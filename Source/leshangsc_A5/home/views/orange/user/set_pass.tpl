<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title><{$con_datas.site_name}></title>
<meta name="keywords" content="<{$con_datas.key_word}>" />
<meta name="description" content="<{$con_datas.description}>" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/css/weebox.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/common.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/layout.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/user.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.pngfix.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/koala.min.1.5.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script>
	var APP_PATH="<{$app}>";var ISLOGIN="<{$user.id}>"
	$(document).ready(function(){
	$("form").submit(
		function(){
			if($("#password").val()==''){
				alert("请填写密码");
				return false;
			}
			
			if($("#confirm_pass").val()==''){
				alert("请填写确认密码");
				return false;
			}
		}
	);
	});

</script>

</head>

<body>
<{include file="public/header.tpl"}>
<div id="body" class="web">
	
    <div class="content">
    	<div class="left_c">
        	<{include file="public/survey.tpl"}>
        </div>
        <div class="right_c">
            <div id="u_center">
            	<div class="u_c_tit">
                    <ul>
                        <li class="line"></li>
                        <li class="title">修改密码</li>
                    </ul>
                </div>
                <div id="forget">
                <ul>
                <form enctype="multipart/form-data" name="form1" action="<{$app}>/user/set_pass" method="post">
                    	<li>新改密码：&nbsp;&nbsp;<input type="password" class="input_box" name="password" id="password"/></li>
                        <li>确认密码：&nbsp;&nbsp;<input type="password" class="input_box" name="confirm_pass" id="confirm_pass" /></li>
                        <li><input type="hidden" name="id" value="<{$id}>" /><input type="hidden" name="ran_code" value="<{$ran_code}>" /><input type="submit" class="subbutton"  value="修改"/>
                </ul>
                </div>
            
        </div>
    </div>
    <div class="clear"></div>
</div>

<{include file="public/footer.tpl"}>
</body>
</html>
