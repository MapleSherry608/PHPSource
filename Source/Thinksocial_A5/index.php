<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

	function isMobile(){
	    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';    
	    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';      
	    function CheckSubstrs($substrs,$text){    
	        foreach($substrs as $substr)    
	            if(false!==strpos($text,$substr)){    
	                return true;    
	            }    
	            return false;    
	    }  
	    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');  
	    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');    
	                
	    $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||    
	              CheckSubstrs($mobile_token_list,$useragent);    
	                
	    if ($found_mobile){    
	        return true;
	    }else{    
	        return false;    
	    }    
	}
	define('IS_MOBILE', intval(isMobile()));
	define('APP_DEBUG', true );
	
	/**
	 * 应用目录设置
	 * 安全期间，建议安装调试完成后移动到非WEB目录
	 */
	define ( 'APP_PATH', './Application/' );
	
	if(!is_file(APP_PATH . 'Member/Conf/config.php')){
		header('Location: ./install.php');
		exit;
	}
	
	/**
	 * 缓存目录设置
	 * 此目录必须可写，建议移动到非WEB目录
	 */
	define ( 'RUNTIME_PATH', './Runtime/' );
	
	/**
	 * 引入核心入口
	 * ThinkPHP亦可移动到WEB以外的目录
	 */
	require './ThinkPHP/ThinkPHP.php';
/*}else{
	header('Location: ./admin.php');
}*/
