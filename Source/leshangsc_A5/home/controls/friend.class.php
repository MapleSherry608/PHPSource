<?php
	class Friend{
		function index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("未登陆，不能加好友!", 1);
			} else {
				$friend_id=intval($_GET['friend_id']);
				$expression=expression();
				$this->assign("expression", $expression['html']);
				$this->assign("friend_id", $friend_id);
				$this->display("user/friend");
			}
		}
		function my_friend(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("请登陆后访问!", 1);
			}
			if(!$this->is_cached("user/my_friend",$_SERVER['REQUEST_URI'])){
				$friend=D("Friend");
				$user=D("User");
				$user_id=$_SESSION['user']['id'];
				$page=new Page($friend->friend_num($_SESSION['user']['id']), PAGENUM);
				$friend_datas=$friend->limit($page->limit)->friends($user_id);
				foreach($friend_datas as $k=>$v){
					if($user_id==$v['user_id']){
						$f_id=$v['friend_id'];
					}
					if($user_id==$v['friend_id']){
						$f_id=$v['user_id'];
					}
					$user_data=$user->load_user($f_id);
					$friend_datas[$k]["friend_info"]=$user_data[0];
				}
				$this->assign("fpage", $page->fpage());
				$this->assign("friend_datas", $friend_datas);
			}
			$this->display("user/my_friend",$_SERVER['REQUEST_URI']);
		}
		
		function my_message(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("请登陆后访问!", 1);
			}
			if(!$this->is_cached("user/my_message",$_SERVER['REQUEST_URI'])){
				$friend=D("Friend");
				$user=D("User");
				$user_id=$_SESSION['user']['id'];
				$page=new Page($friend->message_num($_SESSION['user']['id']), PAGENUM);
				$addfriend_datas=$friend->limit($page->limit)->lists($user_id);
				foreach($addfriend_datas as $k=>$v){
					$user_data=$user->load_user($v['user_id']);
					$addfriend_datas[$k]["friend_info"]=$user_data[0];
				}
				$this->assign("fpage", $page->fpage());
				$this->assign("addfriend_datas", $addfriend_datas);
			}
			$this->display("user/my_message",$_SERVER['REQUEST_URI']);
		}
		
		function verify(){
			$id=intval($_GET['id']);
			$friend=D("Friend");
			$result=$friend->verify($id);
			if(false !== $result){
				$this->success("已加为好友!", 1);
			} else {
				$this->error("加好友失败!", 1);
			}
		}
		function del(){
			$friend=D("Friend");
			if($friend->delete(intval($_GET['id']))){
				$this->success("删除成功!", 1);
			} else {
				$this->error("删除失败!", 1);
			}
		}
		function send_friend(){
			$friend=D("Friend");
			$_POST['user_id']=$_SESSION['user']['id'];
			if($friend->check($_POST['user_id'],$_POST['friend_id'],1)){
				$this->error("已是好友!", 1);
			}
			if($friend->check($_POST['friend_id'],$_POST['user_id'])){
				$this->error("对不起，已发送过添加请求!", 1);
			}
			if($_POST['friend_id']==$_POST['user_id']){
				$this->error("对不起，不能加自己为好友!", 1);
			} elseif(!$_POST['friend_id']){
				
				$this->error("不能加管理员为好友!", 1);
			} else {
				$_POST['verify']=0;
				$result=$friend->add();
				if(false !== $result){
					$this->success("发送成功,请等待用户同意!", 2);
				} else {
					$this->error("发送失败!", 1);
				}
			}
		}
	}