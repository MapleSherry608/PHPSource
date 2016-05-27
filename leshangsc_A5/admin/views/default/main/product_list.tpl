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
		window.location.href="<{$app}>/product/index/id/"+p1;
	}) 
	$("#recommand").click(function(){
		$("#form").attr("action","<{$app}>/product/change_stat/name/is_recommend/value/1");
		$("#form").submit();
	});
	$("#cancel_recommand").click(function(){
		$("#form").attr("action","<{$app}>/product/change_stat/name/is_recommend/value/0");
		$("#form").submit();
	});
	$("#status").click(function(){
		$("#form").attr("action","<{$app}>/product/change_stat/name/status/value/1");
		$("#form").submit();
	});
	$("#cancel_status").click(function(){
		$("#form").attr("action","<{$app}>/product/change_stat/name/status/value/0");
		$("#form").submit();
	});
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
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;商品管理</div></div>
<div class="class_bar">
	<div class="left">当前栏目：<span id="current_class"><{$current_name}></span></div>
    <div class="right">
     <select name="cate" id="cate">
        <option value="" selected="selected">-选择栏目-</option>
        <option value="0">所有商品</option>
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
        <td width="6%" align="center" class="title_color">编号</td>
        <td width="20%" align="center" class="title_color">名称</td>
        <td width="10%" align="center" class="title_color">图片</td>
        <td width="11%" align="center" class="title_color">创建时间</td>
        <td width="11%" align="center" class="title_color">更新时间</td>
        <td width="8%" align="center" class="title_color">状态</td>
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
        <td align="center" class="body_color"><{if $datas[sn].is_recommend}><span class="green">[已推]</span><{else}><span class="red">[未推]</span><{/if}><{if $datas[sn].status}><span class="green">[已上架]</span><{else}><span class="red">[未上架]</span><{/if}></td>
        <td align="center" class="body_color"><{$datas[sn].sort}></td>
        <td align="center" class="body_color"><a href="<{$app}>/product/mod_index/id/<{$datas[sn].id}>">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/product/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的商品吗?')" >删除</a></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无商品!
	<{/if}>
</div>
<div class="fun_bar">
	<div class="oper"><input type="button" name="add" class="admin_button" value="新增" onClick="window.location.href='<{$app}>/product/add_index'"/>&nbsp;<input type="submit" name="dels" class="admin_button" value="删除" onClick="return confirm('你确定要删除选中的分类吗?')" />&nbsp;<input type="button" id="recommand" class="admin_button" value="批量推荐" />&nbsp;<input type="button" id="cancel_recommand" class="admin_button" value="批量取消推荐" />&nbsp;<input type="button" id="status" class="admin_button" value="批量上架" />&nbsp;<input type="button" id="cancel_status" class="admin_button" value="批量取消上架" /></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>
</body>
</html>
