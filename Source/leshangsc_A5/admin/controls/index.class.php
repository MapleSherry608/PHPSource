<?php
	class Index extends Action{
		function index(){
			$this->display("index/login");
		}
		
		function code(){
			ob_clean();
			echo new Vcode(70,29,4);
			
		}	
		function login(){
			$code=trim($_POST["code"]);
			$adm_name=trim($_POST["adm_name"]);
			$adm_pass=md5(trim($_POST["adm_password"]));
			$admin=D("Admin")->find_admin($adm_name,$adm_pass);
			Validate::vcode($code,"验证码错误!");
			if($admin){
				if(Validate::$flag){
					$_SESSION['admin']=$admin;
					$_SESSION['admin']["isLogin"]=true;
					D("Admin")->where(array("id"=>$admin['id']))->update(array("login_time"=>time(),"login_ip"=>get_ip()));
					$this->success("登陆成功",1,"index/admin");
				}else{
					$this->error("验证码错误",1,"index");
				}
			} else {
				$this->error("用户或密码错误!",1,"index");
			}
		}
		function logout(){
			$username=$_SESSION['admin']["adm_name"];
			$_SESSION['admin']=array();
			$_SESSION['admin']["isLogin"]=false;
			$this->success("再见{$username}!", 1, "index");
		}
		function admin(){
			$this->display("index/default");
		}
		function top(){
			debug(0);
			if(!(isset($_SESSION['admin']["isLogin"]) && $_SESSION['admin']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			$config=D("Config");
			$this->assign("admin",$_SESSION['admin']);
			if($_SESSION['admin']["isLogin"]){
				$this->display();
			} else{
				$this->display("index");
			}
		}
		function drag(){
			debug(0);
			$this->display();
		}
		function left(){
			debug(0);
			if(!(isset($_SESSION['admin']["isLogin"]) && $_SESSION['admin']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			$id=intval($_GET['left_id']);
			$this->assign("version",VERSION);
			switch($id){
				case 1:
					$this->assign("front_index",$GLOBALS['root']);
					$this->display("index/index_left");
				break;
				case 2:
					$this->display("index/front_left");
				break;
				case 3:
					$this->display("index/product_left");
				break;
				case 4:
					$this->display("index/user_left");
				break;
				case 5:
					$this->display("index/set_left");
				break;
			}
		}
		function main(){
			if(!(isset($_SESSION['admin']["isLogin"]) && $_SESSION['admin']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			$this->assign("dbsize",D()->dbSize());
			$this->assign("dbversion",D()->dbVersion());
			$this->display("main/main");
		}
		private function validate(){
			validate::notnull($_POST['content'],"内容不能为空");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 1);
			}
		}
		function bottom(){
			debug(0);
			$this->display();
		}
	}