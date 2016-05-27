<?php
	class Cache{
		function del(){
			$filename=trim($_GET['file']);
			$path=PROJECT_PATH."public/uploads/".$filename;
			return Simfile::delete($path);
		}
		function dels($id){
			$num=0;
			$n=count($id);
			foreach($id as $v){
				$filename=trim($v);
				$path=PROJECT_PATH."public/uploads/".$filename;
				if(Simfile::delete($path)){
					$num++;
				}
			}
			if($n==$num){
				return true;
			}
			return false;
		}
	}