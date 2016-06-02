<?php 
namespace Admin\Controller;
class MemberController extends AdminController{
	public function __init__(){
		C('LIST_ROWS',20);
	}
	/**
     * 订单审核
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('Member_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbidmember':
                $this->forbid('Member', $map );
                break;
            case 'resumemember':
                $this->resume('Member', $map );
                break;
			case 'deletemember':
				$id    = array_unique((array)I('id',0));
		        $id    = is_array($id) ? implode(',',$id) : $id;
		        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
		        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
		        if( M("Member")->where($where)->delete()!==false ) {
		        	 M("MemberFans")->where(array('membid'=>array('in', $id )))->delete();
		            $this->success($msg['success'],$msg['url'],$msg['ajax']);
		        }else{
		            $this->error($msg['error'],$msg['url'],$msg['ajax']);
		        }
                break;
			case 'forbiduser':
                $this->forbid('User', $map );
                break;
            case 'resumeuser':
                $this->resume('User', $map );
                break;
            case 'deleteuser':
               $id    = array_unique((array)I('id',0));
		        $id    = is_array($id) ? implode(',',$id) : $id;
		        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
		        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
		        if( M("User")->where($where)->delete()!==false ) {
		            $this->success($msg['success'],$msg['url'],$msg['ajax']);
		        }else{
		            $this->error($msg['error'],$msg['url'],$msg['ajax']);
		        }
                break;
			case 'deletegroup':
				$id    = array_unique((array)I('id',0));
		        $id    = is_array($id) ? implode(',',$id) : $id;
		        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
		        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
		        if( M("MemberGroups")->where($where)->delete()!==false ) {
		            $this->success($msg['success'],$msg['url'],$msg['ajax']);
		        }else{
		            $this->error($msg['error'],$msg['url'],$msg['ajax']);
		        }
                break;
			case 'deletelevel':
				$id    = array_unique((array)I('id',0));
		        $id    = is_array($id) ? implode(',',$id) : $id;
		        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
		        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
		        if( M("MemberLevel")->where($where)->delete()!==false ) {
		            $this->success($msg['success'],$msg['url'],$msg['ajax']);
		        }else{
		            $this->error($msg['error'],$msg['url'],$msg['ajax']);
		        }
                break;
            default:
                $this->error('参数非法');
        }
    }
	/**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
       $title=I('title');
		$status=I('status');
		$where=array();
		if($title){
			$where['nickname']=array('like','%'.$title.'%');
		}
		if(is_numeric($status)){
			$where['status']=$status;	
		}
		$lists=$this->lists('Member',$where,array('id desc'));
        int_to_string($lists,array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核')));
        $this->assign('list',$lists);
		$this->assign('do','listInfo');
		$this->assign('where',$where);
        $this->display();
    }
	/**
	 * 添加用户
	 */
	public function add(){
        if(IS_POST){
        	$member=array(
        		'realname'=>I('realname'),
        		'nickname'=>I('nickname'),
        		'email'=>I('email'),
        		'avatar'=>I('avatar'),
        		'groupid'=>I('groupid'),
        		'levelid'=>I('levelid'),
        		'idcard'=>I('idcard'),
        		'mobile'=>I('mobile'),
        		'qq'=>I('qq'),
        		'birth_date'=>I('birth_date'),
        		'gender'=>I('gender'),
        		'constellation'=>I('constellation'),
        		'zodiac'=>I('zodiac'),
        		'telephone'=>I('telephone'),
        		'studentid'=>I('studentid'),
        		'grade'=>I('grade'),
        		'zipcode'=>I('zipcode'),
        		'graduateschool'=>I('graduateschool'),
        		'company'=>I('company'),
        		'position'=>I('position'),
        		'education'=>I('education'),//学历
        		'revenue'=>I('revenue'),//年收入
        		'emotion'=>I('emotion'),//情感状态
        		'weight'=>I('weight'),//体重
        		'bloodtype'=>I('bloodtype'),//血型
        		'height'=>I('height'),//身高
        		'msn'=>I('msn'),//msn
        		'alipay'=>I('alipay'),//支付宝
        		'taobao'=>I('taobao'),//淘宝账号
        		'site'=>I('site'),//主页
        		'bio'=>I('bio'),//自我介绍
        		'interest'=>I('interest'),//兴趣爱好
			);
			
            /* 检测密码 */
           $password= I('password');
		   $repassword=I('repassword');
            if(!empty($password)){
	            if($password != $repassword){
	                $this->error('密码和重复密码不一致！');
	            }else{
	            	$member['password']=I('password');
	            }
            }
			$mid=I('mid');
			if(empty($mid)){
				$member['createtime']=NOW_TIME;
				if(empty($member['group'])){
					$member['group']=C('MEMB_DEFAULR_GROUPID');
				}
				$memb=D('Member');
				$mid=$memb->addMember($member);
				if(is_numeric($mid)){
					$this->success('用户信息添加成功！',U('index'));
				}else{
					$this->error($mid);
				}
			}else{
				$member['id']=$mid;
				$er=D('Member')->saveMember($member);
				if(is_numeric($er)){
					$this->success('用户信息更新成功！',U('index'));
				}else{
					$this->error($er);
				}
			}
        }else{
        	$id=I('id');
			$groupid=C('MEMB_DEFAULR_GROUPID');
			$levelid=C('MEMB_DEFAULR_LEVELID');
			if(empty($groupid)&&!is_numeric($groupid)){
				$this->error('该平台的用户分组未设置,请联系管理员！');
			}
			if(empty($levelid)&&!is_numeric($levelid)){
				$this->error('该平台的用户等级未设置,请联系管理员！');
			}
			if(!empty($id)){
				$member=M('Member')->where(array('id'=>$id))->find();
				$this->assign('id',$id);
				$this->assign('member',$member);
			}
			$grouplist=M('MemberGroups')->where(array('status'=>1))->select();
			$lecellist=M('MemberLevel')->where(array('status'=>1))->select();
			$this->assign('grouplist',$grouplist);
			$this->assign('lecellist',$lecellist);
			$this->groupid=$groupid;
			$this->levelid=$levelid;
            $this->display();
        }
    }
	/**
	 * 会员组管理
	 */
	public function group(){
       $title=I('title');
		$status=I('status');
		$where=array();
		if($title){
			$where['title']=array('like','%'.$title.'%');
			$where['tiptitle']=$title.'';
		}
		if($status||$status=0){
			$where['status']=$status;	
		}
		
		$lists=$this->lists('MemberGroups',$where,array('id'=>'asc'));
        int_to_string($lists,array('status'=>array(1=>'正常',-1=>'禁用',0=>'隐藏')));
        $this->assign('list',$lists);
		$this->assign('do','listInfo');
		$this->assign('where',$where);
        $this->display();
    }
	/**
	 * 添加会员组管理
	 */
	public function addGroup(){
        if(IS_POST){
        	$data=array(
	        	'title'=>I('title'),
	        	'sort'=>I('sort'),
	        	'status'=>I('status')
			);
			$id=I('id');
			if(empty($id)){
				$data['createtime']=time();
				$er=M('MemberGroups')->add($data);
				if(is_numeric($er)&&$er>0){
					$this->success('用户信息添加成功！',U('group'));
				}else{
					$this->error($er);
				}
			}else{
				$data['id']=$id;
				$er=M('MemberGroups')->where(array('id'=>$id))->save($data);
				if(is_numeric($er)){
					$this->success('用户信息更新成功！',U('group'));
				}else{
					$this->error($er);
				}
			}
        } else {
        	$id=I('id');
			if(!empty($id)){
				$group=M('MemberGroups')->where(array('id'=>$id))->find();
				$this->assign('id',$id);
				$this->assign('group',$group);
			}
            $this->display();
        }
    }
	/**
	 * 等级列表
	 */
	public function level(){
       $title=I('title');
		$status=I('status');
		$where=array();
		if($title){
			$where['title']=array('like','%'.$title.'%');
			$where['tiptitle']=$title.'';
		}
		if($status||$status=0){
			$where['status']=$status;	
		}
		$lists=$this->lists('MemberLevel',$where,array('id'=>'asc'));
        int_to_string($lists,array('status'=>array(1=>'正常',-1=>'禁用',0=>'隐藏')));
        $this->assign('list',$lists);
		$this->assign('do','listInfo');
		$this->assign('where',$where);
        $this->display();
    }
	/**
	 * 等级
	 */
	public function addLevel(){
        if(IS_POST){
        	$data=array(
	        	'title'=>I('title'),
	        	'sort'=>I('sort'),
	        	'status'=>I('status'),
				'grade'=>I('grade'),
				'levelicon'=>I('levelicon'),
			);
			$id=I('id');
			$group=D('MemberLevel');
			if(empty($id)){
				$er=$group->addLevel($data);
				if(is_numeric($er)&&$er>0){
					$this->success('添加成功！',U('level'));
				}else{
					$this->error($er);
				}
			}else{
				$data['id']=$id;
				$er=$group->saveLevel($data);
				if(is_numeric($er)){
					$this->success('更新成功！',U('level'));
				}else{
					$this->error($er);
				}
			}
        } else {
        	$id=I('id');
			if(!empty($id)){
				$level=M('MemberLevel')->where(array('id'=>$id))->find();
				$this->assign('id',$id);
				$this->assign('level',$level);
			}
            $this->display();
        }
    }
	/**
	 * 粉丝列表
	 */
	public function fanslist(){
       	$nickname=I('nickname');
		$follow=I('follow');
		$where=array();
		if($nickname){
			$where['nickname']=array('like','%'.$nickname.'%');
		}
		if($follow||$follow==0){
			$where['follow']=$follow;	
		}
		$lists=$this->lists('MemberFans',$where,array('id'=>'asc'));
        int_to_string($lists,array('follow'=>array(1=>'关注',0=>'取消关注')));
        $this->assign('list',$lists);
		$this->assign('do','listInfo');
		$this->assign('where',$where);
        $this->display();
    }
	/**
     * 用户行为列表
     * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    public function action(){
    	$type=I('type');
		$map=array('status'=>array('gt',-1),"is_user"=>1);
		if(!empty($type)){
			$map['type']=$type;
		}
		$status=I('status');
		$map=array();
		if(!empty($status)){
			$map['status']=$status;
		}
		$title=I('title');
		$map=array();
		if(!empty($title)){
			$map['title']=array('like','%'.$title.'%');
		}
        //获取列表数据
        $Action = M('Action');
        $list = $this->lists($Action,$map);
        int_to_string($list);
        $this->assign('_list',$list);
        $this->meta_title = '用户行为';
        $this->display();
    }
	/**
     * 设置一条或者多条数据的状态
     * @author huajie <banhuajie@163.com>
     */
    public function setStatus(){
        $ids    =   I('request.ids');
        $status =   I('request.status');
        if(empty($ids)){
            $this->error('请选择要操作的数据');
        }

        $map['id'] = array('in',$ids);
        switch ($status){
            case 0  :
                $this->forbid('Action', $map, array('success'=>'禁用成功','error'=>'禁用失败'));
                break;
            case 1  :
                $this->resume('Action', $map, array('success'=>'启用成功','error'=>'启用失败'));
                break;
            default :
                $this->error('参数错误');
                break;
        }
    }
	
