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
	$("#products .product:nth-child(5n)").css('border-right','0');
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
    <{section name=sn loop=$sub_nav_data}>
    <div class="product_box">
        <div class="title_box">
        	<div class="title"><div class="left"><{$sub_nav_data[sn].name}></div><div class="right more"><a href="<{$sub_nav_data[sn].url}>">更多</a></div></div>
            <div class="line"></div>
        </div>
        <div class="content" id="products">
        	<{if $sub_nav_data[sn].products}>
        	<{section name=n loop=$sub_nav_data[sn].products max=5}>
        	<div class="product">
            	<div class="p_img"><a href="<{$sub_nav_data[sn].products[n].url}>"><{if $sub_nav_data[sn].products[n].img}><img src="<{$public}>/uploads/<{$sub_nav_data[sn].products[n].img}>" /><{else}><img src="<{$public}>/images/no_pic.jpg" /><{/if}></a></div>
                <div class="p_name"><a href="<{$sub_nav_data[sn].products[n].url}>"><{$sub_nav_data[sn].products[n].name|truncate:26:"..."}></a></div>
                <div class="p_price">价格:&yen;<{$sub_nav_data[sn].products[n].current_price}></div>
                <div class="p_price ">发布会员:<span class="uname_box"><{$sub_nav_data[sn].products[n].user_info.user_name}>
               
                <div class="user_private_list"><ul><li><a class="letter" data-name="<{$sub_nav_data[sn].products[n].user_info.user_name}>" id="<{$sub_nav_data[sn].products[n].user_info.id}>">发私信</a></li><li><a class="friend" id="<{$sub_nav_data[sn].products[n].user_info.id}>">加好友</a></li></ul></div></span></div>
            </div>
            <{/section}>
            <{else}>
            <div class="no-content"></div>
            <{/if}>
            <div class="clear"></div>
        </div>
    </div>
    <{/section}>
    
    
    
    
</div>
<{include file="public/footer.tpl"}>


</body>
</html>
