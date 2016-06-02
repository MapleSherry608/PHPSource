<script>
$(document).ready(function(){
	$("#appraise_form").submit(
		function(){
			
			if($("#content").val()==''){
				alert("请填写内容");
				return false;
			}
		}
	);
	
})
</script>
<div id="user_box">
	<form enctype="multipart/form-data" name="form1" id="appraise_form" action="<{$app}>/appraise/mod" method="post">
    <div id="reg_box">
        <div class="content">
            
            <table width="100%" border="0" cellspacing="0" cellpadding="12">
            <tr>
            <td width="23%" align="right">评价：</td>
            <td width="77%"><input type="radio" name="level" value="1" <{if $data.level==1}>checked="checked"<{/if}>/>&nbsp;好评&nbsp;&nbsp;<input type="radio" name="level" value="2" <{if $data.level==2}>checked="checked"<{/if}>/>&nbsp;中评&nbsp;&nbsp;<input type="radio" name="level" value="3" <{if $data.level==3}>checked="checked"<{/if}>/>&nbsp;差评</td>
          </tr>
          <tr>
            <td width="23%" align="right">内容：</td>
            <td width="77%"><textarea name="content" id="content"  style="width:345px;height:100px;"><{$data.content}></textarea></td>
          </tr>
          
          <tr>
            <td align="right"></td>
            <td><input type="hidden" name="id" value="<{$id}>" /><input type="submit" class="sub_button" value="提交"/></td>
          </tr>
        </table>
        </div>
    </div>
    </form>
</div>
