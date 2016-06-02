<?php
namespace Home\Controller;
class PersonalController extends HomeController{
	/**
	 * 个人中心
	 */
	public function index(){
		$membmodel=api('Member/Member/getModel');
		$memb=$membmodel->info(is_login());
		if(is_array($memb)&&!empty($memb)){
			session('memb_auth',$memb);
			$person=$this->lists("MemberPersonal",array('status'=>1),"sort desc,id asc");
			$this->assign('memb',$memb);
			$this->assign('person',$person);
			$this->display();
		}else{
			$this->error('请先登入！');
		}
	}
	/**
	 * 积分充值
	 */
	public function rechargeScore(){
	    
	    $open_id        = I('open_id');
	    if(empty($open_id)){
    	    $openid    = session('OPENID');
	    }else{
	        $openid    = $open_id;
	    }
	    $shop_member   = M('member');
	    $core_paylog   = M('core_paylog');
	    
	    $type = I('type');
	    $money = I('money');
	    if(!empty($money) && !empty($type) ){
            $core_paylog->where(array('openid' => $openid, 'status' => 0, 'type' => $type))->delete();
	        $logno = date('YmdHis') . random(6, true);
	        $log = array(
	            'uniacid' => 0,
	            'openid' => $openid,
	            'module' => "personal",
	            'tid' => $logno,
	            'fee' => $money,
	            'type' => $type,
	            'status' => 0
	        );
	        $plid=$core_paylog->add($log);
	    }
	    
	    $setting = array(
	        'pay'=>array(
	            'wechat' => C('wechat'),
	            'alipay' => C('alipay'),
	            'credit' => C('credit'),
	        )
	    );
	    $member = $shop_member->where(array('openid'=>$openid))->find();
	    $member['score'] = $member['score']/100;
	    $this->assign('openid',$openid);
	    $this->assign('setting',$setting);
	    $this->assign('member',$member);
	    
	    //充值
	    if(IS_AJAX){
	        if ($type == 'alipay') {
	            $params = array();
	            $params['tid'] = $logno;
	            $params['user'] = $openid;
	            $params['fee'] = $money;
	            $params['title'] = '积分充值';
	            $setting = array(
	                'pay'=>array(
	                    'wechat' => C('wechat'),
	                    'alipay' => C('alipay'),
	                    'credit' => C('credit'),
	                )
	            );
	            if (is_array($setting['pay'])) {
	                $options = $setting['pay']['alipay'];
	                $alipay = m_m('common')->alipay_build($params, $options, 1, $openid);
	                if (!empty($alipay['url'])) {
	                    $alipay['success'] = true;
	                }
	            }
	            show_json(1, array('alipay' => $alipay));
	        }
	        
	        
	    }
	    
	    $url_openid = I('open_id');
	    $this->assign('
	        ',$url_openid);
	    
	    $this->display();
	}
	
	/**
	 * 修改密码
	 */
	public function updPwd(){
		$this->checkauth();
		if(IS_POST){
			$password=I('password');
			$verifyPassword=I('verifyPassword');
			$paypwd=I('paypwd');
			$verifyPaypwd=I('verifyPaypwd');
			if(($password==$verifyPassword )||($paypwd==$verifyPaypwd)){
				if($password!=$paypwd){
					if((!empty($password) && !empty($verifyPassword))||(!empty($paypwd) && !empty($verifyPaypwd))){
						$membmodel=api('Member/Member/getModel');
						$memb=$membmodel->updatePassword(is_login(),$password,$paypwd);
						if($memb>0){
							$this->success('修改成功',U('Personal/index'));
						}else{
							$this->error($memb);
						}
					}else{
						$this->error('密码不可为空！');
					}
				}else{
					$this->error('登入密码和支付密码不可一致');
				}
			}else{
				$this->error('两次密码输入不一致！');
			}
		}else{
			$this->display();
		}
		
	}
	/**
	 * 修改个人信息
	 */
	public function updperso(){
		if(IS_POST){
			$data=I();
			if(empty($data['nickname']))$this->error('用户昵称不可为空');
			if(empty($data['email']))$this->error('电子邮箱不可为空');
			if(empty($data['id']))$this->error('非法访问');
			M('Member')->save($data);
			$this->success('保存成功!',U('Personal/index'));
		}else{
			$this->checkauth();
			$membmodel=api('Member/Member/getModel');
			$memb=$membmodel->info(is_login());
			$mfile=M('MemberEnlarge')->field('mfield,showname,type,control')->where(array('status'=>1))->order('sort DESC,id ASC')->select();
			foreach ($mfile as $key => $value) {
				$mfile[$key]['control']=json_decode($value['control'],true);
				if($value['mfield']=='avatar')
				$avatar=$mfile[$key];
			}
			$this->assign('memb',$memb);
			$this->assign('avatar',$avatar);
			$this->assign('mfile',$mfile);
			$this->display();
		}
	}
	/**
	 * 一期的时候的个人中心
	 */
	public function oneperso(){
		if(IS_POST){
			$data=I();
			if(empty($data['nickname']))$this->error('用户昵称不可为空');
			if(empty($data['id']))$this->error('非法访问');
			M('Member')->save($data);
			$surl=Cookie('__forward__');
			if(empty($surl)){
				$surl=U('Personal/oneperso');
			}
			$this->success('保存成功!',$surl);
		}else{
			$this->checkauth();
			$membmodel=api('Member/Member/getModel');
			$memb=$membmodel->info(is_login());
			$mfile=M('MemberEnlarge')->field('mfield,showname,type,control')->where(array('status'=>1))->order('sort DESC,id ASC')->select();
			foreach ($mfile as $key => $value) {
				$mfile[$key]['control']=json_decode($value['control'],true);
				if($value['mfield']=='avatar')
				$avatar=$mfile[$key];
			}
			$this->assign('memb',$memb);
			$this->assign('avatar',$avatar);
			$this->assign('mfile',$mfile);
			if(empty($_SERVER['HTTP_REFERER'])){
				Cookie('__forward__',$_SERVER['REQUEST_URI']);
			}else{
				Cookie('__forward__',$_SERVER['HTTP_REFERER']);
			}
			$this->display();
		}
	}
	/**
	 * 点击头像同步微信消息
	 */
	public function synchroniz(){
		$openid=session('OPENID');
		if(!empty($openid)){
			$account=M("member_public")->field('appid,secret')->find();
			$memdata=$this->getUserInfo($account['appid'],$account['secret'],$openid);
			$data=array(
				'nickname'=>$memdata['nickname'],
				'gender'=>$memdata['sex'],
				'residecity'=>$memdata['city'],
				'resideprovince'=>$memdata['province'],
				'nationality'=>$memdata['country'],
				'avatar'=>$memdata['headimgurl'],
			);
			$membmodel=api('Member/Member/getModel');
			$membmodel->updateMemberFields(MEMBID,true,$data,1);
			$fans=array(
				'nickname'=>$memdata['nickname'],
				'follow'=>$memdata['subscribe'],
				'followtime'=>$memdata['subscribe_time'],
				'unfollowtime'=>$memdata['subscribe']?null:time(),
				'updatetime'=>time(),  
			);
			M('MemberFans')->where(array('openid'=>$openid))->save($fans);
			$this->success('同步成功',U('Personal/index'));
			
		}else{
			$this->error('非法访问！');
		}
	}
	/**
	 * 获取用户信息
	 */
	private function getUserInfo($appid,$secrect,$openid){
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secrect;
	    $token = $this->GetWeiXinData($url);
	    $token = json_decode(stripslashes($token));
	    $arr = json_decode(json_encode($token), true);
	    $accessToken = $arr['access_token'];
        //获取用户信息
        $get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid='.$openid.'&lang=zh_CN ';
        $user_obj = $this->GetWeiXinData($get_user_info_url);
        $user_obj = json_decode($user_obj, true);
        return $user_obj;
    }
  	/**
     * 发送get请求,返回数据
     * @param string $url
     * @return bool|mixed
     */
    private function GetWeiXinData($url = ''){
        if (empty($url)) {
            return false;
        }
        $data = file_get_contents($url);
        return $data;
    }
	/**
	 * 积分明细查询
	 */
	public function bondCredit(){
		$this->checkauth();
		$membid=is_login();
		$where=array('credittype'=>'score','membid'=>$membid);
		$gtscore=M('CheckBill')->where(array('credittype'=>'score','membid'=>$membid,'num'=>array('GT',0)))->sum('num');
		$ltscore=M('CheckBill')->where(array('credittype'=>'score','membid'=>$membid,'num'=>array('LT',0)))->sum('num');
		$lists=$this->lists('CheckBill',$where,array('createtime'=>'desc','id'=>'asc'));
		int_to_string($lists,array('type'=>array(1=>'增加',2=>'减少')));
		$this->assign('gtscore',$gtscore);
		$this->assign('ltscore',$ltscore);
		$this->assign('list',$lists);
		$this->display();
	}
    protected function lists ($model,$where=array(),$order='',$field=true){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        if(is_string($model)){
            $model  =   M($model);
        }
        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);
        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);
        if(empty($where)){
            $where  =   array();
        }
        if( !empty($where)){
            $options['where']   =   $where;
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        $total        =   $model->where($options['where'])->count();

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 5;
        }
        $page = new \Think\Page($total, $listRows, $REQUEST);
		$page->rollPage=1;
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $options['limit'] = $page->firstRow.','.$page->listRows;
        $model->setProperty('options',$options);
        return $model->field($field)->select();
    }
}