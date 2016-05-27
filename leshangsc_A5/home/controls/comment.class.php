<?php
	class Comment{
		function add_comment(){
			$comment=D("Comment");
			$nav=D("nav","admin");
			$article_id=intval($_POST['article_id']);
			$pid=intval($_POST['pid']);
			$nav_data=$nav->load(intval($_POST['cate']));
			$_POST['user_id']=$_SESSION['user']['id'];
			$_POST['top']=0;
			$_POST['create_time']=time();
			if($comment->add()){
				$this->success("评论成功", 1, "news/article/id/".$article_id."/pid/".$pid."/m_id/".$nav_data['module_id']);
			} else {
				$this->error("评论失败", 1, "news/article/id/".$article_id."/pid/".$pid."/m_id/".$nav_data['module_id']);
			}
		}
		
		function top(){
			$status=intval($_POST['status']);
			$id=intval($_POST['id']);
			$comment=D("Comment");
			if($status){
				if($comment->add_top($id)){
					echo 1;
				} 
			}
			if(!$status){
				if($comment->del_top($id)){
					echo 1;
				} 
			}
		}
	}