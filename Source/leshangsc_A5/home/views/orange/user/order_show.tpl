<script type="text/javascript">
$(document).ready(function(e) {
    $("#pay").click(
		function(){
			var payLinks=$(this).attr("url");
			window.open(payLinks);
		}
	)
});
</script>
<style>
.bot_line{
	border-bottom:1px dotted #ccc;
}
</style>
<div id="user_box">
    <div id="reg_box">
    	
        <div class="content">
            
            <table width="93%" border="0" cellspacing="0" cellpadding="5" align="center">
              <tr>
                <td class="bot_line" width="23%" align="right">订单号：</td>
                <td class="bot_line" width="77%"><{$data.sn}></td>
              </tr>
               <tr>
                <td class="bot_line" width="23%" align="right">订单状态：</td>
                <td class="bot_line" width="77%"><{$data.status_cn}></td>
              </tr>
              <{if $data.delivery_status==1}>
               <tr>
                <td class="bot_line" width="23%" align="right">快递单号：</td>
                <td class="bot_line" width="77%" style="color:#F60"><{$data.express_sn}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">快递公司：</td>
                <td class="bot_line" width="77%" style="color:#F60"><{$data.express_cn}></td>
              </tr>
              <{/if}>
                <tr>
                <td class="bot_line" width="23%" align="right">名字：</td>
                <td class="bot_line" width="77%"><{$data.name}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">电话：</td>
                <td class="bot_line" width="77%"><{$data.tel}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">地址：</td>
                <td class="bot_line" width="77%"><{$data.address}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">下单时间：</td>
                <td class="bot_line" width="77%"><{if $data.create_time}><{$data.create_time}><{else}>无<{/if}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">支付时间：</td>
                <td class="bot_line" width="77%"><{if $data.pay_time}><{$data.pay_time}><{else}>无<{/if}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">发货时间：</td>
                <td class="bot_line" width="77%"><{if $data.delivery_time}><{$data.delivery_time}><{else}>无<{/if}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">结束时间：</td>
                <td class="bot_line" width="77%"><{if $data.order_time}><{$data.order_time}><{else}>无<{/if}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">商品金额：</td>
                <td class="bot_line" width="77%">&yen;<{$data.total_price}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">运费：</td>
                <td class="bot_line" width="77%">&yen;<{$data.delivery_fee}></td>
              </tr>
              <tr>
                <td class="bot_line" width="23%" align="right">应付金额：</td>
                <td class="bot_line" width="77%" style="color:red">&yen;<{$data.pay_price}></td>
              </tr>
            </table>
        </div>
    </div>
</div>
