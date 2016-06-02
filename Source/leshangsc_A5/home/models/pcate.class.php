<?php
	class Pcate{
		function page_list(){
			$id=intval($_GET['id']);
			return $this->where(array("id"=>$id))->select();
		}
	}