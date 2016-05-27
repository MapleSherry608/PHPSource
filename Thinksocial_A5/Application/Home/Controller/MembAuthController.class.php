<?php
namespace Home\Controller;
class MembAuthController extends HomeController{
	/**
	 * 此处开发用户的登入页面用于在非微信浏览器中访问时登入
	 */
	public function index(){
		$this->display();
	}
}