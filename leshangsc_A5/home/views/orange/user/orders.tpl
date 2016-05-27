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
<link href="<{$res}>/css/cart.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.pngfix.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.lazyload.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/koala.min.1.5.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script type="text/javascript">
var APP_PATH="<{$app}>";
var ISLOGIN="<{$user.id}>";
$(document).ready(function(){
	
	
	
})
</script>
</head>

<body>
<{include file="public/header.tpl"}>
<div id="body" class="web">
	
    <div class="cart_box">
    	<div class="tit">
        	我的订单
        </div>
        <div class="content">
        	<div class="p_tit">
            	<ul>
                	<li style="width:150px;">订单编号</li>
                    <li style="width:450px;">支付方式</li>
                    <li style="width:250px;">总价</li>
                    <li style="width:120px;">操作</li>
                </ul>
            </div>
            
            <div class="p_con" style="font-family:microsoft yahei">
            	<ul >
                	<li style="width:150px;font-size:14px;"><{$order.sn}></li>
                    <li style="width:450px;font-size:16px;">
                    	<{$order.payment_cn}>
                    </li>
                    <li style="width:250px;font-size:16px;color:red">&yen;<{$order.price}></li>
                    
                    <{if $order.payment_id!=4 && $order.payment_id!=1 }>
                    <li style="width:120px;cursor:pointer;font-size:18px;"><a href="<{$payLinks}>" target="_blank">支付</a></li>
                    <{elseif $order.payment_id==1}>
                    <li style="width:120px;cursor:pointer;font-size:18px;"><{$payLinks}></li>
                    <{else}>
                    <li style="width:120px;cursor:pointer;font-size:18px;">请等待发货</li>
                    <{/if}>
                    
                </ul>
                <div class="clear"></div>
            </div>
            
            
            
            
            
        </div>
    </div>
    
</div>
<{include file="public/footer.tpl"}>
</body>
</html>
