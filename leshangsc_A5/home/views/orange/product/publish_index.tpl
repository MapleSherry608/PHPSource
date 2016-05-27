<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title><{$con_datas.site_name}>-会员商品发布</title>
<meta name="keywords" content="<{$con_datas.key_word}>" />
<meta name="description" content="<{$con_datas.description}>" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/common.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/publish.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/ueditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<{$public}>/ueditor/third-party/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<{$public}>/ueditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<{$public}>/ueditor/umeditor.min.js"></script>
<script type="text/javascript" src="<{$public}>/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script>
var APP_PATH="<{$app}>";
var ISLOGIN="<{$user.id}>";
$(document).ready(function(){
	
	
	
	

})
</script>

</head>

<body>
<div class="outline p">
	<div class="title_p"><h1>商品发布</h1></div>
    <div class="content">
    	<form enctype="multipart/form-data" name="form1" id="form1" action="<{$app}>/product/add" method="post">
                  <table width="100%" border="0" cellspacing="1" cellpadding="2">
                          <tr>
                            <td width="18%" align="right">名称：</td>
                            <td width="82%"><input type="text" class="input_box" name="name" id="name" value=""/></td>
                          </tr>
                          <tr>
                            <td align="right" >货号：</td>
                            <td><input name="serial_no" type="text" class="input_box"/><span class="tips">不填系统自动生成</span></td>
                          </tr>
                          <tr>
                            <td align="right" >分类：</td>
                            <td><select name="cate_id">
                        <{section name=sn loop=$nav_datas}>
                        	<{if $nav_datas[sn].has_sub}>
                				<optgroup label="<{$nav_datas[sn].name}>">
                            <{else}>
                               	<option value="<{$nav_datas[sn].nav_id}>"><{$nav_datas[sn].name}></option>
                            <{/if}>
                        <{/section}>
                        </select></td>
                          </tr>
                          <tr>
                            <td align="right" >图片：</td>
                            <td><input name="img" type="file" /></td>
                          </tr>
                          <tr>
                            <td align="right" >价格：</td>
                            <td><input name="current_price" type="text" class="input_box" value=""/></td>
                          </tr>
                          <tr>
                            <td align="right" >运费：</td>
                            <td><input name="delivery_fee" type="text" class="input_box"/><span class="tips">0元为卖家包邮</span></td>
                          </tr>
                          <tr>
                            <td align="right" >库存：</td>
                            <td><input name="inventory" type="text" class="input_box" value=""/></td>
                          </tr>
                          <tr>
                            <td align="right" >内容：</td>
                            <td><textarea name="brief" id="brief"  style="width:800px;height:200px;"></textarea>
                        <script type="text/javascript">  
                         var um = UM.getEditor('brief');
                         </script></td>
                          </tr>
                          <tr>
                            <td align="right" >参数介绍：</td>
                            <td><textarea name="specifications" class="input_box" cols="100" rows="5"></textarea></td>
                          </tr>
                          <tr>
                            <td align="right" ></td>
                            <td><input type="hidden" name="m_cate" value="<{$m_cate}>" /><input type="submit" class="sub_button"  value="发 布"/></td>
                          </tr>
                        </table>
               </form>
    </div>
</div>
</body>
</html>


