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
<link href="<{$res}>/css/news.css" rel="stylesheet" type="text/css" />
<link href="<{$res}>/css/user.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.pngfix.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.lazyload.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/koala.min.1.5.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script>
	var APP_PATH="<{$app}>";
	var ISLOGIN="<{$user.id}>"
	$(function(){
		var isLogin="<{$user.isLogin}>";
		$("#add").click(function(){
			if($("#content").val()==""){
				alert("请填写评论内容!");
				return false;
			} else if(isLogin==""){
				open_url=APP_PATH+'/user/log_index';
				$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 320,clickClose: true});
				return false;
			}
		});
		
		$(".reply").click(
			function(){
				var u_id=$(this).attr("id");
				var u_se="<{$user.user_name}>";
				if(u_id!=u_se){
					var reply="#"+$(this).parent().find(".floor").attr("id")+"楼 @"+u_id+" ";
					$("html,body").animate({scrollTop:$("#submit").offset().top},500);
					$("#content").val(reply);
				} else {
					alert("不能回复自己");
				}
			}
		)
		
		//分享按钮
		$("#weixin").click(function(){
			open_url=APP_PATH+"/news/qrcode_index/urlToEncode/<{$urlToEncode}>";
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:500, height: 400,clickClose: true});
			return false;
		});
		
		$("#twitter").click(
			function(){
				var url="http://v.t.sina.com.cn/share/share.php?url="+encodeURIComponent('<{$current_url}>')+"&title="+encodeURIComponent('<{$data.title}>')+"&appkey=433903842&pic";
				window.open(url);
			}
		)
		$("#qq").click(
			function(){
				var url="http://connect.qq.com/widget/shareqq/index.html?url="+encodeURIComponent('<{$current_url}>')+"&title="+encodeURIComponent('<{$data.title}>')+"&source=shareqq&desc=刚看到这篇文章不错，推荐给你看看～";
				window.open(url);
			}
		)
		$(".top").toggle(
			function(){
				var status=1;
				var id=$(this).attr("id");
				var url="<{$app}>/comment/top";
				$(this).removeClass();
			    $(this).addClass("top_1");
				$.ajax({   
					type:"post",     
					url:url,
					data:{status:status,id:id},
					success:function(msg){
						
						
					},   
					error:function(){   
						alert("顶失败");   
					}   
				});   
			},
			function(){
				var status=0;
				var id=$(this).attr("id");
				var url="<{$app}>/comment/top";
				$(this).removeClass();
				$(this).addClass("top");
				$.ajax({   
					type:"post",     
					url:url,
					data:{status:status,id:id},
					success:function(msg){
						
					},   
					error:function(){   
						alert("顶失败");   
					}   
				});   
			}
		)
		
		//文章推荐
		$("#recommand").toggle(
			function(){
				var status=1;
				var num=$("#re_num").html();
				var num=num*1+1*1;
				$("#re_num").html(num);
				var article_id="<{$data.id}>";
				var url="<{$app}>/news/recommand";
				$(this).removeClass();
			    $(this).addClass("recommand_1");
				$.ajax({   
					type:"post",     
					url:url,
					data:{status:status,article_id:article_id},
					success:function(msg){
					},   
					error:function(){
						alert("推荐失败");   
					}   
				});   
			},
			function(){
				var status=0;
				var num=$("#re_num").html();
				var num=num*1-1*1;
				$("#re_num").html(num);
				var article_id="<{$data.id}>";
				var url="<{$app}>/news/recommand";
				$(this).removeClass();
				$(this).addClass("recommand");
				$.ajax({   
					type:"post",     
					url:url,
					data:{status:status,article_id:article_id},
					success:function(msg){
					},   
					error:function(){   
						alert("推荐失败");   
					}   
				});   
			}
		)
		//文章收藏
		function add_fav(){
			var status=1;
			var article_id="<{$data.id}>";
			var user_id="<{$user.id}>";
			var url="<{$app}>/fav/favourite";
			$(this).removeClass();
			$(this).addClass("fav_1");
			$.ajax({   
				type:"post",     
				url:url,
				data:{status:status,article_id:article_id,user_id:user_id},
				success:function(msg){
				},   
				error:function(){
					alert("收藏失败");   
				}   
			});  
		}
		
		function del_fav(){
			var status=0;
			var article_id="<{$data.id}>";
			var user_id="<{$user.id}>";
			var url="<{$app}>/fav/favourite";
			$(this).removeClass();
			$(this).addClass("fav");
			$.ajax({   
				type:"post",     
				url:url,
				data:{status:status,article_id:article_id,user_id:user_id},
				success:function(msg){
				},   
				error:function(){
					alert("收藏失败");   
				}   
			});  
		}
		var status=$("#fav").attr("status");
		if(status=="1"){
			$("#fav").toggle(del_fav,add_fav);
		} else {
			$("#fav").toggle(add_fav,del_fav);
		}
		
	});
