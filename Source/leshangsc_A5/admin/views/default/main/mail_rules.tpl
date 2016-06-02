<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/css/weebox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.caretInsert.js"></script>
<title>main</title>
<script type="text/javascript">
function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-58); } else { return false; } }
  $(function() { 
  	autoHeight();
	 $(window).resize(autoHeight);
	 
	 $(".mod").click(
		function(){
			var id=$(this).attr("id");
			var data=$(this).attr("data");
			var title="编辑"+data+"模版";
			open_url='<{$app}>/mailRules/mod_temp_index/id/'+id;
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: title, showButton:false,animate: true,width:630, height: 320,clickClose: false});	
		}
	);
	
	
})
</script> 
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/mailRules/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;邮件发送规则</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
	  <{section name=sn loop=$datas}>
      <tr>
        <td width="11%" align="right" class="body_color"><{$datas[sn].name}></td>
        <td width="49%" align="left"  class="body_color"><input name="<{$datas[sn].code}>" type="radio" value="1" <{if $datas[sn].value==1}>checked="checked"<{/if}> >通知&nbsp;<input name="<{$datas[sn].code}>" type="radio" value="0" <{if $datas[sn].value==0}>checked="checked"<{/if}>>不通知&nbsp;&nbsp;<span style="color:#ccc">(<{$datas[sn].description}>)</span></td>
        <td width="40%" align="left"  class="body_color"><a href="javascript:;" class="mod" id="<{$datas[sn].id}>" data="<{$datas[sn].name}>">[编辑发送模版]</a></td>
      </tr>
      <{/section}>
      <tr>
        <td align="right" class="body_color">&nbsp;</td>
        <td align="left" class="body_color" colspan="2"><input type="submit"  class="admin_button" value="编辑" /></td>
      </tr>
      </table>
</div>
</form>
</body>
</html>
