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
	 
	 $('#res_dir').change(function(){ 
		var p1=$(this).val();//这就是selected的值
		
		window.location.href="<{$app}>/code/index/id/"+p1;
	}) 
	
	$("#backup").click(function () {
		$("#list").hide();
		$("#hide").show();	
		return true;
	});
	$("#restore").click(function () {
		$("#list").hide();
		$("#hide").show();	
		return true;
	});
});

function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-155); } else { return false; } }
  $(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
})

</script>
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/code/dels" method="post">


<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;在线模板管理</div></div>


<div class="class_bar">
	<div class="left">当前目录：<span id="current_class"><{$res_dir_list.$current_dir}></span></div>
    <div class="right">
    <select name="res_dir" id="res_dir">
        <option value="" selected="selected">-选择目录-</option>
        
			<{foreach key=key item=item from=$res_dir_list}>
                     <option value="<{$key}>"><{$item}></option>
            <{/foreach}>
            </select>
     </div>
     <div class="clear"></div>
</div>

<div class="info" style="overflow-y:scroll;">
	<div id="list">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="center" class="title_color"><span id="all_select" class="all_select">全选</span></td>
        <td width="33%" align="center" class="title_color">文件名</td>
        <td width="19%" align="center" class="title_color">尺寸</td>
        <td width="20%" align="center" class="title_color">时间</td>
        <td width="17%" align="center" class="title_color">操作</td>
      </tr>
	  
	  <{if $current_dir=="tpl"}>
	  <{foreach key=key item=item from=$dir}>
		 <tr>
			<td style="background:#eaeaea" colspan="5"><{$item}>(<{$key}>)</td>
		 </tr>
		  <{foreach key=k item=data from=$datas.$key}>

		  <tr>
			<td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$data.file}>"></td>
			<td align="center" class="body_color"><{$data.file}></td>
			<td align="center" class="body_color"><{$data.size}></td>
			<td align="center" class="body_color"><{$data.time}></td>
			<td align="center" class="body_color"><a href="<{$app}>/code/mod_index/file/<{$data.file}>/dir/<{$key}>/current_dir/tpl" id="mod">编辑</a></td>
		  </tr>
		  <{/foreach}>
	  <{/foreach}>
	  <{else}>
	  <{foreach key=k item=data from=$datas}>

		  <tr>
			<td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$data.file}>"></td>
			<td align="center" class="body_color"><{$data.file}></td>
			<td align="center" class="body_color"><{$data.size}></td>
			<td align="center" class="body_color"><{$data.time}></td>
			<td align="center" class="body_color"><a href="<{$app}>/code/mod_index/file/<{$data.file}>/dir/<{$current_dir}>/current_dir/<{$current_dir}>" id="mod">编辑</a></td>
		  </tr>
		  <{/foreach}>
		  
	  <{/if}>
	  
      </table>
	  
	<{else}>
    	暂无模板文件!
	<{/if}>
    </div>
	</div>
   
<div class="fun_bar">
	<div class="oper"></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>

</body>
</html>
