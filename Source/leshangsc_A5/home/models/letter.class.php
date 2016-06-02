<?php
	class Letter{
		function lists($user_id){
			return $this->where(array("user_id"=>$user_id))->order("create_time desc")->select();
		}
		function add(){
			return $this->insert();
		}
		function totals($user_id){
			return $this->where(array("user_id"=>$user_id))->total();
		}
		function new_num($user_id){
			return $this->where(array("user_id"=>$user_id,"is_new"=>1))->total();
		}
		function readed($user_id){
			$this->where(array("user_id"=>$user_id))->update("is_new=0");
		}
	}