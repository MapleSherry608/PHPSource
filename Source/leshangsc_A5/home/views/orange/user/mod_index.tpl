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
<script type="text/javascript" src="<{$public}>/js/jquery.lazyload.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/koala.min.1.5.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script>
var APP_PATH="<{$app}>";var ISLOGIN="<{$user.id}>";
$(document).ready(function(e) {
	
	
});
</script>
</head>

<body>
<{include file="public/header.tpl"}>
<div id="body" class="web">
	<div class="left_c">
        <{include file="user/left_info.tpl"}>
    </div>
    <div class="right_c">
    
    	<div class="u_r_box">
        	<div class="tit">
            	<span class="r_icon_11"></span>
        		<span class="txt">我的资料</span>
               
            </div>
            
           
            
            <div class="content">
            	<div class="profile">
            <form enctype="multipart/form-data" name="form1" id="mod_form" action="<{$app}>/user/mod" method="post">
            	 <table width="100%" border="0" cellspacing="0" cellpadding="10">
              <tr>
                <td width="15%" align="right">用户名：</td>
                <td width="85%"><input type="text" class="input_box" id="user_name_r" name="user_name" value="<{$datas.user_name}>"/></td>
              </tr>
              <tr>
                <td align="right">密码：</td>
                <td><input type="password" class="input_box" name="password"  /></td>
              </tr>
              <tr>
                <td align="right">确认密码：</td>
                <td><input type="password" class="input_box" name="confirm_pass"  /></td>
              </tr>
              <tr>
                <td align="right">签名：</td>
                <td ><input type="text" class="input_box" name="signature" value="<{$datas.signature}>"/></td>
              </tr>
              <tr>
                <td align="right">邮箱：</td>
                <td><input type="text" class="input_box" name="email" value="<{$datas.email}>"/></td>
              </tr>
              <tr>
                <td align="right">电话：</td>
                <td><input type="text" class="input_box" name="phone" value="<{$datas.phone}>"/></td>
              </tr>
              <tr>
                <td align="right">地址：</td>
                <td><input type="text" class="input_box" name="address" value="<{$datas.address}>"/></td>
              </tr>
              <tr>
                <td align="right"></td>
                <td><input type="submit" class="sub_button"  value="修 改"/></td>
              </tr>
            </table>
            </div>
            <input type="hidden" name="id" value="<{$datas.id}>" />
    		</form>
            </div>
            <div class="clear"></div>
        </div>
        
       
        
        
        
    </div>
    
   
    <div class="clear"></div>
</div>

<{include file="public/footer.tpl"}>
</body>
</html>
