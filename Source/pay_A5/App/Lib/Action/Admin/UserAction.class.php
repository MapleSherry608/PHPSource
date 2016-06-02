<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class UserAction extends CommonAction {
    public function wx(){
        $db=M('wx_user');
        import('ORG.Util.Page');
        $count=$db->where($where)->count();
		$Page=new Page($count,10);
		$show=$Page->show();
		$list=$db->where($where)->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign("page",$show);
        $this->display();
    }
}