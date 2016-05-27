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
	$(".del").click(
		function(){
			if(confirm("确定要删除吗")){
				 var id=$(this).attr("id");
				 window.location.href=APP_PATH+"/friend/del/id/"+id;
			 }else{
				 return false;
			 }
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
        		<span class="txt">我的好友</span>
               
            </div>
            
           
            
            <div class="content">
            	<{if $friend_datas}>
            	<div class="product_box">
                	<ul>
                    	<{section name=n loop=$friend_datas}>
                    	<li>
                        	<span class="pro_img"><{if $friend_datas[n].friend_info.photo}><img src="<{$public}>/uploads/<{$datas[sn].img}>" width="50" height="50"/><{else}><img src="<{$public}>/images/no_pic.jpg" width="50" height="50"/><{/if}></span>
                            <span class="pro_name">名称:<{$friend_datas[n].friend_info.user_name}><br /><font style="color:green">签名:<{$friend_datas[n].friend_info.signature|truncate:"35"}></font></span>
                            <span class="pro_op del" id="<{$friend_datas[n].id}>" style="color:#699;cursor:pointer;float:right" >删除</span>
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