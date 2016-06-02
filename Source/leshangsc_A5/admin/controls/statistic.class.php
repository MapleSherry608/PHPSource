<?php
	class Statistic{
		function index(){
			if(!$this->is_cached("main/statistic_list",$_SERVER['REQUEST_URI'])){
				
				if($_GET['end_time']!="" || $_GET['start_time']!=""){
					$is_search=1;
					
					if($_GET['start_time']!=""){
						$start_time=strtotime($_GET['start_time']);
					} else {
						$start_time=time();
					}
					if($_GET['end_time']!=""){
						$end_time=strtotime($_GET['end_time']);
					} else {
						$end_time=time();
					}
					if($end_time<$start_time){
						$this->error("终止日期不能大于初始日期!",1);
					}
					if($_GET['start_time']==""){
						$_GET['start_time']="今";
					}
					if($_GET['end_time']==""){
						$_GET['end_time']="今";
					}
					$condition=$_GET['start_time'].'至'.$_GET['end_time'];
				} else{
					$start_time=strtotime(date("Y-01-01",time()));
					$end_time=strtotime(date('Y-01-01',strtotime('+1 year')));
					$is_search=0;
					$condition="";
				}
				$user=D("User");
				$group=D("Group");
				$main_list=$group->main_list();
				$result_class="";
				$result_data="";
				$result_total_data="";
				foreach($main_list as $k=>$v){
					$has_sub=$group->has_sub($v['id']);
					if($has_sub){
						$group_list=$group->where(array("pid"=>$v['id']))->select();
						foreach($group_list as $key=>$val){
							$result_data.=$user->where(array("reg_time >="=>$start_time,"reg_time <="=>$end_time,"group_id"=>$val['id']))->total().",";
							$result_total_data.=$user->where(array("group_id"=>$val['id']))->total().",";
							$result_class.="'".$val['name']."',";
						}
					} else {
						$result_class="'".$v['name']."',";
						$result_data.=$user->where(array("reg_time >="=>$start_time,"reg_time <="=>$end_time,"group_id"=>$v['id']))->total().",";
						$result_total_data.=$user->where(array("group_id"=>$v['id']))->total().",";
					}
				}
				$result_class=substr($result_class,0,-1);
				$result_data=substr($result_data,0,-1);
				$result_total_data=substr($result_total_data,0,-1);
				$result=$user->statistic_result();
				$this->assign("result_class",$result_class);
				$this->assign("result_data",$result_data);
				$this->assign("result_total_data",$result_total_data);
				$this->assign("result",$result);
				$this->assign("condition",$condition);
				$this->assign("is_search",$is_search);
			}
			$this->display("main/statistic_list",$_SERVER['REQUEST_URI']);
		}
		
	}