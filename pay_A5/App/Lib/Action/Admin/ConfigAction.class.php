<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class ConfigAction extends IsAction {
    public function scanning(){
        if(IS_POST){
            $wx_admin_openid=I('post.openid');
            if(S('wx_admin_openid',$wx_admin_openid)){
                $this->success('更新成功');
	    	}else{
	    		$this->error('更新失败');
            }
        }else{
            $wx_admin_openid=S('wx_admin_openid');
            $url = 'http://'.$_SERVER['HTTP_HOST'].U('Home/admin/index');
            $this->assign('url',$url);
            $this->assign('wx_admin_openid',$wx_admin_openid);
            $this->display();
        }
    }
    public function qrcode(){
        $url = 'http://'.$_SERVER['HTTP_HOST'].U('Home/Index/index');;
        $this->assign('url',$url);
        $this->assign('state',$state);
        $this->display();
    }
    public function sys(){
        if(IS_POST){
            $arr=$_POST;
            
            if($arr['ok_kj']==0 and $arr['ok_sl']==0){
                $this->error('必须开启其任一快捷金额与任意金额');
            }
	    	$str_start="<?php\nreturn array(\n";
	    	$str_end="\n);\n?>";
	    	$str_body="";
	    	foreach($arr as $key=>$val){
	    		$val=str_replace("'","\"",$val);
	    		$val=str_replace("\\","",$val);
	    		$str_body.="\t'".$key."' => '".$val."',\n";
	    	}
	    	$str=$str_start.$str_body.$str_end;
	    	$file="./Conf/config.php";
	    	$put=file_put_contents($file,$str);
	    	if($put){
	    		$this->success('更新成功');
	    	}else{
	    		$this->error('更新失败');
	    	}
        }else{
            $this->display();
        }
    }
    public function shop(){
        if(IS_POST){
            $arr=$_POST;
	    	$str_start="<?php\nreturn array(\n";
	    	$str_end="\n);\n?>";
	    	$str_body="";
	    	foreach($arr as $key=>$val){
	    		$val=str_replace("'","\"",$val);
	    		$val=str_replace("\\","",$val);
	    		$str_body.="\t'".$key."' => '".$val."',\n";
	    	}
	    	$str=$str_start.$str_body.$str_end;
	    	$file="./Conf/shop.php";
	    	$put=file_put_contents($file,$str);
	    	if($put){
	    		$this->success('更新成功');
	    	}else{
	    		$this->error('更新失败');
	    	}
        }else{
            $this->display();
        }
    }
    public function form(){
        if(IS_POST){
            $arr=$_POST;
	    	$str_start="<?php\nreturn array(\n";
	    	$str_end="\n);\n?>";
	    	$str_body="";
	    	foreach($arr as $key=>$val){
	    		$val=str_replace("'","\"",$val);
	    		$val=str_replace("\\","",$val);
	    		$str_body.="\t'".$key."' => '".$val."',\n";
	    	}
	    	$str=$str_start.$str_body.$str_end;
	    	$file="./Conf/form.php";
	    	$put=file_put_contents($file,$str);
	    	if($put){
	    		$this->success('更新成功',U('pay'));
	    	}else{
	    		$this->error('更新失败');
	    	}
        }else{
            $this->display();
        }
    }
    public function admin(){
        if(IS_POST){
            $user = I('post.user');
            $pwd  = MD5(I('post.pwd'));
            $where['user']=get_authcode(cookie('admin'),'DECODE','www.php127.com');
            $data['user']=$user;
            $data['pwd'] = $pwd;
            if(M('admin')->where($where)->save($data)){
                cookie('admin',null);
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }else{
            $this->display();
        }
    }
    public function pay(){
        $list=M('pay')->order('id')->select();
        $this->assign('list',$list);
        $this->display();
    }
    public function pay_up(){
        $id=I('get.id');
        $db=M('pay');
        $where['id']=$id;
        if(IS_POST){
            $data['name']= I('post.name');
            $data['ok']  = I('post.ok');
            $data['val'] = serialize($_POST['val']);
            //print_r($_POST); exit;
            //echo $data['val']; exit;
            $F=$db->where($where)->save($data);
            if($F!==false){
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
        }else{
            
            $F=$db->where($where)->find();
            $this->assign('val',unserialize($F['val']));
            $this->assign('F',$F);
            $this->display();
        }
    }
}