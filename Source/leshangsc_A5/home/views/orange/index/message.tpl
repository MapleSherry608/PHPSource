<script>
	$(document).ready(function(){
		$("#mess_form").submit(
		function(){
			
			if($("#title").val()==''){
				alert("请填写标题");
				return false;
			}
			if($("#content").val()==''){
				alert("请填写内容");
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
	$("#forget").click(
		function(){
			open_url=APP_PATH+'/user/get_pass_index';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 300,clickClose: true});	

		}
	)
	});
</script>
<div id="user_box">
	<!--用户登陆-->
	<div id="login_box">
	<div class="title">建议反馈</div>
    <div class="content">
    	<form enctype="multipart/form-data" name="form1" id="mess_form" action="<{$app}>/message/add" method="post">
   	  	<table width="100%" border="0" cellspacing="0" cellpadding="12">
          <tr>
            <td width="23%" align="right">标题：</td>
            <td width="77%"><input name="title" type="text" class="input_box" id="title" size="55" /></td>
          </tr>
          <tr>
            <td align="right">内容：</td>
            <td><textarea name="content" id="content" cols="30" rows="20" class="input_box" style="height:300px;"></textarea></td>
          </tr>
          <tr>
            <td align="right"></td>
            <td><input type="submit" class="sub_button"  value="提 交"/></td>
          </tr>
          <tr>
            <td align="center" colspan="2" height="40" class="user_b_t"><a href="#" id="forget">忘记密码?</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" id="user_reg">注册新帐号</a></td>
          </tr>
        </table>
		</form>
    </div>
    </div>
    
    
</div>