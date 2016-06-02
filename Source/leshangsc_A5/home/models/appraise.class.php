<?php
	class Appraise{
		function add($post){
			return $this->insert($post);
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update($post);
		}
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		function lists(){
			return $this->order("id desc")->select();
		}
		function add_appraise_num($id){
			return $this->query("update ".TABPREFIX."appraise set mod_appraise_num=mod_appraise_num+1 where id='{$id}'");
		}

		function my_lists($uid){
			return $this->order("id desc")->where(array("uid"=>$uid))->select();
		}
		function totals($uid){
			return $this->order("id desc")->where(array("uid"=>$uid))->total();
		}
	}