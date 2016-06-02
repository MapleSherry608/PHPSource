<?php
	class adminnode{
		function lists(){
			$datas=$this->select();
			return $datas;
		}
		function get($m,$a){
			$datas=$this->where(array("model"=>$m,"action"=>$a))->select();
			return $datas[0]['id'];
		}
	}