<?php
	class AdminClass{
		function lists(){
			$datas=$this->select();
			return $datas;
		}
	}