<?php
	class Payment{
		function index(){
			if(!$this->is_cached("main/payment_list",$_SERVER['REQUEST_URI'])){
				$payment=D("Payment");
				$page=new Page($payment->total(), PAGENUM);
				$datas=$payment->limit($page->limit)->lists();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/payment_list",$_SERVER['REQUEST_URI']);
		}
		function mod_index(){
			$name=trim($_GET['name']);
			switch($name){
				case 'chinabank':
					$filename="main/chinabank_mod";
				break;
				case 'tenpay':
					$filename="main/tenpay_mod";
				break;
				case 'alipay':
					$filename="main/alipay_mod";
				break;
				case 'cash':
					$filename="main/cash_mod";
				break;
				case 'weixinpay':
					$filename="main/weixinpay_mod";
				break;
			}
			if(!$this->is_cached($filename,$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$payment=D("Payment");
				$datas=$payment->select($id);
				$this->assign("datas",$datas[0]);
			}
			$this->display($filename,$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$payment=D("Payment");
			$result=$payment->mod($id);
			if(false !== $result){
				$this->clear_cache();
				$this->success("编辑成功!", 1, "payment/index");
			} else {
				$this->error("编辑失败!", 1);
			}
		}
		function uninstall(){
			$payment=D("Payment");
			if($payment->uninst($_GET['id'])){
				$this->clear_cache();
				$this->success("卸载成功!", 1);
			} else {
				$this->error("卸载失败!", 1);
			}
		}
		function install(){
			$payment=D("Payment");
			if($payment->inst($_GET['id'])){
				$this->clear_cache();
				$this->success("安装成功!", 1);
			} else {
				$this->error("安装失败!", 1);
			}
		}
		
		
		
		
		
		
		
		private function validate(){
			validate::notnull($_POST['byname'],"名称不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3);
			}
		}
	}