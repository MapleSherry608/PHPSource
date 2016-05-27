<?php
	class Config{
		
		function index(){
			if(!$this->is_cached("main/config",$_SERVER['REQUEST_URI'])){
				$datas=D("Config")->config_list();
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/config",$_SERVER['REQUEST_URI']);
			
		}
		function mod(){
			$config=D("Config");
			if($_FILES["logo"]["name"]){
				$_POST["logo"]=$this->upload();
			} 
			if($_FILES["favicon"]["name"]){
				$_POST["favicon"]=$this->upload_ico();
			}
			$result=$config->where(array("id"=>1))->update();
			
			if(false !== $result && $config->write_to_file()){
				$this->clear_cache();
				$this->success("编辑成功!", 1, "config/index");
			} else {
				$this->error("编辑失败!", 1, "config/index");
			}
		}
		
		function upload(){
			$up = new FileUpload(); //可以通过参数指定上传位置，可通过set()方法
			$up->set('allowType', array('gif','jpg','png','jpeg','pjpeg'));
			if($up->upload("logo")) { //pic 为上传表单的名称
				return $up->getFileName(); //返回上传后的文件名
			}else{
			//如果上传失败提示出错原因
				$this->error($up->getErrorMsg(), 5, 'config/index');
			}
		}
		function upload_ico(){
			$up_icon = new FileUpload(); //可以通过参数指定上传位置，可通过set()方法
			$up_icon->set('allowType', array('ico'));
			if($up_icon->upload("favicon")) { //pic 为上传表单的名称
				return $up_icon->getFileName(); //返回上传后的文件名
			}else{
			//如果上传失败提示出错原因
				$this->error($up_icon->getErrorMsg(), 5, 'config/index');
			}
		}
	}