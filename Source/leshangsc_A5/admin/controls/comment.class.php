<?php
	class Comment{
		function index(){
			if(!$this->is_cached("main/comment_list",$_SERVER['REQUEST_URI'])){
				$comment=D("Comment");
				$user=D("User");
				$page=new Page($comment->total(), PAGENUM);
				$datas=$comment->limit($page->limit)->lists();
				foreach($datas as $k=>$v){
					$user_info=$user->load($v['user_id']);
					$datas[$k]['user_info']=$user_info;
				}
				
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/comment_list",$_SERVER['REQUEST_URI']);
		}
		function del(){
			$comment=D("Comment");
			if($_POST['dels']){
				if($comment->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "comment/index");
				} else {
					$this->error("删除失败!", 1, "comment/index");
				}
			} else {
				if($comment->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "comment/index");
				} else {
					$this->error("删除失败!", 1, "comment/index");
				}
			}
		}
	}