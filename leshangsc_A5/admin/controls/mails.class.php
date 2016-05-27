<?php
	class Mails{
		function index(){
			if(!$this->is_cached("main/mail",$_SERVER['REQUEST_URI'])){
				$datas=D("Mails")->mail_list();
				$this->assign("datas",$datas[0]);
				
			}
			$this->display("main/mail",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$result=D("Mails")->where(array("id"=>1))->update();
			if(false !== $result){
				$this->clear_cache();
				$this->success("编辑成功!", 1, "mails/index");
			} else {
				$this->error("编辑失败!", 1, "mails/index");
			}
		}
	 function test(){
			$email=trim($_POST['email']);
			
			if(!preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)){
				$this->error("邮箱格式不正确!", 1, "mails/index");
			}
			$datas['FromName']="管理员";
			$datas['Subject']="乐尚商城测试邮件";
			$datas['Body']="您好，这是一封测试邮件，我们的官方网站 <a href='http://www.leesuntech.com'>http://www.leesuntech.com</a>";
			$datas['address']=$email;
			
			
			if(D("Mails")->send_mail($datas)){
				
				$this->success("发送测试邮件成功!", 1);
			} else {
				$this->error("发送测试邮件失败!", 1);
			}
		}
		
		
		
		
		
	}