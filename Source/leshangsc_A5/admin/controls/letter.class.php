<?php
	class Letter{
		function index(){
			if(!$this->is_cached("main/letter_list",$_SERVER['REQUEST_URI'])){
				$letter=D("Letter");
				$user=D("User");
				$page=new Page($letter->total(), PAGENUM);
				$datas=$letter->limit($page->limit)->letter_list();
				foreach($datas as $k=>$v){
					$datas[$k]['from_data']=$user->load($v['from_id']);
					$datas[$k]['user_data']=$user->load($v['user_id']);
				}
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/letter_list",$_SERVER['REQUEST_URI']);
		}
		function del(){
			$letter=D("Letter");
			if($_POST['dels']){
				if($letter->delete($_POST['id'])){
					$this->success("删除成功!", 1, "letter/index");
				} else {
					$this->error("删除失败!", 1, "letter/index");
				}
			} else {
				if($letter->delete($_GET['id'])){
					$this->success("删除成功!", 1, "letter/index");
				} else {
					$this->error("删除失败!", 1, "letter/index");
				}
			}
		}
	}