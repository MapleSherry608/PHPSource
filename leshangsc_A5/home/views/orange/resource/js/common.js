// JavaScript Document
	
$(document).ready(function(){
	$(".nav>ul>li").bind('mouseover',function(){
		$(this).children('.sub_nav').slideDown('fast');
	}).bind('mouseleave',function(){
		$(this).children('.sub_nav').slideUp('fast');
	});
	
	$("#head_photo").bind('mouseover',function(){
		$(this).children('.sub_nav').slideDown('fast');
	}).bind('mouseleave',function(){
		$(this).children('.sub_nav').slideUp('fast');
	});
	
	$(".uname_box").bind('mouseover',function(){
		$(this).children('.user_private_list').slideDown('fast');
	}).bind('mouseleave',function(){
		$(this).children('.user_private_list').slideUp('fast');
	});
	
	$("#login").click(
		function(){
			open_url=APP_PATH+'/user/log_index';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 320,clickClose: true});	
		}
	);
	
	$(".letter").click(
		function(){
			if(ISLOGIN){
				id=$(this).attr("id");
				user_name=$(this).attr("data-name");
				title="给"+user_name+"发私信";
				open_url=APP_PATH+'/user/letter_index/id/'+id;
				$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: title, showButton:false,animate: true,width:570, height: 470,clickClose: true});	
			} else {
				window.location.href=APP_PATH+'/user/letter_index';
			}
		}
	);
	
	$(".friend").click(
		function(){
			if(ISLOGIN){
				id=$(this).attr("id");
				title="加好友";
				open_url=APP_PATH+'/friend/index/friend_id/'+id;
				$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: title, showButton:false,animate: true,width:570, height: 470,clickClose: true});	
			} else {
				window.location.href=APP_PATH+'/friend/index';
			}
		}
	);
	
	$("#survey_result").click(
		function(){
			id=$(this).attr("data");
			title="投票结果";
			open_url=APP_PATH+'/survey/survey_result/id/'+id;
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: title, showButton:false,animate: true,width:570, height: 300,clickClose: true});
		}
	);
	
	$("img").lazyload({    
		placeholder : "img/grey.gif",    
		effect : "fadeIn"   
	}); 
	
	
	
	
	$("#logo").click(
			function(){
				window.location.href=APP_PATH;
			}
		);
		$(".m_mail").click(
			function(){
				window.location.href=APP_PATH+"/letter";
			}
		);
		$("#user_mod").click(
			function(){
				open_url=APP_PATH+'/user/mod_index/id/<{$user.id}>';
				$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 600,clickClose: true});	
			}
		);
		$(".m_center").click(
			function(){
				window.location.href=APP_PATH+"/user/index";
			}
		);
		$(".m_logout").click(
			function(){
				window.location.href=APP_PATH+"/user/logout";
			}
		);
		var isLogin="<{$user.isLogin}>";
		$("#publish_news").click(
			function(){
				if(isLogin==""){
					open_url=APP_PATH+'/user/log_index';
					$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 320,clickClose: true});
					return false;
				} else {
					open_url=APP_PATH+'/news/publish_index';
					window.open(open_url);
				}
			}
		);
		$("#publish_product").click(
			function(){
				if(isLogin==""){
					open_url=APP_PATH+'/user/log_index';
					$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 320,clickClose: true});
					return false;
				} else {
					open_url=APP_PATH+'/product/publish_index';
					window.open(open_url);
				}
			}
		);
		$(".m_publish").hover(
			function(){
				$("#p_nav").show();
			},function(){
				$("#p_nav").hide();
			}
		)
		
		$(".m_cart").click(
			function(){
				if(isLogin==""){
					open_url=APP_PATH+'/user/log_index';
					$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 320,clickClose: true});
					return false;
				} else {
					open_url=APP_PATH+'/cart/index';
					window.location.href=open_url;
				}
			}
		);
		
		$(".m_login").click(
			function(){
				open_url=APP_PATH+'/user/log_index';
					$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '乐尚商城', showButton:false,animate: true,width:590, height: 320,clickClose: true});
					return false;
			}
		);
		$(".m_reg").click(
		function(){
			open_url=APP_PATH+'/user/reg_index';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 600,clickClose: true});	
	
			}
		)
		$(".s_but").click(
			function(){
				if($("#search_box").val()==""){
					alert("请输入搜索内容");
				} else {
					$("#search_form").submit();
				}
			}
		)
		$("#txt").click(
			function(){
				var t=$(this).html();
				if(t=="商品"){
					$(this).html("新闻");
					$("#search_form").attr("action",APP_PATH+"/news/search_index");
				} else {
					$(this).html("商品");
					$("#search_form").attr("action",APP_PATH+"/product/search_index");
				} 
			}
		)
		
});
	
