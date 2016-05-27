<?php
	class Comment{
		function add(){
			return $this->insert();
		}
		function lists($id){
			return $this->where(array("article_id"=>$id))->select();
		}
		function add_top($id){
			return $this->where(array("id"=>$id))->update("top=top+1");
		}
		function del_top($id){
			return $this->where(array("id"=>$id))->update("top=top-1");
		}
		function num($article_id){
			return $this->where(array("article_id"=>$article_id))->total();
		}
		function my_lists($uid){
			return $this->order("id desc")->where(array("user_id"=>$uid))->select();
		}
		function totals($uid){
			return $this->order("id desc")->where(array("user_id"=>$uid))->total();
		}
	}