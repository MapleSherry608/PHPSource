<?php
	class Common extends Action {
		function init(){
			
			$m_id=intval($_GET['m_id']);
			$module=D("Module","admin");
			$GLOBALS['m_data']=$module->load($m_id);
			$pcate_datas=D("Pcate","admin")->cate_list();
			$config=D("Config","admin");
			$nav=D("Nav");
			$product=D("Product");
			$user=D("User","admin");
			
			$main_nav=$nav->main_list();
			$sub_nav=$nav->sub_list();
			$pro_main_nav=$nav->main_list(1);
			$con_datas=$config->config_list();
			
			$cur_id=intval($_GET['id']);
			$pid=intval($_GET['pid']);
			foreach($main_nav as $k=>$v){
				if(!$pid){
					if($v['id']==$cur_id){
						$main_nav[$k]['selected']=1;
					} else {
						$main_nav[$k]['selected']=0;
					}
				} else {
					if($v['id']==$pid){
						$main_nav[$k]['selected']=1;
					} else {
						$main_nav[$k]['selected']=0;
					}
				}
				if($v['type']==1){
					$class="product";
				} elseif($v['type']==2){
					$class="news";
				}
				$is_sub=$nav->has_sub($v['id']);
				if(!$is_sub){
					$page_type="lists";
				} else {
					$page_type="index";
				}
				$main_nav[$k]['url']=$GLOBALS['app'].$class."/".$page_type."/id/".$v['id']."/pid/".$v['pid']."/m_id/".$v['module_id'];
			}
			foreach($sub_nav as $k=>$v){
				if($v['type']==1){
					$class="product";
				} elseif($v['type']==2){
					$class="news";
				}
				$sub_nav[$k]['url']=$GLOBALS['app'].$class."/index/id/".$v['id']."/pid/".$v['pid']."/m_id/".$v['module_id'];
			}
			foreach($pro_main_nav as $k=>$v){
				if($v['type']==1){
					$class="product";
				} elseif($v['type']==2){
					$class="news";
				}
				$is_sub=$nav->has_sub($v['id']);
				if(!$is_sub){
					$t=$product->load_cate($v['id']);
					if(!empty($t)){
						foreach($t as $kk=>$vv){
							$nav_data=$nav->load($vv['cate_id']);
							$vv['url']=$GLOBALS['app']."product/show/id/".$vv['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
							if($vv['user_id']){
								$vv['user_info']=$user->load($vv['user_id']);
							}
							$pro_main_nav[$k]['product'][]=$vv;
						}
					}
				} else {
					$sub_nav_data=$nav->sub_one($v['id']);
					if(!empty($sub_nav_data)){
						foreach($sub_nav_data as $sk=>$sv){
							$t=$product->load_cate($sv['id']);
							
							if(!empty($t)){
								foreach($t as $kk=>$vv){
									$vv['url']=$GLOBALS['app']."product/show/id/".$vv['id']."/pid/".$sv['pid']."/m_id/".$sv['module_id'];
									if($vv['user_id']){
										$vv['user_info']=$user->load($vv['user_id']);
									}
									$pro_main_nav[$k]['product'][]=$vv;
								}
								
							}
						}
					}
				}
				if(!$is_sub){
					$page_type="lists";
				} else {
					$page_type="index";
				}
				$pro_main_nav[$k]['url']=$GLOBALS['app'].$class."/".$page_type."/id/".$v['id']."/pid/".$v['pid']."/m_id/".$v['module_id'];
			}
			if($_SESSION['user']['isLogin']){//未阅读私信数量
				$letter=D("Letter");
				$new_letter_num=$letter->new_num($_SESSION['user']['id']);
				$this->assign("new_letter_num",$new_letter_num);
			}
			$this->adv_list();
			$this->survey_list();
			$this->news_list();
			$this->hotword_list();
			$this->hotproduct_list();
			$this->position();
			$this->link_list();
			if($_SESSION['user']['id']){
				$cart=D("Cart");
				$this->assign("cart_num",$cart->cart_num($_SESSION['user']['id']));
			}
			$this->assign("pcate_datas",$pcate_datas);
			$this->assign("con_datas",$con_datas[0]);
			$this->assign("pro_main_nav",$pro_main_nav);
			$this->assign("main_nav",$main_nav);
			$this->assign("sub_nav",$sub_nav);
			$this->assign("user",$_SESSION['user']);
			$this->assign("current_time",time());
		}
		
		function position(){
			$id=intval($_GET['id']);
			$pid=intval($_GET['pid']);
			$nav=D("Nav","admin");
			$html='当前位置：<a href="'.$GLOBALS['app'].'">首页</a>';
			if($_GET['a']=="lists"){
				$nav_1=$nav->load($pid);
				$nav_2=$nav->load($id);
				$nav_data[0]=$nav_1;
				$nav_data[1]=$nav_2;
				
				if($nav_data[0]){
					foreach($nav_data as $k=>$v){
						
						if($v['type']==1){
							$class="product";
						} elseif($v['type']==2){
							$class="news";
						}
						if(!$v['pid']){
							$page_type="index";
						} else {
							$page_type="lists";
						}
						$url=$GLOBALS['app'].$class."/".$page_type."/id/".$v['id']."/pid/".$v['pid']."/m_id/".$v['module_id'];
						$html.='&nbsp;->&nbsp;<a href="'.$url.'">'.$v['name'].'</a>';
					}
				} else {
					$html.='&nbsp;->&nbsp;'.$nav_2['name'];
				}
			} elseif($_GET['a']=="article" || $_GET['a']=="show"){
				$new=D("News");
				$product=D("Product");
				
				$nav_1=$nav->load($pid);
				if($_GET['a']=="article"){
					$new_data=$new->load($id);
					$nav_2=$nav->load($new_data['cate']);
				} else{
					$product_data=$product->load($id);
					$nav_2=$nav->load($product_data['cate_id']);
				}
				if($pid){
					$nav_data[0]=$nav_1;
					$nav_data[1]=$nav_2;
				} else {
					$nav_data[0]=$nav_2;
				}
				
				foreach($nav_data as $k=>$v){
					
					if($v['type']==1){
						$class="product";
					} elseif($v['type']==2){
						$class="news";
					}
					if(!$v['pid']){
						$page_type="index";
					} else {
						$page_type="lists";
					}
					$url=$GLOBALS['app'].$class."/".$page_type."/id/".$v['id']."/pid/".$v['pid']."/m_id/".$v['module_id'];
					$html.='&nbsp;->&nbsp;<a href="'.$url.'">'.$v['name'].'</a>';
				}
				
			}elseif($_GET['a']=="index"){
				$nav_1=$nav->load($id);
				if($nav_1['type']==1){
					$class="product";
				} elseif($nav_1['type']==2){
					$class="news";
				}
				if(!$v['pid']){
					$page_type="index";
				} else {
					$page_type="lists";
				}
				$url=$GLOBALS['app'].$class."/".$page_type."/id/".$nav_1['id']."/pid/".$nav_1['pid']."/m_id/".$nav_1['module_id'];
				$html.='&nbsp;->&nbsp;<a href="'.$url.'">'.$nav_1['name'].'</a>';
				
			} elseif($_GET['a']=="brand_list"){
				$nav_1=$nav->load($pid);
				$brand=D("Brand","admin");
				$brand_data=$brand->load($id);
				$url=$GLOBALS['app']."product/lists/id/".$nav_1['id']."/pid/".$nav_1['pid']."/m_id/".$nav_1['module_id'];
				$html.='&nbsp;->&nbsp;<a href="'.$url.'">'.$nav_1['name'].'</a>';
				$html.='&nbsp;->&nbsp;'.$brand_data['name'];
			}
			$this->assign("position",$html);
		}
		
		function hotword_list(){
			$hotword=D("Hotword");
			$hot_datas=$hotword->lists();
			foreach($hot_datas as $k=>$v){
				if($v['type']==1){
					$class='product';
				} elseif($v['type']==2){
					$class='news';
				}
				$hot_datas[$k]['url']=$GLOBALS['app'].$class."/search_index/keywords/".urlencode($v['keyword']);
			}
			$this->assign("hot_datas",$hot_datas);
		}
		function hotproduct_list(){
			$product=D("Product");
			$nav=D("Nav","admin");
			$hot_product_datas=$product->hot();
			foreach($hot_product_datas as $k=>$v){
				$nav_data=$nav->load($v['cate_id']);
				$hot_product_datas[$k]['url']=$GLOBALS['app']."product/show/id/".$v['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
			}
			$this->assign("hot_product_datas",$hot_product_datas);
		}
		function adv_list(){
			$adv=D("Adv");
			$acate=D("Acate","admin");
			
			$cate_datas=$acate->cate_list();
			$adv_datas=$adv->lists();
			foreach($cate_datas as $key=>$var){
				foreach($adv_datas as $k=>$v){
					if($var['id']==$v['cate']){
						$t[$var['id']][]=$v;
					}
				}
			$this->assign("adv_datas_".$var['id'],$t[$var['id']]);
			}
		}
		function member_news_list(){
			
		}
		
		function link_list(){
			$link=D("Link","admin");
			$link_datas=$link->lists();
			$this->assign("link_datas",$link_datas);
		}
		function news_list(){
			$news=D("News");
			$news_datas=$news->news_list(0,0,"create_time desc");
			$nav=D("Nav","admin");
			$user=D("User","admin");
			$nav_datas=$nav->nav_type_list(2);
			foreach($nav_datas as $key=>$var){
				$i=0;
				foreach($news_datas as $k=>$v){
					if($var['id']==$v['cate']){
						$v['times']=time_ago($v['create_time']);
						$t[$var['id']][$i]=$v;
						$t[$var['id']][$i]['url']=$GLOBALS['app'].'news/article/id/'.$v['id']."/pid/".$v['m_cate']."/m_id/".$var['module_id'];
						if($v['user_id']){
							$t[$var['id']][$i]['user_info']=$user->load($v['user_id']);
						}
						$i++;
					}
				}
				
			$is_sub=$nav->has_sub($var['id']);
			if(!$is_sub){
				$page_type="lists";
			} else {
				$page_type="index";
			}
			$this->assign("news_url_".$var['id'],$GLOBALS['app'].'news/'.$page_type.'/id/'.$var['id']."/pid/".$var['pid']."/m_id/".$var['module_id']);
			$this->assign("news_datas_".$var['id'],$t[$var['id']]);
			}
		}
		function survey_list(){
			$survey=D("Survey");
			$survey_datas=$survey->lists();
			foreach($survey_datas as $k=>$v){
				$this->assign("survey_datas_".$v['id'],$v);
			}
		}
	}