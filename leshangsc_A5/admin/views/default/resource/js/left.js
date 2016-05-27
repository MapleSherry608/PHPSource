// JavaScript Document
$(document).ready(function(){
$(".left_nav ul li a").click(function(){
	$(".left_nav ul li").removeClass("selected");
	$(this).parent().addClass("selected");
	$(this).blur();
})
});