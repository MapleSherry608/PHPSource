<?php
	class Group{
		function index(){
			if(!$this->is_cached("main/group_list",$_SERVER['REQUEST_URI'])){
				$group=D("Group");
				$page=new Page($group->totals(), PAGENUM);
				$main_list=$group->limit($page->limit)->main_list();
				foreach($main_list as $k=>$v){
					$sub=$group->has_sub($v['id']);
					$main_list[$k]['sub']=$sub;
				}
				$sub_list=$group->sub_list();
				$this->assign("main_list",$main_list);
				$this->assign("sub_list",$sub_list);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/group_list",$_SERVER['REQUEST_URI']);
		}
		function add_index(){
			$group=D("Group");
			$main_list=$group->main_list();
			$this->assign("main_list",$main_list);
			$this->display("main/group_add");
		}
		function add(){
			$this->validate();
			$group=D("Group");
			if($group->add()){
				$this->clear_cache();
				$this->success("填加成功!", 1, "group/index");
			} else {
				$this->error("填加失败!", 1, "group/add_index");
			}
		}
		function mod_index(){
			if(!$this->is_cached("main/group_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$group=D("Group");
				$main_list=$group->main_list();
				$datas=$group->select($id);
				$this->assign("main_list",$main_list);
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/group_mod",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$group=D("Group");
			$result=$group->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "group/index");
			} else {
				$this->error("编辑失败!", 1, "group/mod_index/id/{$id}");
			}
		}
		function set_default(){
			$group=D("Group");
			$id=intval($_GET['id']);
			if($group->set_default($id)){
				$this->clear_cache();
				$this->success("编辑成功!", 1, "group/index");
			} else {
				$this->error("编辑失败!", 1, "group/index");
			}
		}
		function del(){
			$group=D("Group");
			if($_POST['dels']){
				if($group->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "group/index");
				} else {
					$this->error("删除失败!", 1, "group/index");
				}
			} else {
				if($group->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "group/index");
				} else {
					$this->error("删除失败!", 1, "group/index");
				}
			}
		}
		private function validate(){
			validate::notnull($_POST['name'],"名称不能为空");
			validate::notnull($_POST['sort'],"排序不能为空");
			validate::number($_POST['sort'],"排序必须为数字");
			
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "group/add_index");
			}
		}
	}