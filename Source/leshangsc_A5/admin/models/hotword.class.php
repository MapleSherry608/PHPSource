<?php
	class Hotword{
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		function lists(){
			return $this->order("times desc")->select();
		}
		
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}

	}