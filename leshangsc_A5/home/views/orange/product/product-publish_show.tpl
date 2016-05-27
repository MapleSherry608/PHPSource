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
<link href="<{$res}>/css/product.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/banner.css" rel="stylesheet" type="text/css" />
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
$(function(){
	$("#products .product_list:nth-child(5n)").css('border-right','0');
	
	var inventory="<{$pro_data.inventory}>";
	var cur_num=$("#p_num").val();
	$("#sub").click(
		function(){
			
			if(cur_num>1){
				cur_num--;
				$("#p_num").val(cur_num);
			} 
		}
	)
	$("#add").click(
		function(){
			if(cur_num<inventory){
				cur_num++;
				$("#p_num").val(cur_num);
			} 
		}
	)
	
	$(".select_box ul li").click(
		function(){
			var pid=$(this).attr("pid");
			var current_li_num=$("#select_box_"+pid+" ul li").length;//当前参数可选数量
			var param_num=$(".select_box").length;//分类数量
			
			if(current_li_num<2){
				$(this).toggleClass("selected");
			} else {
				$("#select_box_"+pid+" ul li").removeClass();
				$(this).addClass("selected");
			}
			var selected_num=$(".select_box ul li.selected").length;//当前选中分类数量
			var arr = new Array();
			if(param_num==selected_num){//如果选完了分类开始判断价格等
				$(".select_box ul li.selected").each(
					function(){
						arr.push($(this).attr("id"));
						//arr+=$(this).attr("id");
					}
				)
				arr=arr.join(",");
				$.post(APP_PATH+"/product/ajax_price",{arr:arr,id:"<{$pro_data.id}>"},
				 function(data){
						  $("#origin_price").html(data.origin_price);
						  $("#price").html(data.current_price);
						  $("#inventory").html(data.inventory);
						  if(data.inventory==0){
							  $("#buy").animate({"opacity":"0"},"fast",function(){
								  $(this).hide();
							  });
						  }else{
							
							  $("#buy").animate({"opacity":"100"},"fast");
						  }
				 },"json");
				
			}
		}
	)
	
	$(".detail_box .tit ul li").click(
		function(){
			var id=$(this).attr("id");
			$(".detail_box .tit ul li").removeClass();
			$(this).addClass("selected");
			$(".detail_box .content").hide();
			$("#con_"+id).show();
		}
	)
	
	$(".appraise_menu ul li").click(
		function(){
			var id=$(this).attr("id");
			$(".appraise_menu ul li").removeClass();
			$(this).addClass("s");
			$(".appraise_content").hide();
			$("#appraise_content_"+id).show();
		}
	)
	
	//加入购物车
	$(".buy").click(
		function(){
			if(!ISLOGIN){
				open_url=APP_PATH+'/user/log_index';
					$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '乐尚商城', showButton:false,animate: true,width:590, height: 320,clickClose: true});
					return false;
			} else {
				var param_num=$(".select_box").length;//分类数量
				var selected_num=$(".select_box ul li.selected").length;//当前选中分类数量
				var specs="";
				if(param_num!=selected_num){
					alert("请选择参数!");
					return false;
				}
				if(param_num==0){
					specs=0;
				} else{
					$(".select_box ul li.selected").each(function(index, element) {
						specs+=$(this).attr("id")+',';
                    });
					
				}
				var price=$("#price").text();
				var pid="<{$pro_data.id}>";
				var uid="<{$user.id}>";
				var url="<{$app}>/cart/add_cart";
				var p_num=$("#p_num").val();
				$.ajax({   
					type:"post",     
					url:url,
					data:{pid:pid,uid:uid,amount:p_num,price:price,specs:specs},
					success:function(msg){
						if(msg==1){
							alert("商品已加入购物车!");
							var num=$("#cart_num").html();
							num++;
							$("#cart_num").html(num);
						}else if(msg==2){
							alert("购物车中已有此商品!");
						}else if(msg==4){
							alert("不能买自己发布的商品!");
						}
					},   
					error:function(){
						alert("加入购物车失败");   
					}   
				});  
			}
		}
	)
	
	$(".nobuy").live("click",function(){
		alert("不好意思，没货了");
		return false;
	});
	//产品收藏
	$("#fav").click(
		function(){
			var pid="<{$pro_data.id}>";
			var user_id="<{$user.id}>";
			if(!user_id){
				alert("请先登录后再收藏商品!");
				return false;
			}
			var url="<{$app}>/Profav/add_fav";
		
			$.ajax({   
				type:"post",     
				url:url,
				data:{pid:pid,user_id:user_id},
				success:function(msg){
					if(msg==1){
						alert("收藏商品成功!");
					}else if(msg==2){
						alert("此商品您已收藏!");
					}
				},   
				error:function(){
					alert("收藏失败");   
				}   
			});  
		}
	)
	
	
	$("#publish").click(
	  function(){
		  if(!ISLOGIN){
			  open_url=APP_PATH+'/user/log_index';
			  $.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '乐尚商城', showButton:false,animate: true,width:590, height: 320,clickClose: true});
			  return false;
		  } else {
			  	var id="<{$pro_data.id}>";
			  	$.get(APP_PATH+"/appraise/ajax_after_use",{id:id},function(data){
					if(data){
						
						open_url=APP_PATH+'/appraise/publish/uid/<{$user.id}>/pid/<{$pro_data.id}>';
						$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '发表评价', showButton:false,animate: true,width:590, height: 300,clickClose: true});	
					} else {
						alert("购买且收货后再评价!");
					}
				});
			  	
	  
		  }
	  	}
	  )
	
	$("#submit").click(
	  function(){
		  if(!ISLOGIN){
			  open_url=APP_PATH+'/user/log_index';
			  $.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '乐尚商城', showButton:false,animate: true,width:590, height: 320,clickClose: true});
			  return false;
		  } else {
			  	open_url=APP_PATH+'/appraise/publish/uid/<{$user.id}>/pid/<{$pro_data.id}>';
				//$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '发表评论', showButton:false,animate: true,width:590, height: 300,clickClose: true});	
	  
		  }
	  	}
	  )
	  
	 
});
</script>
</head>

