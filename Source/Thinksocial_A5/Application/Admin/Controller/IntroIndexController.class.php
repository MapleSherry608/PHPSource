<?php
namespace Admin\Controller;
class IntroIndexController extends AdminController {
	
    public function index(){
        
        
        
		$this->system_info_mysql = M()->query("select version() as v;");
    	$this->coumemb=M('Member')->count();
		$this->aclog=M('ActionLog')->count();
		$admininfo=M('User')->field('username,status,email,mobile,sex,birthday,qq,login,reg_ip,reg_time,last_login_ip,last_login_time')->where(array('id'=>USERID))->find();
		int_to_string($admininfo,array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核')));
    	$this->assign('admininfo',$admininfo);
    	$this->display(); 
    }
    
    public function post(){
       	exit();
    }
}