</script>
</head>

<body>
<{include file="public/header.tpl"}>
<div id="body" class="web">
	<div class="position"><{$position}></div>
    <div class="content">
    	<div class="left_c">
        	<{include file="public/survey.tpl"}>
        </div>
        <div class="right_c">
        	
             <div class="c_title">
             	<h1><{$data.title}></h1>
             </div>
             <div class="c_content">
             	<{$data.content}>
             </div>
              <div class="share_box">
             	<div class="left">
                	<span class="recommand" id="recommand" title="推荐这篇文章"></span>
                    <span class="re_num" id="re_num"><{$data.recommand}></span>
                    <{if $user.isLogin}>
                    	<{if $is_fav}>
                        	<span class="fav_1" id="fav" status="1" title="取消收藏"></span>
                        <{else}>
                        	<span class="fav" id="fav" status="0" title="收藏这篇文章"></span>
                        <{/if}>
                    <{/if}>
                </div>
                <div class="right">
                	<span class="twitter" id="twitter">分享到微博</span>
                    <span class="weixin" id="weixin">分享到微信</span>
                    <span class="qq" id="qq">分享到QQ</span>
                </div>
                <div class="clear"></div>
             </div>
             <form enctype="multipart/form-data" action="<{$app}>/comment/add_comment" method="post">
             <div class="comments">
             
             <{section name=sn loop=$data.comments}>
             	<div class="lists">
                	<div class="photo"><{if $data.comments[sn].user_info.photo}><img src="<{$public}>/uploads/<{$data.comments[sn].user_info.photo}>" /><{else}><img src="<{$res}>/images/user_default.gif"><{/if}></div>
                    <div class="info">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="uname"><span class="uname_box"><a class="uname uname_area" target="_blank"><{$data.comments[sn].user_info.user_name}></a><div class="user_private_list"><ul><li><a class="letter" data-name="<{$data.comments[sn].user_info.user_name}>" id="<{$data.comments[sn].user_id}>">发私信</a></li><li><a class="friend" id="<{$data.comments[sn].user_id}>">加好友</a></li></ul></div></span> • <abbr class="timeago" title="<{$data.comments[sn].create_time|date_format:'%Y-%m-%d %H:%M:%S'}>"><{$data.comments[sn].create_time}></abbr> </td>
                          </tr>
                          <tr>
                            <td><{$data.comments[sn].content}></td>
                          </tr>
                          <tr>
                            <td class="icon"><div class="top" id="<{$data.comments[sn].id}>"></div><div class="top_t">顶</div><div class="reply" id="<{$data.comments[sn].user_info.user_name}>"></div><div class="reply_t">回复</div><div class="floor" id="<{$smarty.section.sn.rownum}>"><{$smarty.section.sn.rownum}>楼</div></td>
                          </tr>
                        </table>

                    </div>
                    <div class="clear"></div>
                </div>
                <{/section}>
                
             	<div class="submit" id="submit">
                	<div class="title">文章评论(<{$comment_num}>)</div>
                    <div class="comment_box"><textarea name="content" id="content"></textarea></div>
                    <div class="comment_button"><input type="submit" name="add" id="add"  value="提交评论" /></div>
                </div>
             </div>
              <input type="hidden" name="article_id" value="<{$data.id}>" />
             <input type="hidden" name="pid" value="<{$data.m_cate}>" />
             <input type="hidden" name="cate" value="<{$data.cate}>" />
             </form>
        </div>
    </div>
    <div class="clear"></div>
</div>

<{include file="public/footer.tpl"}>
</body>
</html>
