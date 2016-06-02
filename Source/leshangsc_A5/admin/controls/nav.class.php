<?php
	class Nav{
		function index(){
			if(!$this->is_cached("main/nav_list",$_SERVER['REQUEST_URI'])){
				$nav=D("Nav");
				$page=new Page($nav->totals(), PAGENUM);
				$datas=$nav->limit($page->limit)->main_list(0);
				foreach($datas as $k=>$v){
					$datas[$k]['sub']=$nav->sub_one($v['id']);
				}
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/nav_list",$_SERVER['REQUEST_URI']);
		}
		function add_index(){
			$nav=D("Nav");
			$module=D("Module");
			$module_list=$module->lists();
			$datas=$nav->main_list(0);
			$this->assign("datas",$datas);
			$this->assign("module_list",$module_list);
			$this->display("main/nav_add");
		}
		function mod_index(){
			if(!$this->is_cached("main/nav_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$nav=D("Nav");
				$module=D("Module");
				$module_list=$module->lists();
				$main_list=$nav->main_list(0);
				
				$datas=$nav->select($id);
				$this->assign("main_list",$main_list);
				$this->assign("datas",$datas[0]);
				$this->assign("module_list",$module_list);
			}
			
			$this->display("main/nav_mod",$_SERVER['REQUEST_URI']);
		}
		
		function add(){
			$this->validate();
			$module=D("Module");
			$nav=D("nav");
			$m_data=$module->load($_POST['module_id']);
			$_POST['type']=$m_data['type'];
			if($nav->add()){
				$this->clear_cache();
				$this->success("填加成功!", 1, "nav/index");
			} else {
				$this->error("填加失败!", 1, "nav/add_index");
			}
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$module=D("Module");
			$nav=D("nav");
			$m_data=$module->load($_POST['module_id']);
			$_POST['type']=$m_data['type'];
			$result=$nav->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "nav/index");
			} else {
				$this->error("编辑失败!", 1, "nav/mod_index/id/{$id}");
			}
		}
		
		function del(){
			$nav=D("nav");
			if($_POST['dels']){
				if($nav->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "nav/index");
				} else {
					$this->error("删除失败!", 1, "nav/index");
				}
			} else {
				if($nav->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "nav/index");
				} else {
					$this->error("删除失败!", 1, "nav/index");
				}
			}
		}
		
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "nav/add_index");
			}
		}
	}