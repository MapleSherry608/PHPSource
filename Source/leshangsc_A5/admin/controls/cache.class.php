<?php
	class Cache{
		function index(){
			$this->assign("status",$_GET['status']);
			$this->display("main/clear_cache");
		}
		function useless(){
			$folder=PROJECT_PATH."public/uploads";
			$data=Simfile::getSubFile($folder);
			$adv=D("Adv");
			$brand=D("Brand");
			$config=D("Config");
			$news=D("News");
			$product=D("Product");
			$user=D("User");
			foreach($data as $key=>$var){
				$datas[$key]['file']=basename($var);
				$datas[$key]['size']=tosize(filesize($var));
				$datas[$key]['time']=date("Y-m-d H:i:s",filemtime($var));
				
				$adv_filter=$adv->filter_img($datas[$key]['file']);
				$brand_filter=$brand->filter_img($datas[$key]['file']);
				$config_filter=$config->filter_img($datas[$key]['file']);
				$config_filter_ico=$config->filter_ico($datas[$key]['file']);
				$news_filter=$news->filter_img($datas[$key]['file']);
				$product_filter=$product->filter_img($datas[$key]['file']);
				$user_filter=$user->filter_img($datas[$key]['file']);
				if($adv_filter || $brand_filter || $config_filter || $config_filter_ico || $news_filter || $product_filter || $user_filter){
					unset($datas[$key]);
				}
				
			}
			$page=new Page(count($datas), PAGENUM);
			$datas=array_slice($datas,$page->limit,PAGENUM);
			$this->assign("datas",$datas);
			$this->assign("fpage", $page->fpage());
			$this->display("main/useless_list");
		}
		
		function del(){
			$ca=D("Cache");
			if($ca->del()){
				$this->clear_cache();
				$this->success("删除成功!", 1);
			} else {
				$this->error("删除失败!", 1);
			}
		}
		function dels(){
			$ca=D("Cache");
			$id=$_POST['id'];
			if(!count($id)){
				$this->error("请选择删除项目", 1);
			}
			if($ca->dels($id)){
				$this->clear_cache();
				$this->success("删除成功!", 1);
			} else {
				$this->error("删除失败!", 1);
			}
		}
		
		function clear(){
			$num=0;
			$result=0;
			foreach($_POST as $k=>$v){
				$num+=$v;
			}
			if($_POST['cache']){
				if($this->clear_all_cache()){
					$result++;
				}
			}
			if($_POST['compile_cache']){
				if($this->clear_compiled_tpl()){
					$result++;
				}
			}
			if($_POST['runtime_cache']){
				$folder=dirname(dirname(dirname(__FILE__)));
				$res=Simfile::delete($folder);
				if($res){

					$result++;
				}
			}
			if($num==$result){
				$this->redirect("cache/index/status/1");
			} else {
				$this->redirect("cache/index/status/0");
			}
			
		
			
			
		}
		
		
		
	}