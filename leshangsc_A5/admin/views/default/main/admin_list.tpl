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
<form enctype="multipart/form-data" action="<{$app}>/admin/del" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;管理员管理</div></div>
<div class="info" style="overflow-y:scroll;">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
      		<td width="9%" align="center" class="title_color"><span id="all_select" class="all_select">全选</span></td>
            <td width="10%" align="center" class="title_color">编号</td>
            <td width="30%" align="center" class="title_color">管理员名称</td>
            <td width="15%" align="center" class="title_color">最近登陆时间</td>
            <td width="13%" align="center" class="title_color">最近登陆IP</td>
            <td width="18%" align="center" class="title_color">操作</td>
          </tr>
          <{section name=sn loop=$datas}>
          <tr >
           <td align="center" class="body_color">
           <{if !$datas[sn].is_default}>
           <input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].id}>">
           <{/if}>
           </td>
            <td align="center" class="body_color"><{$datas[sn].id}></td>
            <td align="center" class="body_color"><{$datas[sn].adm_name}></td>
            <td align="center" class="body_color"><{$datas[sn].login_time|date_format:"%Y-%m-%d %H:%M:%S"}></td>
            <td align="center" class="body_color"><{$datas[sn].login_ip}></td>
            <td align="center" class="body_color"><{if $datas[sn].is_default}><span style="color:red">默认</span>&nbsp;&nbsp;<{else}><a href="<{$app}>/admin/set_default/id/<{$datas[sn].id}>">设为默认</a>&nbsp;&nbsp;<{/if}><a href="<{$app}>/admin/mod_index/id/<{$datas[sn].id}>">编辑</a><{if !$datas[sn].is_default}>&nbsp;&nbsp;<a href="<{$app}>/admin/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的管理员吗?')" >删除</a><{/if}></td>
          </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无内容!
	<{/if}>
</div>
<div class="fun_bar">
		<div class="oper"><input type="button" name="add" class="admin_button" value="新增" onClick="window.location.href='<{$app}>/admin/add_index'"/>&nbsp;<input type="submit" name="dels" class="admin_button" value="删除" onClick="return confirm('你确定要删除选中的分类吗?')" /></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>
</body>
</html>
