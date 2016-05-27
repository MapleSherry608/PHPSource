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
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;支付管理</div></div>
<div class="info" style="overflow-y:scroll;">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="10%" align="center" class="title_color">编号</td>
        <td width="13%" align="center" class="title_color">名称</td>
        <td width="20%" align="center" class="title_color">介绍</td>
         <td width="8%" align="center" class="title_color">手序费</td>
        <td width="8%" align="center" class="title_color">排序</td>
        <td width="10%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
        <td align="center" bgcolor="#EBEBEB"><{$datas[sn].id}></td>
        <td align="center" bgcolor="#EBEBEB"><strong><{$datas[sn].byname}></strong></td>
        <td align="center" bgcolor="#EBEBEB"><{$datas[sn].introduction|truncate:30:"..."}></td>
        <td align="center" bgcolor="#EBEBEB"><{$datas[sn].fee}>%</td>
        <td align="center" bgcolor="#EBEBEB"><{$datas[sn].sort}></td>
        <td align="center" bgcolor="#EBEBEB"><{if $datas[sn].p_install==2}><a href="<{$app}>/payment/mod_index/id/<{$datas[sn].id}>/name/<{$datas[sn].typename}>">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/payment/uninstall/id/<{$datas[sn].id}>" onClick="return confirm('你确定要卸载吗?')" >卸载</a><{else}><a href="<{$app}>/payment/install/id/<{$datas[sn].id}>">安装</a><{/if}></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无分类!
	<{/if}>
</div>
<div class="fun_bar">
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</body>
</html>
