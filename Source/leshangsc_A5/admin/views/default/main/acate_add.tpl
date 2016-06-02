<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<title>main</title>
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/acate/add" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;添加广告分类</div></div>
<div class="info">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="20%" align="right" class="title_color">名称</td>
        <td width="80%" align="left"  class="body_color"><input name="name" type="text" class="input_box"/><span class="tips">*</span></td>
        </tr>
      <tr>
        <td align="right" class="title_color">排序</td>
        <td align="left" class="body_color"><input name="sort" type="text" class="input_box"/><span class="tips">*</span></td>
        </tr>
      <tr>
        <td align="right" class="title_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="add" class="admin_button" value="提交" /></td>
      </tr>
      
      </table>
</div>
</form>
</body>
</html>
