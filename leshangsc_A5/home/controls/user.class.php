<?php
	class User{
		function index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/user_center",$_SERVER['REQUEST_URI'].$_SESSION['user']['id'])){
				$user=D("User");
				$nav=D("Nav","admin");
				$news=D("News");
				$friend=D("Friend");
				$product=D("Product");
				$profav=D("profav");
				$letter=D("Letter");
				$pub_datas=$user->load_pub_info($_SESSION['user']['id']);
				$pub_pro_datas=$user->load_pub_pro($_SESSION['user']['id']);
				$all_news=$news->news_list(0,0);
				foreach($pub_datas as $key=>$var){
					foreach($var['news_info'] as $k=>$v){
						$pub_datas[$key]['news_info'][$k]['create_time']=time_ago($v['create_time']);
						$nav_data=$nav->load($v['cate']);
						$pub_datas[$key]['news_info'][$k]['url']=$GLOBALS['app'].'news/article/id/'.$v['id']."/pid/".$v['m_cate']."/m_id/".$nav_data['module_id'];
					}
					foreach($var['comment_info'] as $k=>$v){
						$pub_datas[$key]['comment_info'][$k]['create_time']=time_ago($v['create_time']);
						
					}
				}
				foreach($pub_pro_datas as $key=>$var){
					foreach($var['pro_info'] as $k=>$v){
						$pub_pro_datas[$key]['pro_info'][$k]['add_time']=time_ago($v['add_time']);
						$nav_data=$nav->load($v['cate_id']);
						$pub_pro_datas[$key]['pro_info'][$k]['url']=$GLOBALS['app']."product/show/id/".$v['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
					}
				}
				//类似商品start
				$profav_data=$profav->lists($_SESSION['user']['id']);
				foreach($profav_data as $k=>$v){
					$d=$product->load($v['pid']);
					$cate_ids[]=$d['cate_id'];
				}
				$cate_ids=array_flip(array_flip($cate_ids)); 
				foreach($cate_ids as $k=>$v){
					$p=$product->load_cate($v);
					foreach($p as $sk=>$sv){
						$nav_data=$nav->load($sv['cate_id']);
						$sv['url']=$GLOBALS['app']."product/show/id/".$sv['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
						$like_data[]=$sv;
					}
				}
				shuffle($like_data);
				$this->assign("like_data",$like_data);
				//类似商品end
				$news_num=$news->news_num($_SESSION['user']['id']);
				$product_num=$product->product_num($_SESSION['user']['id']);
				$no_verify_num=$news_num+$product_num;
				$user_info=$user->load_user($_SESSION['user']['id']);
				$this->assign("user_info",$user_info[0]);
				$this->assign("all_news",$all_news);
				$this->assign("letter_num",$letter->new_num($_SESSION['user']['id']));
				$this->assign("friend_num",$friend->message_num($_SESSION['user']['id']));
				$this->assign("no_verify_num",$no_verify_num);
				$this->assign("news_datas",$pub_datas[0]['news_info']);
				$this->assign("pro_datas",$pub_pro_datas[0]['pro_info']);
				$this->assign("comment_datas",$pub_datas[0]['comment_info']);

			}
			$this->display("user/user_center",$_SERVER['REQUEST_URI'].$_SESSION['user']['id']);
		}
		function reg_index(){
			$this->display("user/regist");
		}
		function log_index(){
			$this->display("user/login");
		}
		function mod_index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(intval($_GET['window'])){
				$page="user/mod_window";
			} else {
				$page="user/mod_index";
			}
			if(!$this->is_cached($page,$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$user=D("User");
				$datas=$user->select($id);
				$this->assign("datas",$datas[0]);
			}
			$this->display($page,$_SERVER['REQUEST_URI']);
		}
		function mod_logo_index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			$id=intval($_GET['id']);
			$datas=D("User")->load_user($id);
			$this->assign("id", $id);
			$this->assign("photo", $datas[0]['photo']);
			$this->display("user/mod_logo");
		}
		//我发布的商品
		function pub_product(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/pub_product",$_SERVER['REQUEST_URI'])){
				$product=D("Product");
				$nav=D("Nav","admin");
				$module=D("module","admin");
				$id=intval($_GET['id']);
				$user_id=intval($_SESSION['user']['id']);
				if($id){
					$current_name=$nav->load($id);
				} else {
					$current_name['name']="全部商品";
				}
				$nav_datas=$module->pub_nav_list(1);
				foreach($nav_datas as $k=>$v){
					if(!$v['pid']){
						$m_cate=$v['nav_id'];
					}
					$nav_datas[$k]['has_sub']=$nav->has_sub($v['nav_id']);
				}
				
				$page=new Page($product->totals_u($id), PAGENUM);
				$datas=$product->limit($page->limit)->list_u($id,$user_id);
				foreach($datas as $k=>$v){
					$nav_data=$nav->load($v['cate_id']);
					$datas[$k]['url']=$GLOBALS['app']."product/show/id/".$v['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
				}
				$this->assign("nav_datas", $nav_datas);
				$this->assign("datas",$datas);
				$this->assign("current_name",$current_name['name']);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("user/pub_product",$_SERVER['REQUEST_URI']);
		}
		//我的订单
		function my_orders(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/my_orders",$_SERVER['REQUEST_URI'])){
				$orders=D("Orders");
				$product=D("Product","admin");
				$uid=$_SESSION['user']['id'];
				$oi=D("Ordersitem","admin");
				$spec=D("Spec","admin");
				$page=new Page($orders->totals($uid), PAGENUM);
				$datas=$orders->limit($page->limit)->orders_list($uid);
				foreach($datas as $k=>$v){
					$items=$oi->load($v['id']);
					$datas[$k]['create_time']=date("Y-m-d H:i:s",$v['create_time']);
					foreach($items as $ik=>$iv){
						$datas[$k]['product'][$ik]=$product->load($iv['pid']);
						$datas[$k]['product'][$ik]['amount']=$iv['amount'];
						$datas[$k]['product'][$ik]['price']=$iv['price'];
						$specs=explode(",",$iv['specs']);
						foreach($specs as $sk=>$sv){
							$datas[$k]['product'][$ik]['specs_cn'][$sv]=$spec->field('name')->load($sv);
						}
					}
					$datas[$k]['pay_price']=$v['total_price']+$v['delivery_fee'];
				}
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("user/my_orders",$_SERVER['REQUEST_URI']);
		}
		function order_detail(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/order_show",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$uid=$_SESSION['user']['id'];
				$orders=D("Orders");
				$payment=D("Payment");
				$express=D("Express","admin");
				$data=$orders->load($id,$uid);
				$data['status_cn']='';//订单状态
				$html='';
				
				$config=$payment->load($data['payment_id']);
				$payLinks=$payment->getAlipayCode($data,$config);
				$express_data=$express->load($data['express_id']);
				$data['express_cn']=$express_data['name'];
				switch($data['order_status']){
					case 5://未结单，正在交易状态
						if($data['pay_status']==1){
							if($data['delivery_status']==0){
								
								$html.="<span style='color:red'>未发货</span>";
								
							}elseif ($data['delivery_status']==1){
								$html.="<span style='color:green'>已发货</span>";
							}else{
								$data['status_cn']="<span style='color:red'>已收货</span>";
							}
							
						} else{
							
							if($data['payment_id']==4){
								if($data['delivery_status']==0){
									$html.="<span style='color:red'>未发货</span>";
								}elseif ($data['delivery_status']==1){
									$html.="<span style='color:green'>已发货</span>";
								}else{
									$data['status_cn']="<span style='color:red'>已收货</span>";
								}
							} else {
								$html.="<span style='color:red'>未付款</span>";
								$html.="<input type='button' url='".$payLinks."' id='pay' value='付款'>";
							}
						}
					break;
					case 0:
						$html.="<span style='color:red'>交易失败</span>&nbsp;&nbsp;";
						$html.="关闭原因:".$data['reason'];
					break;
					case 1:
						$html.="<span style='color:green'>交易成功</span>";
					break;
					case 2:
						$data['status_cn']="<span style='color:green'>已退款</span>";
					break;
					case 3:
						$data['status_cn']="<span style='color:green'>已退货</span>";
					break;
					case 4:
						$data['status_cn']="<span style='color:green'>已退款，已退货</span>";
					break;
				}
				if($data['create_time']){
					$data['create_time']=date("Y-m-d H:i:s",$data['create_time']);
				}
				if($data['pay_time']){
					$data['pay_time']=date("Y-m-d H:i:s",$data['pay_time']);
				}
				if($data['delivery_time']){
					$data['delivery_time']=date("Y-m-d H:i:s",$data['delivery_time']);
				}
				if($data['order_time']){
					$data['order_time']=date("Y-m-d H:i:s",$data['order_time']);
				}
				$data['pay_price']=$data['total_price']+$data['delivery_fee'];
				$data['status_cn']=$html;
				$this->assign("data",$data);
				
			}
			$this->display("user/order_show",$_SERVER['REQUEST_URI']);
		}
		//我的评价
		function my_appraise(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/my_appraise",$_SERVER['REQUEST_URI'])){
				$uid=$_SESSION['user']['id'];
				$appraise=D("Appraise");
				$product=D("Product","admin");
				$nav=D("Nav","admin");
				$page=new Page($appraise->totals($uid), PAGENUM);
				$datas=$appraise->limit($page->limit)->my_lists($uid);
				foreach($datas as $k=>$v){
					$datas[$k]['content_time']=date("Y-m-d H:i:s",$v['content_time']);
					$datas[$k]['product']=$product->load($v['pid']);
					$datas[$k]['url']=$GLOBALS['app']."product/show/id/".$v['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
				}
				$this->assign("fpage", $page->fpage());
				$this->assign("datas",$datas);
			}
			$this->display("user/my_appraise",$_SERVER['REQUEST_URI']);
		}
		//我的咨询
		function my_consult(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/my_consult",$_SERVER['REQUEST_URI'])){
				$uid=$_SESSION['user']['id'];
				$consult=D("Consult");
				$product=D("Product","admin");
				$page=new Page($consult->totals($uid), PAGENUM);
				$datas=$consult->limit($page->limit)->my_lists($uid);
				foreach($datas as $k=>$v){
					if($datas[$k]['a_time']){
						$datas[$k]['a_time']=date("Y-m-d H:i:s",$v['a_time']);
					}
					$datas[$k]['q_time']=date("Y-m-d H:i:s",$v['q_time']);
					$datas[$k]['product']=$product->load($v['pid']);
				}
				$this->assign("fpage", $page->fpage());
				$this->assign("datas",$datas);
			}
			$this->display("user/my_consult",$_SERVER['REQUEST_URI']);
		}
		function del_consult(){
			$consult=D("Consult");
			if($_GET['id']){
				if($consult->delete($_GET['id'])){
					$this->success("删除成功!", 1);
				} else {
					$this->error("删除失败!", 1);
				}
			} 
		}
		//我的收藏
		function my_fav_pro(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/my_favourite",$_SERVER['REQUEST_URI'])){
				$uid=$_SESSION['user']['id'];
				$profav=D("Profav");
				$product=D("Product","admin");
				$nav=D("Nav","admin");
				$page=new Page($profav->totals($uid), PAGENUM);
				$datas=$profav->limit($page->limit)->lists($uid);
				foreach($datas as $k=>$v){
					$datas[$k]['product']=$product->load($v['pid']);
					$nav_data=$nav->load($datas[$k]['product']['cate_id']);
					$datas[$k]['url']=$GLOBALS['app']."product/show/id/".$v['pid']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
				}
				$this->assign("fpage", $page->fpage());
				$this->assign("datas",$datas);
			}
			$this->display("user/my_favourite",$_SERVER['REQUEST_URI']);
		}
		
		function del_fav_pro(){
			$profav=D("Profav");
			if($_GET['id']){
				if($profav->delete($_GET['id'])){
					$this->success("删除成功!", 1);
				} else {
					$this->error("删除失败!", 1);
				}
			} 
		}
		
		//我发布的资讯
		function pub_news(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/pub_news",$_SERVER['REQUEST_URI'])){
				$news=D("News");
				$nav=D("Nav","admin");
				$module=D("module","admin");
				$id=intval($_GET['id']);
				if($id){
					$current_name=$nav->load($id);
				} else {
					$current_name['name']="全部新闻";
				}
				$nav_datas=$module->pub_nav_list(2);
				foreach($nav_datas as $k=>$v){
					if(!$v['pid']){
						$m_cate=$v['nav_id'];
					}
					$nav_datas[$k]['has_sub']=$nav->has_sub($v['nav_id']);
				}
				$page=new Page($news->totals_u($id), PAGENUM);
				$datas=$news->limit($page->limit)->list_u($id);
				foreach($datas as $k=>$v){
					$nav_data=$nav->load($v['cate']);
					$datas[$k]['create_time']=date("Y-m-d H:i:s",$v['create_time']);
					$datas[$k]['url']=$GLOBALS['app']."news/article/id/".$v['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
				}
				$this->assign("nav_datas", $nav_datas);
				$this->assign("datas",$datas);
				$this->assign("current_name",$current_name['name']);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("user/pub_news",$_SERVER['REQUEST_URI']);
		}
		
		//我的评论
		function my_comment(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/my_comment",$_SERVER['REQUEST_URI'])){
				$uid=$_SESSION['user']['id'];
				$comment=D("Comment");
				$news=D("News");
				$nav=D("Nav","admin");
				$page=new Page($comment->totals($uid), PAGENUM);
				$datas=$comment->limit($page->limit)->my_lists($uid);
				foreach($datas as $k=>$v){
					$news_info=$news->load($v['article_id']);
					$nav_data=$nav->load($news_info['cate']);
					$datas[$k]['create_time']=date("Y-m-d H:i:s",$v['create_time']);
					$datas[$k]['url']=$GLOBALS['app']."news/article/id/".$v['article_id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
				}
				$this->assign("fpage", $page->fpage());
				$this->assign("datas",$datas);
			}
			$this->display("user/my_comment",$_SERVER['REQUEST_URI']);
		}
		
		function del_comment(){
			$comment=D("Comment");
			if($_GET['id']){
				if($comment->delete($_GET['id'])){
					$this->success("删除成功!", 1);
				} else {
					$this->error("删除失败!", 1);
				}
			} 
		}
		
		
		//我的新闻收藏
		function my_fav_news(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("user/my_favourite_news",$_SERVER['REQUEST_URI'])){
				$uid=$_SESSION['user']['id'];
				$fav=D("Fav");
				$news=D("News");
				$nav=D("Nav","admin");
				$page=new Page($fav->totals($uid), PAGENUM);
				$datas=$fav->limit($page->limit)->lists($uid);
				foreach($datas as $k=>$v){
					$news_info=$news->load($v['article_id']);
					$nav_data=$nav->load($news_info['cate']);
					$datas[$k]['title']=$news_info['title'];
					$datas[$k]['url']=$GLOBALS['app']."news/article/id/".$v['article_id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
				}
				$this->assign("fpage", $page->fpage());
				$this->assign("datas",$datas);
			}
			$this->display("user/my_favourite_news",$_SERVER['REQUEST_URI']);
		}
		
		function del_fav_news(){
			$fav=D("Fav");
			if($_GET['id']){
				if($fav->delete($_GET['id'])){
					$this->success("删除成功!", 1);
				} else {
					$this->error("删除失败!", 1);
				}
			} 
		}
		
		
		
		function letter_index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->error("未登陆，不能发私信!", 1);
			} else {
				$id=intval($_GET['id']);
				$expression=expression();
				$this->assign("expression", $expression['html']);
				$this->assign("id", $id);
				$this->display("user/letter");
			}
		}
		function send_letter(){
			$letter=D("Letter");
			$friend=D("Friend");
			$_POST['from_id']=$_SESSION['user']['id'];
			if($_POST['from_id']==$_POST['user_id']){
				$this->error("对不起，不能自己给自己发私信!", 1);
			} elseif(!$_POST['user_id']){
				$this->error("不能给管理员发私信!", 10);
			} elseif(!$friend->check($_POST['user_id'],$_POST['from_id'],1)){
				$this->error("对不起,还不是好友,不能发私信!", 1);
			}else {
				$_POST['is_new']=1;
				$_POST['create_time']=time();
				$result=$letter->add();
				if(false !== $result){
					$this->success("发送成功!", 1);
				} else {
					$this->error("发送失败!", 1);
				}
			}
		}
		function mod_logo(){
			$id=intval($_GET['id']);
			$src = $_SERVER['DOCUMENT_ROOT'].$_GET['src'];
			$path=$_GET['src'];
			$crop = new Crop();
			$crop->initialize($src, $src, $_GET['x'], $_GET['y'], 161, 161, $_GET['w'], $_GET['h']);
			$success = $crop->generate_shot();
			$msg = $success ? '编辑成功' : '编辑失败';
			echo JSON(array('result' => $success, 'msg' => $msg));
		}
		function mod(){
			$id=intval($_POST['id']);
			$user=D("User");
			if($_FILES["photo"]["tmp_name"]){
				$_POST["photo"]=$this->upload();
			}
			if($_POST['password'] && $_POST['confirm_pass']){
				$_POST['password']=md5($_POST['password']);
				$_POST['confirm_pass']=md5($_POST['confirm_pass']);
			}
			$result=$user->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "user/index");
			} else {
				$this->error($user->getMsg(), 1, "user/index");
			}
		}
		function reg(){
			$user=D("User");
			$group=D("Group","admin");
			
			if($_FILES["photo"]["tmp_name"]){
				$_POST["photo"]=$this->upload();
			}
			$password=trim($_POST['password']);
			$_POST['password']=md5($_POST['password']);
			$_POST['confirm_pass']=md5($_POST['confirm_pass']);
			$_POST['ran_code']=rand(10000,99999);
			$group_data=$group->load_default();
			$_POST['score']=$group_data['score'];
			$_POST['group_id']=$group_data['id'];
			$_POST['reg_time']=time();
			$_POST['log_time']="";
			$result=$user->add();
			if(false !== $result){
				//自动登陆
				
				$_SESSION['user']=$user->where(array("id"=>$result))->find();
				$_SESSION['user']['password']=$password;
				$_SESSION['user']["isLogin"]=true;
				
				$mailRules=D("MailRules","admin");
				$template=$mailRules->load_temp("set_reg");
				if($template['value']){
					$datas=array("FromName"=>"管理员","Subject"=>"注册成功","Body"=>$template['template'],"address"=>trim($_POST['email']));
					
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
					
				}
				
				$user->where(array("id"=>$result))->update(array("log_time"=>time()));
				$this->success("注册成功!", 1, "user/index");
			} else {
				$this->error($user->getMsg(), 1, "index/index");
			}
		}
		function login(){
			$user_name=trim($_POST['user_name']);
			$password=trim($_POST['password']);
			$user_u=D('User')->where(array("user_name"=>$user_name,"password"=>md5($password)))->find();
			$user_e=D('User')->where(array("email"=>$user_name,"password"=>md5($password)))->find();
			
			if($user_u || $user_e){
				if($user_u){
					$_SESSION['user']=$user_u;
					D("User")->where(array("id"=>$user_u['id']))->update(array("log_time"=>time()));
				}
				if($user_e){
					$_SESSION['user']=$user_e;
					D("User")->where(array("id"=>$user_e['id']))->update(array("log_time"=>time()));
				}
				$_SESSION['user']["isLogin"]=true;
				$this->success("登陆成功",1,"user/index");
			} else {
				$this->error("用户或密码错误!",1,"index/index");
			}
		}
		
		
		
		function get_pass_index(){
			$this->display("user/get_pass");
		}
		function get_pass(){
			$email=trim($_POST['email']);
			$user=D('User')->where(array("email"=>$email))->find();
			if($user){
				$mail=D("Mail","admin");
				
				$link="http://".$_SERVER['SERVER_NAME'].$GLOBALS["url"]."set_pass_index/id/{$user['id']}/ran_code/".$user['ran_code'];
				$body="请点击以下链接找回密码!".$link;
				
				$datas=array("FromName"=>"管理员","Subject"=>"找回密码邮件","Body"=>$body,"address"=>$email,"is_html"=>true);
				if($mail->send_mail($datas)){
					$this->success("发送成功,请查看您的邮箱",3);
				} else{
					$this->error("发送失败",1);
				}
			} else {
				$this->error("无此邮箱，请重新填写!",1);
			}
		}
		function set_pass_index(){
			$id=intval($_GET['id']);
			$ran_code=trim($_GET['ran_code']);
			$user=D('User');
			$user_info=$user->field("ran_code,id")->where(array("id"=>$id))->find();
			if($ran_code==$user_info['ran_code'] && $user_info){
				
				$this->assign("id",$user_info['id']);
				$this->assign("ran_code",$user_info['ran_code']);
				$this->display("user/set_pass");
			}else{
				$this->error("用户信息不正确!",1,"user/set_pass_index");
			}
		}
		function set_pass(){
			$id=intval($_POST['id']);
			$ran_code=intval($_POST['ran_code']);
			$password=trim($_POST['password']);
			$confirm_pass=trim($_POST['confirm_pass']);
			$user=D('User');
			if($password==$confirm_pass){
				$_POST['password']=md5($password);
				$result=$user->where(array("id"=>$id))->update();
				if($result){
					$this->success("密码修改成功",3,"index/index");
				} else {
					$this->error("密码修改失败!",3,"user/set_pass_index/id/".$id."/ran_code/".$ran_code);
				}
			} else {
				$this->error("两次密码输入不一致",3,"user/set_pass_index/id/".$id."/ran_code/".$ran_code);
			}
		}
		
		
		
		
		function logout(){
			$username=$_SESSION['user']["user_name"];
			$_SESSION['user']=array();
			$_SESSION['user']["isLogin"]=false;
			//session_destroy();
			unset($_SESSION['user']);
			$this->success("再见{$username}!", 1, "index/index");
		}
		
		private function upload(){
			$up = new FileUpload(); //可以通过参数指定上传位置，可通过set()方法
			$up->set('allowType', array('gif','jpg','png','jpeg','pjpeg'));
			if($up->upload("photo")) { //pic 为上传表单的名称
				return $up->getFileName(); //返回上传后的文件名
			}else{
			//如果上传失败提示出错原因
				$this->error($up->getErrorMsg(), 5, 'user/index');
			}
		}
	}