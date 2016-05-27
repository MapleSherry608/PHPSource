<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Member\Model;
use Think\Model;
/**
 * 会员模型
 */
class MemberModel extends Model{
	/**
	 * 数据表前缀
	 * @var string
	 */
	protected $tablePrefix = UC_TABLE_PREFIX;

	/**
	 * 数据库连接
	 * @var string
	 */
	protected $connection = UC_DB_DSN;

	/* 用户模型自动验证 */
	protected $_validate = array(
		/* 验证用户名 */
		array('nickname', '1,30', -1, self::VALUE_VALIDATE, 'length' ), //用户名长度不合法
		array('openid', '', -2, self::VALUE_VALIDATE, 'unique'), //用Openid已经被使用

		/* 验证密码 */
		array('password','require',-3,self::MUST_VALIDATE,'regex',self::MODEL_INSERT), //密码不可为空
		array('password', '6,50', -4, self::VALUE_VALIDATE, 'length'), //密码长度不合法
		array('paypwd', '6,50', -4, self::VALUE_VALIDATE, 'length'), //密码长度不合法

		/* 验证邮箱 */
		array('email', 'email', -5, self::VALUE_VALIDATE), //邮箱格式不正确
		array('email', '1,50', -6, self::VALUE_VALIDATE, 'length'), //邮箱长度不合法
		array('email', '', -7, self::VALUE_VALIDATE, 'unique'), //邮箱被占用

		/* 验证手机号码 */
		array('mobile', '//', -8, self::VALUE_VALIDATE), //手机格式不正确 TODO:
		array('mobile', '', -9, self::VALUE_VALIDATE, 'unique'), //手机号被占用
	);

