<?php
	class orders{
		function add_order(){
			if(isset($_POST['rancode'])) {  
				if($_POST['rancode'] == $_SESSION['rancode']){
					 $this->error("不能重复提交表单!", 1);
				}else{  
					 $_SESSION['rancode'] =$_POST['rancode']; //存储code
				}  
			}
			$order=D("Orders");
			$orderitem=D("Ordersitem");
			$payment=D("Payment");
			$post['sn']=$this->build_order_no();
			$post['create_time']=time();
			$post['pay_status']=0;
			$post['delivery_status']=0;
			$post['order_status']=5;
			$post['name']=$_POST['name']?trim($_POST['name']):$this->error("请填写姓名!", 1);
			$post['tel']=$_POST['tel']?trim($_POST['tel']):$this->error("请填写电话!", 1);
			$post['address']=$_POST['address']?trim($_POST['address']):$this->error("请填写地址!", 1);
			$post['message']=isset($_POST['message'])?trim($_POST['message']):"";
			$post['district']=$_POST['district']?intval($_POST['district']):$this->error("请选择省级地区!", 1);
			$post['sdistrict']=$_POST['sdistrict']?intval($_POST['sdistrict']):$this->error("请选择市级地区!", 1);
			$post['total_price']=$_POST['total_price'];
			$post['delivery_fee']=$_POST['delivery_fee'];
			$post['payment_id']=intval($_POST['payment_id']);
			$post['score']=intval($_POST['score']);
			$post['uid']=intval($_POST['user_id']);
			
			
			if($oid=$order->add($post)){
				foreach($_POST['pid'] as $v){
					$item['pid']=$v;
					$item['oid']=$oid;
					$item['amount']=$_POST['amount'][$v];
					$item['price']=$_POST['price'][$v];
					$item['specs']=$_POST['specs'][$v];
					$item['uid']=intval($_POST['user_id']);
					$orderitem->add($item);
				}
				$order=$post;
				
				//删除购物车
				$cart=D("Cart");
				foreach($_POST['id'] as $v){
					$cart->del($v,$post['uid']);
				}
				
				switch($post['payment_id']){
					case '1':
						$payment_cn='网银在线';
						$config=$payment->load($post['payment_id']);
						$payLinks=$payment->getChinabankCode($post,$config);
					break;
					case '2':
						$payment_cn='财付通';
						$config=$payment->load($post['payment_id']);
						$payLinks=$payment->getTenpayCode($post,$config);
					break;
					case '3':
						$payment_cn='支付宝';
						$config=$payment->load($post['payment_id']);
						$payLinks=$payment->getAlipayCode($post,$config);
					break;
					case '4':
						$payment_cn='货到付款';
					break;
				}
				$order['payment_cn']=$payment_cn;
				$order['price']=$post['total_price']+$post['delivery_fee'];
				$this->assign("payLinks",$payLinks);
				$this->assign("order",$order);
				$this->display("user/orders");
			} else {
				$this->error("订单提交失败!", 500);
			}
			
		}
		
		
		
		function build_order_no(){
			return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
		}
	}