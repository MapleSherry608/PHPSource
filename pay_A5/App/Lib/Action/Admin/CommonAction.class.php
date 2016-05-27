<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class CommonAction extends Action {
	public function _initialize(){
		header("Content-Type:text/html;charset=utf-8");
		$pay_list=M('pay')->order('id')->select();
        $this->assign('pay_list',$pay_list);
	}
}