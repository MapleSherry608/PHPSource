<?php
	class Survey{
		function index(){
			if(!$this->is_cached("main/survey_list",$_SERVER['REQUEST_URI'])){
				$survey=D("Survey");
				$page=new Page($survey->total(), PAGENUM);
				$datas=$survey->limit($page->limit)->lists();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
			}
			$this->display("main/survey_list");
		}
		function add_index(){
			$this->display("main/survey_add");
		}
		function add(){
			$this->validate();
			$survey=D("Survey");
			if($survey->add()){
				$this->clear_cache();
				$this->success("填加成功!", 1, "survey/index");
			} else {
				$this->error("填加失败!", 1, "survey/add_index");
			}
		}
		function mod_index(){
			if(!$this->is_cached("main/survey_mod",$_SERVER['REQUEST_URI'])){
				$id=intval($_GET['id']);
				$survey=D("Survey");
				$datas=$survey->select($id);
				$datas[0]['result']=unserialize(htmlspecialchars_decode($datas[0]['result']));
				$this->assign("datas",$datas[0]);
			}
			$this->display("main/survey_mod",$_SERVER['REQUEST_URI']);
		}
		function mod(){
			$this->validate();
			$id=intval($_POST['id']);
			$survey=D("Survey");
			$result=$survey->mod($id);
			if(false !== $result){
				$this->success("编辑成功!", 1, "survey/index");
			} else {
				$this->error("编辑失败!", 1, "survey/mod_index/id/{$id}");
			}
		}
		function del(){
			$survey=D("Survey");
			if($_POST['dels']){
				if($survey->delete($_POST['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "survey/index");
				} else {
					$this->error("删除失败!", 1, "survey/index");
				}
			} else {
				if($survey->delete($_GET['id'])){
					$this->clear_cache();
					$this->success("删除成功!", 1, "survey/index");
				} else {
					$this->error("删除失败!", 1, "survey/index");
				}
			}
		}
		private function validate(){
			validate::notnull($_POST['topic'],"调查主题不能为空");
			validate::notnull($_POST['item1'],"调查项目1不能为空");
			validate::notnull($_POST['item2'],"调查项目2不能为空");
			validate::notnull($_POST['item3'],"调查项目3不能为空");
			validate::notnull($_POST['item4'],"调查项目4不能为空");
			validate::notnull($_POST['item5'],"调查项目5不能为空");
			if(!validate::$flag){
				$msg=implode("<br>",validate::getMsg());
				$this->error($msg, 3, "survey/add_index");
			}
		}
	}