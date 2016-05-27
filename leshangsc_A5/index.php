<?php
	/**
	 * 单一入口文件
	 */
	include("./temp.inc.php");
	define("TPLSTYLE", $temp['template']);                        //默认模板存放的目录
	define("BROPHP", "./brophp");  //框架源文件的位置
	define("APP", "./home");           //设置当前应用的目录
	define("PAGENUM",$temp['home_page_num']);                                 //默认每页显示记录数
	
	if(!file_exists("Install/install_lock.txt")){
		header("Location:Install/index.php");
		exit();
	}
	if(is_mobile_request()){
		header("Location:mobile.php");
	}

	require(BROPHP.'/brophp.php'); //加载框架的入口文件
	
	function is_mobile_request()  
		{  
			 $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
			 $mobile_browser = '0';  
			 if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
			  $mobile_browser++;  
			 if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
			  $mobile_browser++;  
			 if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
			  $mobile_browser++;  
			 if(isset($_SERVER['HTTP_PROFILE']))  
			  $mobile_browser++;  
			 $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
			 $mobile_agents = array(  
				'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
				'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
				'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
				'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
				'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
				'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
				'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
				'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
				'wapr','webc','winw','winw','xda','xda-'
				);  
			 if(in_array($mobile_ua, $mobile_agents))  
			  $mobile_browser++;  
			 if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
			  $mobile_browser++;  
			 // Pre-final check to reset everything if the user is on Windows  
			 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
			  $mobile_browser=0;  
			 // But WP7 is also Windows, with a slightly different characteristic  
			 if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
			  $mobile_browser++;  
			 if($mobile_browser>0)  
				return true;  
			 else
				return false;
		}
?>