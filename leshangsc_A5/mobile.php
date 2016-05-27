<?php
	/**
	 * 单一入口文件
	 */
	 include("./temp.inc.php");
	define("TPLSTYLE", "default");                        //默认模板存放的目录
	define("BROPHP", "./brophp");  //框架源文件的位置
	define("APP", "./mobile");           //设置当前应用的目录
	define("PAGENUM",$temp['admin_page_num']);                                 //默认每页显示记录数
	require(BROPHP.'/brophp.php'); //加载框架的入口文件


?>