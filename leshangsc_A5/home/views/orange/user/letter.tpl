<script>
$(document).ready(function(){
	
	$("#send_form").submit(
		function(){
			
			if($("#message").val()==''){
				alert("请填写私信内容");
				return false;
			}
		}
	);
	
	$(".expression").click(
		function(){
			var html=$(this).attr("id");
			var t=$("#message").val()+html;
			$("#message").val(t);
		}
	)
	

})
</script>
<div id="user_box">
	<!--用户登陆-->
	<div id="login_box">
    <div class="content">
    	<form enctype="multipart/form-data" name="form1" id="send_form" action="<{$app}>/user/send_letter" method="post">
   	  	<table width="100%" border="0" cellspacing="0" cellpadding="12">
          <tr>
            <td width="23%" align="right">私信内容：</td>
            <td width="77%"><textarea name="message" id="message"  style="width:345px;height:100px;"></textarea></td>
          </tr>
          <tr>
            <td width="23%" align="right">表情：</td>
            <td width="77%"><div style="width:360px;"><{$expression}></div></td>
          </tr>
          <tr>
            <td align="right"></td>
            <td><input type="hidden" name="user_id" value="<{$id}>" /><input type="submit" class="sub_button"  value="发 送"/></td>
          </tr>
        </table>
		</form>
    </div>
    </div>
    
    
</div>
