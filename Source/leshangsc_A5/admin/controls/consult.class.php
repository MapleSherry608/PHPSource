<?php
	class Consult{
		function index(){
			if(!$this->is_cached("main/consult_list",$_SERVER['REQUEST_URI'])){
				$consult=D("Consult");
				$product=D("Product");
				$user=D("User");
				$page=new Page($consult->total(), PAGENUM);
				$datas=$consult->limit($page->limit)->consult_list();
				foreach($datas as $k=>$v){
					$datas[$k]['user']=$user->load($v['uid']);
					$datas[$k]['product']=$product->load($v['pid']);
				}
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/consult_list",$_SERVER['REQUEST_URI']);
		}
		function feedback_index(){
			if(!$this->is_cached("main/consult_feedback",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$consult=D("Consult");
				$product=D("Product");
				$user=D("User");
				$datas=$consult->load($id);
				$datas['product']=$product->load($datas['pid']);
				$datas['user']=$user->load($datas['uid']);
				$this->assign("datas",$datas);
			}
			$this->display("main/consult_feedback",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$id=intval($_POST['id']);
			$consult=D("Consult");
			$_POST['is_reply']=1;
			$_POST['a_time']=time();
			$result=$consult->mod($id);
			if(false !== $result){
				$mailRules=D("MailRules","admin");
				$consult_data=$consult->load($id);
				$template=$mailRules->load_temp("consult_reply");
				if($template['value']){
					$user_data=D("User")->load($consult_data['uid']);
					$datas=array("FromName"=>"管理员","Subject"=>"回复咨询","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
				}
				$this->success("回复成功!", 1, "consult/feedback_index/id/{$id}");
			} else {
				$this->error("回复失败!", 1, "consult/feedback_index/id/{$id}");
			}
		}
		function del(){
			$consult=D("Consult");
			if($_POST['dels']){
				if($consult->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "consult/index");
				} else {
					$this->error("删除失败!", 1, "consult/index");
				}
			} else {
				if($consult->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "consult/index");
				} else {
					$this->error("删除失败!", 1, "consult/index");
				}
			}
		}
		function verify(){
			$id=intval($_GET['id']);
			$consult=D("Consult");
			if($consult->verify($id)){
				$this->clear_cache();
				$this->success("审核通过!", 1);
			} else {
				$this->error("审核失败!", 1);
			}
		}
	}