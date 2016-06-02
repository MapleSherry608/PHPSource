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
})
</script> 

</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/nav/add" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;添加导航</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="20%" align="right" class="left_color">名称</td>
        <td width="80%" align="left"  class="body_color"><input name="name" type="text" class="input_box"/><span class="tips">*</span></td>
        </tr>
      <tr>
        <td width="11%" align="right" class="left_color">所属栏目</td>
        <td width="89%" align="left"  class="body_color">
        <select name="pid">
         <option value="0">一级栏目</option>
        <{section name=sn loop=$datas}>
            <option value="<{$datas[sn].id}>"><{$datas[sn].name}></option>
        <{/section}>
        </select><span class="tips">*</span></td>
      </tr>
       <tr>
        <td width="11%" align="right" class="left_color">模块</td>
        <td width="89%" align="left"  class="body_color">
        <select name="module_id">
        	<{section name=sn loop=$module_list}>
             <option value="<{$module_list[sn].id}>"><{$module_list[sn].name}></option>
         	<{/section}>
        </select><span class="tips">*</span></td>
      </tr>
      <tr>
        <td align="right" class="left_color">排序</td>
        <td align="left" class="body_color"><input name="sort" type="text" class="input_box"/><span class="tips">*</span></td>
        </tr>
      <tr>
	  <tr>
        <td align="right" class="left_color">是否显示</td>
        <td align="left" class="body_color"><input name="display" type="radio" value="1" checked="checked" />显示&nbsp;&nbsp;<input name="display" type="radio" value="0" />隐藏</td>
        </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="add" class="admin_button" value="提交" /></td>
      </tr>
      
      </table>
</div>
</form>
</body>
</html>
