<?php
	class User{
		function index(){
			if(!$this->is_cached("main/user_list",$_SERVER['REQUEST_URI'])){
				$user=D("User");
				$cate=D("Cate");
				if(!$_GET['key']){
					$page=new Page($user->total(), PAGENUM);
					$datas=$user->limit($page->limit)->user_list();
				} else {
					$get['key']=trim($_GET['key']);
					$page_param="search/1";
					if($get['key']){
						$page_param.="/key/{$get['key']}";
					}
					$page=new Page($user->search_total($_GET), PAGENUM,$page_param);
					$datas=$user->limit($page->limit)->search($_GET);
				}
				$this->assign("is_search",$_GET['key']);
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/user_list",$_SERVER['REQUEST_URI']);
		}
		
		function mod_index(){
			if(!$this->is_cached("main/user_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$user=D("User");
				$data=$user->load($id);
				$this->assign("data",$data);
			}
			$this->display("main/user_mod",$_SERVER['REQUEST_URI']);
		}
		
		function mod(){
		
			$id=intval($_POST['id']);
			$score=intval($_POST['score']);
			$_POST['password']=md5($_POST['password']);
			$user=D("User");
			$group=D("Group");
			$group_data=$group->score_range($score);
		
			$user->update_group($id,$_POST['score'],$group_data['id']);
			
			$result=$user->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "user/index");
			} else {
				$this->error("编辑失败!", 1, "user/index");
			}
		}
		
		function del(){
			$user=D("User");
			if($_POST['dels']){
				if($user->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "user/index");
				} else {
					$this->error("删除失败!", 1, "user/index");
				}
			} else {
				if($user->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "user/index");
				} else {
					$this->error("删除失败!", 1, "user/index");
				}
			}
		}
		
		
	}