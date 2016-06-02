<?php
	class Hotword{
		function index(){
			if(!$this->is_cached("main/hotword_list",$_SERVER['REQUEST_URI'])){
				$hotword=D("hotword");
				$page=new Page($hotword->total(), PAGENUM);
				$datas=$hotword->limit($page->limit)->lists();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/hotword_list",$_SERVER['REQUEST_URI']);
		}
		
		
		function mod_index(){
			if(!$this->is_cached("main/hotword_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$hotword=D("hotword");
				$datas=$hotword->select($id);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/hotword_mod",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$hotword=D("hotword");
			$result=$hotword->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "hotword/index");
			} else {
				$this->error("编辑失败!", 1, "hotword/mod_index/id/{$id}");
			}
		}
		function del(){
			$hotword=D("hotword");
			if($_POST['dels']){
				if($hotword->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "hotword/index");
				} else {
					$this->error("删除失败!", 1, "hotword/index");
				}
			} else {
				if($hotword->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "hotword/index");
				} else {
					$this->error("删除失败!", 1, "hotword/index");
				}
			}
		}
		
		private function validate(){
			validate::notnull($_POST['keyword'],"关键字不能为空");
			validate::notnull($_POST['times'],"次数不能为空");
			validate::number($_POST['times'],"次数必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "hotword/add_index");
			}
		}
	}