<?php
	class News{
		function index(){
			if(!$this->is_cached("main/news_list",$_SERVER['REQUEST_URI'])){
				$news=D("News");
				$nav=D("Nav");
				$id=intval($_GET['id']);
				$page=new Page($news->totals($id), PAGENUM);
				$datas=$news->limit($page->limit)->news_list($id);
				if($id){
					$current_name=$nav->load($id);
				} else {
					$current_name['name']="全部新闻";
				}
				$main=$nav->main_list(2);
				foreach($main as $k=>$v){
					$has_sub=$nav->has_sub($v['id']);
					$main[$k]['has_sub']=$has_sub;
				}
				$sub=$nav->sub_list(2);
				$this->assign("main", $main);
				$this->assign("sub", $sub);
				$this->assign("datas",$datas);
				$this->assign("current_name",$current_name['name']);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/news_list",$_SERVER['REQUEST_URI']);
		}
		function publish_index(){
			if(!$this->is_cached("main/publish_list",$_SERVER['REQUEST_URI'])){
				$news=D("News");
				$nav=D("Nav");
				$module=D("module");
				$user=D("User");
				$id=intval($_GET['id']);
				$page=new Page($news->totals_u($id), PAGENUM);
				$datas=$news->limit($page->limit)->news_list_u($id);
				foreach($datas as $k=>$v){
					$user_data=$user->load($v['user_id']);
					$datas[$k]['user_info']=$user_data;
				}
				if($id){
					$current_name=$nav->load($id);
				} else {
					$current_name['name']="全部新闻";
				}
				$nav_datas=$module->pub_nav_list();
				$this->assign("nav_datas", $nav_datas);
				$this->assign("datas",$datas);
				$this->assign("current_name",$current_name['name']);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/publish_nlist",$_SERVER['REQUEST_URI']);
		}
		
		function verify_new(){
			$id=intval($_GET['id']);
			$user_id=intval($_GET['user_id']);
			$news=D("News");
			$user=D("User");
			$group=D("Group");
			$config_data=D("config")->config_list();
			$user_data=$user->load($user_id);
			$score=$user_data['score']+$config_data[0]['pub_news_score'];
			$group_data=$group->score_range($score);
			if($news->verify($id) && $user->update_group($user_id,$score,$group_data['id'])){
				
				$mailRules=D("MailRules","admin");
				$template=$mailRules->load_temp("publish_news");
				if($template['value']){
					$user_data=D("User")->load($user_id);
					$datas=array("FromName"=>"管理员","Subject"=>"发布新闻审核通过","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
				}
				
				$this->success("审核通过!", 1, "news/publish_index");
			} else {
				$this->error("审核失败!", 1, "news/publish_index");
			}
		}
		
		function add_index(){
			if(!$this->is_cached("main/news_add",$_SERVER['REQUEST_URI'])){
				$nav=D("Nav");
				$main=$nav->main_list(2);
				foreach($main as $k=>$v){
					$has_sub=$nav->has_sub($v['id']);
					$main[$k]['has_sub']=$has_sub;
				}
				$sub=$nav->sub_list(2);
				$this->assign("main", $main);
				$this->assign("sub", $sub);
			}
			$this->display("main/news_add",$_SERVER['REQUEST_URI']);
		}
		function add(){
			$this->validate();
			$news=D("News");
			$nav=D("Nav");
			$m_cate=$nav->load(intval($_POST['cate']));
			$post['title']=trim($_POST['title']);
			$post['cate']=intval($_POST['cate']);
			$post['m_cate']=$m_cate['pid'];
			$post['description']=trim($_POST['description']);
			$post['content']=trim($_POST['content']);
			if($_FILES["thumb"]["name"]){
				$post["thumb"]=$this->upload();
			} 
			$post['verify']=1;
			$post['user_id']=0;
			$post['sort']=intval($_POST['sort']);
			$post['create_time']=time();
			$post['update_time']=0;
			$post['recommand']=0;
			if($news->add($post)){
				$this->clear_cache();
				$this->success("填加成功!", 1, "news/index");
			} else {
				$this->error("填加失败!", 1, "news/add_index");
			}
		}
		
		function mod_index(){
			if(!$this->is_cached("main/news_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$news=D("News");
				$nav=D("Nav");
				$main=$nav->main_list(2);
				foreach($main as $k=>$v){
					$has_sub=$nav->has_sub($v['id']);
					$main[$k]['has_sub']=$has_sub;
				}
				$sub=D("Nav")->sub_list(2);
				$datas=$news->select($id);
				$this->assign("main", $main);
				$this->assign("sub", $sub);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/news_mod",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$news=D("News");
			$nav=D("Nav");
			$m_cate=$nav->load(intval($_POST['cate']));
			$_POST['m_cate']=$m_cate['pid'];
			$_POST['update_time']=time();
			if($_FILES["thumb"]["name"]){
				$_POST["thumb"]=$this->upload();
			}
			$result=$news->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "news/index");
			} else {
				$this->error("编辑失败!", 1, "news/mod_index/id/{$id}");
			}
		}
		
		function publish(){
			$news=D("News");
			$user=D("user");
			$page=new Page($news->total(), PAGENUM);
			$datas=$news->limit($page->limit)->news_list();
			foreach($datas as $key=>$var){
				$datas[$key]['user_info']=$user->load($var['user_id']);
			}
			$this->assign("datas",$datas);
			$this->assign("fpage", $page->fpage());
			$this->display("main/publish");
		}
		
		function del(){
			$news=D("News");
			if($_POST['dels']){
				if($news->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "news/index");
				} else {
					$this->error("删除失败!", 1, "news/index");
				}
			} else {
				if($news->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "news/index");
				} else {
					$this->error("删除失败!", 1, "news/index");
				}
			}
		}
		
		function del_publish(){
			$news=D("News");
			if($_POST['dels']){
				if($news->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "news/publish_index");
				} else {
					$this->error("删除失败!", 1, "news/publish_index");
				}
			} else {
				if($news->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "news/publish_index");
				} else {
					$this->error("删除失败!", 1, "news/publish_index");
				}
			}
		}
		
		function verify(){
			$id=intval($_GET['id']);
			$news=D("News");
			if($news->verify($id)){
				$this->clear_cache();
				$this->success("审核通过!", 1, "news/publish");
			} else {
				$this->error("审核失败!", 1, "news/publish");
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
		
		private function validate(){
			validate::notnull($_POST['title'],"标题不能为空");
			validate::notnull($_POST['content'],"内容不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "news/add_index");
			}
		}
	}