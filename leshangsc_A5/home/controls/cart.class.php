<?php
	class Cart{
		function index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("请登陆后访问!", 1);
			}
			if(!$this->is_cached("user/cart_list",$_SERVER['REQUEST_URI'])){
				$uid=intval($_SESSION['user']['id']);
				$cart=D("Cart");
				$product=D("Product");
				$user=D("User");
				$payment=D("Payment","admin");
				$district=D("District","admin");
				$spec=D("Spec","admin");
				$user_info=$user->load_user($uid);
				$payment_info=$payment->lists();
				$datas=$cart->cart_list($uid);
				$total_price=0;
				$delivery_fee=0;
				foreach($datas as $k=>$v){
					$datas[$k]['pro']=$product->load($v['pid']);
					$total_price+=$v['price'];
					$delivery_fee+=$datas[$k]['pro']['delivery_fee'];
					$specs=explode(",",$v['specs']);
					foreach($specs as $sk=>$sv){
						$datas[$k]['specs_cn'][$sv]=$spec->field('name')->load($sv);
					}
				}
				$rancode = mt_rand(0,1000000);//生成一个随机数，避免重复提交
				$this->assign("district",$district->district_lists());
				$this->assign("payment_info",$payment_info);
				$this->assign("user_info",$user_info[0]);
				$this->assign("datas",$datas);
				$this->assign("total_price",$total_price);
				$this->assign("delivery_fee",$delivery_fee);
				$this->assign("payable",$total_price+$delivery_fee);
				$this->assign("rancode",$rancode);
			}
			$this->display("user/cart_list",$_SERVER['REQUEST_URI']);
		}
		function add_cart(){
			$product=D("Product");
			$uid=intval($_POST['uid']);
			$pro_data=$product->load(intval($_POST['pid']));
			if($pro_data['user_id']==$uid){
				echo 4;
			} else {
				$cart=D("Cart");
				$_POST['price']=intval($_POST['price']);
				$is_cart=$cart->is_cart(intval($_POST['pid']),$uid);
				$_POST['add_time']=time();
				if($_POST['specs']!=0){
					$_POST['specs'] = substr($_POST['specs'],0,strlen($_POST['specs'])-1); 
				} else {
					$_POST['specs']=0;
				}
				if($is_cart){
					echo 2;
				} else {
					if($id=$cart->add()){
						echo 1;
					} 
				}
			}
		}
		
		function del_cart(){
			$cart=D("Cart");
			$id=intval($_GET['id']);
			$uid=intval($_SESSION['user']['id']);
			if($cart->del($id,$uid)){
				$this->success("此商品已从购物车中删除", 1, "cart/index");
			} else {
				$this->error("删除失败", 1, "cart/index");
			}
		}
	}