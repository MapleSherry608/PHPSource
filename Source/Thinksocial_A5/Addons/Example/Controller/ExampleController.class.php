<?php

namespace Addons\Example\Controller;
use Home\Controller\AddonsController;

class ExampleController extends AddonsController{
	
		//实现的pageHeader钩子方法
        public function ieader(){
			$this->display('index');
        }
}
