<?php
	class Fav{
		function lists($user_id){
			return $this->where(array("user_id"=>$user_id))->select();
		}
		function add(){
			return $this->insert();
		}
		function del($article_id,$user_id){
			return $this->where(array("article_id"=>$article_id,"user_id"=>$user_id))->delete();
		}
		function is_fav($article_id,$user_id){
			return $this->where(array("article_id"=>$article_id,"user_id"=>$user_id))->find();
		}
		function totals($user_id){
			return $this->where(array("user_id"=>$user_id))->total();
		}
	}