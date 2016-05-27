<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<title>main</title>
<script type="text/javascript"> 
$(document).ready(function()
{
});
</script>
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/cache/dels" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;无效图片管理</div></div>
<div class="info" style="overflow-y:scroll;">
	<div id="list">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="8%" align="center" class="title_color"><span id="all_select" class="all_select">全选</span></td>
        <td width="15%" align="center" class="title_color">文件名</td>
        <td width="20%" align="center" class="title_color">文件名</td>
        <td width="19%" align="center" class="title_color">尺寸</td>
        <td width="20%" align="center" class="title_color">时间</td>
        <td width="25%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
        <td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].file}>"></td>
        <td align="center" class="body_color"><img src="<{$public}>/uploads/<{$datas[sn].file}>" width='50' height='50'></td>
        <td align="center" class="body_color"><{$datas[sn].file}></td>
        <td align="center" class="body_color"><{$datas[sn].size}></td>
        <td align="center" class="body_color"><{$datas[sn].time}></td>
        <td align="center" class="body_color"><a href="<{$app}>/cache/del/file/<{$datas[sn].file}>" onClick="return confirm('你确定要删除选中的文件吗?')" >删除</a></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无无效图片文件!
	<{/if}>
    </div>
    
</div>
<div class="fun_bar">
	<div class="oper"><input type="submit" name="dels" class="admin_button" value="删除" onClick="return confirm('你确定要删除选中的项目吗?')" /></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>

</body>
</html>
