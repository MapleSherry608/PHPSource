<?php
	class Appraise{
		function publish(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("请登陆后访问!", 1);
			}
			if(!$this->is_cached("user/publish_appraise",$_SERVER['REQUEST_URI'])){
				
				$this->assign("uid",intval($_GET['uid']));
				$this->assign("pid",intval($_GET['pid']));
			}
			$this->display("user/publish_appraise",$_SERVER['REQUEST_URI']);
		}
		
		function publish_mod(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("请登陆后访问!", 1);
			}
			if(!$this->is_cached("user/mod_publish_appraise",$_SERVER['REQUEST_URI'])){
				
				$config=D("Config","admin");
				$appraise=D("Appraise");
				$mod_appraise=$config->config_list();
				$this->assign("mod_appraise",$mod_appraise[0]['mod_appraise']);
				$this->assign("data",$appraise->load(intval($_GET['id'])));
				$this->assign("id",intval($_GET['id']));
			}
			$this->display("user/mod_publish_appraise",$_SERVER['REQUEST_URI']);
		}
		
		function ajax_check_bought(){
			
			$at=D("appraisetimes","admin");
			$pid=$_GET['id'];
			$uid=$_SESSION['user']['id'];
			
			$t=$at->load($pid,$uid);
			
			if($t['times']>0){
				exit("true");
			} else {
				exit("false");
			}
			
			
		}
		function mod(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("请登陆后访问!", 1);
			}
			$id=intval($_POST['id']);
			$config=D("Config","admin");
			$appraise=D("Appraise");
			$appraise_info=$appraise->load($id);
			$mod_appraise=$config->config_list();
			
			if($appraise_info['mod_appraise_num']>$mod_appraise[0]['mod_appraise']){
				$this->error("您已超出修改评价次数!", 3);
			}
			
			
			
			$post['level']=intval($_POST['level']);
			$post['content']=trim($_POST['content']);
			$post['content_time']=time();
			$result=$appraise->mod($id,$post);
			
			if(false !== $result && $appraise->add_appraise_num($id)){
				
				$this->success("修改评价成功", 1);
			} else {
				echo $_SESSION['user']['id'];
				$this->error("修改评价失败!", 1);
			}
		}
		function add(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("请登陆后访问!", 1);
			}
			$at=D("appraisetimes","admin");
			$appraise=D("Appraise");
			$pid=intval($_POST['pid']);
			$uid=intval($_POST['uid']);
			$t=$at->load($pid,$uid);
			
			if($t['times']<=0){
				$this->error("已评价!", 1);
			} elseif(!$t) {
				$this->error("未购买!", 1);
			}
			$post['uid']=intval($_POST['uid']);
			$post['pid']=intval($_POST['pid']);
			$post['level']=intval($_POST['level']);
			$post['content']=trim($_POST['content']);
			$post['content_time']=time();
			$post['reply_time']="";
			$post['mod_appraise_num']=0;
			if($appraise->add($post)){
				$at->mod_subtraction($t['id']);
				$this->success("评价成功", 1);
			} else {
				$this->error("评价失败!", 1);
			}
		}
	}