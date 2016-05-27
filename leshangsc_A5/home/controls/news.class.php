<?php
	class News{
		function index(){
			if(!$this->is_cached("news/".$GLOBALS['m_data']['classname'],$_SERVER['REQUEST_URI'])){
				$news=D("News");
				$nav=D("Nav","admin");
				$user=D("User","admin");
				$id=intval($_GET['id']);
				$last_newsdata=$news->news_list($id,1,"id desc");
				foreach($last_newsdata as $k=>$v){
					$cate_info=$nav->load($v['cate']);
					$last_newsdata[$k]['url']=$GLOBALS['app']."news/article/id/".$v['id']."/pid/".$v['m_cate']."/m_id/".$cate_info['module_id'];
					$last_newsdata[$k]['create_time']=time_ago($v['create_time']);
				}
				$news_info=$nav->sub_one($id);
				foreach($news_info as $k=>$v){
					$news_info[$k]['news']=$news->news_list($v['id'],0);
					foreach($news_info[$k]['news'] as $kk=>$vv){
						$news_info[$k]['news'][$kk]['create_time']=time_ago($vv['create_time']);
						$news_info[$k]['news'][$kk]['url']=$GLOBALS['app']."news/article/id/".$vv['id']."/pid/".$vv['m_cate']."/m_id/".$v['module_id'];
						if($vv['user_id']){
							$news_info[$k]['news'][$kk]['user_info']=$user->load($vv['user_id']);
						}
					}
					$news_info[$k]['url']=$GLOBALS['app']."news/lists/id/".$v['id']."/pid/".$v['pid']."/m_id/".$v['module_id'];
				}
				$this->assign("news_info",$news_info);
				$this->assign("last_newsdata",$last_newsdata);
			}
			$this->display("news/".$GLOBALS['m_data']['classname'],$_SERVER['REQUEST_URI']);
		}
		function lists(){
			if(!$this->is_cached("news/".$GLOBALS['m_data']['classname']."_list",$_SERVER['REQUEST_URI'])){
				$news=D("News");
				$nav=D("Nav","admin");
				$user=D("User","admin");
				$comment=D("Comment");
				$id=intval($_GET['id']);
				$pid=intval($_GET['pid']);
				$m_id=intval($_GET['m_id']);
				if($pid){
					$is_main=0;
				} else {
					$is_main=1;
				}
				$page=new Page($news->totals($id,$is_main), PAGENUM,"id/{$id}/pid/{$pid}/m_id/{$m_id}");
				
				$last_newsdata=$news->news_list($pid,1,"id desc");
				foreach($last_newsdata as $k=>$v){
					$cate_info=$nav->load($v['cate']);
					$last_newsdata[$k]['url']=$GLOBALS['app']."news/article/id/".$v['id']."/pid/".$v['m_cate']."/m_id/".$cate_info['module_id'];
					$last_newsdata[$k]['create_time']=time_ago($v['create_time']);
				}
				$news_datas=$news->limit($page->limit)->news_list($id,$is_main);
				foreach($news_datas as $k=>$v){
					if(!$v['user_id']){
						$news_datas[$k]['user_info']=array("id"=>0,"user_name"=>"admin");
					} else {
						$news_datas[$k]['user_info']=$user->load($v['user_id']);
					}
					$cate_info=$nav->load($v['cate']);
					$news_datas[$k]['cate_info']=$cate_info;
					$news_datas[$k]['cate_info']['url']=$GLOBALS['app']."news/index/id/".$cate_info['id']."/pid/".$cate_info['pid']."/m_id/".$cate_info['module_id'];
					$news_datas[$k]['create_time']=time_ago($v['create_time']);
					$nav_data=$nav->load($v['cate']);
					$news_datas[$k]['url']=$GLOBALS['app'].'news/article/id/'.$v['id']."/pid/".$v['m_cate']."/m_id/".$nav_data['module_id'];
					$news_datas[$k]['comment_num']=$comment->num($v['id']);
				}
				$cate_info=$nav->load($id);
				$this->assign("cate_info",$cate_info);
				$this->assign("news_datas",$news_datas);
				$this->assign("last_newsdata",$last_newsdata);
				$this->assign("fpage", $page->fpage(1,2,3,4,5,6));
			}
		$this->display("news/".$GLOBALS['m_data']['classname']."_list",$_SERVER['REQUEST_URI']);
		}
		
		function article(){
			if($GLOBALS['m_data']['auth']!="none"){
				if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
					$this->error("请登陆后访问",3);
				} else {
					$auth_array=unserialize(htmlspecialchars_decode($GLOBALS['m_data']['auth']));
					if(!in_array($_SESSION['user']["group_id"],$auth_array)){
						$this->error("对不起，您无权访问",3);
					}
				}
			}
			if(!$this->is_cached("news/".$GLOBALS['m_data']['classname']."_article",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$news=D("News");
				$comment=D("Comment");
				$user=D("User");
				$fav=D("Fav");
				$data=$news->article($id);
				foreach($data as $k=>$v){
					if(!$v['user_id']){
						$data[$k]['user_info']=array("nickname"=>"admin");
					}
					$data[$k]["comments"]=$comment->lists($v['id']);
					foreach($data[$k]["comments"] as $kk=>$vv){
						$comment_num++;
						$t=$user->load_user($vv['user_id']);
						$data[$k]["comments"][$kk]['user_info']=$t[0];
						$data[$k]['comments'][$kk]['create_time']=time_ago($vv['create_time']);
					}
					$data[$k]['create_time']=time_ago($v['create_time']);
				}
				$current_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$urlToEncode=str_replace("/","|",$current_url);
				$data[0]['content']=htmlspecialchars_decode($data[0]['content']);
				$is_fav=$fav->is_fav($id,$_SESSION['user']['id']);
				$this->assign("comment_num",$comment_num); //评论数量
				$this->assign("data",$data[0]);   //新闻数据
				$this->assign("urlToEncode",$urlToEncode); //二维码地址
				$this->assign("current_url",$current_url); //当前地址
				$this->assign("is_fav",$is_fav); //是否已添加收藏
			}
			$this->display("news/".$GLOBALS['m_data']['classname']."_article",$_SERVER['REQUEST_URI']);
		}
		function qrcode_index(){
			$urlToEncode=trim($_GET['urlToEncode']);
			$this->assign("urlToEncode",$urlToEncode);
			$this->display("public/qrcode");
		}
		function qrcode(){
			$urlToEncode=str_replace("|","/",$_GET['urlToEncode']);
			$a=new QR($urlToEncode);
			header("Content-Type:Image/png");
			echo $a->image(8);
		}
		
		function recommand(){
			$status=intval($_POST['status']);
			$id=intval($_POST['article_id']);
			$news=D("News");
			if($status){
				if($news->add_recommand($id)){
					echo 1;
				} 
			}
			if(!$status){
				if($news->del_recommand($id)){
					echo 1;
				} 
			}
		}
		function publish_index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("news/publish",$_SERVER['REQUEST_URI'])){
				$module=D("module","admin");
				$nav=D("nav","admin");
				$nav_datas=$module->pub_nav_list(2);
				
				foreach($nav_datas as $k=>$v){
					if(!$v['pid']){
						$m_cate=$v['nav_id'];
					}
					$nav_datas[$k]['has_sub']=$nav->has_sub($v['nav_id']);
				}
				$this->assign("m_cate", $m_cate);
				$this->assign("nav_datas", $nav_datas);
			}
			$this->display("news/publish_index",$_SERVER['REQUEST_URI']);
		}
		
		function publish_mod(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("news/publish",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$module=D("module","admin");
				$news=D("News");
				$data=$news->article($id);
			    
				if($data[0]['user_id']!=$_SESSION['user']['id']){
					$this->error("不是本会员发布，不能编辑",1,"user/pub_news");
				}
				
				$nav_datas=$module->pub_nav_list(2);
				foreach($nav_datas as $k=>$v){
					if(!$v['pid']){
						$m_cate=$v['nav_id'];
					}
				}
				$this->assign("data", $data[0]);
				$this->assign("m_cate", $m_cate);
				$this->assign("nav_datas", $nav_datas);
			}
			$this->display("news/publish_mod",$_SERVER['REQUEST_URI']);
		}
		
		
		function search_index(){
			$news=D("News");
			$nav=D("Nav","admin");
			$user=D("User","admin");
			$hotword=D("Hotword");
			if(isset($_GET['keywords'])){
				$keywords=trim(iconv("gbk","utf-8",$_GET['keywords']));
			} else {
				$keywords=trim($_POST['keywords']);
			}
			$hotword->addkeyword($keywords,2);
			$page=new Page($news->search_total($keywords), PAGENUM);
			$news_datas=$news->search_list($keywords);
			foreach($news_datas as $k=>$v){
				if(!$v['user_id']){
					$news_datas[$k]['user_info']=array("user_name"=>"admin");
				} else {
					$news_datas[$k]['user_info']=$user->load($v['user_id']);
				}
				$cate_info=$nav->load($v['cate']);
				$news_datas[$k]['cate_info']=$cate_info;
				$news_datas[$k]['create_time']=time_ago($v['create_time']);
				$nav_data=$nav->load($v['cate']);
				$news_datas[$k]['url']=$GLOBALS['app'].'news/article/id/'.$v['id']."/pid/".$v['m_cate']."/m_id/".$nav_data['module_id'];
				
			}
			$last_newsdata=$news->news_list(0,0,"id desc");
			foreach($last_newsdata as $k=>$v){
				$cate_info=$nav->load($v['cate']);
				$last_newsdata[$k]['url']=$GLOBALS['app']."news/article/id/".$v['id']."/pid/".$v['m_cate']."/m_id/".$cate_info['module_id'];
				$last_newsdata[$k]['create_time']=time_ago($v['create_time']);
			}
			$this->assign("keywords",$keywords);
			$this->assign("last_newsdata",$last_newsdata);
			$this->assign("fpage", $page->fpage());
			$this->assign("news_datas", $news_datas);
			$this->display("news/news_search");
			
		}
		function add(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			$news=D("News");
			$config=D("Config","admin");
			$config_data=$config->config_list();
			$_POST['verify']=$config_data[0]["auto_news_verify"];
			if($config_data[0]["auto_news_verify"]){ //如果默认自动审核，将计算用户积分并累加，计算当前分组等级
				$user=D("User","admin");
				$group=D("Group","admin");
				$user_data=$user->load($_SESSION['user']['id']);
				$score=$user_data['score']+$config_data[0]['pub_news_score'];
				$group_data=$group->score_range($score);
			}
			$_POST['create_time']=time();
			$_POST['update_time']=0;
			$_POST['sort']=0;
			$_POST['recommand']=0;
			$_POST['user_id']=$_SESSION['user']['id'];
			if($_FILES["thumb"]["name"]){
				$_POST["thumb"]=$this->upload();
			}
			$success=$config_data[0]["auto_news_verify"]?"填加成功，已审核通过":"填加成功，请等待审核";
			if($config_data[0]["auto_news_verify"]){
				if($news->add() && $user->update_group($_SESSION['user']['id'],$score,$group_data['id'])){
					$this->success($success, 1, "news/publish_index");
				} else {
					$this->error("填加失败!", 1, "news/publish_index");
				}
			}else {
				if($news->add()){
					$this->success($success, 1, "news/publish_index");
				} else {
					$this->error("填加失败!", 1, "news/publish_index");
				}
			}
		}
		function mod(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			$news=D("News");
			$id=intval($_POST['id']);
			$_POST['update_time']=time();
			if($_FILES["thumb"]["name"]){
				$_POST["thumb"]=$this->upload();
			}
			$result=$news->mod($id);
			if(false !== $result){
				$this->success("编辑成功", 1, "user/index");
			} else {
				$this->error("编辑失败!", 1, "user/index");
			}
		}
		
		function del(){
			$news=D("News");
			$id=intval($_GET['id']);
			if($news->delete($id)){
				$this->success("删除成功!", 1);
			} else {
				$this->error("删除失败!", 1);
			}
		}
		function upload(){
			$up = new FileUpload(); //可以通过参数指定上传位置，可通过set()方法
			$up->set('allowType', array('gif','jpg','png','jpeg','pjpeg'));
			if($up->upload("thumb")) { //pic 为上传表单的名称
				return $up->getFileName(); //返回上传后的文件名
			}else{
			//如果上传失败提示出错原因
				$this->error($up->getErrorMsg(), 5, 'news/index');
			}
		}
	}