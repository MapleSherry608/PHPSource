<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Member\Api;
use Member\Api\Api;
use Member\Model\MemberModel;

class MemberApi extends Api{
	/**
	 * api函数调用的接口的方法
	 * @author 荒漠屠夫 <1032453491@qq.com>
	 */
	public static function getModel(){
		$model = new MemberApi();
		return $model;
	}
    /**
     * 构造方法，实例化操作模型
     */
    protected function _init(){
    	
        $this->model = new MemberModel();
    }

    /**
     * 注册一个新用户
     * @param  string $username 用户昵称
     * @param  string $password 用户密码
     * @param  string $email    用户邮箱
     * @param  string $mobile   用户手机号码
     * @return integer          注册成功-用户信息，注册失败-错误编号
     */
    public function register($nickname, $password, $email, $mobile = '',$avatar='',$openid='',$data=array()){
        return $this->model->register($nickname, $password, $email, $mobile,$avatar,$openid,$data);
    }
    /**
     * 用户登录认证
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-ID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function login($username, $password, $type = 1){
        return $this->model->login($username, $password, $type);
    }
/**
	 * 验证返回码
	 * $key 错误代码
	 */
	public function errorCode($key){
		 return $this->model->errorCode($key);
	}
		/**
	 * 直接修改用户密码！
	 */
	public function updatePassword($uid=0,$password=null,$paypwd=null){
		 return $this->model->updatePassword($uid, $password,$paypwd);
	}
    /**
     * 获取用户信息
     * @param  string  $uid         用户ID或用户名
     * @param  string $field 需要查询的字段
     * @return array                用户信息
     */
    public function info($uid, $field = true){
        return $this->model->info($uid, $field);
    }


    /**
     * 检测邮箱
     * @param  string  $email  邮箱
     * @return integer         错误编号
     */
    public function checkEmail($email){
        return $this->model->checkField($email, 2);
    }

    /**
     * 检测手机
     * @param  string  $mobile  手机
     * @return integer         错误编号
     */
    public function checkMobile($mobile){
        return $this->model->checkField($mobile, 3);
    }
	/**
	 * 用户登入时间更新
	 */
	public function updateLogin($uid){
		  return $this->model->updateLogin($uid);
	}
    /**
     * 更新用户信息
     * @param int $uid 用户id
     * @param string $password 密码，用来验证
     * @param array $data 修改的字段数组
     * @return true 修改成功，false 修改失败
     * @author 沙哈拉的寂寞 <1032453491@qq.com>
     */
    public function updateMemberFields($uid, $password, $data,$type=0){
       return $this->model->updateMemberFields($uid, $password, $data,$type);
    }

}
