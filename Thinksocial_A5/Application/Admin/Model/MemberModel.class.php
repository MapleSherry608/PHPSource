<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model{

	//自动验证
	protected $_validate = array(
		array('email','require','邮箱不可为空！'),
		array('realname','require','用户名称不可为空！'),
		array('email','','该邮箱已被注册！',self::EXISTS_VALIDATE,'unique',self::MODEL_INSERT),
	);
	/* 用户模型自动完成 */
	protected $_auto = array(
		array('password', 'think_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
		array('createtime', NOW_TIME, self::MODEL_INSERT),
		array('lockip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
		array('logintime', NOW_TIME),
		array('status', '2', self::MODEL_INSERT, 'string'),
	);
	/**
	 * 修改密码
	 */
	public function updateInfo($membid, $password){
		if(empty($membid)){
			return array('status'=> 0 ,'details'=>"用户ID不可为空！");
		}
		if(USERID){
			$data=array(
				"id"=>$membid,
				"password"=>$password
			);
			$this->save($data);
			action_log("user_upde_membpwd",'Member',$membid,$membid,USERID,1);
			return array('status'=>1 ,'details'=>"信息修改成功!");
		}else{
			return array('status'=>0 ,'details'=>"未获取到管理员密码");
		}
	}
	/**
	 * 添加用户
	 */
	public function addMember($data){
		if($this->create($data)){
			$uid = $this->add();
			action_log('user_add_member', 'Member',$uid,$uid,USERID,1);
			return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
		} else {
			return $this->getError(); //错误详情见自动验证注释
		}
	}
	/**
	 * 添加用户
	 */
	public function saveMember($data){
		if($this->create($data,2)){
			$uid = $this->save();
			action_log('user_save_member', 'Member',$uid,$uid,USERID,1);
			
			return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
		} else {
			return $this->getError(); //错误详情见自动验证注释
		}
	}
}
?>