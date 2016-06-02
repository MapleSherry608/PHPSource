<?php
	class Survey{
		function lists(){
			$datas=$this->select();
			return $datas;
		}
		function add(){
			return $this->insert();
		}
		
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
	}