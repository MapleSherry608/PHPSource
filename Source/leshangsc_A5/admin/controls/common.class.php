<?php
	class Common extends Action {
		function init(){
			if(!(isset($_SESSION['admin']["isLogin"]) && $_SESSION['admin']["isLogin"]==true)){
				$this->redirect("index/index");
			}
			
			$model=$_GET['m'];
			$action=$_GET['a'];
			$adminNode=D("Adminnode","admin");
			$adminGroup=D("Admingroup","admin");
			$nodeId=$adminNode->get($model,$action);
			if($nodeId){
				$g_datas=$adminGroup->load($_SESSION['admin']["group_id"]);
				$auth=unserialize(htmlspecialchars_decode($g_datas['auth']));
				
				if(!in_array($nodeId,$auth)){
					$this->error("您无权操作!", 2);
				}
			}
		}
		
		
	}