<?php
	class Profav{
		function lists($user_id){
			return $this->where(array("user_id"=>$user_id))->select();
		}
		function add(){
			return $this->insert();
		}
		function del($pid,$user_id){
			return $this->where(array("pid"=>$pid,"user_id"=>$user_id))->delete();
		}
		function is_fav($pid,$user_id){
			return $this->where(array("pid"=>$pid,"user_id"=>$user_id))->find();
		}
		function totals($user_id){
			return $this->where(array("user_id"=>$user_id))->total();
		}
	}