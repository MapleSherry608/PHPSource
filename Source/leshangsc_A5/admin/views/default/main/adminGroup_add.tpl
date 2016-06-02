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
	 $("#all").click(
		function(){
			if($(this).is(':checked')){
				$("[name='auth[]']").attr("checked",'true');
			} else {
				$("[name='auth[]']").removeAttr("checked");
			}
		}
	  );
})
</script> 
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/adminGroup/add" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;添加管理员组</div></div>
<div class="info" style="overflow-y:scroll;">
	 <table width="100%" border="0" cellspacing="1" cellpadding="7">
      <tr>
        <td width="10%" align="right" class="left_color">名称</td>
        <td width="90%" align="left"  class="body_color"><input name="name" type="text" class="input_box" value="<{$datas.name}>"/><span class="tips">*</span></td>
        </tr>
       <tr>
        <td width="10%" align="right" class="left_color"></td>
        <td width="90%" align="left"  class="body_color"><input type="checkbox" id="all" />全选</td>
       </tr>
      <tr>
        <td align="right" class="left_color" valign="top">权限</td>
        <td align="left" class="body_color">
        	<table width="100%" border="0" cellspacing="0" cellpadding="5">
            <{section name=sn loop=$class}>
              <tr>
                <td bgcolor="#e8e8e8" colspan="4"><b style="color:#666"><{$class[sn].name}></b></td>
              </tr>
             
              <tr>
              	 <{section name=n loop=$node}>
                    <{if $class[sn].id==$node[n].class_id}><td><input type="checkbox" name="auth[]" value="<{$node[n].id}>"/>&nbsp;<{$node[n].name}></td><{/if}>
                 <{/section}>
              </tr>
             
            <{/section}>
            </table>

            
        </td>
        </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="hidden" name="id" value="<{$datas.id}>" /><input type="submit" name="add" class="admin_button" value="提交" /></td>
      </tr>
      
      </table>
	  
	  
</div>
</form>
</body>
</html>
