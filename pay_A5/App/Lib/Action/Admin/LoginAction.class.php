<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class LoginAction extends Action {
    public function index(){
        if(IS_POST){
            $this->go();
            exit;
        }
        if(cookie('admin')){
            header("location: ".U('Index/index'));
        }
        $this->display();
    }
    private function go(){
        $user=I('post.user');
        $pwd =I('post.pwd');
        $time=I('post.cookie');
        $db=M('Admin');
        $where['user']=$user;
        if($db->where($where)->find()){
            $where['pwd']=MD5($pwd);
            if($db->where($where)->find()){
                //将用户名加密到cookie
                //setcookie("admin",get_authcode($user,'','www.php127.com'),$time,"/");
                cookie("admin",get_authcode($user,'',C('cookie_pwd')),$time);
                $this->success('登陆成功',U('Index/index'));
            }else{
                $this->error('密码错误');
            }
        }else{
            $this->error('用户名不存在');
        }
    }
    public function out(){
        cookie('admin',null);
        header("location: ".U('Index/index'));
    }
}