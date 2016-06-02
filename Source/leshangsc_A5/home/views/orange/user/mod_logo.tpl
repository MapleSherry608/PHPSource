<title>编辑头像</title>
<link href="<{$public}>/css/jquery.Jcrop.css" type="text/css" rel="Stylesheet" media="screen">
<script type="text/javascript"  src="<{$public}>/js/jquery.js"></script>
<script type="text/javascript" src="<{$public}>/js/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="<{$public}>/js/jQuery.UtrialAvatarCutter.js"></script>
<style>
	#picture_original{
		float:left;
		margin-right:20px;
	}
	#picture_120{
		float:left;
	}
	#button{
		clear:both;
		margin-top:20px;
	}
</style>
	<div>
        <div id="picture_original">
            <img src="<{$public}>/uploads/<{$photo}>"/>
        </div>
        
        <div id="picture_120"></div>
    
    </div>
    <div id="button">
    <input type="hidden" id="photo" value="<{$photo}>">
	<input type="button" value="确定" id="save_btn" style="width:80px;"/></div>
    
    <script type="text/javascript">
var cutter = new jQuery.UtrialAvatarCutter(
	{
		//主图片所在容器ID
		content : "picture_original",
		
		//缩略图配置,ID:所在容器ID;width,height:缩略图大小
		purviews : [{id:"picture_120",width:120,height:120},{id:"picture_50",width:50,height:50},{id:"picture_30",width:30,height:30}],
		
		//选择器默认大小
		selector : {width:120,height:120},
		cropattrs : {boxWidth: 500, boxHeight: 500}
	}
);
$(window).load(function(){
	cutter.init();

	//确定按钮动作
	$("#save_btn").click(
		function(){
			var datas = cutter.submit();
			var photo=$("#photo").val();
			//alert("x="+datas.x+"\ny="+datas.y+"\nw="+datas.w+"\nh="+datas.h+"\ns="+datas.s);
			$.getJSON('<{$app}>/user/mod_logo/id/<{$id}>', {x: datas.x, y: datas.y, w: datas.w, h: datas.h, src: datas.s}, function(data) {
				alert(data.msg);
				window.close();
			});
			
			return false;
		}
	);
	
});
</script>
