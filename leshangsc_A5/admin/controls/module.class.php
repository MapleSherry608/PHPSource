<?php
	class Module{
		function index(){
			if(!$this->is_cached("main/module_list",$_SERVER["REQUEST_URI"])){
				$module=D("Module");
				$page=new Page($module->total(), PAGENUM);
				$datas=$module->limit($page->limit)->lists();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/module_list",$_SERVER["REQUEST_URI"]);
		}
		function add_show(){
			$group=D("Group");
			$main_list=$group->main_list();
			foreach($main_list as $k=>$v){
				$main_list[$k]['sub']=$group->has_sub($v['id']);
			}
			$sub_list=$group->sub_list();
			$this->assign("main_list",$main_list);
			$this->assign("sub_list",$sub_list);
			$this->display("main/module_add");
		}
		function mod_show(){
			
			if(!$this->is_cached("main/module_mod",$_SERVER["REQUEST_URI"])){
				
				$id=intval($_GET['id']);
				
				$module=D("Module");
				$group=D("Group");
				$datas=$module->select($id);
				$datas[0]['auth']=unserialize(htmlspecialchars_decode($datas[0]['auth']));
				
				$main_list=$group->main_list();
				
				foreach($main_list as $k=>$v){
					$main_list[$k]['sub']=$group->has_sub($v['id']);
				}
				$sub_list=$group->sub_list();
				$this->assign("main_list",$main_list);
				$this->assign("sub_list",$sub_list);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/module_mod",$_SERVER["REQUEST_URI"]);
		}
		
		function add(){
			$this->validate();
			$module=D("Module");
			if($_POST['auth']){
				$_POST['auth']=serialize($_POST['auth']);
			} else {
				$_POST['auth']="none";
			}
			if($module->add()){
				$this->clear_cache();
				$this->success("填加成功!", 1, "module/index");
			} else {
				$this->error("填加失败!", 1, "module/add_show");
			}
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$module=D("Module");
			
			if($_POST['auth']){
				$_POST['auth']=serialize($_POST['auth']);
			} else {
				$_POST['auth']="none";
			}
			$result=$module->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "module/mod_show/id/{$id}");
			} else {
				
				$this->error("编辑失败!", 10, "module/mod_show/id/{$id}");
			}
		}
		
		function del(){
			$module=D("Module");
			if($_POST['dels']){
				if($module->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "module/index");
				} else {
					$this->error("删除失败!", 1, "module/index");
				}
			} else {
				if($module->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "module/index");
				} else {
					$this->error("删除失败!", 1, "module/index");
				}
			}
		}
		
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			validate::notnull($_POST['classname'],"分类名称不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "module/add_show");
			}
		}
	}