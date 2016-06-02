<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class IsAction extends Action {
	public function _initialize(){
		header("Content-Type:text/html;charset=utf-8");
		if(!cookie('admin')){
			header("location: ".U('Login/index'));
		}else{
			$User=M('admin');
			$where['user']=get_authcode(cookie('admin'),'DECODE',C('cookie_pwd'));
			$F=$User->where($where)->find();
			if(!$F){
                cookie('admin',null);
				$this->error('账号异常,请重新登录',U('Login/index'));
			}else{
                if(MXPAY_DEBUG){
                    $this->assign("MXPAY_DEBUG",1);
                    $this->assign("MXPAY_DEBUG_Name",'当前为演示模式,禁止修改此配置!');
                }
				$this->assign("Admin",$F);
			}
		}
	}
}