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
<script type="text/javascript" src="<{$public}>/js/jquery.lazyload.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/koala.min.1.5.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script>var APP_PATH="<{$app}>";var ISLOGIN="<{$user.id}>"
$(document).ready(function(e) {
    $(".agree").click(
		function(){
			window.location.href=APP_PATH+"/friend/verify/id/"+$(this).attr("id");
		}
	)
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
            	<span class="r_icon_13"></span>
        		<span class="txt">我的好友消息</span>
               
            </div>
            
           
            
            <div class="content">
            	<{if $addfriend_datas}>
            	<div class="orders_box">
                	<ul>
                    	<{section name=n loop=$addfriend_datas}>
                    	<li>
                        	
                            
                            <span>●&nbsp;会员：<{$addfriend_datas[n].friend_info.user_name}></span>
                            <span>●&nbsp;消息：<{$addfriend_datas[n].message}></span>
                            <span class="right agree" id="<{$addfriend_datas[n].id}>" style="color:#699;cursor:pointer;float:right" >同意</span>
                            <div class="clear" ></div>
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
