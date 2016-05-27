// JavaScript Document
function autoHeight() 
 { var h = $(window).height(); var h_old = 300; if (h > h_old) { $(".info").css('height', h-120); } else { return false; } }
$(function() { 
  	autoHeight();
	$(window).resize(autoHeight); 
})
$(document).ready(function(e) {
    $(".all_select").click(
		function(){
			$("[name='id[]']").attr("checked",'true');
		}
	);
});