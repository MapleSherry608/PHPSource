<?php defined('G_IN_ADMIN')or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>科技狗云购系统后台首页</title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
	 body{ background-color:#fefeff; font:12px/1.5 arial,宋体b8b\4f53,sans-serif;}
	.width30{  font-size:12px; border-radius:5px 2px 20px 2px;  }
	.on {color: #fff;background: #0c0;padding: 3px 6px;font-size: 20px;}
	
	.title{ font-size:15px; font-weight:bold; color:#444; line-height:30px; border-bottom:1px solid #ccc;}
	.div-news{ height:50px; background-color:#fff}
	.div-user span{ display:block; font-size:12px; font: 12px/1.5 arial,宋体b8b\4f53,sans-serif; line-height:20px; color:#999}
	.div-user{ background-color:#fff; padding:20px;width:30%;float:left;  border-bottom:1px solid #eee }
	.div-button{ float:left;background-color:#fff; float:left; padding:20px; margin:0 10px; width:55%;border-radius:5px 5px 5px 5px;}
	.div-button ul li{ float:left; margin:0px 25px;}
	.div-button li a{  cursor:pointer; text-decoration:none}
	.div-button li span{ display:block; width:60px; text-align:center; line-height:32px;} 
	
	.div-system{background-color:#fff; float:left; padding:20px; margin:0 10px;border-right:1px solid #eee}
	.div-webinfo{background-color:#fff; float:left; padding:20px; margin:0 10px; width:27%;border-right:1px solid #eee }
	.div-about{background-color:#fff; float:left; padding:20px; margin:0 10px; overflow:hidden}
	 li{font:12px/1.5 arial,宋体b8b\4f53,sans-serif;}
	.div-system ul li{height:30px; line-height:30px;color:#333;border-bottom:1px dotted #ddd; font-size: 14px;}
	.div-system ul li i{height:30px; line-height:30px; display:inline-block; color:#666;}
	
		
	.div-about ul li{height:30px; line-height:30px;color:#333;border-bottom:1px dotted #ddd;}
	.div-about ul li i{width:90px;height:30px; line-height:30px; display:inline-block; color:#666;}
	
	.div-webinfo ul li{height:30px; line-height:30px;color:#333;border-bottom:1px dotted #ddd;}
	.div-webinfo ul li i{width:90px;height:30px; line-height:30px; display:inline-block; color:#666;}
	
	.CMS_message{background-color: #eef3f7;border: 1px solid #d5dfe8; height:20px; padding:5px 0px; overflow:hidden}
	.CMS_message li{ text-indent:50px; height:25px; line-height:25px; color:#09c;font-size:12px; font-weight:bold;}
	
</style>
</head>
<body>

<p>
    <br/>
</p>
<div class="bk30"></div>
<div class="div-user lr10">
	<h1>Hello, <font color="#4c95b6"><?php echo $info['username'] ;?></font><h1>
    <span>所属角色: 超级管理员</span>
    <span>上次登录时间: <?php echo date("Y-m-d H:i:s",$info['logintime']); ?></span>
    <span>上次登录IP: <?php echo $info['loginip']; ?></span>
</div>
<div class="div-button">
<div class="bk15"></div>
	<ul>
    	<li><a  href="<?php echo G_MODULE_PATH; ?>/content/article_add"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_t.png"><span>添加文章</span></a></li>
        <li><a href="<?php echo G_MODULE_PATH; ?>/content/goods_add"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_g.png"><span>添加商品</span></a></li>
        <li><a href="<?php echo WEB_PATH; ?>/member/member/list"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_m.png"><span>会员管理</span></a></li>
        <li><a href="<?php echo G_MODULE_PATH; ?>/setting/webcfg"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_s.png"><span>系统设置</span></a></li>
        <li><a href="<?php echo G_WEB_PATH; ?>"><img src="<?php echo G_GLOBAL_STYLE; ?>/global/image/btn_60_60_i.png"><span>网站首页</span></a></li>
    </ul>
</div>

<div class="bk10"></div>
<script type="text/javascript">
(function(A){
   function _ROLL(obj){
      this.ele = document.getElementById(obj);
	  this.interval = false;
	  this.currentNode = 0;
	  this.passNode = 0;
	  this.speed = 100;
	  this.childs = _childs(this.ele);
	  this.childHeight = parseInt(_style(this.childs[0])['height']);
	      addEvent(this.ele,'mouseover',function(){
				                               window._loveYR.pause();
											});
		  addEvent(this.ele,'mouseout',function(){
				                               window._loveYR.start(_loveYR.speed);
											});
   }
   function _style(obj){
     return obj.currentStyle || document.defaultView.getComputedStyle(obj,null);
   }
   function _childs(obj){
	  var childs = [];
	  for(var i=0;i<obj.childNodes.length;i++){
		 var _this = obj.childNodes[i];
		 if(_this.nodeType===1){
			childs.push(_this);
		 }
	  }   
	  return childs;
   }
	function addEvent(elem,evt,func){
	   if(-[1,]){
		   elem.addEventListener(evt,func,false);   
	   }else{
		   elem.attachEvent('on'+evt,func);
	   };
	}
	function innerest(elem){
      var c = elem;
	  while(c.childNodes.item(0).nodeType==1){
	      c = c.childNodes.item(0);
	  }
	  return c;
	}
   _ROLL.prototype = {
      start:function(s,v){
	          var _this = this;
			  
			  _this.hh=v;
			  _this.speed = s || 100;//速度
		      _this.interval = setInterval(function(){
									
						    _this.ele.scrollTop += 1;							
							if(_this.ele.scrollTop==_this.hh){								
								//clearInterval(_this.interval);
							}
							
							_this.passNode++;
							if(_this.passNode%_this.childHeight==0){
								  var o = _this.childs[_this.currentNode] || _this.childs[0];
								  _this.currentNode<(_this.childs.length-1)?_this.currentNode++:_this.currentNode=0;
								  _this.passNode = 0;
								  _this.ele.scrollTop = 0;
								  _this.ele.appendChild(o);
							}
						  },_this.speed);
	  },
	
	  pause:function(){
		 var _this = this;
	     clearInterval(_this.interval);
	  }
   }
    A.marqueen = function(obj){A._loveYR = new _ROLL(obj); return A._loveYR;}
})(window);

marqueen('roll').start(50,30);
</script>

<div style="overflow:hidden">
<!------------>

 <div class="div-system width30">
        <div class="title"><span class="on">科技狗VIP企业版云购程序功能演示</span></div>
        	<div class="bk10"></div>
            <ul>      
                <li><i>科技狗VIP企业版功能介绍:</i><a href="http://yungou.techgou.com/price.html" target="_blank">点击了解</a></li>	
                <li><i>安卓IOS双APP演示: </i><a href="http://yungou.techgou.com/app/" target="_blank">http://yungou.techgou.com/app/</a></li>				
                <li><i>电脑端演示地址: </i><a href="http://qiye.techgou.com" target="_blank">http://qiye.techgou.com</a></li>
				<li><i>手机端演示地址: </i><a href="http://m.qiye.techgou.com" target="_blank">http://m.qiye.techgou.com</a></li>
				<li><i>前台演示账号 </i>账号：13838051420 密码：techgou</li>
                <li><i>后台演示地址: </i><a href="http://qiye.techgou.com/?admin" target="_blank">http://qiye.techgou.com/?admin</a></li>
				<li><i>后台登录账号: </i>账号：techgou 密码：techgou</li>
                
				 <li><i>微信端演示: </i>关注科技狗官方微信查看（微信搜：科技Dog）</li>
				 <p><img src="/images/code.jpg"></p>
				<li><i>购买咨询QQ: </i><a href="http://wpa.qq.com/msgrd?v=3&uin=544254520&site=qq&menu=yes" target="_blank">544254520</a></li>
				<li><i>技术支持QQ群: </i><a href="http://jq.qq.com/?_wv=1027&k=29fLjsv" target="_blank">239556205</a></li>
            </ul>      
    </div>


    <div class="div-system width30">
        <div class="title"><span class="on">科技狗VIP个人版云购程序功能演示</span></div>
        	<div class="bk10"></div>
            <ul>      
			 <li><i>安卓IOS双APP演示: </i><a href="http://yungou.techgou.com/app/" target="_blank">http://yungou.techgou.com/app/</a></li>
                <li><i>VIP功能介绍: </i><a href="http://yungou.techgou.com/price.html" target="_blank">点击了解</a></li>			
                <li><i>电脑端演示地址: </i><a href="http://vip.techgou.com" target="_blank">http://vip.techgou.com</a></li>
				<li><i>手机端演示地址: </i><a href="http://m.vip.techgou.com" target="_blank">http://m.vip.techgou.com</a></li>
				<li><i>前台演示账号 </i>账号：13838051420 密码：techgou</li>
                <li><i>后台演示地址: </i><a href="http://vip.techgou.com/?admin" target="_blank">http://vip.techgou.com/?admin</a></li>
				<li><i>后台登录账号: </i>账号：techgou 密码：techgou</li>
				<li><i>购买咨询QQ: </i><a href="http://wpa.qq.com/msgrd?v=3&uin=544254520&site=qq&menu=yes" target="_blank">544254520</a></li>
				<li><i>技术支持QQ群: </i><a href="http://jq.qq.com/?_wv=1027&k=29fLjsv" target="_blank">239556205</a></li>
				
            </ul>      
    </div>
	<?php
	$tj_category=$this->db->GetList("SELECT cateid FROM `@#_category` WHERE `model` = '1'");
	$tj_brand=$this->db->GetList("SELECT id FROM `@#_brand`");
	$tj_article=$this->db->GetList("SELECT * FROM `@#_article`");
	$tj_shoplist=$this->db->GetList("SELECT id FROM `@#_shoplist`");	
	$time=time();
	$tj_shoplist_xsjx=$this->db->GetList("SELECT id FROM `@#_shoplist` where `xsjx_time`>'$time'");
	$tj_member=$this->db->GetList("SELECT uid FROM `@#_member`");
	
	$tm=time()-24*3600;
	$tj_member_new=$this->db->GetList("SELECT uid FROM `@#_member` where `time`>'$tm' ");
	$tj_shoplist_new=$this->db->GetList("SELECT id FROM `@#_shoplist` where `time`>'$tm' ");
	$tj_member_account=$this->db->GetList("SELECT money FROM `@#_member_account` where `pay`='账户' and `type`=1 and `time`>'$tm'");
	$today_money=0;
	foreach ($tj_member_account as $account){
		$today_money=$account['money']+$today_money;
	}
	?>
  
    
    <div class="div-about width30">
        <div class="title">关于我们</div>
        <div class="bk10"></div>
        <ul>
        	<li><i>程序版本:</i>V5.50<font color="#f60">【免费版】</font></li>
			<li><i>更新时间:</i>2016.4.20</li>
			<li><i>程序更新：</i><a href="http://mp.weixin.qq.com/s?__biz=MzIzNDMyNzUyNw==&mid=100000018&idx=1&sn=7a5f8d6ad144264ab3acacc9ac3cba0c#rd" target="_blank">点击更新</a></li>
  <li><i>技术支持：</i><a href="http://www.techgou.com" target="_blank">河南科技狗网络技术有限公司</a></li>
  <li><i>技术支持QQ群: </i><a href="http://jq.qq.com/?_wv=1027&k=29fLjsv" target="_blank">239556205</a></li>
        </ul>
         
    </div>
<!------------>
</div>
</body>
</html> 
