<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<title>main</title>
<script type="text/javascript">
$(document).ready(function(){
	$("#clear").click(function(){
		var cache=$("#cache").attr("checked");
		var compile_cache=$("#compile_cache").attr("checked");
		var runtime_cache=$("#runtime_cache").attr("checked");
		if(!cache && !compile_cache && !runtime_cache){
			alert("必须选择一项");
			return false;
		}
	});
});
</script>

</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/cache/clear" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;清除缓存</div></div>
<div class="info">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td align="center" class="body_color"><label ><input id="cache" type="checkbox" checked="checked"  name="cache" value="1"/>
        模板缓存</label> <label >
        <input id="compile_cache" type="checkbox"   value="1" name="compile_cache" checked="checked"  />
       编译缓存</label>
	    <input id="runtime_cache" type="checkbox"   value="1" name="runtime_cache" checked="checked"  />
       即时运行缓存</label>
	   </td>
        </tr>
		<tr>
			<td align="center" class="body_color" style="color:red"><{if $status}>已成功清除缓存，请刷新前台页面!<{/if}></td>
		</tr>
      <tr>
        <td align="center" bgcolor="#E8E8E8"><input type="submit" name="clear" id="clear" class="admin_button" value="清除" /></td>
        </tr> 
      </table>
</div>
</form>

</body>
</html>
