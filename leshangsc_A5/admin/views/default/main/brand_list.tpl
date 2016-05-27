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
<form enctype="multipart/form-data" action="<{$app}>/brand/del" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;品牌管理</div></div>
<div class="info" style="overflow-y:scroll;">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="9%" align="center" class="title_color" ><span id="all_select" class="all_select">全选</span></td>
        <td width="10%" align="center" class="title_color">编号</td>
        <td width="13%" align="center" class="title_color">名称</td>
        <td width="12%" align="center" class="title_color">图片</td>
        <td width="20%" align="center" class="title_color">介绍</td>
        <td width="8%" align="center" class="title_color">排序</td>
        <td width="10%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
        <td align="center" bgcolor="#fff" ><input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].id}>"></td>
        <td align="center" bgcolor="#fff"><{$datas[sn].id}></td>
        <td align="center" bgcolor="#fff"><strong><{$datas[sn].name}></strong></td>
        <td align="center" bgcolor="#fff"><{if $datas[sn].img}><img src="<{$public}>/uploads/<{$datas[sn].img}>" width='100' height='50'><{else}>暂无<{/if}></td>
        <td align="center" bgcolor="#fff"><{$datas[sn].description|truncate:70:"..."}></td>
        <td align="center" bgcolor="#fff"><{$datas[sn].sort}></td>
        <td align="center" bgcolor="#fff"><a href="<{$app}>/brand/mod_index/id/<{$datas[sn].id}>">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/brand/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的分类吗?')" >删除</a></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无分类!
	<{/if}>
</div>
<div class="fun_bar">
	<div class="oper"><input type="button" name="add" class="admin_button" value="新增" onClick="window.location.href='<{$app}>/brand/add_index'"/>&nbsp;<input type="submit" name="dels" class="admin_button" value="删除" onClick="return confirm('你确定要删除选中的分类吗?')" /></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>
</body>
</html>
