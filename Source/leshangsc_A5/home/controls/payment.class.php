<?php
	class Payment{
		
		function return_chinabankpay(){
			$v_oid          = trim($_POST['v_oid']);
			$v_pmode        = trim($_POST['v_pmode']);
			$v_pstatus      = trim($_POST['v_pstatus']);
			$v_pstring      = trim($_POST['v_pstring']);
			$v_amount       = trim($_POST['v_amount']);
			$v_moneytype    = trim($_POST['v_moneytype']);
			$remark1        = trim($_POST['remark1']);
			$remark2        = trim($_POST['remark2']);
			$v_md5str       = trim($_POST['v_md5str']);
			$payment=D("Payment");
			$info=$payment->load(1);
			$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$info['authkey']));
			 /* 检查秘钥是否正确 */
			if ($v_md5str==$md5string)
			{
				if ($v_pstatus == '20'){
				/* 改变订单状态 */
					$orders=D("Orders");
					$status=$orders->order_paid($v_oid);
					if (!$status)
					{
						$this->error("支付失败",3);
					} else {
						
						
						$orders_info=$orders->load_sn($sn);
						$this->assign("orders_info",$orders_info);
						$this->display("user/order_success");
						return true;
					}
				}
			}else{
				$this->error("支付验证失败",3);
			}
		}
		
		function return_tenpay(){
			$payment=D("Payment");
			$info=$payment->load(2);
			$key = $info['authkey'];
			/* 创建支付应答对象 */
			$resHandler = new PayResponseHandler();
			$resHandler->setKey($key);
				if($resHandler->isTenpaySign())
				{
				//商户单号
				$sp_billno = $resHandler->getParameter("sp_billno");
				//财付通交易单号
				$transaction_id = $resHandler->getParameter("transaction_id");
				//金额,以分为单位
				$total_fee = $resHandler->getParameter("total_fee");
				$pay_result = $resHandler->getParameter("pay_result");
					if( "0" == $pay_result ) 
					{
						$status=$orders->order_paid($sp_billno);
						$orders_info=$orders->load_sn($sp_billno);
						$this->assign("orders_info",$orders_info);
						$this->display("user/order_success");
					}
					else
					{
						$this->error("支付失败",3);
					}
				}
				else
				{
					$this->error("支付验证失败",3);
				}
		}
		
		function return_alipay(){
			  $request=$_REQUEST;
			  ksort($request);
			  reset($request);
			  
			  foreach ($request as $key=>$val)
			  {
				  if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key!='class_name' && $key!='act' )
				  {
					  $sign .= "$key=$val&";
				  }
			  }
	  		  $payment=D("Payment");
			  $info=$payment->load(3);
			  $sign = substr($sign, 0, -1) . $info['authkey'];
	  
			  if (md5($sign) != $request['sign'])
			  {
				  $this->error("支付验证失败",3);
			  }
			  
			  $sn = $request['out_trade_no'];
			  $money = $request['total_fee'];
			  if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED' || $request['trade_status'] == 'WAIT_SELLER_SEND_GOODS'){
				  
				  $orders=D("Orders");
				  $status=$orders->order_paid($sn);
				  $orders_info=$orders->load_sn($sn);
				  $this->assign("orders_info",$orders_info);
				  $this->display("user/order_success");
			  } else {
				  $this->error("支付失败",3);
			  }
		}
		function notify_alipay(){
			  $request=$_REQUEST;
			  ksort($request);
			  reset($request);
			  
			  foreach ($request as $key=>$val)
			  {
				  if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key!='class_name' && $key!='act' )
				  {
					  $sign .= "$key=$val&";
				  }
			  }
	  		  $payment=D("Payment");
			  $info=$payment->load(3);
			  $sign = substr($sign, 0, -1) . $info['authkey'];
	  
			  if (md5($sign) != $request['sign'])
			  {
				  $this->error("支付验证失败",3);
			  }
			  
			  $sn = $request['out_trade_no'];
			  $money = $request['total_fee'];
			  if ($request['trade_status'] == 'TRADE_SUCCESS' || $request['trade_status'] == 'TRADE_FINISHED' || $request['trade_status'] == 'WAIT_SELLER_SEND_GOODS'){
				  
				  $orders=D("Orders");
				  $status=$orders->order_paid($sn);
				  $orders_info=$orders->load_sn($sn);
				  $this->assign("orders_info",$orders_info);
				  $this->display("user/order_success");
			  } else {
				  $this->error("支付失败",3);
			  }
		}
	}