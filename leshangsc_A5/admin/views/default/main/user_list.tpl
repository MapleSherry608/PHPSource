<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/css/weebox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<title>main</title>
<script language="javascript" type="text/javascript"> 

function autoHeight() { 
	var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-155); } else { return false; } 
}
$(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
	
	
})
$(document).ready(function(){
	$(".mod").click(
		function(){
			var id=$(this).attr("id");
			open_url='<{$app}>/user/mod_index/id/'+id;
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '编辑会员', showButton:false,animate: true,width:590, height: 520,clickClose: false});	
		}
	);
});

</script> 
</head>

<body>

<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;用户管理</div></div>
<form action="<{$app}>/user/index/search/1" method="get">
<div class="class_bar">
	<div class="left">搜索内容：<{if $is_search}>搜索列表<{else}>所有列表<{/if}></div>
    <div class="right">
     搜索条件:&nbsp;<input type="text" name="key" style="width:130px"/>&nbsp;&nbsp;<input type="submit" value="搜索" id="user-search" />
     </div>
     <div class="clear"></div>
</div>
</form>
<form enctype="multipart/form-data" action="<{$app}>/user/del" method="post">
<div class="info" style="overflow-y:scroll;">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="6%" align="center" class="title_color"><span id="all_select" class="all_select">全选</span></td>
        <td width="6%" align="center" class="title_color">编号</td>
        <td width="11%" align="center" class="title_color">用户</td>
        <td width="16%" align="center" class="title_color">签名</td>
        <td width="10%" align="center" class="title_color">电话</td>
         <td width="8%" align="center" class="title_color">积分</td>
        <td width="15%" align="center" class="title_color">邮箱</td>
        <td width="12%" align="center" class="title_color">地址</td>
        <td width="9%" align="center" class="title_color">头像</td>
        <td width="13%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
        <td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].id}>"></td>
        <td align="center" class="body_color"><{$datas[sn].id}></td>
        <td align="center" class="body_color"><{$datas[sn].user_name}></td>
        <td align="center" class="body_color"><{$datas[sn].signature}></td>
        <td align="center" class="body_color"><{$datas[sn].phone}></td>
        <td align="center" class="body_color"><{$datas[sn].score}></td>
         <td align="center" class="body_color"><{$datas[sn].email}></td>
         <td align="center" class="body_color"><{$datas[sn].address}></td>
         <td align="center" class="body_color"><{if $datas[sn].photo!=""}><img src="<{$public}>/uploads/<{$datas[sn].photo}>" width=50 height=50 ><{else}><img src="<{$res}>/images/user_default.gif" width=50 height=50 /><{/if}></td>
        <td align="center" class="body_color"><a href="javascript:;" class="mod" id="<{$datas[sn].id}>">编辑</span>&nbsp;&nbsp;<a href="<{$app}>/user/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的分类吗?')" >删除</a></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无用户!
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
