<?php
	class Consult{
		function add($post){
			return $this->insert($post);
		}
		function lists($pid){
			return $this->where(array("verify"=>1,"pid"=>$pid))->order("id desc")->select();
		}
		function my_lists($uid){
			return $this->order("id desc")->where(array("uid"=>$uid))->select();
		}
		function totals($uid){
			return $this->order("id desc")->where(array("uid"=>$uid))->total();
		}
	}