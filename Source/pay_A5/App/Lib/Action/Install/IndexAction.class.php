<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class IndexAction extends Action {
    //安装首页
	public function index(){ 
		if(is_file('Data/install.lock')){
			$this->error('已经成功安装了梦雪实体小店收款系统，请不要重复安装!', U('Home/Index/index'));
		}
		session('step', 0);
		session('error', false);
        $a = new InstallAction();
        $a->step1();
		//$this->display();
	}

	//安装完成
	public function complete(){
		$step = session('step');

		if(!$step){
			$this->redirect('index');
		} elseif($step != 3) {
			$this->redirect("Install/step{$step}");
		}
        $admin = session('admin_info');
        $data['user'] = $admin['username'];
        $data['pwd'] = MD5($admin['password']);
        $data['intro'] = '系统管理员';
        $data['time'] = time();
        M('admin')->add($data);
		//创建入口文件
		//write_index();
		file_put_contents('Data/install.lock', '');
		
		session('step', null);
		session('error', null);
		$this->display();
	}
}