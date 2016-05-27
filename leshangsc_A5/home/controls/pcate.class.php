<?php
	class Pcate{
		function index(){
			$pcate=D("Pcate");
			$datas=$pcate->page_list();
			$datas[0]['content']=html_entity_decode($datas[0]['content']);
			$this->assign("datas",$datas[0]);
			$this->display("index/page");
		}
	}