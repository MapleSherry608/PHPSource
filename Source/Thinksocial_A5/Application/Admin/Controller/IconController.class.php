<?php
namespace Admin\Controller;
use Think\Controller;
class IconController extends Controller {
    
    public function index(){
    	$callback=I('callback');
    	$this->assign('callback',$callback);
    	$this->display('Public/icon');    
    }
}