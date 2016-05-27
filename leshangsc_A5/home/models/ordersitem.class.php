<?php
	class Ordersitem {
		function add($post){
			return $this->insert($post);
		}
		function load($pid,$uid){
			return $this->where(array("pid"=>$pid,"uid"=>$uid))->select();
		}
	}