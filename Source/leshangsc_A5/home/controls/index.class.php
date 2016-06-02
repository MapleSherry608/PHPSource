<?php
	class Index {
		function index(){
			
			$config=D("Config","admin");
			$con_datas=$config->config_list();
			if($con_datas[0]['closed']){
				$this->assign("site_close_html",htmlspecialchars_decode($con_datas[0]['site_close_html']));
				$tpl="public/closed";
			} else {
				$tpl="index/index";
			}
			
			if(!$this->is_cached($tpl,$_SERVER['REQUEST_URI'])){
				$this->clear_cache();
				$appraise=D("Appraise");
				$product=D("Product");
				$user=D("User");
				
				$appraise_list=$appraise->lists();

				foreach($appraise_list as $k=>$v){
					$t=$user->load_user($v['uid']);
					$appraise_list[$k]['user']=$t[0];
					$appraise_list[$k]['pro']=$product->load($v['pid']);
					$appraise_list[$k]['content_time']=time_ago($v['content_time']);
				}
				$this->assign("appraise_list",$appraise_list);
			}
			
			$this->display($tpl,$_SERVER['REQUEST_URI']);
		}
		
	}