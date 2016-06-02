<?php
	class Profav{
		function index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("请登陆后访问!", 1);
			}
			if(!$this->is_cached("user/fav_list")){
				$fav=D("Profav");
				$news=D("News");
				$nav=D("Nav","admin");
				$page=new Page($fav->totals($_SESSION['user']['id']), PAGENUM);
				$fav_datas=$fav->limit($page->limit)->lists($_SESSION['user']['id']);
				foreach($fav_datas as $k=>$v){
					$news_data=$news->load($v['article_id']);
					foreach($news_data as $kk=>$vv){
						$nav_data=$nav->load($vv['cate']);
						$fav_datas[$k]['module_id']=$nav_data['module_id'];
						$fav_datas[$k]['url']=$GLOBALS["app"]."news/article/id/".$v['article_id']."/pid/".$vv['m_cate']."/m_id/".$nav_data['module_id'];
					}
					$fav_datas[$k]['news_info']=$news_data[0];
					
				}
				$this->assign("fav_datas",$fav_datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("user/fav_list");
		}
		function add_fav(){
			$fav=D("Profav");
			$is_fav=$fav->is_fav(intval($_POST['pid']),intval($_POST['user_id']));
			if($is_fav){
				echo 2;
			} else {
				if($id=$fav->add()){
					echo 1;
				} 
			}
		}
		
		function del_fav(){
			$fav=D("Profav");
			$pid=intval($_POST['pid']);
			$user_id=intval($_POST['user_id']);
			if($fav->del($pid,$user_id)){
				echo 1;
			} 
		}
	}