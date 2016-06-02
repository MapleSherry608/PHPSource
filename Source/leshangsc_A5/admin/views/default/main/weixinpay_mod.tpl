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
<form enctype="multipart/form-data" action="<{$app}>/payment/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;编辑支付方式</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="left_color">接口名称</td>
        <td width="89%" align="left"  class="body_color"><input name="byname" type="text" class="input_box" value="<{$datas.byname}>"/><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">描述</td>
        <td width="89%" align="left"  class="body_color"><input name="introduction" type="text" class="input_box" value="<{$datas.introduction}>"/><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">介绍</td>
        <td width="89%" align="left"  class="body_color"><textarea name="notes" cols="80" rows="5"><{$datas.notes}></textarea></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">APPID</td>
        <td width="89%" align="left"  class="body_color"><input name="partner_id" type="text" class="input_box" value="<{$datas.partner_id}>"/></td>
      </tr>
	  <tr>
        <td width="11%" align="right" class="left_color">MCHID商户号</td>
        <td width="89%" align="left"  class="body_color"><input name="parameter1" type="text" class="input_box" value="<{$datas.parameter1}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">商户支付密钥(Key)</td>
        <td width="89%" align="left"  class="body_color"><input name="authkey" type="text" class="input_box" value="<{$datas.authkey}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">AppSecret</td>
        <td width="89%" align="left"  class="body_color"><input name="parameter2" type="text" class="input_box" value="<{$datas.parameter2}>"/>%</td>
      </tr>
    
      <tr>
        <td align="right" class="left_color">排序</td>
        <td align="left" class="body_color"><input name="sort" type="text" class="input_box" value="<{$datas.sort}>"/><span class="tips">*</span></td>
        </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="mod" class="admin_button" value="编辑" /></td>
      </tr>
      </table>
</div>
<input type="hidden" name="id" value="<{$datas.id}>" />
</form>
</body>
</html>