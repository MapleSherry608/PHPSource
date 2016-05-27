<script>
$(document).ready(function(){
	$("#email").focus(function(){
		$(this).val("");
	});
	$("form").submit(
		function(){
			if($("#email").val()=='请填写您的注册邮箱'){
				$("#email").val("");
			}
			
			if($("#email").val()==''){
				alert("请填写用户名或邮箱");
				return false;
			}
		}
	);
	
	$("#user_reg").click(
		function(){
			open_url=APP_PATH+'/user/reg_index';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 600,clickClose: true});	

		}
	)
	
	$("#message").click(
		function(){
			open_url=APP_PATH+'/message';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 600,clickClose: true});	

		}
	)
	
	

})
</script>
<div id="user_box">
	<!--用户登陆-->
	<div id="login_box">
	<div class="title">找回密码</div>
    <div class="content">
    	<form enctype="multipart/form-data" name="form1" action="<{$app}>/user/get_pass" method="post">
   	  	<table width="100%" border="0" cellspacing="0" cellpadding="12">
          <tr>
            <td width="23%" align="right">邮箱：</td>
            <td width="77%"><input type="text" class="input_box" name="email" id="email" value="请填写您的注册邮箱"/></td>
          </tr>
          <tr>
            <td align="right"></td>
            <td><input type="submit" class="sub_button"  value="提 交"/></td>
          </tr>
          <tr>
            <td align="center" colspan="2" height="40" class="user_b_t"><a href="#" id="user_reg">注册新帐号</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" id="message">意见反馈</a></td>
          </tr>
        </table>
		</form>
    </div>
    </div>
    
    
</div>
