<?php
	class Letter{
		function index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("请登陆后查看!", 1);
			}
			if(!$this->is_cached("user/my_letter",$_SERVER['REQUEST_URI'])){
				$letter=D("Letter");
				$user=D("User");
				$page=new Page($letter->totals($_SESSION['user']['id']), PAGENUM);
				$datas=$letter->limit($page->limit)->lists($_SESSION['user']['id']);
				foreach($datas as $k=>$v){
					$datas[$k]['create_time']=time_ago($v['create_time']);
					$user_info=$user->load_user($v['from_id']);
					$datas[$k]['user_info']=$user_info[0];
					$datas[$k]['message']=$this->preg_message($v['message']);
				}
				$letter->readed($_SESSION['user']['id']);
			}
			$this->assign("fpage", $page->fpage());
			$this->assign("datas",$datas);
			$this->display("user/my_letter",$_SERVER['REQUEST_URI']);
		}
		function preg_message($message){
			$pa = '/\[exp\](.*?)\[\/exp\]/';
			preg_match_all($pa,$message,$match);
			foreach($match as $kk=>$vv){
				foreach($vv as $k=>$v){
					if($kk){
						$v=str_replace('&quot;','',$v);
						$exp_num=get_exp_num($v);
						$html="<img src='http://res.mail.qq.com/zh_CN/images/mo/DEFAULT2/".$exp_num.".gif' />";
						$message=str_replace($match[0][$k],$html,$message);
					}
				}
			}
			return $message;
		}
	}