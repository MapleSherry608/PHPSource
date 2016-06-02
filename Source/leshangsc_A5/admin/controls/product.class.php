<?php
	class Product{
		function index(){
			if(!$this->is_cached("main/product_list",$_SERVER['REQUEST_URI'])){
				$product=D("Product");
				$nav=D("Nav");
				$id=intval($_GET['id']);
				if($id){
					$current_name=$nav->load($id);
				} else {
					$current_name['name']="全部商品";
				}
				$main=$nav->main_list(1);
				foreach($main as $k=>$v){
					$has_sub=$nav->has_sub($v['id']);
					$main[$k]['has_sub']=$has_sub;
				}
				$sub=$nav->sub_list(1);
				$page=new Page($product->totals($id), PAGENUM);
				$datas=$product->limit($page->limit)->lists($id);
				$this->assign("main", $main);
				$this->assign("sub", $sub);
				$this->assign("datas",$datas);
				$this->assign("current_name",$current_name['name']);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/product_list",$_SERVER['REQUEST_URI']);
		}
		
		function add_index(){
			if(!$this->is_cached("main/product_add",$_SERVER['REQUEST_URI'])){
				$nav=D("Nav");
				$brand=D("Brand");
				$spec=D("Spec");
				$brand_datas=$brand->lists();
				$spec_main=$spec->main_list();
				$spec_sub=$spec->sub_list();
				$main=D("Nav")->main_list(1);
				foreach($main as $k=>$v){
					$has_sub=$nav->has_sub($v['id']);
					$main[$k]['has_sub']=$has_sub;
				}
				$sub=D("Nav")->sub_list(1);
				$this->assign("spec_main", $spec_main);
				$this->assign("spec_sub", $spec_sub);
				$this->assign("main", $main);
				$this->assign("sub", $sub);
				$this->assign("brand_datas", $brand_datas);
			}
			$this->display("main/product_add",$_SERVER['REQUEST_URI']);
		}
		function add(){
			$product=D("Product");
			$pro_spec=D("Productspec");
			$pro_post["img"]="";
			if($_FILES["img"]["name"]){
				$pro_post["img"]=$this->upload();
			} 
			foreach($_POST['spec_sub'] as $k=>$v){
				foreach($v as $kk=>$vv){
					foreach($_POST['spec_main'] as $sk=>$sv){
						//$specs_arr[$kk][$sk]=$sv.",".$_POST['spec_sub'][$sv][$kk];
						$specs_arr[$kk][$sv]=$_POST['spec_sub'][$sv][$kk];
						$specs_origin[$kk]=$_POST['origin_prices'][$kk];
						$specs_current[$kk]=$_POST['current_prices'][$kk];
						$specs_inventories[$kk]=$_POST['inventories'][$kk];
					}
				}
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
			$pro_post['spec_main']=serialize($_POST['spec_main']);
			$pro_post['click']=0;
			$pro_post['is_recommend']=0;
			$pro_post['add_time']=time();
			$pro_post['update_time']=0;
			$pro_post['status']=0;
			$pro_post['sort']=intval($_POST['sort']);
			$pro_post['verify']=0;
			$pro_post['user_id']=0;
			if($id=$product->add($pro_post)){
				foreach($specs_arr as $k=>$v){
					$post['specs']=serialize($v);
					$post['origin_price']=$specs_origin[$k];
					$post['current_price']=$specs_current[$k];
					$post['inventory']=$specs_inventories[$k];
					$post['pid']=$id;
					if(!$pro_spec->add($post)){
						$this->error("规格参数不完整,规格填加失败!", 1);
					}
				}
				$this->success($product->getMsg(), 1, "product/index");
			} else {
				$this->error($product->getMsg(), 1);
			}
		}
		
		function publish_index(){
			if(!$this->is_cached("main/publish_plist",$_SERVER['REQUEST_URI'])){
				$product=D("Product");
				$nav=D("Nav");
				$module=D("module");
				$user=D("User");
				$id=intval($_GET['id']);
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
				$datas=$product->limit($page->limit)->list_u($id);
				foreach($datas as $k=>$v){
					$datas[$k]['user']=$user->load($v['user_id']);
				}
				$this->assign("nav_datas", $nav_datas);
				$this->assign("datas",$datas);
				$this->assign("current_name",$current_name['name']);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/publish_plist",$_SERVER['REQUEST_URI']);
		}
		
		function mod_index(){
			if(!$this->is_cached("main/product_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$product=D("Product");
				$pro_spec=D("Productspec");
				$nav=D("Nav");
				$brand=D("Brand");
				$spec=D("Spec");
				$datas=$product->load($id);
				$spec_datas=$pro_spec->lists($id);
				$brand_datas=$brand->lists();
				$spec_main=$spec->main_list();
				$spec_sub=$spec->sub_list();
				foreach($spec_datas as $k=>$v){
					$spec_datas[$k]['specs']=unserialize(htmlspecialchars_decode($v['specs']));
				}
				$datas['spec_main']=unserialize(htmlspecialchars_decode($datas['spec_main']));
				
				$main=$nav->main_list(1);
				foreach($main as $k=>$v){
					$has_sub=$nav->has_sub($v['id']);
					$main[$k]['has_sub']=$has_sub;
				}
				$sub=D("Nav")->sub_list(1);
				$this->assign("datas", $datas);
				$this->assign("spec_datas", $spec_datas);
				$this->assign("spec_datas_num", count($spec_datas));
				$this->assign("spec_main", $spec_main);
				$this->assign("spec_sub", $spec_sub);
				$this->assign("main", $main);
				$this->assign("sub", $sub);
				$this->assign("brand_datas", $brand_datas);
			}
			$this->display("main/product_mod",$_SERVER['REQUEST_URI']);
		}
		function mod_publish_index(){
			if(!$this->is_cached("main/product_mod_publish",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$product=D("Product");
				$module=D("Module");
				$nav=D("Nav");
				$datas=$product->load($id);
				$nav_datas=$module->pub_nav_list(1);
				
				foreach($nav_datas as $k=>$v){
					if(!$v['pid']){
						$m_cate=$v['nav_id'];
					}
					$nav_datas[$k]['has_sub']=$nav->has_sub($v['nav_id']);
				}
				$this->assign("datas", $datas);
				$this->assign("nav_datas", $nav_datas);
			}
			$this->display("main/product_mod_publish",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$id=intval($_POST['id']);
			$product=D("Product");
			$pro_spec=D("Productspec");
			if($_FILES["img"]["name"]){
				$pro_post["img"]=$this->upload();
			} 
			foreach($_POST['spec_sub'] as $k=>$v){
				foreach($v as $kk=>$vv){
					foreach($_POST['spec_main'] as $sk=>$sv){
						$specs_arr[$kk][$sv]=$_POST['spec_sub'][$sv][$kk];
						$specs_origin[$kk]=$_POST['origin_prices'][$kk];
						$specs_current[$kk]=$_POST['current_prices'][$kk];
						$specs_inventories[$kk]=$_POST['inventories'][$kk];
					}
				}
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
			$pro_post['spec_main']=serialize($_POST['spec_main']);
			$pro_post['click']=0;
			$pro_post['update_time']=time();
			$pro_post['sort']=intval($_POST['sort']);
			$result=$product->mod($id,$pro_post);
			if(false !== $result){
				$pro_spec->del($id);
				foreach($specs_arr as $k=>$v){
					$post['specs']=serialize($v);
					$post['origin_price']=$specs_origin[$k];
					$post['current_price']=$specs_current[$k];
					$post['inventory']=$specs_inventories[$k];
					$post['pid']=$id;
					if(!$pro_spec->add($post)){
						$this->error("规格参数不完整,规格填加失败!", 1);
					}
				}
				$this->clear_cache();
				$this->success("编辑成功!", 1);
			} else {
				$this->error("编辑失败!", 1);
			}
		}
		
		function serial_no(){
			srand((double)microtime()*1000000);
			return date("Ymd-His") . '-' . rand(100,999);
		}
		
		function del(){
			$product=D("Product");
			if($_POST['dels']){
				if($product->delete($_POST['id'])){
					$this->clear_cache();
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
		function verify(){
			$id=intval($_GET['id']);
			$user_id=intval($_GET['user_id']);
			$product=D("Product");
			$user=D("User");
			$group=D("Group");
			$config_data=D("config")->config_list();
			$user_data=$user->load($user_id);
			$score=$user_data['score']+$config_data[0]['pub_pro_score'];
			$group_data=$group->score_range($score);
			if($product->verify($id) && $user->update_group($user_id,$score,$group_data['id'])){
				
				$mailRules=D("MailRules","admin");
				$template=$mailRules->load_temp("publish_product");
				if($template['value']){
					$user_data=D("User")->load($user_id);
					$datas=array("FromName"=>"管理员","Subject"=>"发布商品审核通过","Body"=>$template['template'],"address"=>trim($user_data['email']));
					$mails=D("Mails","admin");
					$datas['Body']=$mails->replace_body($datas['Body']);
					$mails->send_mail($datas);
				}
				
				
				$this->success("审核通过!", 1, "product/publish_index");
			} else {
				$this->error("审核失败!", 1, "product/publish_index");
			}
		}
		
		function del_publish(){
			$product=D("Product");
			if($_POST['dels']){
				if($product->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "product/publish_index");
				} else {
					$this->error("删除失败!", 1, "product/publish_index");
				}
			} else {
				if($product->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "product/publish_index");
				} else {
					$this->error("删除失败!", 1, "product/publish_index");
				}
			}
		}
		
		function change_stat(){
			if(empty($_POST['id'])){
				$this->error("请选择项目!", 1);
			}
			$id=implode(",",$_POST['id']);
			$name=trim($_GET['name']);
			$value=intval($_GET['value']);
			$post=array($name=>$value);
			$product=D("Product");
			if($product->where($id)->update($post)){
				$this->clear_cache();
				$this->success("更新成功!", 1, "product/index");
			} else {
				$this->error("更新失败!", 1, "product/index");
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
		
		
	}