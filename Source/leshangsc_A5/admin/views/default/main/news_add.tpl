<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/ueditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<{$public}>/ueditor/third-party/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<{$public}>/ueditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<{$public}>/ueditor/umeditor.min.js"></script>
<script type="text/javascript" src="<{$public}>/ueditor/lang/zh-cn/zh-cn.js"></script>
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
<form enctype="multipart/form-data" action="<{$app}>/news/add" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;添加新闻</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="left_color">标题</td>
        <td width="89%" align="left"  class="body_color"><input name="title" type="text" class="input_box"/><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">分类</td>
        <td width="89%" align="left"  class="body_color">
        <select name="cate">
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
        </select><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">图片</td>
        <td width="89%" align="left"  class="body_color"><input name="thumb" type="file" /></td>
      </tr>
      
      <tr>
        <td width="11%" align="right" class="left_color">内容</td>
        <td width="89%" align="left"  class="body_color"><textarea name="content" id="content" cols="80" rows="5" style="width:800px;height:240px;"></textarea>
		<script type="text/javascript">  
		 var um = UM.getEditor('content');
         </script></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">简介</td>
        <td width="89%" align="left"  class="body_color"><textarea name="description" cols="80" rows="5"></textarea>
		</td>
      </tr>
      <tr>
        <td align="right" class="left_color">排序</td>
        <td align="left" class="body_color"><input name="sort" type="text" class="input_box"/><span class="tips">*</span></td>
        </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit"  class="admin_button" value="提交" /></td>
      </tr>
      </table>
</div>
</form>
</body>
</html>
