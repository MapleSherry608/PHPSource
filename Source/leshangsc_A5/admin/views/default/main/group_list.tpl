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
<form enctype="multipart/form-data" action="<{$app}>/group/del" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;会员分组管理</div></div>
<div class="info" style="overflow-y:scroll;">
	<{if $main_list}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="9%" align="center" class="title_color" ><span id="all_select" class="all_select">全选</span></td>
        <td width="10%" align="center" class="title_color">编号</td>
        <td width="20%" align="center" class="title_color">名称</td>
        <td width="15%" align="center" class="title_color">积分</td>
        <td width="10%" align="center" class="title_color">排序</td>
        <td width="17%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$main_list}>
      <tr>
        <td align="center" bgcolor="#EBEBEB" ><input type="checkbox" name="id[]" id="checkbox" value="<{$main_list[sn].id}>"></td>
        <td align="center" bgcolor="#EBEBEB"><{$main_list[sn].id}></td>
        <td align="center" bgcolor="#EBEBEB"><strong><{$main_list[sn].name}></strong></td>
        <td align="center" bgcolor="#EBEBEB"><{$main_list[sn].score}></td>
        <td align="center" bgcolor="#EBEBEB"><{$main_list[sn].sort}></td>
        <td align="center" bgcolor="#EBEBEB"><{if !$main_list[sn].sub}><{if $main_list[sn].is_default}><span style="color:red">默认分组</span>&nbsp;|&nbsp;<{else}><a href="<{$app}>/group/set_default/id/<{$main_list[sn].id}>">设为默认</a>&nbsp;&nbsp;<{/if}><{/if}><a href="<{$app}>/group/mod_index/id/<{$main_list[sn].id}>">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/group/del/id/<{$main_list[sn].id}>" onClick="return confirm('你确定要删除选中的分类吗?')" >删除</a></td>
      </tr>
          <{section name=n loop=$sub_list}>
          <{if $sub_list[n].pid==$main_list[sn].id}>
          	<tr>
                <td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$main_list[sn].id}>"></td>
                <td align="center" class="body_color"><{$sub_list[n].id}></td>
                <td align="center" class="body_color" style="text-indent:3em;">└<{$sub_list[n].name}></td>
                <td align="center" class="body_color"><{$sub_list[n].score}></td>
                <td align="center" class="body_color"><{$sub_list[n].sort}></td>
                <td align="center" class="body_color"><{if $sub_list[n].is_default}><span style="color:red">默认分组</span>&nbsp;&nbsp;<{else}><a href="<{$app}>/group/set_default/id/<{$sub_list[n].id}>">设为默认</a>&nbsp;&nbsp;<{/if}><a href="<{$app}>/group/mod_index/id/<{$sub_list[n].id}>">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/group/del/id/<{$sub_list[n].id}>" onClick="return confirm('你确定要删除选中的分类吗?')" >删除</a></td>
              </tr>
          	<{/if}>
          <{/section}>
      <{/section}>
      </table>
	<{else}>
    	暂无分类!
	<{/if}>
</div>
<div class="fun_bar">
	<div class="oper"><input type="button" name="add" class="admin_button" value="新增" onClick="window.location.href='<{$app}>/group/add_index'"/>&nbsp;<input type="submit" name="dels" class="admin_button" value="删除" onClick="return confirm('你确定要删除选中的分类吗?')" /></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>
</body>
</html>
