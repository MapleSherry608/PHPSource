<script type="text/javascript">
$(document).ready(function(e) {
    $("#express_id").change(function(){
		var id=$(this).val();
		if(id!=0){
			$("#express_sn").show();
		}else {
			$("#express_sn").hide();
		}
	});
	
	$("#form1").submit(
		function(){
			
			if($("#express_id").val()!=0){
				if($("#express_sn_txt").val()==""){
					alert("请填写快递单号!");
					return false;
				}
			}
		}
	)
});
</script>
<form enctype="multipart/form-data" name="form1" id="form1" action="<{$app}>/orders/order_deliver" method="post">
   	  	<table width="100%" border="0" cellspacing="0" cellpadding="12">
          <tr>
            <td width="23%" align="right"  style="border-bottom:1px solid #ccc">订单号：</td>
            <td width="77%" style="border-bottom:1px solid #ccc"><{$data.sn}></td>
          </tr>
          <tr>
            <td align="right"  style="border-bottom:1px solid #ccc">收货姓名：</td>
            <td  style="border-bottom:1px solid #ccc"><{$data.name}></td>
          </tr>
          <tr>
            <td align="right"  style="border-bottom:1px solid #ccc">联系电话：</td>
            <td style="border-bottom:1px solid #ccc"><{$data.tel}></td>
          </tr>
          <tr>
            <td align="right" style="border-bottom:1px solid #ccc">收货地址：</td>
            <td style="border-bottom:1px solid #ccc"><{$data.address}></td>
          </tr>
          <tr>
            <td align="right" style="border-bottom:1px solid #ccc">实付金额：</td>
            <td style="border-bottom:1px solid #ccc">&yen;<{$data.pay_price}></td>
          </tr>
          <tr>
            <td align="right" style="border-bottom:1px solid #ccc">支付方式：</td>
            <td style="border-bottom:1px solid #ccc"><{$data.payment_cn}></td>
          </tr>
          <tr>
            <td align="right" style="border-bottom:1px solid #ccc">快递：</td>
            <td style="border-bottom:1px solid #ccc">
            	<select name="express_id" id="express_id">
                	<option value="0">无需快递</option>
                    <{foreach from=$express_list item=list}>
                    <option value="<{$list.id}>"><{$list.name}></option>
                    <{/foreach}>
                </select>
            </td>
          </tr>
          <tr style="display:none;" id="express_sn">
            <td align="right" style="border-bottom:1px solid #ccc">快递单号：</td>
            <td style="border-bottom:1px solid #ccc">
            	<input type="text" name="express_sn" id="express_sn_txt" value="" />
            </td>
          </tr>
          
          <tr>
            <td align="right"></td>
            <td><input type="hidden" name="id" value="<{$data.id}>"/><input type="submit" class="submit_button"  value="确认发货"/></td>
          </tr>
          
        </table>
		</form>