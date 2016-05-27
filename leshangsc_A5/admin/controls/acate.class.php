<?php
	class Acate{
		function index(){
			if(!$this->is_cached("main/acate_list",$_SERVER['REQUEST_URI'])){
				$acate=D("Acate");
				$page=new Page($acate->total(), PAGENUM);
				$datas=$acate->limit($page->limit)->cate_list();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/acate_list");
		}
		function add_show(){
			$this->display("main/acate_add");
		}
		function edit_show(){
			if(!$this->is_cached("main/acate_edit",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$acate=D("acate");
				$datas=$acate->select($id);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/acate_edit",$_SERVER['REQUEST_URI']);
		}
		
		function add(){
			$this->validate();
			$acate=D("acate");
			$result=$acate->add();
			if(false !== $result){
				$this->clear_cache();
				$this->success("填加成功!", 1);
			} else {
				$this->error("填加失败!", 1);
			}
		}
		function edit(){
			$this->validate();
			$id=intval($_POST['id']);
			$acate=D("acate");
			$result=$acate->edit($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "acate/edit_show/id/{$id}");
			} else {
				$this->error("编辑失败!", 1, "acate/edit_show/id/{$id}");
			}
		}
		
		function del(){
			$acate=D("acate");
			if($_POST['dels']){
				if($acate->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "acate/index");
				} else {
					$this->error("删除失败!", 1, "acate/index");
				}
			} else {
				if($acate->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "acate/index");
				} else {
					$this->error("删除失败!", 1, "acate/index");
				}
			}
		}
		
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "acate/add_show");
			}
		}
	}