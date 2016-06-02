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
<form enctype="multipart/form-data" action="<{$app}>/hotword/del" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;关键字管理</div></div>
<div class="info" style="overflow-y:scroll;">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="7%" align="center" class="title_color" ><span id="all_select" class="all_select">全选</span></td>
        <td width="10%" align="center" class="title_color">编号</td>
        <td width="25%" align="center" class="title_color">关键字</td>
        <td width="8%" align="center" class="title_color">搜索次数</td>
		<td width="8%" align="center" class="title_color">类型</td>
        <td width="10%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
        <td align="center" bgcolor="#fff" ><input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].id}>"></td>
        <td align="center" bgcolor="#fff"><{$datas[sn].id}></td>
        <td align="center" bgcolor="#fff"><strong><{$datas[sn].keyword}></strong></td>
        <td align="center" bgcolor="#fff"><{$datas[sn].times}>次</td>
		<td align="center" bgcolor="#fff"><{$datas[sn].type|replace:"1":"商品"|replace:"2":"新闻"}></td>
        <td align="center" bgcolor="#fff"><a href="<{$app}>/hotword/mod_index/id/<{$datas[sn].id}>">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/hotword/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的分类吗?')" >删除</a></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无分类!
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
