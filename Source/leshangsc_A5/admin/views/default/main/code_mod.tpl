<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<{$public}>/codemirror/codemirror.css">
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>

<script src="<{$public}>/codemirror/codemirror.js"></script>
<script src="<{$public}>/codemirror/xml.js"></script>
<script src="<{$public}>/codemirror/javascript.js"></script>
<script src="<{$public}>/codemirror/css.js"></script>
<script src="<{$public}>/codemirror/htmlmixed.js"></script>
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
	  
	   var mixedMode = {
        name: "htmlmixed",
        scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
                       mode: null}]
      };
	  
	  var editor = CodeMirror.fromTextArea(document.getElementById("tpl_content"), {
	  
	  lineNumbers: true,
        mode: mixedMode,
        selectionPointer: true
      });
		editor.setOption('lineWrapping', true);
		
		$(window).resize(function(){
			var h = $(window).height();
			editor.setSize('auto', h-160);
		})
		var h = $(window).height();
		editor.setSize('auto', h-160);

})
</script> 

</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/code/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;在线模板编辑</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="7">
      <tr>
        <td width="7%" align="right" class="left_color">文件</td>
        <td width="93%" align="left"  class="body_color"><{$tpl.name}></td>
        </tr>
       
      <tr>
        <td align="right" class="left_color" valign="top">代码</td>
        <td align="left" class="body_color" style="background:#eaeaea" >
        	
		<textarea name="tpl_content" id="tpl_content" cols="120" rows="30" ><{$tpl.content}></textarea>
		
        </td>
        </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="hidden" name="name" value="<{$tpl.name}>" /><input type="hidden" name="dir" value="<{$tpl.dir}>" /><input type="hidden" name="current_dir" value="<{$tpl.current_dir}>" /><input type="submit" name="add" class="admin_button" value="提交" />&nbsp;&nbsp;<input type="button" name="back" class="admin_button" value="返回" onclick="javascript:history.go(-1);"/></td>
      </tr>
      
      </table>
</div>
</form>
</body>
</html>
