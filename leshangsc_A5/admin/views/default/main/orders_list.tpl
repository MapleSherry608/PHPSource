<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<{$public}>/css/global.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<title>main</title>
<script language="javascript" type="text/javascript"> 
function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-155); } else { return false; } }
  $(function() { 
  	autoHeight();
	 $(window).resize(autoHeight); 
	 
	 $("#del").click(function(){
		$("#form1").attr("action","<{$app}>/orders/del");
		$("#form1").submit();
	 })
})


</script>

<style>
.info table td{
	border-bottom:1px dotted #ccc;
}
</style>

</head>

<body>
<form id="form1" enctype="multipart/form-data" action="<{$app}>/orders/index/search/1" method="get">
<div class="info_bar"><div class="title_left"></div><div class="title_bg">·&nbsp;商品订单</div></div>
<div class="class_bar">
	<div class="left">当前状态：<{if $is_search}>搜索列表<{else}>所有列表<{/if}></div>
    <div class="right">
    	订单编号:&nbsp;<input type="text" name="sn" style="width:100px"/>&nbsp;&nbsp;姓名:&nbsp;<input type="text" name="name" style="width:50px"/>&nbsp;&nbsp;电话:&nbsp;<input type="text" name="tel" style="width:80px"/>&nbsp;
        <select name="pay_status" id="pay_status">
            <option value="-1" <{if $smarty.get.pay_status==-1}>selected="selected"<{/if}> >支付状态</option>
            <option value="0" <{if $smarty.get.pay_status=='0'}>selected="selected"<{/if}>>未支付</option>
            <option value="1" <{if $smarty.get.pay_status==1}>selected="selected"<{/if}>>支付成功</option>
            <option value="2" <{if $smarty.get.pay_status==2}>selected="selected"<{/if}>>支付失败</option>  
        </select>
        <select name="payment_id" id="payment_id">
            <option value="-1" <{if $smarty.get.payment_id==-1}>selected="selected"<{/if}> >支付方式</option>
            <option value="1" <{if $smarty.get.payment_id==1}>selected="selected"<{/if}> >网银在线</option>
            <option value="2" <{if $smarty.get.payment_id==2}>selected="selected"<{/if}>>财付通</option>
            <option value="3" <{if $smarty.get.payment_id==3}>selected="selected"<{/if}>>支付宝</option>  
            <option value="4" <{if $smarty.get.payment_id==4}>selected="selected"<{/if}>>货到付款</option>
        </select>
        <select name="delivery_status" id="delivery_status">
            <option value="-1" <{if $smarty.get.delivery_status==-1}>selected="selected"<{/if}> >发货状态</option>
            <option value="0" <{if $smarty.get.delivery_status=='0'}>selected="selected"<{/if}>>未发货</option>
            <option value="1" <{if $smarty.get.delivery_status==1}>selected="selected"<{/if}>>已发货</option>
            <option value="2" <{if $smarty.get.delivery_status==2}>selected="selected"<{/if}>>已收货</option>  
        </select>
        <select name="order_status" id="order_status">
            <option value="-1" <{if $smarty.get.order_status==-1}>selected="selected"<{/if}>>订单状态</option>
            <option value="0" <{if $smarty.get.order_status=='0'}>selected="selected"<{/if}>>交易失败</option>
            <option value="1" <{if $smarty.get.order_status==1}>selected="selected"<{/if}>>交易成功</option>
            <option value="2" <{if $smarty.get.order_status==2}>selected="selected"<{/if}>>已退款</option>
            <option value="3" <{if $smarty.get.order_status==3}>selected="selected"<{/if}>>已退货</option>  
            <option value="4" <{if $smarty.get.order_status==4}>selected="selected"<{/if}>>已退款，已退货</option>
            <option value="5" <{if $smarty.get.order_status==5}>selected="selected"<{/if}>>未结单</option>  
        </select>
        <input type="submit" value="搜索" />
    </div>
    <div class="clear"></div>
