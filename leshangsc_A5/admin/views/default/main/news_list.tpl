<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<title>main</title>
<script language="javascript" type="text/javascript"> 
$(document).ready(function(){ 
	$('#cate').change(function(){ 
		var p1=$(this).val();//这就是selected的值
		window.location.href="<{$app}>/news/index/id/"+p1;
	}) 
}) 
function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-155); } else { return false; } }
  $(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
})
</script> 

</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/news/del" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;新闻管理</div></div>
<div class="class_bar">
	<div class="left">当前栏目：<span id="current_class"><{$current_name}></span></div>
    <div class="right">
    <select name="cate" id="cate">
        <option value="" selected="selected">-选择栏目-</option>
        <option value="0">所有新闻</option>
            <{section name=sn loop=$main}>
            	<{if $main[sn].has_sub}>
                <optgroup label="<{$main[sn].name}>">
                    <{section name=n loop=$sub}>
                        <{if $sub[n].pid==$main[sn].id}>
                          <option value="<{$sub[n].id}>"><{$sub[n].name}></option>
                        <{/if}>
                    <{/section}>
                </optgroup>
                <{else}>
                <option value="<{$main[sn].id}>"><{$main[sn].name}></option>
                <{/if}>
            <{/section}>
            </select>
     </div>
     <div class="clear"></div>
</div>
<div class="info" style="overflow-y:scroll;">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="5%" align="center" class="title_color"><span id="all_select" class="all_select">全选</span></td>
        <td width="12%" align="center" class="title_color">编号</td>
        <td width="23%" align="center" class="title_color">标题</td>
        <td width="13%" align="center" class="title_color">创建时间</td>
        <td width="13%" align="center" class="title_color">更新时间</td>
        <td width="10%" align="center" class="title_color">有图</td>
        <td width="6%" align="center" class="title_color">排序</td>
		<td width="6%" align="center" class="title_color">推荐</td>
        <td width="10%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
        <td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].id}>"></td>
        <td align="center" class="body_color"><{$datas[sn].id}></td>
        <td align="center" class="body_color"><{$datas[sn].title}></td>
        <td align="center" class="body_color"><{$datas[sn].create_time|date_format:"%Y-%m-%d %H:%M:%S"}></td>
        <td align="center" class="body_color"><{if $datas[sn].update_time}><{$datas[sn].update_time|date_format:"%Y-%m-%d %H:%M:%S"}><{else}>暂无<{/if}></td>
        <td align="center" class="body_color"><{if $datas[sn].thumb}><span style="color:green">有图</span><{else}><span style="color:red">无图</span><{/if}></td>
        <td align="center" class="body_color"><{$datas[sn].sort}></td>
		<td align="center" class="body_color"><{$datas[sn].recommand}>次</td>
        <td align="center" class="body_color"><a href="<{$app}>/news/mod_index/id/<{$datas[sn].id}>">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/news/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的新闻吗?')" >删除</a></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无新闻!
	<{/if}>
</div>
<div class="fun_bar">
	<div class="oper"><input type="button" name="add" class="admin_button" value="新增" onClick="window.location.href='<{$app}>/news/add_index'"/>&nbsp;<input type="submit" name="dels" class="admin_button" value="删除" onClick="return confirm('你确定要删除选中的分类吗?')" /></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>
</body>
</html>
