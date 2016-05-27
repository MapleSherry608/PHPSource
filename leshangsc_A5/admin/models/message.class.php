<?php
	class Message{
		function message_list(){
			$datas=$this->order("id desc")->select();
			return $datas;
		}
	}