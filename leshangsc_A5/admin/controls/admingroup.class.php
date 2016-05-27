<?php
	class AdminGroup{
		function index(){
			if(!$this->is_cached("main/adminGroup_list",$_SERVER['REQUEST_URI'])){
				$adminGroup=D("AdminGroup");
				$page=new Page($adminGroup->total(), PAGENUM);
				$datas=$adminGroup->limit($page->limit)->lists();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/adminGroup_list",$_SERVER['REQUEST_URI']);
		}
		function add_index(){
			$adminClass=D("AdminClass");
			$adminNode=D("AdminNode");
			$class=$adminClass->lists();
			$node=$adminNode->lists();
			$this->assign("class",$class);
			$this->assign("node",$node);
			$this->display("main/adminGroup_add");
		}
		function mod_index(){
			if(!$this->is_cached("main/adminGroup_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$adminGroup=D("adminGroup");
				$adminClass=D("AdminClass");
				$adminNode=D("AdminNode");
				$class=$adminClass->lists();
				$node=$adminNode->lists();
				$datas=$adminGroup->select($id);
				$datas[0]['auth']=unserialize(htmlspecialchars_decode($datas[0]['auth']));
				$this->assign("class",$class);
				$this->assign("node",$node);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/adminGroup_mod",$_SERVER['REQUEST_URI']);
		}
		
		function add(){
			$this->validate();
			$adminGroup=D("adminGroup");
			$_POST['auth']=serialize($_POST['auth']);
			$result=$adminGroup->add();
			if(false !== $result){
				$this->clear_cache();
				$this->success("填加成功!", 1, "adminGroup/index");
			} else {
				$this->error("填加失败!", 1, "adminGroup/add_index");
			}
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$adminGroup=D("adminGroup");
			$_POST['auth']=serialize($_POST['auth']);
			$result=$adminGroup->mod($id);
			if(false !== $result){
				$this->clear_cache();
				$this->success("编辑成功!", 1, "adminGroup/mod_index/id/{$id}");
			} else {
				$this->error("编辑失败!", 1, "adminGroup/mod_index/id/{$id}");
			}
		}
		
		function del(){
			$adminGroup=D("adminGroup");
			if($_POST['dels']){
				if($adminGroup->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "adminGroup/index");
				} else {
					$this->error("删除失败!", 1, "adminGroup/index");
				}
			} else {
				if($adminGroup->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "adminGroup/index");
				} else {
					$this->error("删除失败!", 1, "adminGroup/index");
				}
			}
		}
		
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3);
			}
		}
	}