<script>
$(document).ready(function(){
	$("#user_name_r").focus(function(){
		$(this).val("");
	});
	
	$("#reg_form").submit(
		function(){
			if($("#user_name_r").val()=='仅英文或数字'){
				$("#user_name_r").val("");
			}
		}
	);
	
	
	$("#user_log").click(
		function(){
			open_url=APP_PATH+'/user/log_index';
			$.weeboxs.open(open_url, {boxid: 1,contentType: 'ajax',title: '', showButton:false,animate: true,width:590, height: 320,clickClose: true});	
		}
	);
	

})
</script>
<div id="user_box">
	<form enctype="multipart/form-data" name="form1" id="reg_form" action="<{$app}>/user/reg" method="post">
    <!--用户注册-->
    <div id="reg_box">
    	<div class="reg_top">
        	<div class="photo"></div>
            <div class="photo_txt">上传照片<span class="upload"><input type="file" name="photo" id="photo" value="" /></span></div>
        </div>
    	<div class="title_r">用户注册</div>
        <div class="content">
            
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="23%" align="right">用户名：</td>
                <td width="77%"><input type="text" class="input_box" id="user_name_r" name="user_name" value="仅英文或数字"/></td>
              </tr>
              <tr>
                <td align="right">密码：</td>
                <td><input type="password" class="input_box" name="password"  /></td>
              </tr>
              <tr>
                <td align="right">确认密码：</td>
                <td><input type="password" class="input_box" name="confirm_pass"  /></td>
              </tr>
              <tr>
                <td align="right">签名：</td>
                <td ><input type="text" class="input_box" name="signature" value=""/></td>
              </tr>
              <tr>
                <td align="right">邮箱：</td>
                <td><input type="text" class="input_box" name="email" value=""/></td>
              </tr>
              <tr>
                <td align="right">电话：</td>
                <td><input type="text" class="input_box" name="phone" value=""/></td>
              </tr>
              <tr>
                <td align="right">地址：</td>
                <td><input type="text" class="input_box" name="address" value=""/></td>
              </tr>
              <tr>
                <td align="right"></td>
                <td><input type="submit" class="sub_button"  value="注 册"/></td>
              </tr>
              <tr>
              <td align="right"></td>
            <td height="40" class="user_b_t">已注册?&nbsp;&nbsp;<a href="#" id="user_log">登陆</a></td>
          </tr>
            </table>
        </div>
    </div>
    <input type="hidden" name="ran_code" value=""/>
    </form>
</div>
