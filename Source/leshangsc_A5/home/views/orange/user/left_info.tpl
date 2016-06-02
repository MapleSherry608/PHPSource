<script>
$(document).ready(function(e) {
    $(".ubox .list ul li:last").css("border-bottom","none");
	$(".u_box:nth-child(3n)").css("margin-bottom","none");
});
</script>
<div class="u_box">
	<div class="tit">
    	<span class="p_icon"></span>
        <span class="txt">商品类</span>
    </div>
    <div class="list">
    	<ul>
        	<li <{if $smarty.get.a=='pub_product'}>class="select"<{/if}>><span class="p_icon_0 icon"></span><span class="txt"><a href="<{$app}>/user/pub_product">我发布的商品</a></span></li>
        	<!--<li><span class="p_icon_1 icon"></span><span class="txt"><a href="">我的购物车</a></span></li>-->
            <li <{if $smarty.get.a=='my_orders'}>class="select"<{/if}>><span class="p_icon_2 icon"></span><span class="txt"><a href="<{$app}>/user/my_orders">我的订单</a></span></li>
            <li <{if $smarty.get.a=='my_appraise'}>class="select"<{/if}>><span class="p_icon_3 icon"></span><span class="txt"><a href="<{$app}>/user/my_appraise">我的评价</a></span></li>
            <li <{if $smarty.get.a=='my_consult'}>class="select"<{/if}>><span class="p_icon_4 icon"></span><span class="txt"><a href="<{$app}>/user/my_consult">我的咨询</a></span></li>
            <li <{if $smarty.get.a=='my_fav_pro'}>class="select"<{/if}>><span class="p_icon_5 icon"></span><span class="txt"><a href="<{$app}>/user/my_fav_pro">我的收藏</a></span></li>
        </ul>
    </div>
</div>
<div class="u_box">
	<div class="tit">
    	<span class="n_icon"></span>
        <span class="txt">资讯类</span>
    </div>
    <div class="list">
    	<ul>
        	<li <{if $smarty.get.a=='pub_news'}>class="select"<{/if}>><span class="n_icon_1 icon"></span><span class="txt"><a href="<{$app}>/user/pub_news">我发布的资讯</a></span></li>
            <!--<li><span class="n_icon_2 icon"></span><span class="txt"><a href="">我推荐的文章</a></span></li>-->
            <li <{if $smarty.get.a=='my_comment'}>class="select"<{/if}>><span class="n_icon_3 icon"></span><span class="txt"><a href="<{$app}>/user/my_comment">我的评论</a></span></li>
            <li  <{if $smarty.get.a=='my_fav_news'}>class="select"<{/if}>><span class="n_icon_4 icon"></span><span class="txt"><a href="<{$app}>/user/my_fav_news">我的收藏</a></span></li>
        </ul>
    </div>
</div>
<div class="u_box">
	<div class="tit">
    	<span class="u_icon"></span>
        <span class="txt">会员类</span>
    </div>
    <div class="list">
    	<ul>
        	<li  <{if $smarty.get.a=='mod_index'}>class="select"<{/if}>><span class="u_icon_1 icon"></span><span class="txt"><a href="<{$app}>/user/mod_index/window/0">我的资料</a></span></li>
            <li  <{if $smarty.get.m=='letter'}>class="select"<{/if}>><span class="u_icon_2 icon"></span><span class="txt"><a href="<{$app}>/letter">我的私信</a></span></li>
             <li <{if $smarty.get.a=='my_message'}>class="select"<{/if}>><span class="u_icon_3 icon"></span><span class="txt"><a href="<{$app}>/friend/my_message">我的好友消息</a></span></li>
            <li <{if $smarty.get.a=='my_friend'}>class="select"<{/if}>><span class="u_icon_3 icon"></span><span class="txt"><a href="<{$app}>/friend/my_friend">我的好友</a></span></li>
            <li><span class="u_icon_4 icon"></span><span class="txt"><a href="<{$app}>/user/logout">退出</a></span></li>
        </ul>
    </div>
</div>

<!--
<div class="primary">
	<div class="u_photo"><{if $user.photo}><img src="<{$public}>/uploads/<{$user.photo}>" /><{else}><img src="<{$res}>/images/user_default.gif"><{/if}></div>
    <div class="u_name"><h1><{$user.user_name}></h1><p><{$user.signature}></p></div>
    <div class="clear"></div>
</div>
<div class="secondary">
	<ul>
    	<li>电话：<{$user.phone}></li>
        <li>地址：<{$user.address}></li>
        <li>邮箱：<{$user.email}></li>
    </ul>
</div>
-->