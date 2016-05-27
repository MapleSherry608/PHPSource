<?php
	class Mails{
		function mail_list(){
			return $this->select();
		}
		function load(){
			return $this->where(array("id"=>1))->find();
		}
		function send_mail($datas){
			require_once(PROJECT_PATH.'phpmailer/class.phpmailer.php');
			
			
			
			$datas['body']=$this->replace_body($datas['body']);
			$getmailServer=$this->load();
			$m= new PHPmailer();
			$m->SetLanguage("zh_cn");
    		$m->CharSet ="utf-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    		$m->IsSMTP(); // 设定使用SMTP服务
   			$m->SMTPDebug  = 0;                     // 启用SMTP调试功能
                                           // 1 = errors and messages
                               // 2 = messages only
   			$m->SMTPAuth   = true;              // 启用 SMTP 验证功能
  			//$m->SMTPSecure = "ssl";                 // 安全协议
   			$m->Host       = $getmailServer['mail_host'];      // SMTP 服务器
   			$m->Port       = intval($getmailServer['port']);                   // SMTP服务器的端口号
   			$m->Username   = $getmailServer['user_name'];  // SMTP服务器用户名
   			$m->Password   = $getmailServer['password'];             // SMTP服务器密码
			$m->From = $getmailServer['user_name']; //邮件发送者em地址 　　
			$m->FromName = $datas['FromName']; 
    		$m->Subject    = $datas['Subject'];
    		$m->Body    =  $datas['Body']; 
			$m->Encoding = "base64";
			$m->AltBody ="text/html";
    		$m->AddAddress($datas['address'],"");
			
			$m->IsHTML(true);
    		if(!$m->Send()) {
       			return false;
    		} else {
       			return true;
        	}
		}
		function replace_body($body){
			$config=D("Config","admin");
			$config_data=$config->config_list();
			$label=array("{sitename}"=>$config_data[0]['site_name'],"{domain}"=>$_SERVER['SERVER_NAME'],"{username}"=>$_SESSION['user']['user_name'],"{password}"=>$_SESSION['user']['password'],"{score}"=>$_SESSION['user']['score'],"{address}"=>$_SESSION['user']['address']);
			foreach($label as $k=>$v){
				$body=str_replace($k,$v,$body);
			}
			return $body;
		}
	}