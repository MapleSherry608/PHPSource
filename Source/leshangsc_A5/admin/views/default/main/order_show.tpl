<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<link href="<{$public}>/css/weebox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>

<title>main</title>
<script language="javascript" type="text/javascript"> 
function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-60); } else { return false; } }
  $(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
})
$(document).ready(function(){
	$("#pay").click(
		function(){
			open_url='<{$app}>/orders/pay_index/id/<{$data.id}>';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '订单付款', showButton:false,animate: true,width:590, height: 350,clickClose: false});	
		}
	);
	$("#deliver").click(
		function(){
			open_url='<{$app}>/orders/deliver_index/id/<{$data.id}>';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '发货信息', showButton:false,animate: true,width:590, height: 450,clickClose: false});	
		}
	);
	$("#close").click(
		function(){
			open_url='<{$app}>/orders/close_index/id/<{$data.id}>';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '关闭交易', showButton:false,animate: true,width:590, height: 450,clickClose: false});	
		}
	);
	$("#mod_pay").click(
		function(){
			open_url='<{$app}>/orders/mod_pay_index/id/<{$data.id}>';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '修改价格', showButton:false,animate: true,width:590, height: 450,clickClose: false});	
		}
	);
	$("#mod_receiving").click(
		function(){
			open_url='<{$app}>/orders/mod_receiving_index/id/<{$data.id}>';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '修改收货信息', showButton:false,animate: true,width:590, height: 350,clickClose: false});	
		}
	);
	$("#receive").click(
		function(){
			if(confirm("确认收货么?")){
				window.location.href="<{$app}>/orders/receive/id/<{$data.id}>";
			} else return false;
			
		}
	)
});
</script>
</head>

<body>
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;订单详情</div></div>
<div class="info" style="overflow-y:scroll;">
      <table width="100%" border="0" cellspacing="1" cellpadding="5">
      <tr>
        <td width="11%" align="right" class="left_color">订单号</td>
        <td width="89%" align="left"  class="body_color"><{$data.sn}></td>
      </tr>
      <tr>
        <td align="right" class="left_color">订单状态</td>
        <td align="left" class="body_color"><{$data.status_cn}></td>
      </tr>
      <tr>
        <td align="right" class="left_color" valign="top">商品信息</td>
        <td align="left" class="body_color">
        	<{section name=n loop=$data.product}>
        		<div class="product_list">
                	<span class="p_img"><img src="<{$public}>/uploads/<{$data.product[n].img}>"></span>
                	<span ><{$data.product[n].name}><br><font style="color:#F30">[&yen;<{$data.product[n].price}>×<{$data.product[n].amount}>]</font><br>
                    <font style="color:green">
                    <{foreach from=$data.product[n].specs_cn item=list}>
                        [<{$list.name}>]&nbsp;
                    <{/foreach}>
                    </font>
                    </span>
                    <div class="clear"></div>
                </div>
                
            <{/section}>
        </td>
      </tr>
      <tr>
        <td align="right"   valign="top">用户信息</td>
        <td align="left"  style="color:green">名称:<{$data.user.user_name}><br />邮箱:<{$data.user.email}><br />积分:<{$data.user.score}></td>
      </tr>
      <tr>
        <td align="right" class="left_color" valign="top">收货信息</td>
        <td align="left" class="body_color" style="color:red">名字:<{$data.name}><br />电话:<{$data.tel}><br />地址:<{$data.address}></td>
      </tr>
      <tr>
        <td align="right"  valign="top">订单信息</td>
        <td align="left"  style="color:blue">
        	下单时间:<{if $data.create_time}><{$data.create_time}><{else}>无<{/if}><br />支付时间:<{if $data.pay_time}><{$data.pay_time}><{else}>无<{/if}>
        <br />发货时间:<{if $data.delivery_time}><{$data.delivery_time}><{else}>无<{/if}>
        <br />订单结束时间:<{if $data.order_time}><{$data.order_time}><{else}>无<{/if}></td>
      </tr>
      <tr>
        <td align="right" class="left_color" valign="top">付款信息</td>
        <td align="left" class="body_color" style="color: #F60">商品金额:&yen;<{$data.total_price}><br />运费:&yen;<{$data.delivery_fee}><br />应付金额:&yen;<{$data.pay_price}></td>
      </tr>
      <tr>
        <td class="left_color"></td>
        <td align="left" class="body_color" style="color: #F60"><input type="button" name="add" class="admin_button" value="返回列表" onclick="window.location.href='<{$app}>/orders'"></td>
      </tr>
      
      </table>
</div>
</body>
</html>
