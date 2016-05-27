<?php
namespace Admin\Model;
use Think\Model;
class MemberGroupsModel extends Model{
	//自动验证
	 /* 自动验证规则 */
    protected $_validate = array(
        array('title', 'require', '用户组名称不可为空！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('sort', '/^[0-9]*$/', '排序必须为数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    	array('credit', '/^[0-9]*$/', '获取的条件必须为数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
    	array('createtime', 'time', self::MODEL_INSERT, 'function'),
    );
	/**
	 * 注册一个新用户
	 * @param  string $username 用户名
	 * @param  string $password 用户密码
	 * @param  string $email    用户邮箱
	 * @param  string $mobile   用户手机号码
	 * @return integer          注册成功-用户信息，注册失败-错误编号
	 */
	public function addGroup($data){
		$data=I();
		$data['membid'] = $mid;
		/* 添加用户 */
		if($this->create($data)){
			$uid = $this->add();
			//action_log('user_add_member', 'Member',$uid, $uid, USERID,1);
			return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
		} else {
			return $this->getError(); //错误详情见自动验证注释
		}
	}
	public function saveGroup($id,$membid){
		if(is_numeric($membid)&&$membid>0){
			$data=I();
			$data['membid'] = $id;
			if($this->create($data,2)){
				$uid = $this->save();
				//action_log('user_save_member', 'Member',$uid, $uid, USERID,1);
				return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
			} else {
				return $this->getError(); //错误详情见自动验证注释
			}
		}else{
			return $this->register($id);
		}
	}
}
?>