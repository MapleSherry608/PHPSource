<?php
	class Consult{
		function question(){
			$post['uid']=intval($_POST['user_id']);
			$post['pid']=intval($_POST['pid']);
			$post['question']=trim($_POST['question']);
			$post['answer']="";
			$post['q_time']=time();
			$post['a_time']="";
			$post['is_reply']=0;
			$post['verify']=0;
			$consult=D("consult");
			
			if($consult->add($post)){
				echo 1;
			} else{
				echo 0;
			}
		}
		
	}