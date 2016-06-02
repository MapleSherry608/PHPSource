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
		$("#body .news_box:nth-child(3n)").css('margin-right','0');
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
    <div class="news_list_box">
    	<div class="n_tit">
        	<div class="left"><{$cate_info.name}></div>
        </div>
        <div class="n_cont">
        	<{if $news_datas}>
        	<ul>
            	<{section name=sn loop=$news_datas}>
            	<li><a href="<{$news_datas[sn].url}>"><{$news_datas[sn].title|truncate:52:"...":true}></a>&nbsp;&nbsp;<span style="color:#006600">(<{$news_datas[sn].create_time}>)</span><p><{$news_datas[sn].description}></p></li>
                <{/section}>
            </ul>
            <div class="page">
            	<{$fpage}>
            </div>
            <{else}>
            	<div class="no-content"></div>
            <{/if}>
        </div>
    </div>
    
    <div class="news_hot_box">
    	<div class="n_tit">
        	<div class="left">最新新闻</div>
        </div>
        <div class="n_cont">
        	<ul class="red_l">
                <{section name=sn loop=$last_newsdata}>
                <li><a href="<{$last_newsdata[sn].url}>"><{$last_newsdata[sn].title|truncate:18:"...":true}></a>&nbsp;&nbsp;[<span style="color:#006600"><{$last_newsdata[sn].create_time}></span>]</li>
                <{/section}>
            </ul>
        </div>
    </div>
    
    <div class="clear"></div>
</div>

<{include file="public/footer.tpl"}>
</body>
</html>
