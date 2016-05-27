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
		window.location.href="<{$app}>/product/publish_index/id/"+p1;
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
<form id="form" enctype="multipart/form-data" action="<{$app}>/product/del" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;会员商品管理</div></div>
<div class="class_bar">
	<div class="left">当前栏目：<span id="current_class"><{$current_name}></span></div>
    <div class="right">
     <select name="cate" id="cate">
      <option value="" selected="selected">-选择栏目-</option>
        <option value="0">所有商品</option>
                        <{section name=sn loop=$nav_datas}>
                        	<{if $nav_datas[sn].has_sub}>
                				<optgroup label="<{$nav_datas[sn].name}>">
                            <{else}>
                               	<option value="<{$nav_datas[sn].nav_id}>"><{$nav_datas[sn].name}></option>
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
        <td width="6%" align="center" class="title_color">编号</td>
        <td width="20%" align="center" class="title_color">名称</td>
        <td width="10%" align="center" class="title_color">图片</td>
        <td width="11%" align="center" class="title_color">创建时间</td>
        <td width="11%" align="center" class="title_color">更新时间</td>
        <td width="8%" align="center" class="title_color">会员</td>
        <td width="6%" align="center" class="title_color">排序</td>
        <td width="11%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
        <td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].id}>"></td>
        <td align="center" class="body_color"><{$datas[sn].id}></td>
        <td align="center" class="body_color"><{$datas[sn].name}>&nbsp;<span style="color:green">[点击<{$datas[sn].click}>次]</span></td>
        <td align="center" class="body_color"><{if $datas[sn].img}><img src="<{$public}>/uploads/<{$datas[sn].img}>" width="50" height="50"/><{else}><img src="<{$public}>/images/no_pic.jpg" width="50" height="50"/><{/if}></td>
        <td align="center" class="body_color"><{$datas[sn].add_time|date_format:"%Y-%m-%d %H:%M:%S"}></td>
        <td align="center" class="body_color"><{if $datas[sn].update_time}><{$datas[sn].update_time|date_format:"%Y-%m-%d %H:%M:%S"}><{else}>暂无<{/if}></td>
        <td align="center" class="body_color"><{$datas[sn].user.user_name}></td>
        <td align="center" class="body_color"><{$datas[sn].sort}></td>
        <td align="center" class="body_color"><{if $datas[sn].verify}><span style="color:green">已审核</span><{else}><a href="<{$app}>/product/verify/id/<{$datas[sn].id}>/user_id/<{$datas[sn].user_id}>" style="color:red">审核</a><{/if}>&nbsp;&nbsp;<a href="<{$app}>/product/mod_publish_index/id/<{$datas[sn].id}>">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/product/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的商品吗?')" >删除</a></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无商品!
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
