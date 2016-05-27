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
<script>var APP_PATH="<{$app}>";var ISLOGIN="<{$user.id}>"</script>
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
            	<span class="r_icon_12"></span>
        		<span class="txt">我的私信</span>
               
            </div>
            
           
            
            <div class="content">
            	<{if $datas}>
                    	 <{section name=n loop=$datas}>
                            <div class="u_letter_list">
                                <div class="from uname_box">来自:<a class="uname_area" ><{$datas[n].user_info.user_name}></a>
                                	<div class="user_private_list">
                                    	<ul>
                                        	<li><a class="letter" data-name="<{$datas[n].user_info.user_name}>" id="<{$datas[n].user_info.id}>">发私信</a></li>
                                            <li><a class="friend" id="<{$datas[n].user_info.id}>">加好友</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="m_content" style="height:110px; overflow-y:scroll">
                                    <{$datas[n].message}>
                                </div>
                                <div class="time"><{$datas[n].create_time}>
                                </div>
                            </div>
                        <{/section}>
                       <div class="clear"></div> 
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
