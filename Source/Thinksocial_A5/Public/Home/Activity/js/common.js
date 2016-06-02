/*下拉框*/
function mySelectBox(){
	$(".mySelectBox input").click(function(){
		$(".dragListBox").show();
	});
	$(".dragListBox").click(function(){
		$(this).hide();
	});
	$(".dragListBox li").click(function(){
		$(".mySelectBox input").val($(this).text());
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
}