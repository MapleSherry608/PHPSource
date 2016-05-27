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
<script language="javascript" type="text/javascript"> 
var n="<{$spec_datas_num}>";
function delTr(e){
	var tr=$(e).parent("td").parent("tr").attr("id");
	$("#"+tr).remove();
}
$(document).ready(
	function(){
		
		
		
		
	}
)
function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-58); } else { return false; } }
  $(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
})
</script> 
<style>
.p_input{
	width:40px;
}
</style>
</head>

<body>
<form enctype="multipart/form-data" action="<{$app}>/product/mod" method="post">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;编辑商品</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="left_color">名称</td>
        <td width="89%" align="left"  class="body_color"><input name="name" type="text" class="input_box" value="<{$datas.name}>"/><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">货号</td>
        <td width="89%" align="left"  class="body_color"><input name="serial_no" type="text" class="input_box" value="<{$datas.serial_no}>"/><span class="tips">不填系统自动生成</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">分类</td>
        <td width="89%" align="left"  class="body_color">
        <select name="cate_id">
                        <{section name=sn loop=$nav_datas}>
                        	<{if $nav_datas[sn].has_sub}>
                				<optgroup label="<{$nav_datas[sn].name}>">
                            <{else}>
                               	<option value="<{$nav_datas[sn].nav_id}>" <{if $datas.cate_id==$nav_datas[sn].nav_id}>selected="selected"<{/if}> ><{$nav_datas[sn].name}></option>
                            <{/if}>
                        <{/section}>
                        </select><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">原价</td>
        <td width="89%" align="left"  class="body_color"><input name="origin_price" type="text" class="input_box" value="<{$datas.origin_price}>"/><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">现价</td>
        <td width="89%" align="left"  class="body_color"><input name="current_price" type="text" class="input_box" value="<{$datas.current_price}>"/><span class="tips">*</span></td>
      </tr>
      
      <tr>
        <td width="11%" align="right" class="left_color">库存</td>
        <td width="89%" align="left"  class="body_color"><input name="inventory" type="text" class="input_box" value="<{$datas.inventory}>"/><span class="tips">*</span></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">运费</td>
        <td width="89%" align="left"  class="body_color"><input name="delivery_fee" type="text" class="input_box" value="<{$datas.delivery_fee}>"/><span class="tips">（注：0元为卖家包邮）</span></td>
      </tr>
      
      <tr>
        <td width="11%" align="right" class="left_color">图片</td>
        <td width="89%" align="left"  class="body_color"><input name="img" type="file" /></td>
      </tr>
      
      <tr>
        <td width="11%" align="right" class="left_color">内容</td>
        <td width="89%" align="left"  class="body_color"><textarea name="brief" id="brief" cols="80" rows="5" style="width:800px;height:240px;"><{$datas.brief}></textarea>
		<script type="text/javascript">  
		 var um = UM.getEditor('brief');
         </script></td>
      </tr>
      <tr>
        <td width="11%" align="right" class="left_color">参数介绍</td>
        <td width="89%" align="left"  class="body_color"><textarea name="specifications" id="specifications" cols="80" rows="5" style="width:800px;height:240px;"><{$datas.specifications}></textarea>
        <script type="text/javascript">  
		 var um = UM.getEditor('specifications');
         </script></td>
		</td>
      </tr>
      <tr>
        <td align="right" class="left_color">排序</td>
        <td align="left" class="body_color"><input name="sort" type="text" class="input_box" value="<{$datas.sort}>"/><span class="tips">*</span></td>
        </tr>
      <tr>
        <td align="right" class="left_color">&nbsp;</td>
        <td align="left" class="body_color"><input type="hidden" name="id" value="<{$datas.id}>" /><input type="submit" name="mod" class="admin_button" value="提交" /></td>
      </tr>
      </table>
</div>
</form>
<div style="display:none;" id="con">
	<{section name=sn loop=$spec_main}>
    	<div id="add_ob<{$spec_main[sn].id}>">
        	<select name="spec_sub[<{$spec_main[sn].id}>][]">
            	<{section name=n loop=$spec_sub}>
                <{if $spec_main[sn].id==$spec_sub[n].pid}>
            	<option value="<{$spec_sub[n].id}>"><{$spec_sub[n].name}></option>
                <{/if}>
                <{/section}>
            </select>
        </div>
    <{/section}>
    <div id="add_origin_price">
    	<input type="text" name="origin_prices[]" class="p_input"/>
    </div>
	<div id="add_current_price">
    	<input type="text" name="current_prices[]" class="p_input"/>
    </div>
    <div id="add_inventory">
    	<input type="text" name="inventories[]" class="p_input"/>
    </div>
	<div id="add_oper">
    	<a class="del_tr" style="cursor:pointer" onclick="delTr(this)">删除</a>
    </div>
</div>
</body>
</html>
