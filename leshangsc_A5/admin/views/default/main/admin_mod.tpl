<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$public}>/ckeditor/ckeditor.js"></script>
<title>main</title>
<script type="text/javascript">
function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-58); } else { return false; } }
  $(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
})
</script> 

</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/admin/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;编辑管理员</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="left_color">用户名</td>
        <td width="89%" align="left"  class="body_color"><input name="adm_name" type="text" class="input_box" value="<{$datas.adm_name}>"/><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">分组</td>
        <td width="89%" align="left"  class="body_color">
        <select name="group_id">
        <{section name=sn loop=$g_datas}>
            <option <{if $g_datas[sn].id==$datas.group_id}> selected="selected" <{/if}> value="<{$g_datas[sn].id}>"><{$g_datas[sn].name}></option>
        <{/section}>
        </select><span class="tips">*</span></td>
      </tr>

      <tr>
        <td align="right" class="left_color">密码</td>
        <td align="left" class="body_color"><input  name="adm_password" type="password" class="input_box" /><span class="tips">*</span></td>
        </tr>
      <tr>
        <td align="right" class="left_color">确认密码</td>
        <td align="left" class="body_color"><input  name="confirm_pass" type="password" class="input_box" /><span class="tips">*</span></td>
        </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="mod" class="admin_button" value="编辑" /></td>
      </tr>
      </table>
</div>
<input type="hidden" name="id" value="<{$datas.id}>" />
</form>
</body>
</html>
