<?php
	class Adv{
		function index(){
			if(!$this->is_cached("main/adv_list",$_SERVER['REQUEST_URI'])){
				$adv=D("Adv");
				$acate=D("acate");
				$page=new Page($adv->total(), PAGENUM);
				$datas=$adv->limit($page->limit)->adv_list();
				foreach($datas as $k=>$v){
					$cate_info=$acate->load($v['cate']);
					$datas[$k]['cate_info']=$cate_info;
				}
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/adv_list",$_SERVER['REQUEST_URI']);
		}
		
		function add_show(){
			if(!$this->is_cached("main/adv_add",$_SERVER['REQUEST_URI'])){
				$acate=D("acate")->cate_list();
				$this->assign("acate", $acate);
			}
			$this->display("main/adv_add",$_SERVER['REQUEST_URI']);
		}
		function add(){
			$this->validate();
			$adv=D("Adv");
			$_POST["pic"]=$this->upload();
			if($adv->add()){
				$this->clear_cache();
				$this->success("填加成功!", 1);
			} else {
				$this->error("填加失败!", 1, "adv/add_show");
			}
		}
		
		function mod_index(){
			if(!$this->is_cached("main/adv_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$adv=D("Adv");
				$acate=D("acate")->cate_list();
				$datas=$adv->select($id);
				$this->assign("acate", $acate);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/adv_mod",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$Adv=D("Adv");
			if($_FILES["pic"]["name"]){
				$_POST["pic"]=$this->upload();
			}
			$result=$Adv->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "adv/mod_index/id/{$id}");
			} else {
				$this->error("编辑失败!", 1, "adv/mod_index/id/{$id}");
			}
		}
		function del(){
			$adv=D("Adv");
			if($_POST['dels']){
				if($adv->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "adv/index");
				} else {
					$this->error("删除失败!", 1, "adv/index");
				}
			} else {
				if($adv->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "adv/index");
				} else {
					$this->error("删除失败!", 1, "adv/index");
				}
			}
		}
		function upload(){
			$up = new FileUpload(); //可以通过参数指定上传位置，可通过set()方法
			$up->set('allowType', array('gif','jpg','png','jpeg','pjpeg'));
			if($up->upload("pic")) { //pic 为上传表单的名称
				return $up->getFileName(); //返回上传后的文件名
			}else{
			//如果上传失败提示出错原因
				$this->error($up->getErrorMsg(), 5, 'adv/index');
			}
		}
		
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			validate::notnull($_POST['url'],"链接不能为空");
			validate::url($_POST['url'],"链接格式不正确");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "adv/add_show");
			}
		}
	}