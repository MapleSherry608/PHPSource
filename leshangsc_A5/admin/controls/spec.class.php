<?php
	class Spec{
		function index(){
			if(!$this->is_cached("main/spec_list",$_SERVER['REQUEST_URI'])){
				$spec=D("Spec");
				$page=new Page($spec->totals(), PAGENUM);
				$datas=$spec->limit($page->limit)->main_list();
				foreach($datas as $k=>$v){
					$datas[$k]['sub']=$spec->sub_one($v['id']);
				}
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/spec_list",$_SERVER['REQUEST_URI']);
		}
		function add_show(){
			$spec=D("Spec");
			$datas=$spec->main_list();
			$this->assign("datas",$datas);
			$this->display("main/spec_add");
		}
		function mod_show(){
			if(!$this->is_cached("main/spec_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$spec=D("Spec");
				$spec_list=$spec->main_list();
				$datas=$spec->select($id);
				$this->assign("spec_list",$spec_list);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/spec_mod",$_SERVER['REQUEST_URI']);
		}
		
		function add(){
			$this->validate();
			$spec=D("Spec");
			if($spec->add()){
				$this->clear_cache();
				$this->success("填加成功!", 1, "spec/index");
			} else {
				$this->error("填加失败!", 1, "spec/add_show");
			}
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$spec=D("Spec");
			$result=$spec->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "spec/mod_show/id/{$id}");
			} else {
				
				$this->error("编辑失败!", 1, "spec/mod_show/id/{$id}");
			}
		}
		
		function del(){
			$spec=D("Spec");
			if($_POST['dels']){
				if($spec->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "spec/index");
				} else {
					$this->error("删除失败!", 1, "spec/index");
				}
			} else {
				if($spec->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "spec/index");
				} else {
					$this->error("删除失败!", 1, "spec/index");
				}
			}
		}
		
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "spec/add_show");
			}
		}
	}