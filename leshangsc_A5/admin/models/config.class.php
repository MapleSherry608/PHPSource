<?php
	class Config{
		function config_list(){
			return $this->select();
		}
		function write_to_file(){
			$filename=PROJECT_PATH."temp.inc.php";
			$content=$this->config_list();
			
			$t='<?php $temp = '. var_export($content[0],true).';?>';
			return file_put_contents($filename, $t);
		}
		function filter_img($img){
			return  $this->where(array("logo"=>$img))->find();
		}
		function filter_ico($img){
			return  $this->where(array("favicon"=>$img))->find();
		}
	}