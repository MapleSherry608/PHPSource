<?php
	class Link{
		function lists(){
			return $this->order("sort asc")->select();
		}
		function add(){
			return $this->insert();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function filter_img($img){
			return  $this->where(array("img"=>$img))->find();
		}
	}