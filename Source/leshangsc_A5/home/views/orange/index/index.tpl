<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title><{$con_datas.site_name}></title>
<meta name="keywords" content="<{$con_datas.key_word}>" />
<meta name="description" content="<{$con_datas.description}>" />
<link rel="shortcut icon" href="<{$public}>/uploads/<{$con_datas.favicon}>" />
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
		$(function(){
			var _wrap=$('#appraise_list');
			var _interval=2000;
			var _moving;
			_wrap.hover(function(){
			clearInterval(_moving);
			},function(){
			_moving=setInterval(function(){
			var _field=_wrap.find('li:first');
			var _h=_field.outerHeight();
			_field.animate({marginTop:-_h+'px'},600,function(){
			_field.css('marginTop',0).appendTo(_wrap.find("ul"));
			})
			},_interval)
			}).trigger('mouseleave');
		});
		
		
});
</script>
</head>

<body>
<{include file="public/header.tpl"}>
<div id="body" class="web_body">
	<div class="content_box">
    	<div class="banner left">
        	<!-- 代码 开始 -->
            <div id="fsD1" class="index_focus">  
                <div id="D1pic1" class="fPic">  
                    <{section name=sn loop=$adv_datas_8}>
                    <div class="fcon" style="display: none;">
                        <a target="_blank" href="<{$adv_datas_8[sn].url}>"><img src="<{$public}>/uploads/<{$adv_datas_8[sn].pic}>" style="opacity: 1; "></a>
                        <span class="shadow"><a target="_blank" href="<{$adv_datas_8[sn].url}>"><{$adv_datas_8[sn].name}></a></span>
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
        <div class="r_banner right">
        	<{section name=sn loop=$adv_datas_16}>
            	 <a target="_blank" href="<{$adv_datas_16[sn].url}>"><img src="<{$public}>/uploads/<{$adv_datas_16[sn].pic}>" style="opacity: 1; "></a>
            <{/section}>
        </div>
        <div class="r_news_box right">
        	<div class="title">
            	<div class="left t">新闻速递</div>
                <div class="right m"><a href="<{$news_url_42}>">更多速递</a></div>
            </div>
            <div class="content">
            	<ul>
                	<{section name=sn loop=$news_datas_42 max=4}>
                	<li><a href="<{$news_datas_42[sn].url}>">·<{$news_datas_42[sn].title|truncate:30:"...":true}></a>&nbsp;&nbsp;[<span style="color:#006600"><{$news_datas_42[sn].times}></span>]</li>
                    <{/section}>
                </ul>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    
    <{section name=sn loop=$pro_main_nav}>
    <div class="product_box">
        <div class="title_box">
        	<div class="title"><div class="left"><{$pro_main_nav[sn].name}></div><div class="right more"><a href="<{$pro_main_nav[sn].url}>">更多</a></div></div>
            <div class="line"></div>
        </div>
        <div class="content" id="products">
        	<{if $pro_main_nav[sn].product}>
        	<{section name=n loop=$pro_main_nav[sn].product max=5}>
            <{if !$pro_main_nav[sn].product[n].user_info}>
            
        	<div class="product">
            	<div class="p_img"><a href="<{$pro_main_nav[sn].product[n].url}>"><{if $pro_main_nav[sn].product[n].img}><img src="<{$public}>/uploads/<{$pro_main_nav[sn].product[n].img}>" /><{else}><img src="<{$public}>/images/no_pic.jpg" /><{/if}></a></div>
                <div class="p_name"><a href="<{$pro_main_nav[sn].product[n].url}>"><{$pro_main_nav[sn].product[n].name|truncate:26:"..."}></a></div>
                <div class="p_price"><span style="text-decoration:line-through;color:#666666">原价:&yen;<{$pro_main_nav[sn].product[n].origin_price}></span>&nbsp;&nbsp;现价:<{$pro_main_nav[sn].product[n].current_price}></div>
            </div>
            
            <{else}>
            
            <div class="product">
            	<div class="p_img"><a href="<{$pro_main_nav[sn].product[n].url}>"><{if $pro_main_nav[sn].product[n].img}><img src="<{$public}>/uploads/<{$pro_main_nav[sn].product[n].img}>" /><{else}><img src="<{$public}>/images/no_pic.jpg" /><{/if}></a></div>
                <div class="p_name"><a href="<{$pro_main_nav[sn].product[n].url}>"><{$pro_main_nav[sn].product[n].name|truncate:26:"..."}></a></div>
                <div class="p_price"><span >价格:&yen;<{$pro_main_nav[sn].product[n].current_price}></span></div>
                <div class="p_price ">发布会员:<span class="uname_box"><{$pro_main_nav[sn].product[n].user_info.user_name}>
               
                <div class="user_private_list"><ul><li><a class="letter" data-name="<{$pro_main_nav[sn].product[n].user_info.user_name}>" id="<{$pro_main_nav[sn].product[n].user_info.id}>">发私信</a></li><li><a class="friend" id="<{$pro_main_nav[sn].product[n].user_info.id}>">加好友</a></li></ul></div></span></div>
            </div>
            
            <{/if}>
            <{/section}>
             <{else}>
            <div class="no-content"></div>
            <{/if}>
            <div class="clear"></div>
        </div>
    </div>
    <{/section}>
    
    <div class="mid_banner">
    	<{section name=sn loop=$adv_datas_17}>
            	 <a target="_blank" href="<{$adv_datas_17[sn].url}>"><img src="<{$public}>/uploads/<{$adv_datas_17[sn].pic}>"></a>
            <{/section}>
    </div>
    
    <div class="unews_box">
    	<div class="title"><div class="left">会员最新文章</div><div class="right more"><a href="<{$news_url_49}>">更多</a></div></div>
        <div class="content" id="unews_list">
        	<{section name=sn loop=$news_datas_49 max=3}>
        	<dl>
            	<dt><{if $news_datas_49[sn].thumb}><img src="<{$public}>/uploads/<{$news_datas_49[sn].thumb}>" /><{else}><img src="<{$public}>/images/no_pic.jpg"><{/if}></dt>
                <dd>
                	<div class="n_title"><a href="<{$news_datas_49[sn].url}>"><{$news_datas_49[sn].title|truncate:30:"...":true}></a>&nbsp;&nbsp;[<span style="color:#006600"><{$news_datas_49[sn].times}></span>]&nbsp;&nbsp;发布会员:<span class="uname_box"><{$news_datas_49[sn].user_info.user_name}>
               
                <div class="user_private_list"><ul><li><a class="letter" data-name="<{$news_datas_49[sn].user_info.user_name}>" id="<{$news_datas_49[sn].user_info.id}>">发私信</a></li><li><a class="friend" id="<{$news_datas_49[sn].user_info.id}>">加好友</a></li></ul></div></span></div>
                    <div class="n_des"><{$news_datas_49[sn].description|truncate:150:"...":true}></div>
                </dd>
            </dl>
            <{/section}>
        </div>
    </div>
    <div class="comment_box">
    	<div class="title"><div class="left">最新评论</div></div>
        <div class="content" id="appraise_list">
        	<ul>
            	<{section name=sn loop=$appraise_list}>
                <li>
                    <div class="c_p_img"><img src="<{$public}>/uploads/<{$appraise_list[sn].pro.img}>" ></div>
                    <div class="c_p_con"><{$appraise_list[sn].user.user_name}><br /><span title="<{$appraise_list[sn].content}>"><{$appraise_list[sn].content|truncate:25:"...":true}></span><br /><span style="color:#006600">(<{$appraise_list[sn].content_time}>)</span></div>
                    <div class="clear"></div>
                </li>
                <{/section}>
            </ul>
        </div>
    </div>
    <div class="clear"></div>
	<div class="link_box">
		<div class="title">友情链接</div>
		<div class="content">
			<ul>
				<{section name=sn loop=$link_datas}>
				<li><a href="<{$link_datas[sn].url}>" target="_blank"><{$link_datas[sn].name}></a></li>
				<{/section}>
				<div class="clear"></div>
			</ul>
		</div>
	</div>
</div>
<{include file="public/footer.tpl"}>


</body>
</html>
