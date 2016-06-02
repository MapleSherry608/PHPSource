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
<link href="<{$res}>/css/user.css" rel="stylesheet" type="text/css" />
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
	
	$("#score_money_but").click(
		function(){
			if($(this).is(":checked")){
				$("#score_input").show();
				$("#score").val("");
			} else {
				$("#score_input").hide();
				$("#payable").html("<{$payable}>");
				$("#total_price").val("<{$payable}>");
			}
		}
	)
	
	$("#score").keyup(
		function(){
			var score=parseFloat("<{$user_info.score}>");
			var score_money=parseFloat("<{$con_datas.score_money}>");
			var input_score=$(this).val();
			var payable=parseFloat("<{$payable}>");
			var totalprice=parseFloat("<{$total_price}>");
			var every_max_score=parseFloat("<{$con_datas.every_max_score}>");
			
			if(every_max_score!=0){
				if(score>=every_max_score){
					max_score=every_max_score;
				} else {
					max_score=score;
				}
			} else {
				max_score=score;
			}
			if(input_score>max_score){
				input_score=max_score;
			}else if(input_score<0 || isNaN(input_score)){
				input_score=0;
			}
			$(this).val(input_score);
			if(input_score>0){
				money=input_score/score_money;
				payable=payable-money;
				totalprice=totalprice-money
				if(payable<0) payable=0;
				$("#payable").html(payable);
				$("#total_price").val(totalprice);
			}else if(input_score==0){
				$("#payable").html("<{$payable}>");
				$("#total_price").val("<{$total_price}>");
			}
		}
	)
	
	$("input[name='payment_id']:eq(0)").attr("checked","checked");
	
	
	$("#district").change(
		function(){
			var id=$(this).val();
			$("#sdistrict").empty();
			if(id==0){
				
				$("#sdistrict").prepend("<option value=0>请选择</option>"); 
			} else {
			 $.post("<{$app}>/district/ajax_district",{id:id},
			  function(data){
				$.each( data, function(index, content){ 
				  $("#sdistrict").prepend("<option value='"+index+"'>"+content+"</option>"); 
				});
				
			  },
			  "json");//这里返回的类型有：json,html,xml,text
			}
		}
	)
	
	if($("#district").val()){
		var id=$("#district").val();
		 $.post("<{$app}>/district/ajax_district",{id:id},
			  function(data){
				$.each( data, function(index, content){ 
				  $("#sdistrict").prepend("<option value='"+index+"'>"+content+"</option>"); 
				});
				
			  },
			  "json");
	}
	
	
	$("#submit").click(
		function(){
			var data="<{$datas.0.id}>";
			if(!data){
				alert("购物车里没有商品!");
				return false;
			}
		}
	)
	
	
})
</script>
</head>

<body>
<{include file="public/header.tpl"}>
<form action="<{$app}>/orders/add_order" enctype="multipart/form-data" method="post">

