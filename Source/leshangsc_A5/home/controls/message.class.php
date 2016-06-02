<?php
	class Message{
		function index(){
			$this->display("index/message");
		}
		function add(){
			$message=D("Message");
			$_POST['create_time']=time();
			if($message->add()){
				$this->success("填加成功!", 1);
			} else {
				$this->error($message->getMsg(), 1);
			}
		}
	}