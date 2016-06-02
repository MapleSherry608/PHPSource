<?php
	class Admingroup {
		function lists(){
			$datas=$this->select();
			return $datas;
		}
		function add(){
			return $this->insert();
		}
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		
	}