<body>
<{include file="public/header.tpl"}>
<div id="body" class="web_body">
	
    <div class="mid_banner">
    	<{section name=sn loop=$adv_datas_18}>
            	 <a target="_blank" href="<{$adv_datas_18[sn].url}>"><img src="<{$public}>/uploads/<{$adv_datas_18[sn].pic}>"></a>
            <{/section}>
    </div>
    <div class="position"><{$position}></div>
    <div class="product_show_box">
    	<div class="image">
        	<img src="<{$public}>/uploads/<{$pro_data.img}>">
        </div>
        <div class="split_line"></div>
        <div class="right_box">
        
        	<div class="name"><{$pro_data.name}></div>
            <div class="price_box">
            	<div class="tit">价格</div>
                <div class="price">
                	<p class="current" id="current_price">&yen;<span id="price"><{$pro_data.current_price}></span></p>
                    <p class="origin">运费:&yen;<{$pro_data.delivery_fee}></p>
                </div>
                <div class="clear"></div>
            </div>
            <div class="class_box">
            	<div class="tit">销量</div>
                <div class="class">已售出<span style="color:red">10</span>件</div>
            </div>
            <div class="class_box">
            	<div class="tit">评分</div>
                <div class="class">
                	<span class="percent_bar left"><div style="width:<{$pro_data.good_per}>%;height:10px; background:#FF0000;"></div></span>
                    <div class="left"><{$pro_data.good_per}>%&nbsp;&nbsp;已有<span style="color:red"><{$pro_data.appraise_num}></span>人评价</div>
                </div>
            </div>
            
             <{foreach from=$pro_data.spec_main key=k item=v}>
            <div class="class_box">
            	<div class="tit"><{$v}></div>
                <div class="class">
                	<div class="select_box" id="select_box_<{$k}>">
                    	<ul>
                        	<{foreach from=$pro_data.specs key=sk item=sv}>
                            	<{if $k==$sv}>
                        		<li id="<{$sk}>" pid="<{$sv}>"><{$pro_data.specs_cn.$sk}></li>
                                <{/if}>
                            <{/foreach}>
                        </ul>
                    </div>
                </div>
            </div>
            <{/foreach}>
            <div class="class_box">
            	<div class="tit">数量</div>
                <div class="class"><div class="calc_box" id="sub">-</div><div style="float:left"><input type="text" class="p_num" value="1" id="p_num" readonly="readonly" /></div><div class="calc_box" id="add">+</div><span style="color:red;float:left;margin-left:10px;" >库存<span id="inventory"><{$pro_data.inventory}></span>件</span></div>
            </div>
            <div class="class_box">
            	<div class="tit">操作</div>
                <div class="class"><div class="buy" id="buy">加入购物车</div>&nbsp;<div class="fav" id="fav">加入收藏</div></div>
            </div>
            <div class="class_box">
            	<div class="tit">其他</div>
                <div class="class">上架时间：<{$pro_data.add_time|date_format:'%Y-%m-%d'}>&nbsp;&nbsp;浏览次数：<{$pro_data.click}></div>
            </div>
            <div class="class_box">
            	<div class="tit">发布会员</div>
                <div class="class"><span style="color:red"><{$pro_data.user_info.user_name}></span>&nbsp;<span style="color:#363;cursor:pointer;font-size:12px;font-weight:bold" class="friend" data-name="<{$pro_data.user_info.user_name}>" id="<{$pro_data.user_id}>">加好友</span>&nbsp;<span style="color:#363;cursor:pointer;font-size:12px;font-weight:bold" class="letter" data-name="<{$pro_data.user_info.user_name}>" id="<{$pro_data.user_id}>">发私信</span></div>
            </div>
            
        </div>
        <div class="clear"></div>
    </div>
    
    
    <div class="left">
    	<div class="hot_pro_box">
        	<div class="tit">热销排行
            </div>
            <div class="content">
            	<ul>
                	<{section name=sn loop=$hot_product_datas max=6}>
                	<li>
                    	<span class="hot_logo"><a href="<{$hot_product_datas[sn].url}>"><{if $hot_product_datas[sn].img}><img src="<{$public}>/uploads/<{$hot_product_datas[sn].img}>"><{else}><img src="<{$res}>/images/nopic.gif"><{/if}></a></span>
                        <span class="hot_name"><{$hot_product_datas[sn].name|truncate:"50":"..."}></span>
                        <span class="hot_price">&yen;<{$hot_product_datas[sn].current_price}></span>
                    </li>
                    <{/section}>
                </ul>
            </div>
        </div>
    </div>
    <div class="right">
    	<div class="detail_box">
        	<div class="tit">
            	<ul class="left">
                	<li class="selected" id="xq">商品详情</li>
                    <li id="pj">商品评价</li>
                </ul>
                <ul class="right">
                	<span class="serial_no">货号:<{$pro_data.serial_no}></span>
                </ul>
            </div>
            <div class="content" id="con_xq">
            	<{$pro_data.brief}>
            </div>
            <div class="content" id="con_pj" style="display:none;">
            	<div class="percent_box">
                	<span class="percentage">
                    	<{$pro_data.good_per}>%
                    </span>
                    <span class="percent_line">
                    	<ul>
                        	<li><div class="left">好评(<{$pro_data.good_per}>%)</div><div class="left" style="width:<{$pro_data.good_per}>%;height:10px; background:#FF0000;margin-left:20px;margin-top:6px;"></div><div class="clear"></div></li>
                            <li><div class="left">中评(<{$pro_data.middle_per}>%)</div><div class="left" style="width:<{$pro_data.middle_per}>%;height:10px; background:#FF9900;margin-left:20px;margin-top:6px;"></div><div class="clear"></div></li>
                            <li><div class="left">差评(<{$pro_data.bad_per}>%)</div><div class="left" style="width:<{$pro_data.bad_per}>%;height:10px; background:green;margin-left:20px;margin-top:6px;"></div><div class="clear"></div></li>
                        </ul>
                    </span>
                    <span class="button"><div class="publish" id="publish">发表评价</div></span>
                </div>
                <div class="appraise_menu">
                	<ul>
                    	<li class="s" id="all">所有(<{$pro_data.appraise_num}>)</li>
                        <li id="good">好评(<{$pro_data.good_num}>)</li>
                        <li id="mid">中评(<{$pro_data.middle_num}>)</li>
                        <li id="bad">差评(<{$pro_data.bad_num}>)</li>
                    </ul>
                </div>
                <div class="appraise_content" id="appraise_content_all">
                	<{section name=sn loop=$pro_data.appraise}>
                	<dl>
                    	<dt>
                        	<div class="left"><span class="uname_box"><a class="uname uname_area" target="_blank"><{$pro_data.appraise[sn].user.user_name}></a><div class="user_private_list"><ul><li><a class="letter" data-name="<{$pro_data.appraise[sn].user.user_name}>" id="<{$pro_data.appraise[sn].user.id}>">发私信</a></li><li><a class="friend" id="<{$pro_data.appraise[sn].user.id}>">加好友</a></li></ul></div></span>&nbsp;&nbsp;<{$pro_data.appraise[sn].content_time|date_format:"%Y-%m-%d %H:%M:%S"}></div>
                            <div class="right">
                            	<{if $pro_data.appraise[sn].level==1}>
                                	<span style="color:red">
                                <{elseif $pro_data.appraise[sn].level==2}>
                                	<span style="color: #FF9900">
                                <{else}>
                                	<span style="color:green">
                                <{/if}>
                                    <{$pro_data.appraise[sn].level|replace:"1":"好评"|replace:"2":"中评"|replace:"3":"差评"}></span></div>
                        </dt>
                        <dd>
                        	<p><{$pro_data.appraise[sn].content}></p>
                            <p><span style="color:red">回复：</span><{$pro_data.appraise[sn].reply}>&nbsp;&nbsp;<span style="color:green">回复时间:<{$pro_data.appraise[sn].reply_time|date_format:'%Y-%m-%d %H:%M:%S'}></span></p>
                        </dd>
                	</dl>
                    <{/section}>
                </div>
                
                <div class="appraise_content" id="appraise_content_good" style="display:none;">
                	<{section name=sn loop=$pro_data.appraise}>
                    <{if $pro_data.appraise[sn].level==1}>
                	<dl>
                    	<dt>
                        	<div class="left"><span class="uname_box"><a class="uname uname_area" target="_blank"><{$pro_data.appraise[sn].user.user_name}></a><div class="user_private_list"><ul><li><a class="letter" data-name="<{$pro_data.appraise[sn].user.user_name}>" id="<{$pro_data.appraise[sn].user.id}>">发私信</a></li><li><a class="friend" id="<{$pro_data.appraise[sn].user.id}>">加好友</a></li></ul></div></span>&nbsp;&nbsp;<{$pro_data.appraise[sn].content_time|date_format:'%Y-%m-%d %H:%M:%S'}></div>
                            
                        </dt>
                        <dd>
                        	<p><{$pro_data.appraise[sn].content}></p>
                            <p><span style="color:red">回复：</span><{$pro_data.appraise[sn].reply}>&nbsp;&nbsp;<span style="color:green">回复时间:<{$pro_data.appraise[sn].reply_time|date_format:'%Y-%m-%d %H:%M:%S'}></span></p>
                        </dd>
                	</dl>
                    <{/if}>
                    <{/section}>
                </div>
                
                <div class="appraise_content" id="appraise_content_mid" style="display:none;">
                	<{section name=sn loop=$pro_data.appraise}>
                    <{if $pro_data.appraise[sn].level==2}>
                	<dl>
                    	<dt>
                        	<div class="left"><span class="uname_box"><a class="uname uname_area" target="_blank"><{$pro_data.appraise[sn].user.user_name}></a><div class="user_private_list"><ul><li><a class="letter" data-name="<{$pro_data.appraise[sn].user.user_name}>" id="<{$pro_data.appraise[sn].user.id}>">发私信</a></li><li><a class="friend" id="<{$pro_data.appraise[sn].user.id}>">加好友</a></li></ul></div></span>&nbsp;&nbsp;<{$pro_data.appraise[sn].content_time|date_format:'%Y-%m-%d %H:%M:%S'}></div>
                            
                        </dt>
                        <dd>
                        	<p><{$pro_data.appraise[sn].content}></p>
                            <p><span style="color:red">回复：</span><{$pro_data.appraise[sn].reply}>&nbsp;&nbsp;<span style="color:green">回复时间:<{$pro_data.appraise[sn].reply_time|date_format:'%Y-%m-%d %H:%M:%S'}></span></p>
                        </dd>
                	</dl>
                    <{/if}>
                    <{/section}>
                </div>
                <div class="appraise_content" id="appraise_content_bad" style="display:none;">
                	<{section name=sn loop=$pro_data.appraise}>
                    <{if $pro_data.appraise[sn].level==3}>
                	<dl>
                    	<dt>
                        	<div class="left"><span class="uname_box"><a class="uname uname_area" target="_blank"><{$pro_data.appraise[sn].user.user_name}></a><div class="user_private_list"><ul><li><a class="letter" data-name="<{$pro_data.appraise[sn].user.user_name}>" id="<{$pro_data.appraise[sn].user.id}>">发私信</a></li><li><a class="friend" id="<{$pro_data.appraise[sn].user.id}>">加好友</a></li></ul></div></span>&nbsp;&nbsp;<{$pro_data.appraise[sn].content_time|date_format:'%Y-%m-%d %H:%M:%S'}></div>
                            
                        </dt>
                        <dd>
                        	<p><{$pro_data.appraise[sn].content}></p>
                            <p><span style="color:red">回复：</span><{$pro_data.appraise[sn].reply}>&nbsp;&nbsp;<span style="color:green">回复时间:<{$pro_data.appraise[sn].reply_time|date_format:'%Y-%m-%d %H:%M:%S'}></span></p>
                        </dd>
                	</dl>
                    <{/if}>
                    <{/section}>
                </div>
                
            </div>
            
            
        </div>
    </div>
    
    <div class="clear"></div>
</div>
<{include file="public/footer.tpl"}>


</body>
</html>
