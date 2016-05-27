<?php
	class Message{
		function add(){
			return $this->insert($_POST,1,1);
		}
	}