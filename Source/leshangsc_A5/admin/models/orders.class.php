<?php
	class Orders{
		function orders_list(){
			$datas=$this->order("id desc")->select();
			return $datas;
		}
		function search($post){
			$condition=array();
			if($post['sn']!=''){
				$a=array("sn"=>"%'{$post['sn']}'%");
				$condition=array_merge($condition,$a);
			}
			if($post['name']!=''){
				$b=array("name"=>"%'{$post['name']}'%");
				$condition=array_merge($condition,$b);
			}
			if($post['tel']!=''){
				$c=array("tel"=>"%'{$post['tel']}'%");
				$condition=array_merge($condition,$c);
			}
			if($post['pay_status']!=-1){
				$d=array("pay_status"=>"%'{$post['pay_status']}'%");
				$condition=array_merge($condition,$d);
			}
			if($post['payment_id']!=-1){
				$d=array("payment_id"=>"%'{$post['payment_id']}'%");
				$condition=array_merge($condition,$d);
			}
			if($post['delivery_status']!=-1){
				$e=array("delivery_status"=>"%'{$post['delivery_status']}'%");
				$condition=array_merge($condition,$e);
			}
			if($post['order_status']!=-1){
				$f=array("order_status"=>"%'{$post['order_status']}'%");
				$condition=array_merge($condition,$f);
			}
			return $this->order("id desc")->where($condition)->select();
		}
		function search_total($post){
			
			$condition=array();
			if($post['sn']!=''){
				$a=array("sn"=>"%'{$post['sn']}'%");
				$condition=array_merge($condition,$a);
			}
			if($post['name']!=''){
				$b=array("name"=>"%'{$post['name']}'%");
				$condition=array_merge($condition,$b);
			}
			if($post['tel']!=''){
				$c=array("tel"=>"%'{$post['tel']}'%");
				$condition=array_merge($condition,$c);
			}
			if($post['pay_status']!=-1){
				$d=array("pay_status"=>"%'{$post['pay_status']}'%");
				$condition=array_merge($condition,$d);
			}
			if($post['payment_id']!=-1){
				$d=array("payment_id"=>"%'{$post['payment_id']}'%");
				$condition=array_merge($condition,$d);
			}
			if($post['delivery_status']!=-1){
				$e=array("delivery_status"=>"%'{$post['delivery_status']}'%");
				$condition=array_merge($condition,$e);
			}
			if($post['order_status']!=-1){
				$f=array("order_status"=>"%'{$post['order_status']}'%");
				$condition=array_merge($condition,$f);
			}
			return $this->where($condition)->total();
		}
		function load($id){
			return $this->where(array("id"=>$id))->find();
		}
		function paid($id){
			return $this->where(array("id"=>$id))->update(array("pay_status"=>1,"pay_time"=>time()));
		}
		function deliver($id,$post){
			return $this->where(array("id"=>$id))->update($post);
		}
		function load_pid($pid){
			return $this->where(array("pid"=>$pid))->order("id desc")->select();
		}
		function mod($id,$post){
			return $this->where(array("id"=>$id))->update($post);
		}
		function success($id){
			return $this->where(array("id"=>$id))->update(array("order_status"=>1,"order_time"=>time()));
		}
		function close($id){
			return $this->where(array("id"=>$id))->update(array("order_status"=>0,"reason"=>trim($_POST['reason']),"order_time"=>time()));
		}
	}