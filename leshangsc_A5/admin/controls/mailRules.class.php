<?php
	class MailRules{
		
		function index(){
			if(!$this->is_cached("main/mail_rules",$_SERVER['REQUEST_URI'])){
				$datas=D("MailRules")->lists();
				$this->assign("datas",$datas);
			}
			$this->display("main/mail_rules",$_SERVER['REQUEST_URI']);
			
		}
		function mod(){
			$num=count($_POST);
			$res_num=0;
			$mailRules=D("MailRules");
			foreach($_POST as $k=>$v){
				$res=$mailRules->where(array("code"=>trim($k)))->update(array("value"=>$v));
				if($res!==false){
					$res_num++;
				}
			}
			if($num==$res_num){
				$this->success("编辑成功!", 1);
			} else {
				$this->error("编辑失败!", 1);
			}
		}
		
		function mod_temp_index(){
			if(!$this->is_cached("main/temp_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$mailRules=D("MailRules");
				$label=array("{sitename}"=>"网站名称","{domain}"=>"网站域名","{username}"=>"用户名","{password}"=>"用户密码","{score}"=>"用户积分","{address}"=>"用户地址");
				$data=$mailRules->load($id);
				$this->assign("data",$data);
				$this->assign("label",$label);
			}
			$this->display("main/temp_mod",$_SERVER['REQUEST_URI']);
		}
		
		function mod_temp(){
			$mailRules=D("MailRules");
			$id=intval($_POST['id']);
			$result=$mailRules->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1);
			} else {
				$this->error("编辑失败!", 1);
			}
		}
		
	}