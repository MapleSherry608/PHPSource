/*下拉框*/
function mySelectBox(){
	$(".mySelectBox").append('<p><i class="am-icon-caret-down"></i></p>');
	$(".mySelectBox input").attr("readonly","readonly");
	$(".mySelectBox input").click(function(){
		$(".dragListBox").show();
	});
	$(".dragListBox").click(function(){
		$(this).hide();
	});
	$(".dragListBox li").click(function(){
		$(".mySelectBox input").val($(this).text());
		$(".inputKm i").html($(this).attr('data-unit'));
		$("#modeId").val($(this).attr('data-modeId'));
		$(".dragListBox").hide();
	});
};
/**
 * 图片裁剪
 * @param {objPic} jquery图片对象
 * @param {num} 图片宽度或高度
 */
function clipPic(objPic,num){
	if(objPic.width()>=objPic.height()){
		objPic.height(num);
	}else{
		objPic.width(num);
	}
};
