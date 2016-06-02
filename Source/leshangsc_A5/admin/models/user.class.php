<?php
	class User{
		function user_list(){
			$datas=$this->order("id desc")->select();
			return $datas;
		}
		function search($post){
			return $this->order("id desc")->where(array("user_name"=>"%'{$post['key']}'%"),array("email"=>"%'{$post['key']}'%"),array("phone"=>"%'{$post['key']}'%"))->select();
		}
		function search_total($post){
			return $this->order("id desc")->where(array("user_name"=>"%'{$post['key']}'%"),array("email"=>"%'{$post['key']}'%"),array("phone"=>"%'{$post['key']}'%"))->total();
		}
		
		function load($user_id){
			$datas=$this->where(array("id"=>$user_id))->find();
			return $datas;
		}
		function mod($id){
			return $this->where(array("id"=>$id))->update();
		}
		function update_group($user_id,$score,$group_id){
			return $this->where(array("id"=>$user_id))->update(array("score"=>$score,"group_id"=>$group_id));
		}
		function filter_img($img){
			return  $this->where(array("photo"=>$img))->find();
		}
		
		function statistic_result(){
			$today_start_time= strtotime(date('Y-m-d'));
			$today_end_time=strtotime(date('Y-m-d',strtotime('+1 day')));
			
			$month_start_time= strtotime(date('Y-m-01'));
			$month_end_time=strtotime(date('Y-m-d',strtotime('+1 month')));
			
			$total=$this->total();
			$today=$this->where(array("reg_time >="=>$today_start_time,"reg_time <="=>$today_end_time))->total();
			$month=$this->where(array("reg_time >="=>$month_start_time,"reg_time <="=>$month_end_time))->total();
			$res['total']=$total;
			$res['today']=$today;
			$res['month']=$month;
			return $res;
		}
	}