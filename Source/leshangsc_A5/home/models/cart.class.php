<?php
	class Cart{
		function add(){
			return $this->insert();
		}
		function cart_list($uid){
			return $this->where(array("uid"=>$uid))->select();
		}
		function cart_num($uid){
			return $this->where(array("uid"=>$uid))->total();
		}
		function is_cart($pid,$uid){
			return $this->where(array("pid"=>$pid,"uid"=>$uid))->find();
		}
		function del($id,$uid){
			return $this->where(array("id"=>$id,"uid"=>$uid))->delete();
		}
		
	}