<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<title>main</title>
<script type="text/javascript">
function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-58); } else { return false; } }
  $(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
})
</script> 

</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/appraise/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;回复评价</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="left_color">商品名称</td>
        <td width="89%" align="left"  class="body_color"><{$datas.product.name}></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">用户名</td>
        <td width="89%" align="left"  class="body_color">
        <{$datas.user.user_name}></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">评价级别</td>
        <td width="89%" align="left"  class="body_color">
        <{$datas.level|replace:"1":"<span style='color:red'>好评</span>"|replace:"2":"<span style='color:grey'>中评</span>"|replace:"3":"<span style='color:green'>差评</span>"}></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">评价时间</td>
        <td width="89%" align="left"  class="body_color"><{$datas.content_time|date_format:"%Y-%m-%d %H:%M:%S"}></td>
        </tr>
      <tr>
      <tr>
        <td width="11%" align="right" class="left_color">内容</td>
        <td width="89%" align="left"  class="body_color"><{$datas.content}></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">回复</td>
        <td width="89%" align="left"  class="body_color"><textarea name="reply" cols="80" rows="5"><{$datas.reply}></textarea></td>
      </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="mod" class="admin_button" value="提交" /></td>
      </tr>
      </table>
</div>
<input type="hidden" name="id" value="<{$datas.id}>" />
</form>
</body>
</html>
