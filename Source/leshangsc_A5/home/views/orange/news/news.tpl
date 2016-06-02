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
	
	<div class="news_banner">
        <!-- 代码 开始 -->
        <div id="fsD1" class="focus">  
            <div id="D1pic1" class="fPic">  
            	<{section name=sn loop=$adv_datas_13}>
                <div class="fcon" style="display: none;">
                    <a target="_blank" href="<{$adv_datas_13[sn].url}>"><img src="<{$public}>/uploads/<{$adv_datas_13[sn].pic}>" style="opacity: 1; "></a>
                    <span class="shadow"><a target="_blank" href="<{$adv_datas_13[sn].url}>"><{$adv_datas_13[sn].name}></a></span>
                </div>
                <{/section}>
            </div>
            <div class="fbg">  
            <div class="D1fBt" id="D1fBt">  
                <a href="javascript:void(0)" hidefocus="true" target="_self" class="current"><i>1</i></a>  
                <a href="javascript:void(0)" hidefocus="true" target="_self" class=""><i>2</i></a>  
                <a href="javascript:void(0)" hidefocus="true" target="_self" class=""><i>3</i></a>  
            </div>  
            </div>  
            <span class="prev"></span>   
            <span class="next"></span>    
        </div>  
        <script type="text/javascript">
            Qfast.add('widgets', { path: "<{$public}>/js/terminator2.2.min.js", type: "js", requires: ['fx'] });  
            Qfast(false, 'widgets', function () {
                K.tabs({
                    id: 'fsD1',   //焦点图包裹id  
                    conId: "D1pic1",  //** 大图域包裹id  
                    tabId:"D1fBt",  
                    tabTn:"a",
                    conCn: '.fcon', //** 大图域配置class       
                    auto: 1,   //自动播放 1或0
                    effect: 'fade',   //效果配置
                    eType: 'click', //** 鼠标事件
                    pageBt:true,//是否有按钮切换页码
                    bns: ['.prev', '.next'],//** 前后按钮配置class                          
                    interval: 5000  //** 停顿时间  
                }) 
            })  
        </script>
        <!-- 代码 结束 -->
    </div>
    
    <div class="hot_news">
    	<div class="h_tit">
        	●&nbsp;最新新闻
        </div>
        <div class="h_cont m">
        	<ul>
            	<{section name=sn loop=$last_newsdata max=7}>
            	<li><a href="<{$last_newsdata[sn].url}>"><{$last_newsdata[sn].title|truncate:30:"...":true}></a>&nbsp;&nbsp;<span style="color:#006600">(<{$last_newsdata[sn].create_time}>)</span></li>
                <{/section}>
                
            </ul>
        </div>
    </div>
    
    <div class="clear"></div>
    <{section name=sn loop=$news_info}>
    <div class="news_box">
    	<div class="n_tit">
        	<div class="left"><{$news_info[sn].name}></div><div class="right m"><a href="<{$news_info[sn].url}>">更多</a></div>
        </div>
        <div class="n_cont">
        	<ul>
            	<{section name=n loop=$news_info[sn].news max=9}>
            	<li><a href="<{$news_info[sn].news[n].url}>"><{$news_info[sn].news[n].title|truncate:32:"...":true}></a>&nbsp;&nbsp;<span style="color:#006600">(<{$news_info[sn].news[n].create_time}>)</span></li>
                <{/section}>
            </ul>
        </div>
    </div>
    <{/section}>
    
    <div class="clear"></div>
</div>

<{include file="public/footer.tpl"}>
</body>
</html>
