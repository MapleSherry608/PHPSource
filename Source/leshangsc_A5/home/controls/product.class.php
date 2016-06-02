<?php
	class Product {
		function index(){
			if(!$this->is_cached("product/".$GLOBALS['m_data']['classname'],$_SERVER['REQUEST_URI'])){
				$product=D("Product");
				$nav=D("Nav","admin");
				$user=D("User","admin");
				$id=intval($_GET['id']);
				$sub_nav_data=$nav->sub_lists($id);
				foreach($sub_nav_data as $k=>$v){
					$product_datas=$product->load_cate($v['id']);
					foreach($product_datas as $kk=>$vv){
						$product_datas[$kk]['url']=$GLOBALS['app']."product/show/id/".$vv['id']."/pid/".$v['pid']."/m_id/".$v['module_id'];
						$product_datas[$kk]['user_info']=$user->load($vv['user_id']);
					}
					$sub_nav_data[$k]['products']=$product_datas;
					$sub_nav_data[$k]['url']=$GLOBALS['app']."product/lists/id/".$v['id']."/pid/".$v['pid']."/m_id/".$v['module_id'];
				}
				$this->assign("sub_nav_data",$sub_nav_data);
				$this->assign("product_datas",$product_datas);
			}
			$this->display("product/".$GLOBALS['m_data']['classname'],$_SERVER['REQUEST_URI']);
		}
		
		function lists(){
			if(!$this->is_cached("product/".$GLOBALS['m_data']['classname']."_list",$_SERVER['REQUEST_URI'])){
				$product=D("Product");
				$nav=D("Nav","admin");
				$user=D("User","admin");
				$brand=D("Brand","admin");
				$id=intval($_GET['id']);
				$m_id=intval($_GET['m_id']);
				$nav_datas=$nav->load($id);
				$page=new Page($product->load_cate_total($id), PAGENUM,"id/".$id."/pid/".intval($_GET['pid'])."/m_id/".$m_id);
				$product_datas=$product->limit($page->limit)->load_cate($id);
				
				foreach($product_datas as $k=>$v){
					$nav_data=$nav->load($v['cate_id']);
					$product_datas[$k]['url']=$GLOBALS['app'].'product/show/id/'.$v['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
					$product_datas[$k]['user_info']=$user->load($v['user_id']);
				}
				$brand_datas=$brand->lists();
				foreach($brand_datas as $k=>$v){
					
					$brand_datas[$k]['url']=$GLOBALS['app'].'product/brand_list/id/'.$v['id']."/pid/".$id."/m_id/".$m_id;
				}
				$this->assign("brand_datas",$brand_datas);
				$this->assign("nav_datas",$nav_datas);
				$this->assign("fpage", $page->fpage(1,2,3,4,5,6));
				$this->assign("product_datas",$product_datas);
			}
			
			$this->display("product/".$GLOBALS['m_data']['classname']."_list",$_SERVER['REQUEST_URI']);
		}
		
		function brand_list(){
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
			if(!$this->is_cached("product/".$GLOBALS['m_data']['classname']."_blist",$_SERVER['REQUEST_URI'])){
				$product=D("Product");
				$nav=D("Nav","admin");
				$user=D("User","admin");
				$id=intval($_GET['id']);
				$page=new Page($product->load_brand_total($id), PAGENUM,"id/".$id."/pid/".intval($_GET['pid'])."/m_id/".$m_id);
				$product_datas=$product->limit($page->limit)->load_brand($id);
				foreach($product_datas as $k=>$v){
					$nav_data=$nav->load($v['cate_id']);
					$product_datas[$k]['url']=$GLOBALS['app'].'product/show/id/'.$v['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
					$product_datas[$k]['user_info']=$user->load($v['user_id']);
				}
				$this->assign("fpage", $page->fpage(1,2,3,4,5,6));
				$this->assign("product_datas",$product_datas);
			}
			$this->display("product/".$GLOBALS['m_data']['classname']."_blist",$_SERVER['REQUEST_URI']);
		}
		
		function show(){
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
			if(!$this->is_cached("product/".$GLOBALS['m_data']['classname']."_show",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$product=D("Product");
				$spec=D("Spec","admin");
				$pro_spec=D("Productspec","admin");
				$appraise=D("Appraise","admin");
				$consult=D("Consult");
				$user=D("User","admin");
				$pro_data=$product->load($id);
				$pro_data['appraise']=$appraise->load_pid($pro_data['id']);
				$pro_data['appraise_num']=count($pro_data['appraise']);
				$pro_data['good_num']=$appraise->level_num($pro_data['id'],1);
				$pro_data['middle_num']=$appraise->level_num($pro_data['id'],2);
				$pro_data['bad_num']=$appraise->level_num($pro_data['id'],3);
				$pro_data['good_per']=floor($pro_data['good_num']/$pro_data['appraise_num']*100);
				$pro_data['middle_per']=floor($pro_data['middle_num']/$pro_data['appraise_num']*100);
				$pro_data['bad_per']=floor($pro_data['bad_num']/$pro_data['appraise_num']*100);
				$pro_data['spec_main']=unserialize(htmlspecialchars_decode($pro_data['spec_main']));
				$pro_data['brief']=htmlspecialchars_decode($pro_data['brief']);
				$pro_data['pro_specs']=$pro_spec->lists($id);
				$consult_data=$consult->lists($id);//咨询内容
				foreach($pro_data['pro_specs'] as $k=>$v){
					$t=unserialize(htmlspecialchars_decode($v['specs']));
					$pro_data['pro_specs'][$k]['specs']=array_flip($t);
					foreach($t as $tk=>$tv){
						$s=$spec->load($tv);
						$pro_data['specs_cn'][$tv]=$s['name'];
						$pro_data['specs'][$tv]=$tk;
					}
				}
				foreach($pro_data['appraise'] as $k=>$v){
					$pro_data['appraise'][$k]['user']=$user->load($v['uid']);
				}
				foreach($pro_data['spec_main'] as $k=>$v){
					$spec_data=$spec->load($v);
					unset($pro_data['spec_main'][$k]);
					$pro_data['spec_main'][$v]=$spec_data['name'];
				}
				foreach($consult_data as $k=>$v){
					$consult_data[$k]['user']=$user->load($v['uid']);
				}
				$IPaddress=get_ip();
				if($_COOKIE["click"]!=$IPaddress){
					setcookie("click",$IPaddress,time()+60*60);
					$product->click($id);
				}
				if($pro_data['user_id']){
					$pro_data['user_info']=$user->load($pro_data['user_id']);
				}
				$this->assign("pro_data",$pro_data);
				$this->assign("consult_data",$consult_data);
			}
			$this->display("product/".$GLOBALS['m_data']['classname']."_show",$_SERVER['REQUEST_URI']);
		}
		
		function search_index(){
			if(!$this->is_cached("product/product_search",$_SERVER['REQUEST_URI'])){
				$product=D("Product");
				$nav=D("Nav","admin");
				$hotword=D("Hotword");
				if(isset($_GET['keywords'])){
					$keywords=trim(iconv("gbk","utf-8",$_GET['keywords']));
				} else {
					$keywords=trim($_POST['keywords']);
				}
				$hotword->addkeyword($keywords,1);
				$page=new Page($product->search_total($keywords), PAGENUM);
				$product_datas=$product->search_list($keywords);
				foreach($product_datas as $k=>$v){
					$nav_data=$nav->load($v['cate_id']);
					$product_datas[$k]['url']=$GLOBALS['app']."product/show/id/".$v['id']."/pid/".$nav_data['pid']."/m_id/".$nav_data['module_id'];
				}
				$this->assign("keywords",$keywords);
				$this->assign("fpage", $page->fpage());
				$this->assign("product_datas", $product_datas);
			}
			$this->display("product/product_search",$_SERVER['REQUEST_URI']);
		}
		
		function add(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			$config=D("Config","admin");
			$config_data=$config->config_list();
			
			if($config_data[0]["auto_pro_verify"]){ //如果默认自动审核，将计算用户积分并累加，计算当前分组等级
				$user=D("User","admin");
				$group=D("Group","admin");
				$user_data=$user->load($_SESSION['user']['id']);
				$score=$user_data['score']+$config_data[0]['pub_pro_score'];
				$group_data=$group->score_range($score);
			}
			
			$product=D("Product");
			$pro_post["img"]="";
			if($_FILES["img"]["name"]){
				$pro_post["img"]=$this->upload();
			} 
			
			//提交商品数据
			$pro_post['name']=trim($_POST['name']);
			if(trim($_POST['serial_no'])==""){
				$pro_post['serial_no']=date("Ymdhis").rand(100,999);
			} else {
				$pro_post['serial_no']=trim($_POST['serial_no']);
			}
			$pro_post['cate_id']=intval($_POST['cate_id']);
			$pro_post['brand_id']=0;
			$pro_post['origin_price']=0;
			$pro_post['current_price']=trim($_POST['current_price']);
			$pro_post['inventory']=trim($_POST['inventory']);
			if($_POST['delivery_fee']==""){
				$pro_post['delivery_fee']=0;
			} else {
				$pro_post['delivery_fee']=trim($_POST['delivery_fee']);
			}
			$pro_post['brief']=trim($_POST['brief']);
			$pro_post['specifications']=trim($_POST['specifications']);
			$pro_post['spec_main']="";
			$pro_post['click']=0;
			$pro_post['is_recommend']=0;
			$pro_post['add_time']=time();
			$pro_post['update_time']=0;
			$pro_post['status']=0;
			$pro_post['sort']=intval($_POST['sort']);
			$pro_post['verify']=$config_data[0]["auto_pro_verify"];
			$pro_post['user_id']=$_SESSION['user']['id'];
			if($config_data[0]["auto_pro_verify"]){
				if($product->add($pro_post)&& $user->update_group($_SESSION['user']['id'],$score,$group_data['id'])){
					$this->success($product->getMsg(), 1,"product/publish_index");
				} else {
					$this->error($product->getMsg(), 3);
				}
			}else {
				if($product->add($pro_post)){
					$this->success($product->getMsg(), 1,"product/publish_index");
				} else {
					$this->error($product->getMsg(), 3);
				}
			}
			
		}
		function upload(){
			$up = new FileUpload(); //可以通过参数指定上传位置，可通过set()方法
			$up->set('allowType', array('gif','jpg','png','jpeg','pjpeg'));
			if($up->upload("img")) { //pic 为上传表单的名称
				return $up->getFileName(); //返回上传后的文件名
			}else{
			//如果上传失败提示出错原因
				$this->error($up->getErrorMsg(), 5);
			}
		}
		
		function publish_index(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("product/publish_index")){
				$module=D("module","admin");
				$nav=D("nav","admin");
				$nav_datas=$module->pub_nav_list(1);
				
				foreach($nav_datas as $k=>$v){
					if(!$v['pid']){
						$m_cate=$v['nav_id'];
					}
					$nav_datas[$k]['has_sub']=$nav->has_sub($v['nav_id']);
				}
				$this->assign("m_cate", $m_cate);
				$this->assign("nav_datas", $nav_datas);
			}
			$this->display("product/publish_index");
		}
		
		function publish_mod(){
			if(!(isset($_SESSION['user']["isLogin"]) && $_SESSION['user']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			if(!$this->is_cached("product/publish_mod")){
				$id=intval($_GET['id']);
				$module=D("module","admin");
				$nav=D("nav","admin");
				$nav_datas=$module->pub_nav_list(1);
				$product=D("Product","admin");
				foreach($nav_datas as $k=>$v){
					if(!$v['pid']){
						$m_cate=$v['nav_id'];
					}
					$nav_datas[$k]['has_sub']=$nav->has_sub($v['nav_id']);
				}
				$this->assign("data", $product->load($id));
				$this->assign("m_cate", $m_cate);
				$this->assign("nav_datas", $nav_datas);
			}
			$this->display("product/publish_mod");
		}
		
		function mod(){
			$id=intval($_POST['id']);
			$product=D("Product","admin");
			if($_FILES["img"]["name"]){
				$pro_post["img"]=$this->upload();
			} 
			//提交商品数据
			$pro_post['name']=trim($_POST['name']);
			if(trim($_POST['serial_no'])==""){
				$pro_post['serial_no']=date("Ymdhis").rand(100,999);
			} else {
				$pro_post['serial_no']=trim($_POST['serial_no']);
			}
			$pro_post['cate_id']=intval($_POST['cate_id']);
			$pro_post['brand_id']=intval($_POST['brand_id']);
			$pro_post['origin_price']=trim($_POST['origin_price']);
			$pro_post['current_price']=trim($_POST['current_price']);
			$pro_post['inventory']=trim($_POST['inventory']);
			if($_POST['delivery_fee']==""){
				$pro_post['delivery_fee']=0;
			} else {
				$pro_post['delivery_fee']=trim($_POST['delivery_fee']);
			}
			$pro_post['brief']=trim($_POST['brief']);
			$pro_post['specifications']=trim($_POST['specifications']);
			$pro_post['update_time']=time();
			$result=$product->mod($id,$pro_post);
			if(false !== $result){
				
				$this->success("编辑成功!", 1);
			} else {
				$this->error("编辑失败!", 1);
			}
		}
		
		function del(){
			$product=D("Product","admin");
			if($_POST['dels']){
				if($product->delete($_POST['id'])){
					$this->success("删除成功!", 1);
				} else {
					$this->error("删除失败!", 1);
				}
			} else {
				if($product->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1);
				} else {
					$this->error("删除失败!", 1);
				}
			}
		}
		
		
		
		function ajax_price(){
			$arr=$_POST['arr'];
			$arr=explode(",",$arr);
			sort($arr);
			$arr=implode(",",$arr);
			$id=intval($_POST['id']);
			$pro_spec=D("Productspec","admin");
			$pro_specs=$pro_spec->lists($id);
			foreach($pro_specs as $k=>$v){
				$pro_specs[$k]['specs']=unserialize(htmlspecialchars_decode($v['specs']));
				sort($pro_specs[$k]['specs']);
				$pro_specs[$k]['specs']=implode(",",$pro_specs[$k]['specs']);
				if($pro_specs[$k]['specs']==$arr){
					$target['origin_price']=$v['origin_price'];
					$target['current_price']=$v['current_price'];
					$target['inventory']=$v['inventory'];
				}
			}
			
			print_r(json_encode($target));
		}
	}