<?php
	class Adv{
		function load($id){
			return $this->where(array("cate"=>$id))->order("sort asc")->select();
		}
		function lists(){
			return $this->select();
		}
	}