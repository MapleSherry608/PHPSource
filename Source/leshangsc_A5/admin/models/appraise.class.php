<?php
	class Appraise{
		function appraise_list(){
			$datas=$this->order("id desc")->select();
			return $datas;
		}
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function load_pid($pid){
			return $this->where(array("pid"=>$pid))->order("id desc")->select();
		}
		function level_num($pid,$level){
			return $this->where(array("pid"=>$pid,"level"=>$level))->total();
		}
	}