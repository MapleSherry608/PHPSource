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
    $("#sub_nav ul li").click(function(e) {
        var id=$(this).attr("id");
		$("#sub_nav ul li").removeClass("select");
		$(this).addClass("select");
		$("#publish_content div").hide();
		$("#public_"+id).show();
    });
	$("#user_mod").click(
		  function(){
			  open_url=APP_PATH+'/user/mod_index/id/<{$user.id}>/window/1';
			  $.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '编辑会员资料', showButton:false,animate: true,width:590, height: 600,clickClose: true});	
		  }
	  );
	
	$("#letter").click(
		function(){
			 window.location.href=APP_PATH+"/letter";
		}
	)
	$("#message").click(
		function(){
			 window.location.href=APP_PATH+"/friend/my_message";
		}
	)
	$(".mod_photo").click(function(){
		open_url='<{$app}>/user/mod_logo_index/id/'+$(this).attr("id");
		window.open(open_url);
		//$.weeboxs.open(open_url, {contentType: 'ajax',title: '编辑头像', showButton:false,animate: true,width:600, height: 400});
	});
	var leftsize = 0;   
	 $("#left_arrow").click(function(){
		  if(leftsize > -660){
			 leftsize = leftsize - 660;
			 $("#pro_img").animate({"left":leftsize});
			 }
		  else if(leftsize = -660) {
			 leftsize = leftsize - 0;
			 $("#pro_img").animate({"left":leftsize});
		  }
		  else {
			 
		  	$("#pro_img").css('left','0px');
		  }
		  
	  });  
		
	   $("#right_arrow").click(function(){   
		  if(leftsize < -660){
			 leftsize = leftsize + 660;
			 $("#pro_img").animate({"left":leftsize});
			 }
		  else if(leftsize = -660) {
			 leftsize = leftsize + 660;
			 $("#pro_img").animate({"left":leftsize});
		  }
		  else{$("#pro_img").css('left','-1320px');}
	 });   
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
            	<span class="icon_1"></span>
        		<span class="txt">会员中心</span>
            </div>
            <div class="content">
            	<div class="photo">
                	<img src="<{$public}>/uploads/<{$user_info.photo}>" class="mod_photo" style="cursor:pointer" id="<{$user_info.id}>" title="编辑照片"/>
                	<div class="edit" title="编辑资料" id="user_mod"></div>
                </div>
                
                <div class="u_info">
                	<ul>
                    	<li>名称：<{$user_info.user_name nocache}></li>
                        <li>邮箱：<{$user_info.email}></li>
                        <li>积分：<{$user_info.score}></li>
                        <li>电话：<{$user_info.phone}></li>
                        <li>签名：<{$user_info.signature}></li>
                    </ul>
                </div>
                <div class="message">
                	<div class="box" id="letter" style="cursor:pointer">
                    	<div class="letter_icon"><{$letter_num nocache}></div>
                        <div class="txt">我的私信</div>
                    </div>
                    <div class="box" id="message" style="cursor:pointer">
                    	<div class="friend_icon"><{$friend_num nocache}></div>
                        <div class="txt">好友消息</div>
                    </div>
                	<div class="box">
                    	<div class="verify_icon"><{$no_verify_num nocache}></div>
                        <div class="txt">未审核消息</div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="u_r_box">
        	<div class="tit">
            	<span class="icon_2"></span>
        		<span class="txt">猜你喜欢</span>
            </div>
            <div class="content">
            	<div class="left_arrow" id="left_arrow"></div>
                <div class="products">
                	<div style="position:absolute; left:0px;top:0px;width:1320px;" id="pro_img">
                	<ul>
                   		<{section name=n loop=$like_data max=8}>
                    	<li><{if $like_data[n].img}><a href="<{$like_data[n].url}>"><img src="<{$public}>/uploads/<{$like_data[n].img}>" /></a><{else}><img src="<{$public}>/images/no_pic.jpg" /><{/if}></li>
                      	<{/section}>
                    </ul>
                    </div>
                </div>
                <div class="right_arrow" id="right_arrow"></div>
                <div class="clear"></div>
            </div>
        </div>
        
        <div class="u_r_box">
        	<div class="tit">
            	<span class="icon_3"></span>
        		<span class="txt">我发布的内容</span>
                <div class="right">
                	<div class="sub_nav" id="sub_nav">
                    	<ul>
                        	<li id="news" class="select">资讯</li>
                            <li id="products">商品</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="content" id="publish_content">
            	<div class="publish" id="public_news">
                	<ul>
                    	<{section name=n loop=$news_datas max=5}>
                    	<li><span><a href="<{$news_datas[n].url}>" target="_blank"><{$news_datas[n].title}></a></span><span>发布日期：<{$news_datas[n].create_time}></span><{if !$news_datas[n].verify}><span style="color:red">审核中</span><{else}><span style="color:green">已审核</span><{/if}></li>
                        <{/section}>
                    </ul>
                </div>
                <div class="publish" id="public_products" style="display:none;">
                	<ul>
                    	<{section name=n loop=$pro_datas max=5}>
                    	<li><span><a href="<{$pro_datas[n].url}>" target="_blank"><{$pro_datas[n].name}></a></span><span>发布日期：<{$pro_datas[n].add_time}></span><{if !$pro_datas[n].verify}><span style="color:red">审核中</span><{else}><span style="color:green">已审核</span><{/if}></li>
                        <{/section}>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
    
   
    <div class="clear"></div>
</div>

<{include file="public/footer.tpl"}>
</body>
</html>
