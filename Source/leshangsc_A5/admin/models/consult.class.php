<?php
	class Consult{
		function consult_list(){
			$datas=$this->order("id desc")->select();
			return $datas;
		}
		function load($id){
			$datas=$this->where(array("id"=>$id))->find();
			return $datas;
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function verify($id){
			return $this->where(array("id"=>$id))->update("verify=1");
		}
	}