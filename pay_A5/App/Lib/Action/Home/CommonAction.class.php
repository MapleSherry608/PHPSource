<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class CommonAction extends Action {
	public function _initialize(){
        if(!is_file('Data/install.lock')){
            $this->redirect('Install/Index/index');
        }
		header("Content-Type:text/html;charset=utf-8");
		$pay_list=M('pay')->where('ok=1')->order('id')->select();
        $this->assign('pay_list',$pay_list);
        $pay_shop=explode("|",C('shop'));
        //print_r($pay_shop);
        $this->assign('pay_shop',$pay_shop);
	}
}