<?php
	class District {
		function ajax_district(){
			$id=intval($_POST['id']);
			$district=D("District","admin");
			$sdistrict=$district->load($id);
			foreach($sdistrict as $k=>$v){
				$target[$v['id']]=$v['district_name'];
			}
			exit(json_encode($target));
		}
	}