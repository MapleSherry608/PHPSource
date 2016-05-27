<?php
	class Survey{
		function load($id){
			$data=$this->where(array("id"=>$id))->select();
			return $data[0];
		}
		function lists(){
			return $this->select();
		}
		function mod($id,$result){
			return $this->where(array("id"=>$id))->update(array("result"=>$result));
		}
	}