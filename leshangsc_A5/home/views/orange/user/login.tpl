<script>
$(document).ready(function(){
	$("#user_name").focus(function(){
		$(this).val("");
	});
	
	$("#log_form").submit(
		function(){
			if($("#user_name").val()=='邮箱 或 用户名'){
				$("#user_name").val("");
			}
			
			if($("#user_name").val()==''){
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
	
	$("#forget").click(
		function(){
			open_url=APP_PATH+'/user/get_pass_index';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 300,clickClose: true});	

		}
	)
	

})
</script>
<div id="user_box">
	<!--用户登陆-->
	<div id="login_box">
	<div class="title">用户登陆</div>
    <div class="content">
    	<form enctype="multipart/form-data" name="form1" id="log_form" action="<{$app}>/user/login" method="post">
   	  	<table width="100%" border="0" cellspacing="0" cellpadding="12">
          <tr>
            <td width="23%" align="right">用户名：</td>
            <td width="77%"><input type="text" class="input_box" name="user_name" id="user_name" value="邮箱 或 用户名"/></td>
          </tr>
          <tr>
            <td align="right">密码：</td>
            <td><input type="password" class="input_box" name="password"  /></td>
          </tr>
          <tr>
            <td align="right"></td>
            <td><input type="submit" class="sub_button"  value="登 陆"/></td>
          </tr>
          <tr>
            <td align="center" colspan="2" height="40" class="user_b_t"><a href="#" id="forget">忘记密码?</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" id="user_reg">注册新帐号</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" id="message">意见反馈</a></td>
          </tr>
        </table>
		</form>
    </div>
    </div>
    
    
</div>
