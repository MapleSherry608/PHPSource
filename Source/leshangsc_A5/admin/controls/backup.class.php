<?php
	class Backup{
		function index(){
			if(!$this->is_cached("main/backup_list",$_SERVER['REQUEST_URI'])){
				$bu=D("Backup");
				$page=new Page($bu->totals(), PAGENUM);
				$datas=$bu->limit($page->limit)->backup_list();
				$this->assign("datas",$datas);
				$this->assign("fpage", $page->fpage());
				
			}
			$this->display("main/backup_list",$_SERVER['REQUEST_URI']);
		}
		function del(){
			$bu=D("Backup");
			if($bu->del()){
				$this->clear_cache();
				$this->success("删除成功!", 1, "backup/index");
			} else {
				$this->error("删除失败!", 1, "backup/index");
			}
		}
		function dels(){
			$bu=D("Backup");
			$id=$_POST['id'];
			if(!count($id)){
				$this->error("请选择删除项目", 1);
			}
			if($bu->dels($id)){
				$this->clear_cache();
				$this->success("删除成功!", 1, "backup/index");
			} else {
				$this->error("删除失败!", 1, "backup/index");
			}
		}
		function backup(){
			$bu=D("Backup");
			if($bu->backup_database()){
				$this->clear_cache();
				$this->success("备份成功!", 1, "backup/index");
			} else {
				$this->error("备份失败!", 2, "backup/index");
			}
		}
		function restore(){
			$bu=D("Backup");
			$file=trim($_GET['file']);
			$d=$bu->restore($file);
			if($d['res']){
				$this->success("还原成功!", 2, "backup/index");
			} else {
				$this->error("还原失败!".$d['info'], 2, "backup/index");
			}
		}
	}