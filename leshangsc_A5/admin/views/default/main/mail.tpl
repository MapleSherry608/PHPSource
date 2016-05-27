<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<title>main</title>
<script type="text/javascript">
function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-58); } else { return false; } }
  $(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
	 
	 $("#send").click(function () {
		$("#list").hide();
		$("#hide").show();	
		return true;
	});
})
</script> 

</head>

<body>

<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;邮件服务器设置</div></div>
<div class="info" style="overflow-y:scroll;">
	<div id="list">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
	  <form enctype="multipart/form-data" action="<{$app}>/mails/mod" method="post">
      <tr>
        <td width="11%" align="right" class="title_color">SMTP服务器</td>
        <td width="89%" align="left"  class="body_color"><input name="mail_host" type="text" class="input_box" value="<{$datas.mail_host}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="title_color">服务器用户名</td>
        <td width="89%" align="left"  class="body_color"><input name="user_name" type="text" class="input_box" value="<{$datas.user_name}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="title_color">服务器密码</td>
        <td width="89%" align="left"  class="body_color"><input name="password" type="text" class="input_box" value="<{$datas.password}>"/></td>
      </tr>
     <tr>
        <td width="11%" align="right" class="title_color">端口</td>
        <td width="89%" align="left"  class="body_color"><input name="port" type="text" class="input_box" value="<{$datas.port}>"/></td>
      </tr>
      <tr>
        <td align="right" class="title_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="mod" class="admin_button" value="编辑" /></td>
      </tr>
	  </form>
	   <form enctype="multipart/form-data" action="<{$app}>/mails/test" method="post">
	  <tr>
        <td align="right" class="title_color">发送测试邮件</td>
        <td align="left" class="body_color"><input name="email" type="text" class="input_box" value=""/>&nbsp;&nbsp;<input type="submit" name="send" id="send" class="admin_button" value="发送" /></td>
      </tr>
	  </form>
      </table>
	  </div>
	   <div id="hide" style="display: none;" class="waiting">
		 <table width="500" border="0" cellspacing="0" cellpadding="30" align="center">
		  <tr>
			<td align="right"> 正在发送邮件，请稍等.......</td>
			<td> <img src="<{$public}>/images/loading.gif"></td>
		  </tr>
		</table>
		</div>
</div>

</body>
</html>
