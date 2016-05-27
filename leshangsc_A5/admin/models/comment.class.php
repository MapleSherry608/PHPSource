<?php
	class Comment{
		function lists(){
			$datas=$this->order("create_time desc")->select();
			return $datas;
		}
	}