<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>drag</title>
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/drag.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript">
 $(function(){
			  var $img=$("#img");
			  $img.click(function(){
					var fs = parent.document.getElementsByTagName("frameset")[1];
					if(fs.cols=="0, 7, *"){
						fs.cols="225, 7, *";
						$img.attr("src","<{$res}>/images/bar_close.gif");
					} else {
						fs.cols="0, 7, *";
						$img.attr("src","<{$res}>/images/bar_open.gif");
					} 
			  });
});
</script>
</head>
<body>
<div class="blue_box"></div>
<table width="7" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="middle"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><a href="Javascript:void(0)"><img src="<{$res}>/images/bar_close.gif" width="6" height="60" id="img"/></a></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
