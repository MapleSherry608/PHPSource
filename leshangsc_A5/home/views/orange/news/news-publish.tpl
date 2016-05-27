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
<link href="<{$res}>/css/news.css" rel="stylesheet" type="text/css" />
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
	var APP_PATH="<{$app}>";
	var ISLOGIN="<{$user.id}>"
	$(function(){
		$("#body .news_box_pub:nth-child(3n)").css('margin-right','0');
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
    <{section name=sn loop=$news_info}>
    <div class="news_box_pub">
    	<div class="n_tit">
        	<div class="left"><{$news_info[sn].name}></div><div class="right m"><a href="<{$news_info[sn].url}>">更多</a></div>
        </div>
        <div class="n_cont">
            <{section name=n loop=$news_info[sn].news max=3}>
            	<div class="user_news">
                <div class="thumb"><{if $news_info[sn].news[n].thumb}><img src="<{$public}>/uploads/<{$news_info[sn].news[n].thumb}>"><{else}><img src="<{$public}>/images/no_pic.jpg"><{/if}></div>
                <div class="detail">
                    <div class="tit">·<a href="<{$news_info[sn].news[n].url}>"><{$news_info[sn].news[n].title|truncate:32:"...":true}></a>&nbsp;&nbsp;<span style="color:#006600">(<{$news_info[sn].news[n].create_time}>)</span></div>
                    <div class="des"><{$news_info[sn].news[n].description|truncate:150:"...":true}></div>
                    <div class="user">发布用户:<span class="uname_box"><{$news_info[sn].news[n].user_info.user_name}>
               
                <div class="user_private_list"><ul><li><a class="letter" data-name="<{$news_info[sn].news[n].user_info.user_name}>" id="<{$news_info[sn].news[n].user_info.id}>">发私信</a></li><li><a class="friend" id="<{$news_info[sn].news[n].user_info.id}>">加好友</a></li></ul></div></span></div>
                </div>
                </div>
            <{/section}>
        </div>
    </div>
    <{/section}>
    
    <div class="clear"></div>
</div>

<{include file="public/footer.tpl"}>
</body>
</html>
