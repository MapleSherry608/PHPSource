
<div id="user_box">
	<form enctype="multipart/form-data" name="form1" id="mod_form" action="<{$app}>/user/mod" method="post">
    <!--用户注册-->
    <div id="reg_box">
    	<div class="reg_top">
        	<div class="mod_photo" id="<{$datas.id}>"><img src="<{$public}>/uploads/<{$datas.photo}>" title="修改照片"/></div>
            <div class="photo_txt">上传照片<span class="upload"><input type="file" name="photo" id="photo" value="" /></span></div>
        </div>
    	<div class="title_r">资料修改</div>
        <div class="content">
            
            <table width="100%" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td width="23%" align="right">用户名：</td>
                <td width="77%"><input type="text" class="input_box" id="user_name_r" name="user_name" value="<{$datas.user_name}>"/></td>
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
                <td ><input type="text" class="input_box" name="signature" value="<{$datas.signature}>"/></td>
              </tr>
              <tr>
                <td align="right">邮箱：</td>
                <td><input type="text" class="input_box" name="email" value="<{$datas.email}>"/></td>
              </tr>
              <tr>
                <td align="right">电话：</td>
                <td><input type="text" class="input_box" name="phone" value="<{$datas.phone}>"/></td>
              </tr>
              <tr>
                <td align="right">地址：</td>
                <td><input type="text" class="input_box" name="address" value="<{$datas.address}>"/></td>
              </tr>
              <tr>
                <td align="right"></td>
                <td><input type="submit" class="sub_button"  value="修 改"/></td>
              </tr>
            </table>
        </div>
    </div>
    <input type="hidden" name="id" value="<{$datas.id}>" />
    </form>
</div>