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
<script type="text/javascript" src="<{$public}>/js/jquery.bgiframe.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.lazyload.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.weebox.js"></script>
<script type="text/javascript" src="<{$public}>/js/koala.min.1.5.js"></script>
<script type="text/javascript" src="<{$res}>/js/common.js"></script>
<script>
var APP_PATH="<{$app}>";var ISLOGIN="<{$user.id}>";
$(document).ready(function(e) {
	$('#cate').change(function(){ 
		var p1=$(this).val();//这就是selected的值
		window.location.href="<{$app}>/user/pub_news/id/"+p1;
	})
    $("#sub_nav ul li").click(function(e) {
        var id=$(this).attr("id");
		$("#sub_nav ul li").removeClass("select");
		$(this).addClass("select");
		$(".publish").hide();
		$("#publish_"+id).show();
    });
	
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
            	<span class="r_icon_7"></span>
        		<span class="txt">我发布的新闻</span>
                <span class="select_box">
                	<select name="cate" id="cate">
                      <option value="" selected="selected">-选择分类-</option>
                        <option value="0">所有新闻</option>
                            <{section name=sn loop=$nav_datas}>
                                <{if $nav_datas[sn].has_sub}>
                				<optgroup label="<{$nav_datas[sn].name}>">
                            <{else}>
                               	<option value="<{$nav_datas[sn].nav_id}>"><{$nav_datas[sn].name}></option>
                            <{/if}>
                        <{/section}>
                        </select>
                </span>
            </div>
            
            <div class="current">
            	当前分类：<span id="current_class"><{$current_name}></span>
            </div>
            
            <div class="content">
            	<{if $datas}>
            	<div class="orders_box">
                	<ul>
                    	<{section name=sn loop=$datas}>
                    	<li>
                        	
                            
                            <span class="red_l">●&nbsp;新闻名称：<font title="<{$datas[sn].title}>"><a href="<{$datas[sn].url}>" target="_blank"><{$datas[sn].title|truncate:"35"}></a></font></span>
                            <span style="color:green">添加时间:<{$datas[sn].create_time}></span>
                            <span style="color:green"><{if $datas[sn].verify}><font style="color:green">已审核</font><{else}><font style="color:red">未审核</font><{/if}></span>
                            <span class="right red_l"  style="color:#699;cursor:pointer;float:right" ><a href="<{$app}>/news/publish_mod/id/<{$datas[sn].id}>" target="_blank">编辑</a>&nbsp;&nbsp;<a href="<{$app}>/news/del/id/<{$datas[sn].id}>" onClick="return confirm('你确定要删除选中的商品吗?')" >删除</a></span>
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