<div id="body" class="web">
	
    <div class="cart_box">
    	<div class="tit">
        	我的购物车
        </div>
        <div class="content">
        	<div class="p_tit">
            	<ul>
                	<li style="width:150px;">商品图片</li>
                    <li style="width:450px;">名称</li>
                    <li style="width:100px;">单价</li>
                    <li style="width:130px;">数量</li>
                    <li style="width:120px;">操作</li>
                </ul>
            </div>
           <{if $datas}>
            <{section name=sn loop=$datas}>
            <div class="p_con">
            	<ul>
                	<li style="width:150px;"><img src="<{$public}>/uploads/<{$datas[sn].pro.img}>" /></li>
                    <li style="width:450px;">
                    	<div style="line-height:20px;">
                            <{$datas[sn].pro.name}>&nbsp;<p style="color:red;">所选参数：
                            <{foreach from=$datas[sn].specs_cn item=list}>
                                [<{$list.name}>]&nbsp;
                            <{/foreach}>
                            </p>
                        </div>
                    </li>
                    <li style="width:100px;">&yen;<{$datas[sn].price}></li>
                    <li style="width:130px;"><{$datas[sn].amount}></li>
                    <li style="width:120px;cursor:pointer"><a href="<{$app}>/cart/del_cart/id/<{$datas[sn].id}>"   onClick="return confirm('你确定要删除选中的商品吗?')">删除</a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <input type="hidden" name="price[<{$datas[sn].pro.id}>]" value="<{$datas[sn].price}>" />
            <input type="hidden" name="amount[<{$datas[sn].pro.id}>]" value="<{$datas[sn].amount}>" />
            <input type="hidden" name="specs[<{$datas[sn].pro.id}>]" value="<{$datas[sn].specs}>" />
            <input type="hidden" name="pid[]" value="<{$datas[sn].pro.id}>" />
			<input type="hidden" name="id[]" value="<{$datas[sn].id}>" />
            <{/section}>
            <{else}>
            <div class="no-content"></div>
            <{/if}>
            
            <{if $datas}>
            <div class="content">
                <div class="p_tit">
                    <ul>
                        <li style="width:300px;">您的积分</li>
                        <li style="width:300px;">积分抵现</li>
                        <li style="width:100px;">总价</li>
                        <li style="width:130px;">运费</li>
                        <li style="width:120px;">应付款</li>
                    </ul>
                </div>
                <div class="p_con">
                    <ul>
                        <li style="width:300px;color:red;">您有<b><{$user_info.score}></b>，<{$con_datas.score_money}>积分换1元<{if $con_datas.every_max_score!=0}>&nbsp;&nbsp;，每次使用最多<{$con_datas.every_max_score}>积分<{/if}></li>
                        <li style="width:300px;"><input type="checkbox" id="score_money_but" name="score_money" value="1" />&nbsp;
                        	<span id="score_input" style="display:none;">请输入抵的积分<input type="text" name="score" id="score" style="width:50px;"/></span>
                        </li>
                        <li style="width:100px;">&yen;<{$total_price}></li>
                        <li style="width:130px;">&yen;<{$delivery_fee}></li>
                        <li style="width:120px;font-size:16px;color:red;font-weight:bold;">&yen;<span id="payable"><{$payable}></span></li>
                    </ul>
                    <div class="clear"></div>
                </div>
            </div>
            <input type="hidden" name="delivery_fee" id="delivery_fee" value="<{$delivery_fee}>" />
            <input type="hidden" name="total_price" id="total_price" value="<{$total_price}>" />
            <div class="content">
                <div class="p_tit">
                    <ul>
                        <li style="width:300px;">支付方式</li>
                        <li style="width:650px;">介绍</li>
                    </ul>
                </div>
                <{section name=sn loop=$payment_info}>
                <div class="p_con" id="payment">
                    <ul>
                        <li style="width:300px;color: #3399FF; text-align:left;text-indent:9em;"><input type="radio" name="payment_id" value="<{$payment_info[sn].id}>" /><{$payment_info[sn].byname}></li>
                        <li style="width:650px;text-align:left;text-indent:15em;"><{$payment_info[sn].introduction}>
                        	
                        </li>
                        
                    </ul>
                    <div class="clear"></div>
                </div>
                <{/section}>
            </div>
            
            <div class="content">
                <div class="p_tit">
                    <ul>
                        <li style="width:300px;">收货信息</li>
                       
                    </ul>
                </div>
                <div class="p_con">
                    <ul>
                        <li style="width:950px;color: #333333;text-align:left;text-indent:10em;">收货地区：
                        	<select name="district" id="district" class="select_box">
                            	<option value="0">请选择</option>
                            	<{section name=sn loop=$district}>
                            		<option value="<{$district[sn].id}>"><{$district[sn].district_name}></option>
                                <{/section}>
                            </select>&nbsp;&nbsp;
                            <select name="sdistrict" id="sdistrict" class="select_box">
                            		<option value="0">请选择</option>
                            </select>
                        </li>
                        <li style="width:950px;text-align:left;text-indent:10em;">收货地址：<input type="text" name="address" style="width:300px;" class="input_box"/></li>
                        <li style="width:950px;text-align:left;text-indent:10em;">收货姓名：<input type="text" name="name" style="width:150px;" class="input_box"/></li>
                        <li style="width:950px;text-align:left;text-indent:10em;">联系电话：<input type="text" name="tel" style="width:150px;" class="input_box"/></li>
                        <li style="width:950px;text-align:left;text-indent:10em;">用户留言：<input type="text" name="message" style="width:500px;" class="input_box"/></li>
                        <li style="text-align:left;margin-left:60px"><input type="hidden" name="user_id" value="<{$user.id}>" /><input type="submit" id="submit" class="submit" value="提交订单" /></li>
                    </ul>
                    <div class="clear"></div>
                </div>
            </div>
            <{/if}>
            
        </div>
    </div>
    
</div>
<input type="hidden" name="rancode" value="<{$rancode}>">
</form>
<{include file="public/footer.tpl"}>
</body>
</html>
