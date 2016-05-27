<?php
	class Payment{
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		function getChinabankCode($post, $config){
			$data_mid  = trim($config['partner_id']);//商户编号
			$data_oid   = $post['sn'];//订单号
			$data_amount  = intval($post['total_price']+$post['delivery_fee']); //支付金额  
			$data_moneytype  = 'CNY';//币种
			$data_key  = trim($config['authkey']);//MD5密钥
			$data_url ="http://".$_SERVER['SERVER_NAME'].B_APP."/payment/return_chinabankpay";//返回url,地址应为绝对路径,带有http协议
			$data_remark1 = '';//备注1
			$MD5KEY =$data_amount.$data_moneytype.$data_oid.$data_mid.$data_url.$data_key;
			$MD5KEY = strtoupper(md5($MD5KEY)); //md5加密拼凑串,注意顺序不能变
			$def_url  = '<form name="E_FORM"  method="post" action="https://pay3.chinabank.com.cn/PayGate" target="_blank">';
			$def_url .= "<input type=HIDDEN name='v_mid' value='".$data_mid."'>";//商户编号
			$def_url .= "<input type=HIDDEN name='v_oid' value='".$data_oid."'>";//订单号
			$def_url .= "<input type=HIDDEN name='v_amount' value='".$data_amount."'>"; //支付金额  
			$def_url .= "<input type=HIDDEN name='v_moneytype'  value='".$data_moneytype."'>";//币种
			$def_url .= "<input type=HIDDEN name='v_url'  value='".$data_url."'>";//返回url,地址应为绝对路径,带有http协议
			$def_url .= "<input type=HIDDEN name='v_md5info' value='".$MD5KEY."'>"; //md5加密拼凑串
			$def_url .= "<input type=HIDDEN name='remark1' value='".$remark1."'>";//备注
			$def_url .= "</form>";
			$def_url .= "<a href=\"#\"  onclick=\"document.E_FORM.submit()\">支付</a>";
			return $def_url;
		}
		function  getTenpayCode ( $post,$config ) {
			
			$bargainor_id = trim($config['partner_id']);////商户编号
			$key=trim($config['authkey']);//MD5密钥
			$return_url="http://".$_SERVER['SERVER_NAME'].B_APP."/payment/return_tenpay";//返回url,地址应为绝对路径,带有http协议
			//date_default_timezone_set(PRC);
			$strDate = date("Ymd");
			$strTime = date("His");
			$randNum = rand(1000, 9999);//4位随机数
			$strReq = $strTime . $randNum;//10位序列号,可以自行调整。	
			$transaction_id = $bargainor_id . $strDate . $strReq;/* 财付通交易单号，规则为：10位商户号+8位时间（YYYYmmdd)+10位流水号 */
			$sp_billno = $post['sn'];//订单号 商家订单号,长度若超过32位，取前32位。财付通只记录商家订单号，不保证唯一。
			//$total_fee ="1";/* 商品价格（包含运费），以分为单位 */
			$total_fee =intval($post['total_price']+$post['delivery_fee'])*100;/* 商品价格（包含运费），以分为单位 */
			$desc = "订单号：" . $transaction_id;
			/* 创建支付请求对象 */
			$reqHandler = new PayRequestHandler();
			$reqHandler->init();
			$reqHandler->setKey($key);
			$reqHandler->setParameter("bargainor_id", $bargainor_id);			//商户号
			$reqHandler->setParameter("sp_billno", $sp_billno);					//商户订单号
			$reqHandler->setParameter("transaction_id", $transaction_id);		//财付通交易单号
			$reqHandler->setParameter("total_fee", $total_fee);					//商品总金额,以分为单位
			$reqHandler->setParameter("return_url", $return_url);				//返回处理地址
			$reqHandler->setParameter("desc", $desc);	//商品名称
			$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);
			$reqUrl = $reqHandler->getRequestURL();
			
			return $reqUrl;
		}
		
		//(合作商户编号,加密串,返回url, 默认编码,商品名称,商品简介,商户订单号,物流配送费用)
		function  getAlipayCode ( $post,$config ) {
			
			switch ($config['parameter2']){
				case '3':
					$service = 'trade_create_by_buyer';
					break;
				case '2':
					$service = 'create_partner_trade_by_buyer';
					break;
				case '1':
					$service = 'create_direct_pay_by_user';
					break;
    	    }
			
			if($config['payment_id']==4){
				$logistics_payment="BUYER_PAY_AFTER_RECEIVE";
			} else {
				if($post['delivery_fee']==0){
					$logistics_payment="SELLER_PAY";
				} else {
					$logistics_payment="BUYER_PAY";
				}
			}
			
			 # 支付宝交易类型
			 $data [ 'service' ] =  $service ; //create_partner_trade_by_buyer[担保交易]create_direct_pay_by_user[即时到账]
			# 合作商户编号
			 $data [ 'partner' ] =  trim($config['partner_id']);
			 # 请求返回地址
			 $data [ 'return_url' ] =  "http://".$_SERVER['SERVER_NAME'].B_APP."/payment/return_alipay";
			 # 默认编码
			 $data [ '_input_charset' ] =  'utf-8' ;
			 # 默认支付渠道
			 $data [ 'paymenthod' ] =  'bankPay' ;
			 # 默认的网银
			 $data [ 'defaultbank' ] =  'ICBCB2C' ;
			 # 商品名称
			 $data [ 'subject' ] =  $post['sn'] ;
			 # 商品展示URL
			 $data [ 'show_url' ] =  '' ;
			 # 异步通知返回
			 $data [ 'notify_url' ] =  "http://".$_SERVER['SERVER_NAME'].B_APP."/payment/notify_alipay";
			 # 商品简介
			 $data [ 'body' ] =  $body ;
			 # 商户订单号
			 $data [ 'out_trade_no' ] =  $post['sn'] ;
			 # 物流配送费用
			 $data [ 'logistics_fee' ] =  $post['delivery_fee'] ;
			 # 物流费用付款方式
			 $data [ 'logistics_payment' ] =  $logistics_payment; //SELLER_PAY(卖家支付)、BUYER_PAY(买家支付)、BUYER_PAY_AFTER_RECEIVE(货到付款)
			# 物流配送方式
			 $data [ 'logistics_type' ] =  'EXPRESS' ; //物流配送方式：POST(平邮)、EMS(EMS)、EXPRESS(其他快递)
			# 价格
			 $data [ 'price' ] =  $post['total_price'];
			 #$data['total_fee'] = '10.00';
			# 付款方式
			 $data [ 'payment_type' ] =  '1' ;
			 # 商品数量
			 $data [ 'quantity' ] =  '1' ;
			 # 卖家email
			 $data [ 'seller_email' ] = $config['parameter1'];
			 $data  =  array_filter ( $data );
			 ksort ( $data ); reset ( $data );
			 $data [ 'sign' ] =  md5 ( urldecode ( http_build_query ( $data )).  trim($config['authkey']) );
			 $data [ 'sign_type' ] =  'MD5' ;
			 $url  =  'https://www.alipay.com/cooperate/gateway.do?' . http_build_query ( $data );
			return  $url ;
		}
		
	   
	}