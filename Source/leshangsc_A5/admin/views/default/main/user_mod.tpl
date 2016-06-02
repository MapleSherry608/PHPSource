<form enctype="multipart/form-data" name="form1" id="log_form" action="<{$app}>/user/mod" method="post">
   	  	<table width="100%" border="0" cellspacing="0" cellpadding="9">
          <tr>
            <td width="23%" align="right"  style="border-bottom:1px solid #ccc">用户名：</td>
            <td width="77%" style="border-bottom:1px solid #ccc"><input name="user_name" type="text" class="input_box" value="<{$data.user_name}>"/></td>
          </tr>
          <tr>
            <td align="right"  style="border-bottom:1px solid #ccc">密码：</td>
            <td  style="border-bottom:1px solid #ccc"><input name="password" type="password" class="input_box" value=""/></td>
          </tr>
          <tr>
            <td align="right"  style="border-bottom:1px solid #ccc">个人签名：</td>
            <td style="border-bottom:1px solid #ccc"><input name="signature" type="text" class="input_box" value="<{$data.signature}>"/></td>
          </tr>
          <tr>
            <td align="right" style="border-bottom:1px solid #ccc">邮箱：</td>
            <td style="border-bottom:1px solid #ccc"><input name="email" type="text" class="input_box" value="<{$data.email}>"/></td>
          </tr>
          <tr>
            <td align="right" style="border-bottom:1px solid #ccc">电话：</td>
            <td style="border-bottom:1px solid #ccc"><input name="phone" type="text" class="input_box" value="<{$data.phone}>"/></td>
          </tr>
          <tr>
            <td align="right" style="border-bottom:1px solid #ccc">地址：</td>
            <td style="border-bottom:1px solid #ccc"><input name="address" type="text" class="input_box" value="<{$data.address}>"/></td>
          </tr>
		   <tr>
            <td align="right" style="border-bottom:1px solid #ccc">积分：</td>
            <td style="border-bottom:1px solid #ccc"><input name="score" type="text" class="input_box" value="<{$data.score}>"/></td>
          </tr>
		  <tr>
            <td align="right" style="border-bottom:1px solid #ccc">余额：</td>
            <td style="border-bottom:1px solid #ccc"><input name="account" type="text" class="input_box" value="<{$data.account}>"/></td>
          </tr>
		  <tr>
            <td align="right" style="border-bottom:1px solid #ccc">注册时间：</td>
            <td style="border-bottom:1px solid #ccc"><{$data.reg_time|date_format:"%Y-%m-%d"}></td>
          </tr>
		   <tr>
            <td align="right" style="border-bottom:1px solid #ccc">登陆时间：</td>
            <td style="border-bottom:1px solid #ccc"><{if $data.log_time}><{$data.log_time|date_format:"%Y-%m-%d"}><{else}>无<{/if}></td>
          </tr>
          <tr>
            <td align="right"></td>
            <td><input type="hidden" name="id" value="<{$data.id}>"/><input type="submit" class="submit_button"  value="编辑"/></td>
          </tr>
          
        </table>
		</form>