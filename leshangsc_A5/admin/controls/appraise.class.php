<?php
	class Appraise{
		function index(){
			if(!$this->is_cached("main/appraise_list",$_SERVER['REQUEST_URI'])){
				$appraise=D("appraise");
				$product=D("Product");
				$user=D("User");
				$page=new Page($appraise->total(), PAGENUM);
				$datas=$appraise->limit($page->limit)->appraise_list();
				foreach($datas as $k=>$v){
					$datas[$k]['user']=$user->load($v['uid']);
					$datas[$k]['product']=$product->load($v['pid']);
				}
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/appraise_list",$_SERVER['REQUEST_URI']);
		}
		function feedback_index(){
			if(!$this->is_cached("main/appraise_feedback",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$appraise=D("appraise");
				$product=D("Product");
				$user=D("User");
				$datas=$appraise->load($id);
				$datas['product']=$product->load($datas['pid']);
				$datas['user']=$user->load($datas['uid']);
				$this->assign("datas",$datas);
			}
			$this->display("main/appraise_feedback",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$id=intval($_POST['id']);
			$appraise=D("appraise");
			$_POST['reply_time']=time();
			$result=$appraise->mod($id);
			if(false !== $result){
				$mailRules=D("MailRules","admin");
				$appraise_data=$appraise->load($id);
				$template=$mailRules->load_temp("appraise_reply");
				if($template['value']){
					$user_data=D("User")->load($appraise_data['uid']);
					$datas=array("FromName"=>"管理员","Subject"=>"回复评价","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
				}
				
				$this->success("回复成功!", 1, "appraise/feedback_index/id/{$id}");
			} else {
				$this->error("回复失败!", 1, "appraise/feedback_index/id/{$id}");
			}
		}
		function del(){
			$appraise=D("appraise");
			if($_POST['dels']){
				if($appraise->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "appraise/index");
				} else {
					$this->error("删除失败!", 1, "appraise/index");
				}
			} else {
				if($appraise->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "appraise/index");
				} else {
					$this->error("删除失败!", 1, "appraise/index");
				}
			}
		}
	}