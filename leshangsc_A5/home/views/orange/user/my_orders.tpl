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
	
	$(".detail").click(
		  function(){
			  var id=$(this).attr("id");
			  open_url=APP_PATH+'/user/order_detail/id/'+id;
			  $.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '订单详情', showButton:false,animate: true,width:590, height: 480,clickClose: true});	
		  }
	  );
	
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
            	<span class="r_icon_3"></span>
        		<span class="txt">我的订单</span>
               	
            </div>
            
           
            
            <div class="content">
            	<{if $datas}>
            	<div class="orders_box">
                	<ul>
                    	<{section name=sn loop=$datas}>
                    	<li>
                        	
                            
                            <span>●&nbsp;订单号：<{$datas[sn].sn}></span>
                            <span style="color:red">金额：&yen;<{$datas[sn].pay_price}>&nbsp;[含运费]</span>
                            <span style="color:green"><{$datas[sn].payment_id|replace:"1":"网银在线"|replace:"2":"财付通"|replace:"3":"支付宝"|replace:"4":"货到付款"}></span>
                            <{if $datas[sn].payment_id!=4}><span><{$datas[sn].pay_status|replace:0:"<font style='color:#F30'>未支付</font>"|replace:1:"<font style='color:green'>支付成功</font>"|replace:2:"<font style='color:red'>支付失败</font>"}></span><{/if}>
                            <span><{$datas[sn].delivery_status|replace:0:"<font style='color:#F30'>未发货</font>"|replace:1:"<font style='color:green'>已发货</font>"|replace:2:"<font style='color:green'>已收货</font>"}></span>
                            <span><{$datas[sn].order_status|replace:1:"<font style='color:green'>交易成功</font>"|replace:2:"<font style='color:red'>已退款</font>"|replace:3:"<font style='color:green'>已退货</font>"|replace:4:"<font style='color:green'>已退款，已退货</font>"|replace:0:"<font style='color:red'>交易失败</font>"|replace:5:"<font style='color:green'>未结单</font>"}></span>
                            <span class="right detail" id="<{$datas[sn].id}>" style="color:#699;cursor:pointer" >详细内容</span>
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
