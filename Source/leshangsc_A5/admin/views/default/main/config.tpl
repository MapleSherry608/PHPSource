<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/ueditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
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
<form enctype="multipart/form-data" action="<{$app}>/config/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;网站设置</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="body_color">网站标题</td>
        <td width="89%" align="left"  class="body_color"><input name="site_name" type="text" class="input_box" value="<{$datas.site_name}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">关键字</td>
        <td width="89%" align="left"  class="body_color"><input name="key_word" type="text" class="input_box" value="<{$datas.key_word}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">介绍</td>
        <td width="89%" align="left"  class="body_color"><textarea name="description" cols="50" rows="5"><{$datas.description}></textarea></td>
      </tr>
     <tr>
        <td width="11%" align="right" class="body_color">发布文章审核</td>
        <td width="89%" align="left"  class="body_color"><input name="auto_news_verify" type="radio" value="1" <{if $datas.auto_news_verify}>checked="checked"<{/if}> />自动&nbsp;&nbsp;<input name="auto_news_verify" type="radio" value="0" <{if !$datas.auto_news_verify}>checked="checked"<{/if}> />手动</td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">发布商品审核</td>
        <td width="89%" align="left"  class="body_color"><input name="auto_pro_verify" type="radio" value="1" <{if $datas.auto_news_verify}>checked="checked"<{/if}> />自动&nbsp;&nbsp;<input name="auto_pro_verify" type="radio" value="0" <{if !$datas.auto_pro_verify}>checked="checked"<{/if}> />手动</td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">发布文章积分</td>
        <td width="89%" align="left"  class="body_color"><input name="pub_news_score" type="text" class="input_box" value="<{$datas.pub_news_score}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">发布商品积分</td>
        <td width="89%" align="left"  class="body_color"><input name="pub_pro_score" type="text" class="input_box" value="<{$datas.pub_pro_score}>"/></td>
      </tr>
       <tr>
        <td width="11%" align="right" class="body_color">积分抵现</td>
        <td width="89%" align="left"  class="body_color"><input name="score_money" type="text" class="input_box" value="<{$datas.score_money}>"/>&nbsp;<span style="color:red">积分 = 1元</span></td>
      </tr>
       <tr>
        <td width="11%" align="right" class="body_color">每次使用最多积分</td>
        <td width="89%" align="left"  class="body_color"><input name="every_max_score" type="text" class="input_box" value="<{$datas.every_max_score}>"/>&nbsp;<span style="color:red">0为不限制</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">用户修改评价次数</td>
        <td width="89%" align="left"  class="body_color"><input name="mod_appraise" type="text" class="input_box" value="<{if !$datas.mod_appraise}>0<{else}><{$datas.mod_appraise}><{/if}>"/>&nbsp;<span style="color:red">0为不限制</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">网站LOGO</td>
        <td width="89%" align="left"  class="body_color"><input name="logo" type="file" /></td>
      </tr>
	   <tr>
        <td width="11%" align="right" class="body_color">收藏夹图标</td>
        <td width="89%" align="left"  class="body_color"><input name="favicon" type="file" /></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">当前模板</td>
        <td width="89%" align="left"  class="body_color"><input name="template" type="text" class="input_box" value="<{$datas.template}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">前台分页</td>
        <td width="89%" align="left"  class="body_color"><input name="home_page_num" type="text" class="input_box" value="<{$datas.home_page_num}>"/></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="body_color">后台分页</td>
        <td width="89%" align="left"  class="body_color"><input name="admin_page_num" type="text" class="input_box" value="<{$datas.admin_page_num}>"/></td>
      </tr>
	  <tr>
        <td width="11%" align="right" class="body_color">网站关闭</td>
        <td width="89%" align="left"  class="body_color"><input name="closed" type="radio" value="0" <{if !$datas.closed}>checked="checked"<{/if}> />打开&nbsp;&nbsp;<input name="closed" type="radio" value="1" <{if $datas.closed}>checked="checked"<{/if}> />关闭</td>
      </tr>
	   <tr>
        <td width="11%" align="right" class="body_color">关闭内容</td>
        <td width="89%" align="left"  class="body_color"><textarea name="site_close_html" id="site_close_html" cols="80" rows="5" style="width:600px;height:240px;"><{$datas.site_close_html}></textarea>
		<script type="text/javascript">  
		 var um = UM.getEditor('site_close_html');
         </script></td>
      </tr>
      <tr>
        <td align="right" class="body_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="submit" name="mod" class="admin_button" value="编辑" /></td>
      </tr>
      </table>
</div>
</form>
</body>
</html>
