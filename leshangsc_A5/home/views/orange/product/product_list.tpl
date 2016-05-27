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
<link href="<{$res}>/css/banner.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/user.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.pngfix.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.lazyload.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/koala.min.1.5.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script>
var APP_PATH="<{$app}>";var ISLOGIN="<{$user.id}>"
$(function(){
	$("#products .product_list:nth-child(5n)").css('border-right','0');
});
</script>
</head>

<body>
<{include file="public/header.tpl"}>
<div id="body" class="web_body">
	
    <div class="mid_banner">
    	<{section name=sn loop=$adv_datas_18}>
            	 <a target="_blank" href="<{$adv_datas_18[sn].url}>"><img src="<{$public}>/uploads/<{$adv_datas_18[sn].pic}>"></a>
            <{/section}>
    </div>
    <div class="position"><{$position}></div>
    <div class="product_box">
        <div class="title_box">
        	<div class="title"><div class="left"><{$nav_datas.name}></div></div>
            <div class="line"></div>
        </div>
        <div class="content_list" id="products">
        	<{if $product_datas}>
        	<{section name=sn loop=$product_datas}>
        	<div class="product_list">
            	<div class="p_img"><a href="<{$product_datas[sn].url}>"><{if $product_datas[sn].img}><img src="<{$public}>/uploads/<{$product_datas[sn].img}>" /><{else}><img src="<{$public}>/images/no_pic.jpg" /><{/if}></a></div>
                <div class="p_name"><a href="<{$product_datas[sn].url}>"><{$product_datas[sn].name|truncate:26:"..."}></a></div>
                <div class="p_price"><span style="text-decoration:line-through;color:#666666">原价:&yen;<{$product_datas[sn].origin_price}></span>&nbsp;&nbsp;现价:<{$product_datas[sn].current_price}></div>
            </div>
            
            <{/section}>
            
            <{else}>
            <div class="no-content"></div>
            <{/if}>
            
            <div class="clear"></div>
        </div>
    </div>
    	<div class="page">
            	<{$fpage}>
            </div>
    
    <div class="clear"></div>
    
</div>
<{include file="public/footer.tpl"}>


</body>
</html>
