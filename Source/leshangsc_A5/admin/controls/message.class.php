<?php
	class Message{
		function index(){
			if(!$this->is_cached("main/message_list",$_SERVER['REQUEST_URI'])){
				$message=D("Message");
				$page=new Page($message->total(), PAGENUM);
				$datas=$message->limit($page->limit)->message_list();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/message_list");
		}
		function message_show(){
			if(!$this->is_cached("main/message_show",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$message=D("Message");
				$datas=$message->select($id);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/message_show",$_SERVER['REQUEST_URI']);
		}
		
		function del(){
			$message=D("Message");
			if($_POST['dels']){
				if($message->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "message/index");
				} else {
					$this->error("删除失败!", 1, "message/index");
				}
			} else {
				if($message->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "message/index");
				} else {
					$this->error("删除失败!", 1, "message/index");
				}
			}
		}
	}