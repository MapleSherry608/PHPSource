<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<title>main</title>
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/news/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;查看留言</div></div>
<div class="info">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="left_color">标题</td>
        <td width="89%" align="left"  class="body_color"><input readonly="readonly" name="title" type="text" class="input_box" value="<{$datas.title}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">内容</td>
        <td width="89%" align="left"  class="body_color"><textarea readonly="readonly" name="content" cols="80" rows="5"><{$datas.content}></textarea>
      </tr>
      </table>
</div>
<input type="hidden" name="id" value="<{$datas.id}>" />
</form>
</body>
</html>
