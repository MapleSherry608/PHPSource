<?php
	class Product {
		function lists($rec,$cate_id=0){
			if($cate_id){
				$datas=$this->where(array("is_recommend"=>$rec,"cate_id"=>$cate_id,"status"=>1))->find();
			} else {
				$datas=$this->where(array("is_recommend"=>$rec,"status"=>1))->find();
			}
			return $datas;
		}
		function list_u($cate_id,$user_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("id desc")->where(array("user_id"=>$user_id))->select();
			} else {
				$datas=$this->order("id desc")->where(array("cate_id"=>$cate_id,"user_id"=>$user_id))->select();
			}
			return $datas;
		}
		function add($post){
			if($this->insert($post,1,1)){
				$this->setMsg("发布成功");
				return true;
			} else {
				$this->setMsg("发布失败");
				return false;
			}
		}
		function click($id){
			return $this->where(array("id"=>$id))->update("click=click+1");
		}
		function mod($id,$post){
			return $this->where(array("id"=>$id))->update($post);
		}
		function hot(){
			return $this->order("click desc")->select();
		}
		function all(){
			return $this->where(array("status"=>1))->select();
		}
		function load_cate($cate_id){
			return $this->where(array("cate_id"=>$cate_id,"status"=>1))->order("sort desc")->select();
		}
		function load_cate_total($cate_id){
			return $this->where(array("cate_id"=>$cate_id,"status"=>1))->order("sort desc")->total();
		}
		function load($pid){
			return $this->where(array("id"=>$pid))->find();
		}
		function load_brand($bid){
			return $this->where(array("brand_id"=>$bid,"status"=>1))->order("sort desc")->select();
		}
		function load_brand_total($bid){
			return $this->where(array("brand_id"=>$bid,"status"=>1))->total();
		}
		function product_num($uid){
			return $this->where(array("user_id"=>$uid,"verify"=>0))->total();
		}
		function totals_u($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("id desc")->where(array("user_id"=>$_SESSION['user']['id']))->total();
			} else {
				$datas=$this->order("id desc")->where(array("cate_id"=>$cate_id,"user_id"=>$_SESSION['user']['id']))->total();
			}
			return $datas;
		}
		function search_list($keywords){
			return $this->where(array("name"=>"%'{$keywords}'%"))->select();
		}
		function search_total($keywords){
			return $this->where(array("name"=>"%'{$keywords}'%"))->total();
		}
	}