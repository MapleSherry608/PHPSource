<?php
	class Adv{
		function adv_list(){
			$datas=$this->order("id desc")->select();
			return $datas;
		}
		function add(){
			return $this->insert();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		
		function filter_img($img){
			return  $this->where(array("pic"=>$img))->find();
		}
	}