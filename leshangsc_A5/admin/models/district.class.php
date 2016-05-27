<?php
	class District {
		function district_lists(){
			$datas=$this->where(array("pid"=>0))->select();
			return $datas;
		}
		function sdistrict_lists(){
			$datas=$this->where(array("pid <>"=>0))->select();
			return $datas;
		}
		function load($pid){
			$datas=$this->where(array("pid"=>$pid))->select();
			return $datas;
		}
	}