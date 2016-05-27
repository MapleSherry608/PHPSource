<?php
	class Productspec {
		function lists($pid){
			return $this->where(array("pid"=>$pid))->select();
		}
		function add($post){
			return $this->insert($post);
		}
		function mod($id,$post){
			return $this->where(array("id"=>$id))->update($post);
		}
		function del($pid){
			return $this->where(array("pid"=>$pid))->delete();
		}
	}