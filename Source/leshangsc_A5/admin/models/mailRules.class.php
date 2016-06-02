<?php
	class MailRules{
		function lists(){
			return $this->select();
		}
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function load_temp($code){
			return $this->where(array("code"=>$code,"value"=>1))->find();
		}
	}