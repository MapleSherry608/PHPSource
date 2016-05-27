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
<form enctype="multipart/form-data" action="<{$app}>/appraise/del" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;商品评价</div></div>
<div class="info" style="overflow-y:scroll;">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
      <td width="6%" align="center" class="title_color"><span id="all_select" class="all_select">全选</span></td>
        <td width="8%" align="center" class="title_color">编号</td>
        <td width="8%" align="center" class="title_color">商品图</td>
        <td width="10%" align="center" class="title_color">商品名称</td>
        <td width="10%" align="center" class="title_color">评价级别</td>
        <td width="20%" align="center" class="title_color">内容与回复</td>
         <td width="8%" align="center" class="title_color">用户名</td>
        <td width="10%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
      	<td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].id}>"></td>
        <td align="center" class="body_color"><{$datas[sn].id}></td>
        <td align="center" class="body_color"><img src="<{$public}>/uploads/<{$datas[sn].product.img}>" width=40 height=40></td>
        <td align="center" class="body_color"><{$datas[sn].product.name}></td>
        <td align="center" class="body_color"><{$datas[sn].level|replace:"1":"<span style='color:red'>好评</span>"|replace:"2":"<span style='color:grey'>中评</span>"|replace:"3":"<span style='color:green'>差评</span>"}></td>
        <td align="center" class="body_color"><span style="color:red">评价:<{$datas[sn].content}>&nbsp;[<{$datas[sn].content_time|date_format:'%Y-%m-%d %H:%M:%S'}>]</span>&nbsp;<br /><span style="color:green">回复:<{if $datas[sn].reply}><{$datas[sn].reply}><{else}>暂无<{/if}>&nbsp;[<{$datas[sn].reply_time|date_format:'%Y-%m-%d %H:%M:%S'}>]</span></td>
        <td align="center" class="body_color"><{$datas[sn].user.user_name}></td>
        <td align="center" class="body_color"><a href="<{$app}>/appraise/feedback_index/id/<{$datas[sn].id}>">回复</a>&nbsp;&nbsp;<a href="<{$app}>/appraise/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的内容吗?')" >删除</a></td>
      </tr>
      <{/section}>
      </table>
	<{else}>
    	暂无内容!
	<{/if}>
</div>
<div class="fun_bar">
	<div class="oper"><input type="submit" name="dels" class="admin_button" value="删除" onClick="return confirm('你确定要删除选中的项目吗?')" /></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>
</body>
</html>
