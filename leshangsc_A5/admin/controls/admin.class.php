<?php
	class Admin{
		function admin_list(){
			if(!$this->is_cached("main/admin_list",$_SERVER['REQUEST_URI'])){
				$admin=D("Admin");
				$page=new Page($admin->total(), PAGENUM);
				$datas=$admin->limit($page->limit)->admin_list();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/admin_list",$_SERVER['REQUEST_URI']);
		}
		function find_admin($name,$pass){
			return D("Admin")->find_admin($name,$pass);
		}
		function set_default(){
			$admin=D("Admin");
			$id=intval($_GET['id']);
			if($admin->set_default($id)){
				$this->clear_cache();
				$this->success("设置成功!", 1, "admin/admin_list");
			} else {
				$this->error("设置失败!", 1, "admin/admin_list");
			}
		}
		function add_index(){
			$adminGroup=D("AdminGroup");
			$g_datas=$adminGroup->lists();
			$this->assign("g_datas",$g_datas);
			$this->display("main/admin_add");
		}
		function mod_index(){
			if(!$this->is_cached("main/admin_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$adminGroup=D("AdminGroup");
				$datas=D("admin")->load($id);
				$g_datas=$adminGroup->lists();
				$this->assign("g_datas",$g_datas);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/admin_mod",$_SERVER['REQUEST_URI']);
		}
		function add(){
			$this->validate();
			$admin=D("Admin");
			$pass=trim($_POST['adm_password']);
			$confirm_pass=trim($_POST['confirm_pass']);
			if($pass==$confirm_pass && $pass!=""){
				$_POST['adm_password']=md5($pass);
			} else {
				$this->error("密码不一致,编辑失败!", 1, "admin/add_index");
			}
			$_POST['login_time']=0;
			$_POST['is_default']=0;
			$_POST['login_ip']="";
			$result=$admin->add();
			if(false !== $result){
				$this->clear_cache();
				$this->success("填加成功!", 1, "admin/admin_list");
			} else {
				$this->error("填加失败!", 1, "admin/add_index");
			}
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$pass=trim($_POST['adm_password']);
			$confirm_pass=trim($_POST['confirm_pass']);
			
			if($pass==$confirm_pass && $pass!=""){
				$_POST['adm_password']=md5($pass);
			} else {
				$this->error("密码不一致,编辑失败!", 1, "admin/mod_index/id/{$id}");
			}
			$admin=D("Admin");
			$result=$admin->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "admin/admin_list");
			} else {
				$this->error("编辑失败!", 1, "admin/mod_index/id/{$id}");
			}
		}
		function del(){
			$admin=D("Admin");
			if($_POST['dels']){
				if($admin->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "admin/admin_list");
				} else {
					$this->error("删除失败!", 1, "admin/admin_list");
				}
			} else {
				if($admin->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "admin/admin_list");
				} else {
					$this->error("删除失败!", 1, "admin/admin_list");
				}
			}
		}
		private function validate(){
			validate::notnull($_POST['adm_name'],"管理员名不能为空");
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3);
			}
		}
		
	}