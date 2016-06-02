<?php
	class Admin{
		function admin_list(){
			return $this->select();
		}
		function load($id){
			return $this->where(array("id"=>$id))->select();
		}
		function find_admin($name,$pass){
			return $this->where(array("adm_name"=>$name,"adm_password"=>$pass))->find();
		}
		function add(){
			return $this->insert();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function set_default($id){
			$cancel=$this->where(array("is_default"=>1))->update("is_default=0");
			return $this->where(array("id"=>$id))->update("is_default=1");
		}
		
	}