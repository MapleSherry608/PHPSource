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
<form enctype="multipart/form-data" action="<{$app}>/module/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;编辑模块</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="20%" align="right" class="left_color">名称</td>
        <td width="80%" align="left"  class="body_color"><input name="name" type="text" class="input_box" value="<{$datas.name}>"/><span class="tips">*</span></td>
        </tr>
      <tr>
        <td width="20%" align="right" class="left_color">分类名称</td>
        <td width="80%" align="left"  class="body_color"><input name="classname" type="text" class="input_box" value="<{$datas.classname}>"/><span class="tips">*</span></td>
        </tr>
		 <tr>
        <td width="20%" align="right" class="left_color">类型</td>
        <td width="80%" align="left"  class="body_color">
        	<select name="type">
            	<option value="1" <{if $datas.type==1}> selected <{/if}> >普通商品</option>
                <option value="2" <{if $datas.type==2}> selected <{/if}> >新闻资讯</option>
				<option value="3" <{if $datas.type==3}> selected <{/if}> >品牌商品</option>
            </select>
        </td>
        </tr>
      <tr>
        <td align="right" class="left_color">排序</td>
        <td align="left" class="body_color"><input name="sort" type="text" class="input_box" value="<{$datas.sort}>"/><span class="tips">*</span></td>
        </tr>
       <tr>
        <td width="20%" align="right" class="left_color">允许会员发布</td>
        <td width="80%" align="left"  class="body_color"><input type="radio" name="is_user_pub" value="0" <{if $datas.is_user_pub==0}>checked="checked"<{/if}>/>否&nbsp;<input type="radio" name="is_user_pub" value="1" <{if $datas.is_user_pub==1}>checked="checked"<{/if}> />是</td>
        </tr>
       <tr>
        <td align="right" class="left_color">权限</td>
        <td align="left" class="body_color">
        	
            	<table width="100%" border="0" cellspacing="8" cellpadding="0">
                <{section name=sn loop=$main_list}>
                      <tr>
                        <td height="20" style="border-bottom:1px dotted #ccc;">
                        <{if !$main_list[sn].sub}>
                        	
                        		<input type="checkbox" name="auth[]" value="<{$main_list[sn].id}>" 
                                <{section name=a loop=$datas.auth}>
                                <{if $datas.auth[a]==$main_list[sn].id}>checked="checked" <{/if}>
                                <{/section}>
                                />
                            
                        <{/if}>
                        <strong><{$main_list[sn].name}></strong></td>
                      </tr>
                      <{section name=n loop=$sub_list}>
                      <{if $sub_list[n].pid==$main_list[sn].id}>
                      	<tr>
                        	<td style="text-indent:3em">
                            	<input type="checkbox" name="auth[]" value="<{$sub_list[n].id}>" 
                                <{section name=b loop=$datas.auth}>
                                <{if $datas.auth[b]==$sub_list[n].id}>checked="checked" <{/if}>
                                <{/section}> />
                            <{$sub_list[n].name}>
                            </td>
                        </tr>
                      <{/if}>
                      <{/section}>
                <{/section}>
                </table>
            
        </td>
        </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="add" class="admin_button" value="编辑" /></td>
      </tr>
      </table>
</div>
<input type="hidden" name="id" value="<{$datas.id}>" />
</form>
</body>
</html>
