<?php
	class Common extends Action {
		function init(){
			$config=D("Config","admin");
			$nav=D("Nav","home");
			$con_data=$config->config_list();
			$main_nav=$nav->main_list();
			$this->assign("main_nav",$main_nav);
			$this->assign("con_data",$con_data[0]);
		}		
	}