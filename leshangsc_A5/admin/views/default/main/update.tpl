<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<title>main</title>
<script type="text/javascript"> 
$(document).ready(function()
{
	$("#update").click(function () {
		$("#list").hide();
		$("#hide").show();	
		return true;
	});
	
});
</script>
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/update/check" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;在线升级</div></div>
<div class="info">
	<div id="list">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td align="center" class="body_color">
        当前版本：<{$version}>&nbsp;&nbsp;<span style="color:red">提示：升级过程中请不要进行其他操作，直到提示升级完成!</span></td>
        </tr>
      <tr>
        <td align="center" bgcolor="#E8E8E8"><input type="submit" name="update" id="update" class="admin_button" value="检测升级" /></td>
        </tr> 
      </table>
	  </div>
	  
	  <div id="hide" style="display: none;" class="waiting">
     <table width="500" border="0" cellspacing="0" cellpadding="30" align="center">
      <tr>
        <td align="right"> 正在升级文件.......请耐心等待!</td>
        <td> <img src="<{$public}>/images/loading.gif"></td>
      </tr>
    </table>
    </div>
</div>
</form>

</body>
</html>
