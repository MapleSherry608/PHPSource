<?php
	class Spec {
		function lists(){
			$datas=$this->order("sort asc")->select();
			return $datas;
		}
		function main_list(){
			$datas=$this->where(array("pid"=>0))->order("sort asc")->select();
			return $datas;
		}
		function sub_list(){
			$datas=$this->where(array("pid <>"=>0))->order("sort asc")->select();
			return $datas;
		}
		function sub_one($main_id){
			$datas=$this->where(array("pid"=>$main_id))->order("sort asc")->select();
			return $datas;
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
		function totals(){
			return $this->where(array("pid"=>0))->total();
		}
	}