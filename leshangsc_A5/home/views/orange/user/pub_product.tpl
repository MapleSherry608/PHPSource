<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title><{$con_datas.site_name}></title>
<meta name="keywords" content="<{$con_datas.key_word}>" />
<meta name="description" content="<{$con_datas.description}>" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/css/weebox.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/common.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/layout.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/user.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.pngfix.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.lazyload.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/koala.min.1.5.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script>
var APP_PATH="<{$app}>";var ISLOGIN="<{$user.id}>";
$(document).ready(function(e) {
	$('#cate').change(function(){ 
		var p1=$(this).val();//这就是selected的值
		window.location.href="<{$app}>/user/pub_product/id/"+p1;
	})
    $("#sub_nav ul li").click(function(e) {
        var id=$(this).attr("id");
		$("#sub_nav ul li").removeClass("select");
		$(this).addClass("select");
		$(".publish").hide();
		$("#publish_"+id).show();
    });
	$("#user_mod").click(
		  function(){
			  open_url=APP_PATH+'/user/mod_index/id/<{$user.id}>';
			  $.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '编辑会员资料', showButton:false,animate: true,width:590, height: 600,clickClose: true});	
		  }
	  );
});
</script>
</head>

<body>
<{include file="public/header.tpl"}>
<div id="body" class="web">
	<div class="left_c">
        <{include file="user/left_info.tpl"}>
    </div>
    <div class="right_c">
    
    	<div class="u_r_box">
        	<div class="tit">
            	<span class="r_icon_1"></span>
        		<span class="txt">我发布的商品</span>
                <span class="select_box">
                	<select name="cate" id="cate">
                      <option value="" selected="selected">-选择分类-</option>
                        <option value="0">所有商品</option>
                            <{section name=sn loop=$nav_datas}>
                                <{if $nav_datas[sn].has_sub}>
                				<optgroup label="<{$nav_datas[sn].name}>">
                            <{else}>
                               	<option value="<{$nav_datas[sn].nav_id}>"><{$nav_datas[sn].name}></option>
                            <{/if}>
                        <{/section}>
                        </select>
                </span>
            </div>
            
            <div class="current">
            	当前分类：<span id="current_class"><{$current_name}></span>
            </div>
            
            <div class="content">
            	<{if $datas}>
            	<div class="product_box">
                	<ul>
                    	<{section name=sn loop=$datas}>
                    	<li>
                        	<span class="pro_img"><{if $datas[sn].img}><a href="<{$datas[sn].url}>"><img src="<{$public}>/uploads/<{$datas[sn].img}>" width="50" height="50"/></a><{else}><img src="<{$public}>/images/no_pic.jpg" width="50" height="50"/><{/if}></span>
                            <span class="pro_name" style="width:200px;">名称：<{$datas[sn].name}><br />货号：<{$datas[sn].serial_no}></span>
                            <span class="pro_price"  style="width:100px;">&yen;<{$datas[sn].current_price}></span>
                            <span class="pro_ver"><{if $datas[sn].verify}><font style="color:green">已审核</font><{else}><font style="color:red">未审核</font><{/if}></span>
                            <span class="pro_click">点击<{$datas[sn].click}>次</span>
                            <span class="pro_op"><a href="<{$app}>/product/publish_mod/id/<{$datas[sn].id}>" target="_blank">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/product/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的商品吗?')" >删除</a></span>
                        </li>
                        <{/section}>
                    </ul>
                </div>
                <div class="page"><{$fpage}></div>
                <{else}>
                <div class="no-content"></div>
                <{/if}>
            </div>
            <div class="clear"></div>
        </div>
        
       
        
        
        
    </div>
    
   
    <div class="clear"></div>
</div>

<{include file="public/footer.tpl"}>
</body>
</html>
