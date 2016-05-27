<?php
	class Express{
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		function lists(){
			return $this->order("sort asc")->select();
		}
		function add(){
			return $this->insert();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}

	}