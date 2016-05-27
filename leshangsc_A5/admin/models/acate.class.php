<?php
	class Acate {
		function load($id){
			$datas=$this->where(array("id"=>$id))->select();
			return $datas[0];
		}
		function cate_list(){
			$datas=$this->order("id asc")->select();
			return $datas;
		}
		function add(){
			return $this->insert();
		}
		
		function edit($id){
			return $this->where(array("id"=>$id))->update();
		}
		
	}