	/* 用户模型自动完成 */
	protected $_auto = array(
		array('createtime', NOW_TIME, self::MODEL_INSERT),
		array('reg_time', NOW_TIME, self::MODEL_INSERT),
		array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
		array('update_time', NOW_TIME, self::MODEL_BOTH),
		array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
	);
	public function getPwd($pwd){
		return think_md5($pwd,UC_AUTH_KEY);
	}
	/**
	 * 验证返回码
	 * $key 错误代码
	 */
	public function errorCode($key){
		if(is_numeric($key)){
			if($key>0){
				return '用户添加成功，当前用户id为“{$key}”!';
			}
			switch ($key){
	            case '-1':
	                return '用户名长度不合法';
				case '-2':
	                return '用Openid已经被使用';
				case '-3':
	                return '密码不可为空';
				case '-4':
	                return '密码长度不合法';
				case '-5':
	                return '邮箱格式不正确';
				case '-6':
	                return '邮箱长度不合法';
				case '-7':
	                return '邮箱被占用';
				case '-8':
	                return '手机格式不正确';
				case '-9':
	                return '手机号被占用';
				case '-10':
	                return '验证出错：密码不正确！';
				case '-11':
	                return '参数错误！';
	            default:
	                return '无效解析码！';
	        }
		}else{
			return '无效的解析码！';
		}
	}
	/**
	 * 根据配置指定用户状态
	 * @return integer 用户状态
	 */
	protected function getStatus(){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 注册一个新用户
	 * @param  string $username 用户昵称
	 * @param  string $password 用户密码
	 * @param  string $email    用户邮箱
	 * @param  string $mobile   用户手机号码
	 * @return integer          注册成功-用户信息，注册失败-错误编号
	 */
	public function register($nickname, $password, $email, $mobile,$avatar,$openid,$data=array()){
		$openid=strval($openid);
		if(!empty($nickname)){
			$data['nickname'] = $nickname;
		}
		$data['password'] = $this->getPwd($password);
		$data['email'] = $email;
		$data['mobile'] = $mobile;
		$data['avatar']= $avatar;
		//验证手机
		if(empty($data['mobile'])) unset($data['mobile']);
		if(empty($data['nickname'])) unset($data['nickname']);
		if(empty($data['email'])) unset($data['email']);
		if(!empty($openid)){
			$data['openid']=$openid;
			$idb=$this->where(array('openid'=>$openid))->getField('id');
			if(!empty($idb)){
				return $idb;
			}
		}
		
		if(empty($data['groupid'])){
			$data['groupid']=C('MEMB_DEFAULR_GROUPID');
		}
		if(empty($data['levelid'])){
			$data['levelid']=C('MEMB_DEFAULR_LEVELID');
		}
		/* 添加用户 */
		$model = new \Think\Model();
		$model->startTrans();
		if($sd=$this->create($data)){
			$uid = $this->add();
			$model->commit();
			return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
		} else {
			$model->rollback();
			return $this->getError(); //错误详情见自动验证注释
		}
	}
	/**
	 * 直接修改用户密码！
	 */
	public function updatePassword($uid=0,$password=null,$paypwd=null){
		
		if((empty($paypwd)&&empty($password))||empty($uid)){
			return '-11';
		}
		$data['id']=$uid;
		if(!empty($password)){
			$data['password']=$this->getPwd($password);
		}
		if(!empty($paypwd)){
			$data['paypwd']=$this->getPwd($paypwd);
		}
		
		//更新用户信息
		$dat = $this->create($data,2);
		if($dat){
			return $this->where(array('id'=>$uid))->save($data);
		}else{
			return $this->getError();
		}
	}
	/**
	 * 用户登录认证
	 * @param  string  $username 用户名
	 * @param  string  $password 用户密码
	 * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-ID）当状态为5的时候不需要通过密码验证就可以直接登入
	 * @return integer           登录成功-用户ID，登录失败-错误编号
	 */
	public function login($username, $password, $type = 1){
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
			case 5:
				$map['id'] = $username;
				break;
			default:
				return 0; //参数错误
		}
		/* 获取用户数据 */
		$user = $this->where($map)->find();
		if(is_array($user) && $user['status']){
			/* 验证用户密码 */
			if(think_md5($password, UC_AUTH_KEY) === $user['password']||$type==5){
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
	 * @param  string $field 需要查询的字段
	 * @return array                用户信息
	 */
	public function info($uid, $field=true){
		$map = array();
		$map['id'] = $uid;
		$user = $this->field($field)->where($map)->find();
		if(is_array($user) && $user['status'] = 1){
			return $user;
		} else {
			return -1; //用户不存在或被禁用
		}
	}

	/**
	 * 检测用户信息
	 * @param  string  $field  用户名
	 * @param  integer $type   用户名类型 1-电话，2-用户邮箱，3-qq
	 * @return integer         错误编号
	 */
	public function checkField($field, $type = 1){
		$data = array();
		switch ($type) {
			case 1:
				$data['mobile'] = $field;
				break;
			case 2:
				$data['email'] = $field;
				break;
			case 3:
				$data['qq'] = $field;
				break;
			default:
				return 0; //参数错误
		}

		return $this->create($data) ? 1 : $this->getError();
	}

	/**
	 * 更新用户登录信息
	 * @param  integer $uid 用户ID
	 */
	public function updateLogin($uid){
		$data = array(
			'id'              => $uid,
			'logintime' => NOW_TIME,
			'lockip'   => get_client_ip(1),
		);
		$this->save($data);
	}

	/**
	 * 更新用户信息
	 * @param int $uid 用户id
	 * @param string $password 密码，用来验证
	 * @param array $data 修改的字段数组
	 * @param array $type 0标示需要登入密码才可以进行设置 1.表示不用密码就可以进行更新设置
	 * @return true 修改成功，false 修改失败
	 * @author 沙哈拉的寂寞 <1032453491@qq.com>
	 */
	public function updateMemberFields($uid, $password, $data,$type=0){
		if(empty($uid) || empty($password) || empty($data)){
			return '-11';
		}
		
		//更新前检查用户密码
		if(!$this->verifyMember($uid, $password) && empty($type)){
			return '-10';
		}else{
			unset($data['password']);
			unset($data['paypwd']);
		}
		return $this->where(array('id'=>$uid))->save($data);
		
	}

	/**
	 * 验证用户密码
	 * @param int $uid 用户id
	 * @param string $password_in 密码
	 * @return true 验证成功，false 验证失败
	 * @author 沙哈拉的寂寞 <1032453491@qq.com>
	 */
	protected function verifyMember($uid, $password_in){
		$password = $this->getFieldById($uid, 'password');
		if(think_md5($password_in, UC_AUTH_KEY) === $password){
			return 1;
		}
		return false;
	}

}
