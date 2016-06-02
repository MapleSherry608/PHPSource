<?php
	class Appraisetimes {
		function load($pid,$uid){
			return $this->where(array("pid"=>$pid,"uid"=>$uid))->find();
		}
		function add($post){
			return $this->insert($post);
		}
		function mod($id){
			return $this->query("update ".TABPREFIX."appraisetimes set times=times+1 where id='{$id}'");
		}
		function mod_subtraction($id){
			return $this->query("update ".TABPREFIX."appraisetimes set times=times-1 where id='{$id}'");
		}
	}