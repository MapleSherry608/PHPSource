<?php
	class Pcate extends Common{
		function index(){
			if(!$this->is_cached("main/pcate_list",$_SERVER['REQUEST_URI'])){
				$pcate=D("Pcate");
				$page=new Page($pcate->total(), PAGENUM);
				$datas=$pcate->limit($page->limit)->cate_list();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/pcate_list",$_SERVER['REQUEST_URI']);
		}
		
		function add_show(){
			$this->display("main/pcate_add");
		}
		function edit_show(){
			if(!$this->is_cached("main/pcate_edit",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$pcate=D("pcate");
				$datas=$pcate->select($id);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/pcate_edit",$_SERVER['REQUEST_URI']);
		}
		
		function add(){
			$this->validate();
			$pcate=D("pcate");
			if($pcate->add()){
				$this->clear_cache();
				$this->success("填加成功!", 1, "pcate/index");
			} else {
				$this->error("填加失败!", 1, "pcate/add_show");
			}
		}
		function edit(){
			$this->validate();
			$id=intval($_POST['id']);
			$pcate=D("pcate");
			$result=$pcate->edit($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "pcate/index");
			} else {
				$this->error("编辑失败!", 1, "pcate/edit_show/id/{$id}");
			}
		}
		
		function del(){
			$pcate=D("Pcate");
			if($_POST['dels']){
				if($pcate->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "pcate/index");
				} else {
					$this->error("删除失败!", 1, "pcate/index");
				}
			} else {
				if($pcate->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "pcate/index");
				} else {
					$this->error("删除失败!", 1, "pcate/index");
				}
			}
		}
		
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			validate::notnull($_POST['content'],"内容不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "pcate/add_show");
			}
		}
	}