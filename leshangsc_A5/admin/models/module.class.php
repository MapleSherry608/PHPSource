<?php
	class Module {
		function lists(){
			$datas=$this->order("id asc")->select();
			return $datas;
		}
		function pub_nav_list($type){
			return $this->field("id")->where(array("is_user_pub"=>1,"type"=>$type))->r_select(array("nav","id,name,pid","module_id"));
		}

		function load($id){
			$datas=$this->where(array("id"=>$id))->select();
			return $datas[0];
		}
		function add(){
			return $this->insert();
		}
		
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		
	}