<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<title>main</title>
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/survey/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;编辑调查</div></div>
<div class="info">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="left_color">主题</td>
        <td width="89%" align="left"  class="body_color"><input name="topic" type="text" class="input_box" value="<{$datas.topic}>"/><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">调查项目1</td>
        <td width="89%" align="left"  class="body_color"><input name="item1" type="text" class="input_box"  value="<{$datas.item1}>"/><span class="tips">*</span>&nbsp;<{$datas.result.item1}>票</td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">调查项目2</td>
        <td width="89%" align="left"  class="body_color"><input name="item2" type="text" class="input_box" value="<{$datas.item2}>"/><span class="tips">*</span>&nbsp;<{$datas.result.item2}>票</td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">调查项目3</td>
        <td width="89%" align="left"  class="body_color"><input name="item3" type="text" class="input_box" value="<{$datas.item3}>"/><span class="tips">*</span>&nbsp;<{$datas.result.item3}>票</td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">调查项目4</td>
        <td width="89%" align="left"  class="body_color"><input name="item4" type="text" class="input_box" value="<{$datas.item4}>"/><span class="tips">*</span>&nbsp;<{$datas.result.item4}>票</td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">调查项目5</td>
        <td width="89%" align="left"  class="body_color"><input name="item5" type="text" class="input_box" value="<{$datas.item5}>"/><span class="tips">*</span>&nbsp;<{$datas.result.item5}>票</td>
      </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="add" class="admin_button" value="提交" /></td>
      </tr>
      </table>
</div>
<input type="hidden" name="id" value="<{$datas.id}>" />
</form>
</body>
</html>
