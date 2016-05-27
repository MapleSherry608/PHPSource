<?php
	class Product{
		function load($id){
			$datas=$this->where(array("id"=>$id))->select();
			return $datas[0];
		}
		function lists($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("add_time desc")->select();
			} else {
				$datas=$this->order("add_time desc")->where(array("cate_id"=>$cate_id))->select();
			}
			return $datas;
		}
		function list_u($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("id desc")->where(array("user_id <>"=>0))->select();
			} else {
				$datas=$this->order("id desc")->where(array("cate_id"=>$cate_id,"user_id <>"=>0))->select();
			}
			return $datas;
		}
		function mod($id,$post){
			return $this->where(array("id"=>$id))->update($post);
		}
		function verify($id){
			return $this->where(array("id"=>$id))->update("verify=1");
		}
		function totals($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->total();
			} else {
				$datas=$this->where(array("cate_id"=>$cate_id))->total();
			}
			return $datas;
		}
		function totals_u($cate_id){
			if(!isset($cate_id) || !$cate_id){
				$datas=$this->order("id desc")->where(array("user_id <>"=>0))->total();
			} else {
				$datas=$this->order("id desc")->where(array("cate_id"=>$cate_id,"user_id <>"=>0))->total();
			}
			return $datas;
		}
		function add($post){
			if($this->insert($post,1,1)){
				$this->setMsg("添加成功");
				return true;
			} else {
				$this->setMsg("添加失败");
				return false;
			}
		}
		
		function filter_img($img){
			return  $this->where(array("img"=>$img))->find();
		}
	}