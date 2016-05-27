<?php
	class Mail{
		function mail_list(){
			return $this->select();
		}
		function load(){
			return $this->where(array("id"=>1))->find();
		}
		
	}