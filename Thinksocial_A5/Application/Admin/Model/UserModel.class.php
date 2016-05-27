<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
/**
 * 会员模型
 */
class UserModel extends Model{

	/* 用户模型自动验证 */
	protected $_validate = array(
		/* 验证用户名 */
		array('username', '1,30', -1, self::EXISTS_VALIDATE, 'length'), //用户名长度不合法
		array('username', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用

		/* 验证密码 */
		array('password', '6,30', -4, self::EXISTS_VALIDATE, 'length'), //密码长度不合法

		/* 验证邮箱 */
		array('email', 'email', -5, self::EXISTS_VALIDATE), //邮箱格式不正确
		array('email', '1,32', -6, self::EXISTS_VALIDATE, 'length'), //邮箱长度不合法
		array('email', '', -8, self::EXISTS_VALIDATE, 'unique'), //邮箱被占用

		/* 验证手机号码 */
		array('mobile', '//', -9, self::EXISTS_VALIDATE), //手机格式不正确 TODO:
		array('mobile', '', -11, self::EXISTS_VALIDATE, 'unique'), //手机号被占用
	);

	/* 用户模型自动完成 */
	protected $_auto = array(
		array('password', 'think_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
		array('reg_time', NOW_TIME, self::MODEL_INSERT),
		array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
		array('update_time', NOW_TIME),
	);

	/**
	 * 注册一个新用户
	 * @param  string $username 用户名
	 * @param  string $password 用户密码
	 * @param  string $email    用户邮箱
	 * @param  string $mobile   用户手机号码
	 * @return integer          注册成功-用户信息，注册失败-错误编号
	 */
	public function register($data){
		//验证手机
		if(empty($data['mobile'])) unset($data['mobile']);

		/* 添加用户 */
		if($this->create($data)){
			$uid = $this->add();
			return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
		} else {
			return $this->getError(); //错误详情见自动验证注释
		}
	}

	/**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($uid){
        /* 检测是否在当前应用注册 */
        $user = $this->field(true)->find($uid);
        if(!$user || 1 != $user['status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        }

        //记录行为
        action_log('user_login', 'user', $uid,0, $uid,1);

        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

	/**
	 * 获取管理员名称
	 */
	public function getUserName($uid){
        return $this->where(array('id'=>(int)$uid))->getField('username');
    }
	/**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'id'             => $user['id'],
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(1),
        );
        $this->save($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'id'             => $user['id'],
            'username'        => $user['username'],
            'last_login_time' => $user['last_login_time'],
        );

        session('admin_auth', $auth);
        session('admin_auth_sign', data_auth_sign($auth));

    }

	/**
     * 注销当前用户
     * @return void
	 * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    public function logout(){
        session('admin_auth', null);
        session('admin_auth_sign', null);
    }

	/**
	 * 用户登录认证
	 * @param  string  $username 用户名
	 * @param  string  $password 用户密码
	 * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-USERID）
	 * @return integer           登录成功-用户ID，登录失败-错误编号
	 */
	public function loginApprove($username, $password, $type = 1){
		$map = array();
		switch ($type) {
			case 1:
				$map['username'] = $username;
				break;
			case 2:
				$map['email'] = $username;
				break;
			case 3:
				$map['mobile'] = $username;
				break;
			case 4:
				$map['id'] = $username;
				break;
			default:
				return 0; //参数错误
		}

		/* 获取用户数据 */
		$user = $this->where($map)->find();
		
		if(is_array($user) && $user['status']){
			/* 验证用户密码 */
			if(think_md5($password, UC_AUTH_KEY) === $user['password']){
				$this->updateLogin($user['id']); //更新用户登录信息
				return $user['id']; //登录成功，返回用户ID
			} else {
				return -2; //密码错误
			}
		} else {
			return -1; //用户不存在或被禁用
		}
	}

	/**
	 * 获取用户信息
	 * @param  string  $uid         用户ID或用户名
	 * @param  boolean $is_username 是否使用用户名查询
	 * @return array                用户信息
	 */
	public function info($uid, $is_username = false){
		$map = array();
		if($is_username){ //通过用户名获取
			$map['username'] = $uid;
		} else {
			$map['id'] = $uid;
		}

		$user = $this->where($map)->field('id,username,email,mobile,status')->find();
		if(is_array($user) && $user['status'] = 1){
			return array($user['id'], $user['username'], $user['email'], $user['mobile']);
		} else {
			return -1; //用户不存在或被禁用
		}
	}

	/**
	 * 检测用户信息
	 * @param  string  $field  用户名
	 * @param  integer $type   用户名类型 1-用户名，2-用户邮箱，3-用户电话
	 * @return integer         错误编号
	 */
	public function checkField($field, $type = 1){
		$data = array();
		switch ($type) {
			case 1:
				$data['username'] = $field;
				break;
			case 2:
				$data['email'] = $field;
				break;
			case 3:
				$data['mobile'] = $field;
				break;
			default:
				return 0; //参数错误
		}

		return $this->create($data) ? 1 : $this->getError();
	}
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
			//action_log("user_upde_userpwd",'Member',$membid,$membid,USERID,1);
			return array('status'=>1 ,'details'=>"信息修改成功!");
		}else{
			return array('status'=>0 ,'details'=>"未获取到管理员密码");
		}
	}
	/**
	 * 更新用户登录信息
	 * @param  integer $uid 用户ID
	 */
	protected function updateLogin($uid){
		$data = array(
			'id'              => $uid,
			'last_login_time' => NOW_TIME,
			'last_login_ip'   => get_client_ip(1),
		);
		$this->save($data);
	}

	/**
	 * 更新用户信息
	 * @param int $uid 用户id
	 * @param string $password 密码，用来验证
	 * @param array $data 修改的字段数组
	 * @return true 修改成功，false 修改失败
	 * @author 沙哈拉的寂寞 <1032453491@qq.com>
	 */
	public function updateUserFields($uid, $data){
		if(empty($uid) || empty($password) || empty($data)){
			$this->error = '参数错误！';
			return false;
		}

		//更新用户信息
		$data = $this->create($data);
		if($data){
			return $this->where(array('id'=>$uid))->save($data);
		}
		return false;
	}

	/**
	 * 验证用户密码
	 * @param int $uid 用户id
	 * @param string $password_in 密码
	 * @return true 验证成功，false 验证失败
	 * @author 沙哈拉的寂寞 <1032453491@qq.com>
	 */
	public function verifyUser($uid, $password_in){
		$password = $this->getFieldById($uid, 'password');
		if(think_md5($password_in, UC_AUTH_KEY) == $password){
			return true;
		}
		return false;
	}
	
}