</div>
<div class="info" style="overflow-y:scroll;">
	<{if $datas}>
      <table width="100%" border="0" cellspacing="1" cellpadding="5" style="background:#fff">
      <tr>
		 <td width="6%" align="center" class="title_color"><span id="all_select" class="all_select">全选</span></td>
        <td width="5%" align="center" class="title_color">订单号</td>
        <td width="18%" align="center" class="title_color">订单名称</td>
        <td width="10%" align="center" class="title_color">实付款</td>
        <td width="16%" align="center" class="title_color">收货信息</td>
        <td width="10%" align="center" class="title_color">状态</td>
         <td width="8%" align="center" class="title_color">用户名</td>
        <td width="10%" align="center" class="title_color">操作</td>
      </tr>
      <{section name=sn loop=$datas}>
      <tr>
	   <td align="center" class="body_color"><input type="checkbox" name="id[]" id="checkbox" value="<{$datas[sn].id}>"  <{if $datas[sn].order_status!=1}> disabled <{/if}> ></td>
        <td align="center"><{$datas[sn].sn}><br /><span style="color:green"><{$datas[sn].create_time}></span></td>
        <td align="center">
        	
        	<{section name=n loop=$datas[sn].product}>
        		<div class="product_list">
                	<span class="p_img"><img src="<{$public}>/uploads/<{$datas[sn].product[n].img}>"></span>
                	<span title="<{$datas[sn].product[n].name}>" style="cursor:pointer"><{$datas[sn].product[n].name|truncate:25:"..."}><br><font style="color:#F30">[&yen;<{$datas[sn].product[n].price}>×<{$datas[sn].product[n].amount}>]</font><br>
                    <font style="color:green">
                    <{foreach from=$datas[sn].product[n].specs_cn item=list}>
                        [<{$list.name}>]&nbsp;
                    <{/foreach}>
                    </font>
                    </span>
                    <div class="clear"></div>
                </div>
                
            <{/section}>
            
        </td>
        <td align="center"><span style="color:red;font-size:14px;">&yen;<{$datas[sn].pay_price}></span><br />含运费:&yen;<{$datas[sn].delivery_fee}><br><{$datas[sn].payment_id|replace:"1":"网银在线"|replace:"2":"财付通"|replace:"3":"支付宝"|replace:"4":"货到付款"}></td>
      
        <td align="center"><span style="color:red">姓名:<{$datas[sn].name}></span><br />电话:[<{$datas[sn].tel}>]<br /><span style='color:green'>地址:<{if $datas[sn].address}><{$datas[sn].address}><{else}>暂无<{/if}><br /><span style="color:#06C">留言:<{$datas[sn].message}></span></td>
          <td align="center">支付状态:<{$datas[sn].pay_status|replace:0:"<span style='color:#F30'>未支付</span>"|replace:1:"<span style='color:green'>支付成功</span>"|replace:2:"<span style='color:red'>支付失败</span>"}><br>发货状态:<{$datas[sn].delivery_status|replace:0:"<span style='color:#F30'>未发货</span>"|replace:1:"<span style='color:green'>已发货</span>"|replace:2:"<span style='color:green'>已收货</span>"}><br>订单状态:<{$datas[sn].order_status|replace:1:"<span style='color:green'>交易成功</span>"|replace:2:"<span style='color:red'>已退款</span>"|replace:3:"<span style='color:green'>已退货</span>"|replace:4:"<span style='color:green'>已退款，已退货</span>"|replace:0:"<span style='color:red'>交易失败</span>"|replace:5:"<span style='color:green'>未结单</span>"}></td>
        <td align="center"><{$datas[sn].user.user_name}></td>
        <td align="center">
         
        <a href="<{$app}>/orders/show/id/<{$datas[sn].id}>">点击进入</a>
        <{if $datas[sn].order_status==1}>
        &nbsp;&nbsp;<a href="<{$app}>/orders/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的订单吗?')" >删除</a>
        <{/if}>
        </td>
      </tr>
     
      <{/section}>
      </table>
	<{else}>
    	暂无内容!
	<{/if}>
</div>
<div class="fun_bar">
	<div class="oper"><input type="button" name="dels" id="del" class="admin_button" value="删除" onClick="return confirm('你确定要删除选中的订单吗?')" /></div>
    <div class="page red_l"><{$fpage}></div>
    <div class="clear"></div>
</div>
</form>
</body>
</html>
