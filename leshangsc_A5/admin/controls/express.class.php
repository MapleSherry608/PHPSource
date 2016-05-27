<?php
	class Express{
		function index(){
			if(!$this->is_cached("main/express_list",$_SERVER['REQUEST_URI'])){
				$express=D("Express");
				$page=new Page($express->total(), PAGENUM);
				$datas=$express->limit($page->limit)->lists();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/express_list",$_SERVER['REQUEST_URI']);
		}
		function add_index(){
			$this->display("main/express_add");
		}
		function add(){
			$this->validate();
			$express=D("Express");
			if($express->add()){
				$this->clear_cache();
				$this->success("填加成功!", 1, "express/index");
			} else {
				$this->error("填加失败!", 1, "express/add_index");
			}
		}
		function mod_index(){
			if(!$this->is_cached("main/express_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$express=D("Express");
				$datas=$express->select($id);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/express_mod",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$express=D("Express");
			$result=$express->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "express/index");
			} else {
				$this->error("编辑失败!", 1, "express/mod_index/id/{$id}");
			}
		}
		function del(){
			$express=D("Express");
			if($_POST['dels']){
				if($express->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "express/index");
				} else {
					$this->error("删除失败!", 1, "express/index");
				}
			} else {
				if($express->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "express/index");
				} else {
					$this->error("删除失败!", 1, "express/index");
				}
			}
		}
		
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "express/add_index");
			}
		}
	}