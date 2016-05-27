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
<form enctype="multipart/form-data" action="<{$app}>/adv/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;编辑广告</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="left_color">名称</td>
        <td width="89%" align="left"  class="body_color"><input name="name" type="text" class="input_box" value="<{$datas.name}>"/><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">分类</td>
        <td width="89%" align="left"  class="body_color">
        <select name="cate">
        <{section name=sn loop=$acate}>
            <option <{if $acate[sn].id==$datas.cate}> selected="selected" <{/if}> value="<{$acate[sn].id}>"><{$acate[sn].name}></option>
        <{/section}>
        </select><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">链接</td>
        <td width="89%" align="left"  class="body_color"><input name="url" type="text" class="input_box" value="<{$datas.url}>"/><span class="tips">*</span></td>
        </tr>
      <tr>
      <tr>
        <td width="11%" align="right" class="left_color">图片</td>
        <td width="89%" align="left"  class="body_color"><input name="pic" type="file" /></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">介绍</td>
        <td width="89%" align="left"  class="body_color"><textarea name="intro" cols="80" rows="5"><{$datas.intro}></textarea></td>
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
<input type="hidden" name="user_id" value="<{$datas.user_id}>" />
<input type="hidden" name="verify" value="<{$datas.verify}>" />
<input type="hidden" name="pic" value="<{$datas.pic}>" />
<input type="hidden" name="id" value="<{$datas.id}>" />
</form>
</body>
</html>
