<?php
	class Link{
		function index(){
			if(!$this->is_cached("main/link_list",$_SERVER['REQUEST_URI'])){
				$link=D("link");
				$page=new Page($link->total(), PAGENUM);
				$datas=$link->limit($page->limit)->lists();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/link_list",$_SERVER['REQUEST_URI']);
		}
		function add_index(){
			$this->display("main/link_add");
		}
		function add(){
			$this->validate();
			$link=D("link");
			if($_FILES["img"]["name"]){
				$_POST["img"]=$this->upload();
			}
			if($link->add()){
				$this->clear_cache();
				$this->success("填加成功!", 1, "link/index");
			} else {
				$this->error("填加失败!", 1, "link/add_index");
			}
		}
		function mod_index(){
			if(!$this->is_cached("main/link_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$link=D("link");
				$datas=$link->select($id);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/link_mod",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$link=D("link");
			if($_FILES["img"]["name"]){
				$_POST["img"]=$this->upload();
			}
			$result=$link->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "link/index");
			} else {
				$this->error("编辑失败!", 1, "link/mod_index/id/{$id}");
			}
		}
		function del(){
			$link=D("link");
			if($_POST['dels']){
				if($link->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "link/index");
				} else {
					$this->error("删除失败!", 1, "link/index");
				}
			} else {
				if($link->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "link/index");
				} else {
					$this->error("删除失败!", 1, "link/index");
				}
			}
		}
		
		function upload(){
			$up = new FileUpload(); //可以通过参数指定上传位置，可通过set()方法
			$up->set('allowType', array('gif','jpg','png','jpeg','pjpeg'));
			if($up->upload("img")) { //img 为上传表单的名称
				return $up->getFileName(); //返回上传后的文件名
			}else{
			//如果上传失败提示出错原因
				$this->error($up->getErrorMsg(), 5, 'link/index');
			}
		}
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			validate::notnull($_POST['url'],"地址不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "link/add_index");
			}
		}
	}