	/**
     * 行为日志列表
     * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    public function actionlog(){
        //获取列表数据
        $map['status']    =   array('gt', -1);
		$map['is_user']    =   0;
		$remark=I('remark');
		if(!empty($remark)){
			$map['remark']=array('like','%'.$remark.'%');
		}
		$issystem=I('issystem');
		if(is_numeric($issystem)&&in_array($issystem, array(0,1,2))){
			$map['issystem']=$issystem;
		}
		$status=I('status');
		if(!empty($status)){
			$map['status']=$status;
		}
        $list   =   $this->lists('ActionLog', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '行为日志';
        $this->display('Member/actionlog');
    }
	/**
     * 查看行为日志
     * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    public function edit($id = 0){
        empty($id) && $this->error('参数错误！');
        $info = M('ActionLog')->field(true)->where('is_user= 0')->find($id);
        $this->assign('info', $info);
        $this->meta_title = '查看行为日志';
        $this->display();
    }
	/**
     * 编辑行为
     * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    public function editAction(){
        $id = I('get.id');
		if(!empty($id)){
        	$data = M('Action')->field(true)->find($id);
		}
        $this->assign('data',$data);
        $this->meta_title = '编辑行为';
        $this->display();
    }
	/**
     * 更新行为
     * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    public function saveAction(){
        $res = D('Action')->update();
        if(!$res){
            $this->error(D('Action')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！',U('Member/action'));
        }
    }
	/**
	 * 充值初始化
	 */
	public function initRecharge(){
		$membid=I('membid');
		$this->assign('membid',$membid);
		if(empty($membid)){
			$this->display('error');
		}else{
			$this->display('initRecharge');
		}
	}
	/**
	 * 修改密码
	 */
	public function changePwd(){
		$membid=I('membid');
		$this->assign('membid',$membid);
		if(empty($membid)){
			$this->display('error');
		}else{
			$this->display('updpwd');
		}
	}
	/**
     * 修改密码提交
     * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    public function submitPassword(){
        //获取参数
        $membid = I('post.membid');
        $admpwd = I('post.admpwd');
        empty($admpwd) && $this->error('请输入当前管理员密码');
        $password = I('post.password');
        empty($password) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');
        if($password !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }
    	$api = D('User');
        $res = $api->verifyUser(USERID,$admpwd);
		if($res>0){
        	$res = D("Member")->updateInfo($membid,$password);
	        if($res['status']){
	            $this->success($res['details']);
	        }else{
	            $this->error($res['details']);
	        }
		}else{
			 $this->error("修改密码失败！");
		}
    }
	/**
	 * 完成充值
	 */
	public function submitRecharge(){
		$membid=I('post.membid');
		empty($membid) && $this->error('非法访问');
		$admpwd=I('post.admpwd');
		empty($admpwd) && $this->error('请输入管理员密码');
		$topUpItem=I('post.topUpItem');
		empty($topUpItem) && $this->error('请选择充值项');
		$amount=I('post.amount');
		empty($amount) && $this->error('请输入充值金额');
		$api = D('User');
        $res = $api->verifyUser(USERID,$admpwd);
		if($res>0){
			if($topUpItem==1){
				if($amount>0){
					$resu=setScoreOrDeposit($membid,$amount,'deposit','','Member');
					if($resu){
						action_log("user_add_deposit",'Member',$amount,$membid,USERID,1);
						$this->success('金额充值成功！');
						exit();
					}
				}elseif($amount<0){
					$resu=setScoreOrDeposit($membid,$amount,'deposit','','Member');
					if($resu){
						action_log("user_reduce_deposit",'Member',$amount,$membid,USERID,1);
						$this->success('金额扣款成功！');
						exit();
					}
				}
			}elseif($topUpItem==2){
				if($amount>0){
					$resu=setScoreOrDeposit($membid,$amount,'score','','Member');
					if($resu){
						action_log("user_add_score",'Member',$amount,$membid,USERID,1);
						$this->success('积分充值成功！');
						exit();
					}
				}elseif($amount<0){
					$resu=setScoreOrDeposit($membid,$amount,'score','','Member');
					if($resu){
						action_log("user_reduce_score",'Member',$amount,$membid,USERID,1);
						$this->success('积分扣款成功！');
						exit();
					}
				}
			}
		}
		$this->error('操作失败！');
	}
	/**
	 * 明细列表
	 */
	public function listDetail(){
       	$title=I('title');
		$status=I('status');
		$where=array();
		if($title){
			$where['nickname|realname|mobile']=array('like','%'.$title.'%');
		}
		if($status||$status=0){
			$where['status']=$status;	
		}
		$lists=$this->lists('Member',$where,array('id'=>'asc'));
        int_to_string($lists,array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核')));
        $this->assign('list',$lists);
		$this->assign('do','listInfo');
		$this->assign('where',$where);
        $this->display();
    }
	/**
	 * 积分明细
	 */
	public function scoreDetail($id=null){
		empty($id) && $this->error('请输入当前用户ID');
		$where=array('membid'=>$id,'credittype'=>'score');
		$lists=$this->lists('CheckBill',$where,array('createtime'=>'desc','id'=>'asc'));
		int_to_string($lists,array('type'=>array(1=>'增加',2=>'减少')));
		$this->assign('id',$id);
		$this->assign('list',$lists);
		$this->display();
	}
	/**
	 * 余额明细
	 */
	public function depositDetail($id=null){
		empty($id) && $this->error('请输入当前用户ID');
		$where=array('membid'=>$id,'credittype'=>'deposit');
		$lists=$this->lists('CheckBill',$where,array('createtime'=>'desc','id'=>'asc'));
		int_to_string($lists,array('type'=>array(1=>'增加',2=>'减少')));
		$this->assign('id',$id);
		$this->assign('list',$lists);
		$this->display();
	}
	/**
	 * 个人中心字段管理
	 */
	public function mfield(){
		if(IS_POST){
			$data=I();
			foreach ($data['id'] as $key => $value) {
				$savdat=array(
					'id'=>$value,
					'status'=>intval($data['status'][$key]),
					'sort'=>intval($data['sort'][$key]),
					'updatetime'=>NOW_TIME,
				);
				M('MemberEnlarge')->save($savdat);
			}
			$this->success('修改成功',U("Member/mfield"));
		}else{
			$lists=M('MemberEnlarge')->field('id,mfield,status,sort,showname,type')->order('sort desc,id asc ')->select();
			int_to_string($lists,array('type'=>array(0=>'文本框',1=>'选择框',2=>'文本域',3=>'图片框',4=>'日期框',5=>'单选按钮',6=>'复选按钮')));
			$this->assign('list',$lists);
			$this->display();
		}
	}
	/**
	 * 修改字段
	 */
	public function savefield(){
		if(IS_POST){
			$data=I();
			$savdat=array(
				'id'=>intval($data['id']),
				'status'=>intval($data['status']),
				'sort'=>intval($data['sort']),
				'showname'=>strval($data['showname']),
				'desc'=>strval($data['desc']),
				'updatetime'=>NOW_TIME,
			);
			if(empty($savdat['showname']))$this->error('显示名称不可为空！');
			//if(empty($savdat['sort']))$this->error('排序不可为空！');
			M('MemberEnlarge')->save($savdat);
			$this->success('修改成功',U("Member/mfield"));
		}else{
			$id=I('id');
			$enlarge=M('MemberEnlarge')->where(array('id'=>$id))->find();
			$this->assign('enlarge',$enlarge);
			$this->display();
		}
	}
	/**
	 * 个人中心设在
	 */
	public function membPerson(){
		$lists=M('MemberPersonal')->field('id,icon,name,url,status,sort,createtime')->order('sort desc,id asc ')->select();
		int_to_string($lists,array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核')));
		$this->assign('list',$lists);
		$this->display();
	}
	/**
	 * 个人中心设在
	 */
	public function addMembPer(){
		if(IS_POST){
			$data=I();
			if(empty($data['id'])){
				unset($data['id']);
				M('MemberPersonal')->add($data);
				$this->success('添加成功！',U('Member/membPerson'));
			}else{
				M('MemberPersonal')->save($data);
				$this->success('更新成功！',U('Member/membPerson'));
			}
		}else{
			$id=I('id');
			if(!empty($id)){
				$personal=M('MemberPersonal')->where(array('id'=>$id))->find();
				$this->assign('personal',$personal);
			}
			$this->display();
		}
	}
	    /**
     * 删除日志
     * @param mixed $ids
     * @author huajie <banhuajie@163.com>
     */
    public function remove($ids = 0){
        empty($ids) && $this->error('参数错误！');
        if(is_array($ids)){
            $map['id'] = array('in', $ids);
        }elseif (is_numeric($ids)){
            $map['id'] = $ids;
        }
        $res = M('ActionLog')->where($map)->delete();
        if($res !== false){
            $this->success('删除成功！');
        }else {
            $this->error('删除失败！');
        }
    }

    /**
     * 清空日志
     */
    public function clear(){
        $res = M('ActionLog')->where('1=1')->delete();
        if($res !== false){
            $this->success('日志清空成功！');
        }else {
            $this->error('日志清空失败！');
        }
    }
	/**
	 * 统计
	 */
	public function statistics() {
		$add_num=M('MemberFans')->where(array('followtime'=>array(array('egt',strtotime(date('Y-m-d')) - 86400),array('elt',strtotime(date('Y-m-d'))),'and'),'follow'=>1))->count();
		$cancel_num = M('MemberFans')->where(array('unfollowtime'=>array(array('egt',strtotime(date('Y-m-d')) - 86400),array('elt',strtotime(date('Y-m-d'))),'and'),'follow'=>1))->count();
		$jing_num = $add_num - $cancel_num;
		$total_num = M('MemberFans')->where(array('followtime'=>array('elt',strtotime(date('Y-m-d'))),'follow'=>1))->count();
		
		$today_add_num=M('MemberFans')->where(array('followtime'=>array(array('egt',strtotime(date('Y-m-d'))),array('elt',strtotime(date())),'and'),'follow'=>1))->count();
		$today_cancel_num = M('MemberFans')->where(array('unfollowtime'=>array(array('egt',strtotime(date('Y-m-d'))),array('elt',strtotime(date())),'and'),'follow'=>1))->count();
		$today_jing_num = $today_add_num - $today_cancel_num;
		$today_total_num = M('MemberFans')->where(array('followtime'=>array('elt',strtotime(date('Y-m-d'))),'follow'=>1))->count();
		
		$this->assign('add_num',$add_num);
		$this->assign('cancel_num',$cancel_num);
		$this->assign('jing_num',$jing_num);
		$this->assign('total_num',$total_num);
		
		$this->assign('today_add_num',$today_add_num);
		$this->assign('today_cancel_num',$today_cancel_num);
		$this->assign('today_jing_num',$today_jing_num);
		$this->assign('today_total_num',$today_total_num);
		
		$st = $_REQUEST['datelimit']['start'] ? strtotime($_REQUEST['datelimit']['start']) : strtotime('-30day');
		$et = $_REQUEST['datelimit']['end'] ? strtotime($_REQUEST['datelimit']['end']) : strtotime(date('Y-m-d'));
		$starttime = min($st, $et);
		$this->assign('starttime',$starttime);
		$endtime = max($st, $et);
		$this->assign('endtime',$endtime);
		$day_num = ($endtime - $starttime) / 86400 + 1;
		$endtime += 86399;
		$type = intval($_REQUEST['type']) ? intval($_REQUEST['type']) : 1;
		if (IS_AJAX && IS_POST) {
			$days = array();
			$datasets = array();
			for ($i = 0; $i < $day_num; $i++) {
				$key = date('m-d', $starttime + 86400 * $i);
				$days[$key] = 0;
				$datasets['flow1'][$key] = 0;
				$datasets['flow2'][$key] = 0;
				$datasets['flow3'][$key] = 0;
				$datasets['flow4'][$key] = 0;
			}
			$data = M('MemberFans')->where(array('followtime'=>array(array('egt',$starttime),array('elt',$endtime),'and'),'follow'=>1))->select();
			foreach ($data as $da) {
				$key = date('m-d', $da['followtime']);
				if (in_array($key, array_keys($days))) {
					$datasets['flow1'][$key]++;
				}
			}
			$data = M('MemberFans')->where(array('unfollowtime'=>array(array('egt',$starttime),array('elt',$endtime),'and'),'follow'=>0))->select();
			foreach ($data as $da) {
				$key = date('m-d', $da['unfollowtime']);
				if (in_array($key, array_keys($days))) {
					$datasets['flow2'][$key]++;
				}
			}
			$data0=M('MemberFans')->where(array('unfollowtime'=>array(array('egt',$starttime),array('elt',$endtime),'and'),'follow'=>0))->select();
			$data1=M('MemberFans')->where(array('followtime'=>array(array('egt',$starttime),array('elt',$endtime),'and'),'follow'=>1))->select();
			foreach ($data1 as $da) {
				$key = date('m-d', $da['followtime']);
				if (in_array($key, array_keys($days))) {
					$day[date('m-d', $da['followtime'])]++;
					$datasets['flow3'][$key]++;
				}
			}
			foreach ($data0 as $da) {
				$key = date('m-d', $da['unfollowtime']);
				if (in_array($key, array_keys($days))) {
					$datasets['flow3'][$key]--;
				}
			}
			for ($i = 0; $i < $day_num; $i++) {
				$key = date('m-d', $starttime + 86400 * $i);
				$datasets['flow4'][$key] = M('MemberFans')->where(array('followtime'=>array('lt',($starttime + 86400 * $i + 86439)),'follow'=>1))->count();
			}
			$shuju['label'] = array_keys($days);
			$shuju['datasets'] = $datasets;
			if ($day_num == 1) {
				$day_num = 2;
				$shuju['label'][] = $shuju['label'][0];
				foreach ($shuju['datasets']['flow1'] as $ky => $va) {
					$k = $ky;
					$v = $va;
				}
				$shuju['datasets']['flow1']['-'] = $v;
				foreach ($shuju['datasets']['flow2'] as $ky => $va) {
					$k = $ky;
					$v = $va;
				}
				$shuju['datasets']['flow2']['-'] = $v;
				foreach ($shuju['datasets']['flow3'] as $ky => $va) {
					$k = $ky;
					$v = $va;
				}
				$shuju['datasets']['flow3']['-'] = $v;
				foreach ($shuju['datasets']['flow4'] as $ky => $va) {
					$k = $ky;
					$v = $va;
				}
				$shuju['datasets']['flow4']['-'] = $v;
			}
			$shuju['datasets']['flow1'] = array_values($shuju['datasets']['flow1']);
			$shuju['datasets']['flow2'] = array_values($shuju['datasets']['flow2']);
			$shuju['datasets']['flow3'] = array_values($shuju['datasets']['flow3']);
			$shuju['datasets']['flow4'] = array_values($shuju['datasets']['flow4']);
			echo json_encode($shuju);
			exit();
		}
		$this -> display();
		
	}
	/**
	 * 管理员列表
	 */
	public function userList(){
       $title=I('title');
		$status=I('status');
		$where=array();
		if($title){
			$where['username']=array('like','%'.$title.'%');
		}
		if(is_numeric($status)){
			$where['status']=$status;	
		}
		$lists=$this->lists('User',$where,array('id'=>'asc'));
        int_to_string($lists,array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核'),'sex'=>array(1=>'男',0=>'保密',2=>'女')));
        $this->assign('list',$lists);
		$this->assign('do','listInfo');
		$this->assign('where',$where);
        $this->display();
    }
	/**
	 * 添加管理员
	 */
	public function addUser(){
        if(IS_POST){
        	$user=array(
        		'username'=>I('username'),
        		'email'=>I('email'),
        		'mobile'=>I('mobile'),
        		'qq'=>I('qq'),
        		'sex'=>I('sex'),
        		'birthday'=>I('birthday'),
			);
			 /* 检测密码 */
	        $password= I('password');
			$repassword=I('repassword');
            if(!empty($password)){
	            if($password != $repassword){
	                $this->error('密码和重复密码不一致！');
	            }else{
	            	$user['password']=I('password');
	            }
            }
			$id=I('id');
			if(empty($id)){
				$user['reg_time']=NOW_TIME;
				if(empty($user['group'])){
					$user['group']=C('MEMB_DEFAULR_GROUPID');
				}
				$memb=D('User');
				$id=$memb->register($user);
				if(is_numeric($id)){
					$this->success('用户信息添加成功！',U('userList'));
				}else{
					$this->error($id);
				}
			}else{
				$user['id']=$id;
				$er=D('User')->updateUserFields($id,$user);
				if(is_numeric($er)){
					$this->success('用户信息更新成功！',U('userList'));
				}else{
					$this->error($er);
				}
			}
        }else{
        	$id=I('id');
			if(!empty($id)){
				$user=M('User')->where(array('id'=>$id))->find();
				$this->assign('id',$id);
				$this->assign('user',$user);
			}
            $this->display();
        }
    }
	
	public function updUserPwd(){
		$membid=I('membid');
		$this->assign('membid',$membid);
		if(empty($membid)){
			$this->display('error');
		}else{
			$this->display();
		}
	}
	/**
     * 修改密码提交
     * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    public function subUserPwd(){
        //获取参数
        $membid = I('post.membid');
        $admpwd = I('post.admpwd');
        empty($admpwd) && $this->error('请输入当前管理员密码');
        $password = I('post.password');
        empty($password) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码'); 
        if($password !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }
    	$api = D('User');
        $res = $api->verifyUser(USERID,$admpwd);
		if($res>0){
        	$res = $api->updateInfo($membid,$password);
	        if($res['status']){
	            $this->success($res['details']);
	        }else{
	            $this->error($res['details']);
	        }
		}else{
			 $this->error("修改密码失败！");
		}
    }
}
?>