<?php
	class Orders{
		function index(){
			if(!$this->is_cached("main/orders_list",$_SERVER['REQUEST_URI'])){
				$orders=D("orders");
				$product=D("Product");
				$user=D("User");
				$oi=D("Ordersitem");
				$spec=D("Spec");
				if(!$_GET['search']){
					$page=new Page($orders->total(), PAGENUM);
					$datas=$orders->limit($page->limit)->orders_list();
				} else {
					$get['sn']=trim($_GET['sn']);
					$get['name']=trim($_GET['name']);
					$get['tel']=trim($_GET['tel']);
					$get['pay_status']=intval($_GET['pay_status']);
					$get['payment_id']=intval($_GET['payment_id']);
					$get['delivery_status']=intval($_GET['delivery_status']);
					$get['order_status']=intval($_GET['order_status']);
					$page_param="search/1";
					if($get['sn']){
						$page_param.="/sn/{$get['sn']}";
					}
					if($get['name']){
						$page_param.="/name/{$get['name']}";
					}
					if($get['tel']){
						$page_param.="/name/{$get['tel']}";
					}
					$page_param.="/pay_status/{$get['pay_status']}/payment_id/{$get['payment_id']}/delivery_status/{$get['delivery_status']}/order_status/{$get['order_status']}";
					$page=new Page($orders->search_total($_GET), PAGENUM,$page_param);
					if($get['sn'] || $get['name'] || $get['tel'] || $get['pay_status']!=-1 || $get['payment_id']!=-1 || $get['delivery_status']!=-1 || $get['order_status']!=-1){
						$is_search=1;
					} else {
						$is_search=0;
					}
					$this->assign("is_search",$is_search);
					$datas=$orders->limit($page->limit)->search($_GET);
				}
				foreach($datas as $k=>$v){
					$datas[$k]['user']=$user->load($v['uid']);
					$items=$oi->load($v['id']);
					$datas[$k]['create_time']=date("Y-m-d H:i:s",$v['create_time']);
					foreach($items as $ik=>$iv){
						$datas[$k]['product'][$ik]=$product->load($iv['pid']);
						$datas[$k]['product'][$ik]['amount']=$iv['amount'];
						$datas[$k]['product'][$ik]['price']=$iv['price'];
						$specs=explode(",",$iv['specs']);
						foreach($specs as $sk=>$sv){
							$datas[$k]['product'][$ik]['specs_cn'][$sv]=$spec->field('name')->load($sv);
						}
					}
					$datas[$k]['pay_price']=$v['total_price']+$v['delivery_fee'];
				}
				//P($datas);
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/orders_list",$_SERVER['REQUEST_URI']);
		}
		function show(){
			if(!$this->is_cached("main/order_show",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$orders=D("orders");
				$product=D("Product");
				$user=D("User");
				$spec=D("Spec");
				$oi=D("Ordersitem");
				$data=$orders->load($id);
				$data['user']=$user->load($data['uid']);
				$items=$oi->load($data['id']);
				foreach($items as $ik=>$iv){
					$data['product'][$ik]=$product->load($iv['pid']);
					$data['product'][$ik]['amount']=$iv['amount'];
					$data['product'][$ik]['price']=$iv['price'];
					$specs=explode(",",$iv['specs']);
					foreach($specs as $sk=>$sv){
						$data['product'][$ik]['specs_cn'][$sv]=$spec->field('name')->load($sv);
					}
				}
				if($data['create_time']){
					$data['create_time']=date("Y-m-d H:i:s",$data['create_time']);
				}
				if($data['pay_time']){
					$data['pay_time']=date("Y-m-d H:i:s",$data['pay_time']);
				}
				if($data['delivery_time']){
					$data['delivery_time']=date("Y-m-d H:i:s",$data['delivery_time']);
				}
				if($data['order_time']){
					$data['order_time']=date("Y-m-d H:i:s",$data['order_time']);
				}
				$data['status_cn']='';//订单状态
				$html='';
				switch($data['order_status']){
					case 5://未结单，正在交易状态
						if($data['pay_status']==1){//已付款
							if($data['delivery_status']==0){
								
								$html.="<span style='color:red'>未发货</span>";
								$html.="<input type='button' id='deliver' value='发货'>";
								$html.="<input type='button' id='mod_receiving' value='改收货信息'>";
								$html.="<input type='button' id='close' value='关闭交易'>";
								
							}elseif ($data['delivery_status']==1){
								$html.="<span style='color:green'>已发货</span>";
								$html.="<input type='button' id='receive' value='确认收货'>";
								$html.="<input type='button' id='close' value='关闭交易'>";
							}else{
								$data['status_cn']="<span style='color:red'>已收货</span>";
							}
							
						} else{//未付款
							
							if($data['payment_id']==4){
								if($data['delivery_status']==0){
								
									$html.="<span style='color:red'>未发货</span>";
									$html.="<input type='button' id='deliver' value='发货'>";
									
								}elseif ($data['delivery_status']==1){
									$html.="<span style='color:green'>已发货</span>";
									$html.="<input type='button' id='receive' value='确认收货'>";
									$html.="<input type='button' id='close' value='关闭交易'>";
								}else{
									$data['status_cn']="<span style='color:red'>已收货</span>";
								}
							} else {
								$html.="<span style='color:red'>未付款</span>";
								$html.="<input type='button' id='pay' value='付款'>";
								$html.="<input type='button' id='mod_pay' value='改价/支付方式'>";
								$html.="<input type='button' id='mod_receiving' value='改收货信息'>";
								$html.="<input type='button' id='close' value='关闭交易'>";
							}
							
							
						}
					break;
					case 0:
						$html.="<span style='color:red'>交易失败</span>&nbsp;&nbsp;";
						$html.="关闭原因:".$data['reason'];
					break;
					case 1:
						$html.="<span style='color:green'>交易成功</span>";
					break;
					case 2:
						$data['status_cn']="<span style='color:green'>已退款</span>";
					break;
					case 3:
						$data['status_cn']="<span style='color:green'>已退货</span>";
					break;
					case 4:
						$data['status_cn']="<span style='color:green'>已退款，已退货</span>";
					break;
				}
				$data['status_cn']=$html;
				$data['pay_price']=$data['total_price']+$data['delivery_fee'];
				$this->assign("data",$data);
			}
			$this->display("main/order_show",$_SERVER['REQUEST_URI']);
		}
		//付款
		function pay_index(){
			if(!$this->is_cached("main/pay_index",$_SERVER['REQUEST_URI'])){
				$orders=D("orders");
				$payment=D("payment");
				$id=intval($_GET['id']);
				$data=$orders->load($id);
				$p=$payment->load($data['payment_id']);
				$data['payment_cn']=$p['byname'];
				$data['pay_price']=$data['total_price']+$data['delivery_fee'];
				$this->assign("data",$data);
			}
			$this->display("main/pay_index",$_SERVER['REQUEST_URI']);
		}
		function order_paid(){
			$orders=D("orders");
			$id=intval($_POST['id']);
			$result=$orders->paid($id);
			if(false !== $result){
				$mailRules=D("MailRules","admin");
				$order_data=$orders->load($id);
				$template=$mailRules->load_temp("deliver");
				if($template['value']){
					$user_data=D("User")->load($order_data['uid']);
					$datas=array("FromName"=>"管理员","Subject"=>"支付成功","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
				}
				
				$this->success("支付成功!", 1);
			} else {
				$this->error("支付失败!", 1);
			}
		}
		
		//发货
		function deliver_index(){
			if(!$this->is_cached("main/deliver_index",$_SERVER['REQUEST_URI'])){
				$orders=D("orders");
				$express=D("Express");
				$payment=D("payment");
				$id=intval($_GET['id']);
				$express_list=$express->lists();
				$data=$orders->load($id);
				$p=$payment->load($data['payment_id']);
				$data['payment_cn']=$p['byname'];
				$data['pay_price']=$data['total_price']+$data['delivery_fee'];
				$this->assign("data",$data);
				$this->assign("express_list",$express_list);
			}
			$this->display("main/deliver_index",$_SERVER['REQUEST_URI']);
		}
		
		function order_deliver(){
			$orders=D("orders");
			$id=intval($_POST['id']);
			$post['express_id']=intval($_POST['express_id']);
			$post['express_sn']=intval($_POST['express_sn']);
			$post['delivery_status']=1;
			$post['delivery_time']=time();
			$result=$orders->deliver($id,$post);
			if(false !== $result){
				
				$mailRules=D("MailRules","admin");
				$order_data=$orders->load($id);
				$template=$mailRules->load_temp("deliver");
				if($template['value']){
					$user_data=D("User")->load($order_data['uid']);
					$datas=array("FromName"=>"管理员","Subject"=>"关闭订单","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
				}
				$this->success("发货成功!", 1);
			} else {
				$this->error("发货失败!", 1);
			}
		}
		
		//关闭交易
		function close_index(){
			if(!$this->is_cached("main/close_index",$_SERVER['REQUEST_URI'])){
				$orders=D("orders");
				$express=D("Express");
				$payment=D("payment");
				$id=intval($_GET['id']);
				$express_list=$express->lists();
				$data=$orders->load($id);
				$p=$payment->load($data['payment_id']);
				$data['payment_cn']=$p['byname'];
				$data['pay_price']=$data['total_price']+$data['delivery_fee'];
				$this->assign("data",$data);
				$this->assign("express_list",$express_list);
			}
			$this->display("main/close_index",$_SERVER['REQUEST_URI']);
		}
		
		function close_order(){
			$orders=D("orders");
			$id=intval($_POST['id']);
			$order_data=$orders->load($id);
			$result=$orders->close($id);
			if(false !== $result){
				$mailRules=D("MailRules","admin");
				$template=$mailRules->load_temp("close_order");
				if($template['value']){
					$user_data=D("User")->load($order_data['uid']);
					$datas=array("FromName"=>"管理员","Subject"=>"关闭订单","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
					
				}
				$this->success("订单关闭成功!", 1);
			} else {
				$this->error("订单关闭失败!", 1);
			}
		}
		
		//成功（收货）
		function receive(){
			$orders=D("orders");
			$id=intval($_GET['id']);
			$at=D("appraisetimes");
			$oi=D("Ordersitem");
			$items=$oi->load($id);
			$order_data=$orders->load($id);
			if($order_data['payment_id']==4){
				$post['pay_status']=1;
				$post['pay_time']=time();
				$post['order_status']=1;
				$post['order_time']=time();
				$result=$orders->mod($id,$post);
			} else {
				$result=$orders->success($id);
			}
			if(false !== $result){
				foreach($items as $k=>$v){
					if($t=$at->load($v['pid'],$v['uid'])){
						$at->mod($t['id']);
					} else {
						$apost['pid']=$v['pid'];
						$apost['uid']=$v['uid'];
						$apost['times']=$apost['times']+1;
						$at->add($apost);
					}
				}
				
				$mailRules=D("MailRules","admin");
				$template=$mailRules->load_temp("set_receive");
				if($template['value']){
					$user_data=D("User")->load($order_data['uid']);
					$datas=array("FromName"=>"管理员","Subject"=>"确认收货","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
					
				}
				
				$this->success("交易成功!", 1);
			} else {
				$this->error("交易失败!", 1);
			}
		}
		
		
		//改价
		function mod_pay_index(){
			if(!$this->is_cached("main/mod_pay_index",$_SERVER['REQUEST_URI'])){
				$orders=D("orders");
				$payment=D("payment");
				$id=intval($_GET['id']);
				$data=$orders->load($id);
				$payment_list=$payment->lists();
				$data['pay_price']=$data['total_price']+$data['delivery_fee'];
				$this->assign("data",$data);
				$this->assign("payment_list",$payment_list);
			}
			$this->display("main/mod_pay_index",$_SERVER['REQUEST_URI']);
		}
		function mod_pay(){
			$orders=D("orders");
			$id=intval($_POST['id']);
			$post['total_price']=$_POST['total_price'];
			$post['delivery_fee']=$_POST['delivery_fee'];
			$post['payment_id']=$_POST['payment_id'];
			$result=$orders->mod($id,$post);
			if(false !== $result){
				$mailRules=D("MailRules","admin");
				$order_data=$orders->load($id);
				$template=$mailRules->load_temp("mod_pay");
				if($template['value']){
					$user_data=D("User")->load($order_data['uid']);
					$datas=array("FromName"=>"管理员","Subject"=>"修改订单","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
				}
				$this->success("修改成功!", 1);
			} else {
				$this->error("修改失败!", 1);
			}
		}
		//改收货信息
		function mod_receiving_index(){
			if(!$this->is_cached("main/mod_receiving_index",$_SERVER['REQUEST_URI'])){
				$orders=D("orders");
				$id=intval($_GET['id']);
				$data=$orders->load($id);
				$this->assign("data",$data);
			}
			$this->display("main/mod_receiving_index",$_SERVER['REQUEST_URI']);
		}
		function mod_preceiving(){
			$orders=D("orders");
			$id=intval($_POST['id']);
			$post['name']=$_POST['name'];
			$post['tel']=$_POST['tel'];
			$post['address']=$_POST['address'];
			$result=$orders->mod($id,$post);
			if(false !== $result){
				$mailRules=D("MailRules","admin");
				$order_data=$orders->load($id);
				$template=$mailRules->load_temp("mod_receiving");
				if($template['value']){
					$user_data=D("User")->load($order_data['uid']);
					$datas=array("FromName"=>"管理员","Subject"=>"改收货信息","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
				}
				$this->success("修改成功!", 1);
			} else {
				$this->error("修改失败!", 1);
			}
		}
		
		function del(){
			$orders=D("orders");
			if($_POST['dels']){
				if($orders->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "orders/index");
				} else {
					$this->error("删除失败!", 1, "orders/index");
				}
			} else {
				if($orders->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "orders/index");
				} else {
					$this->error("删除失败!", 1, "orders/index");
				}
			}
		}
	}