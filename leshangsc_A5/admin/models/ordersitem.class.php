<?php
	class Ordersitem {
		function load($oid){
			return $this->where(array("oid"=>$oid))->select();
		}
	}