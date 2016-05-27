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
<script>
var APP_PATH="<{$app}>";var ISLOGIN="<{$user.id}>";
$(document).ready(function(e) {
	
	$(".del").click(function(){
		 if(confirm("确定要删除吗")){
			 var id=$(this).attr("id");
			 window.location.href=APP_PATH+"/user/del_fav_pro/id/"+id;
		 }else{
			 return false;
		 }
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
            	<span class="r_icon_6"></span>
        		<span class="txt">我的收藏</span>
               
            </div>
            
           
            
            <div class="content">
            	<{if $datas}>
            	<div class="orders_box">
                	<ul>
                    	<{section name=sn loop=$datas}>
                    	<li>
                        	
                            
                            <span>●&nbsp;商品名称：<font title="<{$datas[sn].product.name}>" class="red_l"><a href="<{$datas[sn].url}>" target="_blank"><{$datas[sn].product.name|truncate:"75"}></a></font></span>
                           
                            <span class="right del" id="<{$datas[sn].id}>" style="color:#699;cursor:pointer;float:right" >删除</span>
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
