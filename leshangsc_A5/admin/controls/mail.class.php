<?php
	class Mail{
		function index(){
			if(!$this->is_cached("main/mail",$_SERVER['REQUEST_URI'])){
				$datas=D("Mail")->mail_list();
				$this->assign("datas",$datas[0]);
				
			}
			$this->display("main/mail",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$result=D("Mail")->where(array("id"=>1))->update();
			if(false !== $result){
				$this->clear_cache();
				$this->success("编辑成功!", 1, "mail/index");
			} else {
				$this->error("编辑失败!", 1, "mail/index");
			}
		}
	 function test(){
			$email=trim($_POST['email']);
			if(!preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)){
				$this->error("邮箱格式不正确!", 1, "mail/index");
			}
			$datas['FromName']="管理员";
			$datas['Subject']="乐尚商城测试邮件";
			$datas['Body']="您好，这是一封测试邮件，我们的官方网站 <a href='http://www.leesuntech.com'>http://www.leesuntech.com</a>";
			$datas['address']=$email;
			$datas['is_html']=true;
			
			
			if($this->send_mail($datas)){
				$this->success("发送测试邮件成功!", 1, "mail/index");
			} else {
				$this->error("发送测试邮件失败!", 10, "mail/index");
			}
		}
		function send_mail($datas){
			require_once(PROJECT_PATH.'phpmailer/class.phpmailer.php');
			$m=D("Mail");
			$getMailServer=$m->load();
			$mail= new PHPMailer();
			$mail->SetLanguage("zh_cn");
    		$mail->CharSet ="utf-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    		$mail->IsSMTP(); // 设定使用SMTP服务
   			$mail->SMTPDebug  = 0;                     // 启用SMTP调试功能
                                           // 1 = errors and messages
                               // 2 = messages only
   			$mail->SMTPAuth   = true;              // 启用 SMTP 验证功能
  			//$mail->SMTPSecure = "ssl";                 // 安全协议
   			$mail->Host       = $getMailServer['mail_host'];      // SMTP 服务器
   			$mail->Port       = intval($getMailServer['port']);                   // SMTP服务器的端口号
   			$mail->Username   = $getMailServer['user_name'];  // SMTP服务器用户名
   			$mail->Password   = $getMailServer['password'];             // SMTP服务器密码
			$mail->From = $getMailServer['user_name']; //邮件发送者email地址 　　
			$mail->FromName = $datas['FromName']; 
    		$mail->Subject    = $datas['Subject'];
    		$mail->Body    =  $datas['Body']; 
			$mail->Encoding = "base64";
			$mail->AltBody ="text/html";
    		$mail->AddAddress($datas['address'],"");
			
			$mail->IsHTML(true);
    		if(!$mail->Send()) {
				P($mail->ErrorInfo);
       			return false;
    		} else {
       			return true;
        	}
		}
		
	}