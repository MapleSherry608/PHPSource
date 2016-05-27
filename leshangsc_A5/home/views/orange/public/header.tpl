
<div id="top">
	<div class="web_body">
    	<div class="logo" style="background:url(<{$public}>/uploads/<{$con_datas.logo}>) left center no-repeat;"><{$con_datas.site_name}></div>
        <div class="search_box">
        	<form id="search_form" action="<{$app}>/product/search_index" method="post">
        	<div class="s_bg"><div class="left"><input type="text" name="keywords" class="search" id="search_box" value="" /></div><div class="left type"><div id="txt" style=" position:relative">商品</div></div></div>
            <div class="s_but">搜索</div>
            </form>
        </div>
        <div class="member_box">
        	<{if !$user.isLogin}>
            	<div class="m_reg" title="注册"></div>
                <div class="m_login" title="会员登陆"></div>
            <{else}>
            	<div class="m_cart" title="购物车"><{if $cart_num>=1}><div class="cart_num" id="cart_num"><{$cart_num}></div><{/if}></div>
            	<div class="m_center" title="会员中心"></div>
                <div class="m_mail" title="我的私信"></div>
                <div class="m_publish">
                	<div class="p_nav" id="p_nav">
                    	<ul>
                        	<li><a id="publish_news">发布文章</a></li>
                            <li><a id="publish_product">发布商品</a></li>
                        </ul>
                    </div>
                </div>
                <div class="m_logout" title="退出登陆"></div>
            <{/if}>
            
        </div>
        <div class="clear"></div>
    </div>
    <div class="yellow_line"></div>
    <div class="orange_line">
    	<div class="web_body">
        	<div class="nav">
            	<ul>
                	<li <{if $smarty.get.m=="index"}>class="selected"<{/if}>><a href="<{$app}>">首页</a></li>
                	<{section name=sn loop=$main_nav}>
                        <li <{if $main_nav[sn].selected}>class="selected"<{/if}>>
                        <a href="<{$main_nav[sn].url}>"><{$main_nav[sn].name}></a>
                    </li>
                	<{/section}>
                </ul>
            </div>
            <div class="hot_box">
            	热门搜索：
                <{section name=sn loop=$hot_datas max=5}>
                	<a href="<{$hot_datas[sn].url}>"><{$hot_datas[sn].keyword}></a>
                <{/section}>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>