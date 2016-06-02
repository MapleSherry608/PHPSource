<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<title>main</title>
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/message/del" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;留言管理</div></div>
<div class="info" style="overflow-y:scroll;">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="5%" align="center" class="title_color"><span id="all_select" class="all_select">全选</span></td>
        <td width="12%" align="center" class="title_color">编号</td>
        <td width="27%" align="center" class="title_color">标题</td>
        <td width="17%" align="center" class="title_color">留言时间</td>
        <td width="11%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
        <td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].id}>"></td>
        <td align="center" class="body_color"><{$datas[sn].id}></td>
        <td align="center" class="body_color"><{$datas[sn].title}></td>
        <td align="center" class="body_color"><{$datas[sn].create_time|date_format:"%Y-%m-%d %H:%M:%S"}></td>
        <td align="center" class="body_color"><a href="<{$app}>/message/message_show/id/<{$datas[sn].id}>">查看</a>&nbsp;&nbsp;<a href="<{$app}>/message/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的留言吗?')" >删除</a></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无内容!
	<{/if}>
</div>
<div class="fun_bar">
	<div class="oper"><input type="submit" name="dels" class="admin_button" value="删除" onClick="return confirm('你确定要删除选中的分类吗?')" /></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>
</body>
</html>
