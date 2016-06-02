<?php
	class Payment{
		function lists(){
			return $this->order("sort asc")->select();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function uninst($id){
			return $this->where(array("id"=>$id))->update(array("p_install"=>1));
		}
		function inst($id){
			return $this->where(array("id"=>$id))->update(array("p_install"=>2));
		}
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		
	}