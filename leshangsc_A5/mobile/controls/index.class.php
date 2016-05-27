<?php
	class Index {
		function index(){
			if(!$this->is_cached("index/index",$_SERVER['REQUEST_URI'])){
				
				
			}
			
			$this->display("index/index",$_SERVER['REQUEST_URI']);
		}		
	}