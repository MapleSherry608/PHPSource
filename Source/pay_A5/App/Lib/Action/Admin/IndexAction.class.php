<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class IndexAction extends IsAction {
    public function index(){
        $state=S('state');
        $url = 'http://'.$_SERVER['HTTP_HOST'].U('Home/Index/index');
        $this->assign('url',$url);
        $this->display();
    }
    public function main(){
        $db=M('order');
        $list=$db->where($where)->order('s_time desc')->limit(8)->select();
        $arr=get_curl('http://get.php127.com/mxpay/update.php');
        $this->assign('arr',$arr);
        $this->assign('list',$list);
        $this->display();
    }
    public function no(){
        $this->error('此功能正在开发,请期待, 官方网站 pay.php127.com');
    }
}