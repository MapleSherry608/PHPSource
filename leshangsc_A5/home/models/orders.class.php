<?php
	class orders{
		function add($post){
			return $this->insert($post);
		}
		function load($id,$uid){
			return $this->where(array("id"=>$id,"uid"=>$uid))->find();
		}
		function load_sn($sn){
			return $this->where(array("sn"=>$sn))->find();
		}
		function order_paid($sn){
			return $this->where(array("sn"=>$sn))->update(array("pay_status"=>1,"pay_time"=>time()));
		}
		function orders_list($uid){
			$datas=$this->order("id desc")->where(array("uid"=>$uid))->select();
			return $datas;
		}
		
		function totals($uid){
			return $this->order("id desc")->where(array("uid"=>$uid))->total();
		}
	}