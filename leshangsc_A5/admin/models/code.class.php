<?php
	class Code{
		
		function html_dir(){
			$dir=array("index"=>"首页模板","news"=>"新闻模板","product"=>"商品模板","public"=>"公共模板","user"=>"会员模板");
			return $dir;
		}
		function res_dir(){
			$dir=array("css"=>"样式目录","js"=>"JS目录","tpl"=>"模版目录");
			return $dir;
		}
		function html_list($template,$current_dir="tpl"){
			$datas=array();
			if($current_dir=="tpl"){
				$dir=$this->html_dir();
				foreach($dir as $k=>$v){
					$filelist=Simfile::getSubFile(PROJECT_PATH."home/views/".$template."/".$k);
					foreach($filelist as $key=>$var){
						$datas[$k][$key]['file']=basename($var);
						$datas[$k][$key]['size']=tosize(filesize($var));
						$datas[$k][$key]['time']=date("Y-m-d H:i:s",filemtime($var));
					}
				}
			}else{
				$filelist=Simfile::getSubFile(PROJECT_PATH."home/views/".$template."/resource/".$current_dir);
				foreach($filelist as $key=>$var){
					$datas[$key]['file']=basename($var);
					$datas[$key]['size']=tosize(filesize($var));
					$datas[$key]['time']=date("Y-m-d H:i:s",filemtime($var));
				}
			}
			
			return $datas;
		}
		
		function totals($template,$current_dir="tpl"){
			$num=0;
			if($current_dir=="tpl"){
				$dir=$this->html_dir();
				foreach($dir as $k=>$v){
					$num+=count(Simfile::getSubFile(PROJECT_PATH."home/views/".$template."/".$k));
				}
			} else {
				$num+=count(Simfile::getSubFile(PROJECT_PATH."home/views/".$template."/resource/".$current_dir));
			}
			return $num;
		}
	}