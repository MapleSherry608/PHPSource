<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/ueditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<{$public}>/ueditor/third-party/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<{$public}>/ueditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<{$public}>/ueditor/umeditor.min.js"></script>
<script type="text/javascript" src="<{$public}>/ueditor/lang/zh-cn/zh-cn.js"></script>
<title>main</title>
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/pcate/edit" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;编辑单页</div></div>
<div class="info">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="20%" align="right" class="left_color">名称</td>
        <td width="80%" align="left"  class="body_color"><input name="name" type="text" class="input_box" value="<{$datas.name}>"/><span class="tip">*</span></td>
        </tr>
      <tr>
        <td width="11%" align="right" class="left_color">内容</td>
        <td width="89%" align="left"  class="body_color"><textarea name="content" id="content"  style="width:800px;height:240px;" cols="80" rows="5"><{$datas.content}></textarea>
		<script type="text/javascript">  
		 var um = UM.getEditor('content');
         </script></td>
      </tr>
        <td align="right" class="left_color">排序</td>
        <td align="left" class="body_color"><input name="sort" type="text" class="input_box" value="<{$datas.sort}>"/><span class="tip">*</span></td>
        </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="add" class="admin_button" value="编辑" /></td>
      </tr>
      </table>
</div>
<input type="hidden" name="id" value="<{$datas.id}>" />
</form>
</body>
</html>
