<?php
	class Nav {
		function load($id){
			$datas=$this->where(array("id"=>$id))->select();
			return $datas[0];
		}
		function nav_list(){
			$datas=$this->order("id asc")->select();
			return $datas;
		}
		function main_list($type){
			if($type){
				$datas=$this->where(array("pid"=>0,"type"=>$type,"display"=>1))->order("sort asc")->select();
			} else {
				$datas=$this->where(array("pid"=>0,"display"=>1))->order("sort asc")->select();
			}
			return $datas;
		}
		function sub_one($main_id){
			$datas=$this->where(array("pid"=>$main_id))->order("sort asc")->select();
			return $datas;
		}
		function sub_list($type){
			if($type){
				$datas=$this->where(array("pid <>"=>0,"type"=>$type))->order("sort asc")->select();
			} else {
				$datas=$this->where(array("pid <>"=>0))->order("sort asc")->select();
			}
			return $datas;
		}
		function has_sub($main_id){
			$datas=$this->where(array("pid"=>$main_id))->field("id")->find();
			if($datas['id']){
				return 1;
			} else {
				return 0;
			}
		}
		function is_sub($id){
			$datas=$this->where(array("id"=>$id))->field("pid")->find();
			if($datas['pid']){
				return $datas['pid'];
			} else {
				return 0;
			}
		}
		function nav_type_list($type){
			$datas=$this->where(array("type"=>$type))->order("sort asc")->select();
			return $datas;
		}
		//读出子分类导航
		function sub_lists($pid){
			return $this->where(array("pid"=>$pid))->order("sort asc")->select();
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