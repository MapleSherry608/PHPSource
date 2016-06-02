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
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/koala.min.1.5.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script>var APP_PATH="<{$app}>";var ISLOGIN="<{$user.id}>"
</script>
</head>

<body>
<{include file="public/header.tpl"}>
<div id="body" class="web">
	
    <div class="content">
    	<div class="left_c">
        	<{include file="user/left_info.tpl"}>
        </div>
        <div class="right_c">
            <div id="u_center">
            	<div class="u_c_tit">
                    <ul>
                        <li class="line"></li>
                        <li class="title">收藏文章 / Favourite</li>
                    </ul>
                </div>
                <{if $fav_datas}>
                <{section name=n loop=$fav_datas}>
                    <div class="u_fav_list">
                        <ul>
                        	<li><a href="<{$fav_datas[n].url}>" target="_blank"><{$fav_datas[n].news_info.title}></a></li>
                        </ul>
                    </div>
                <{/section}>
                 <{else}>
                <div class="news_empty">
                    暂无收藏
                </div>
                <{/if}>
                <div class="clear"></div>
            </div>
            
            
            
            <div class="page"><{$fpage}></div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<{include file="public/footer.tpl"}>
</body>
</html>
