<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class IndexAction extends CommonAction {
    public function index(){
        $state=S('state');
        if($state){
            if(I('get.state')==$state){
                $this->display();
            }else{
                exit("<h1>这里不是你该来的地方!</h1>");
            }
        }else{
            $this->display();
        }
    }
    public function paylog(){
        $db=M('order');
        import('ORG.Util.Page');
        $where['ok'] = 1;
        $count=$db->where($where)->count();
		$Page=new Page($count,20);
		$show=$Page->show();
        $list=$db->where($where)->order('s_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign("page",$show);
        $this->display();
    }
}