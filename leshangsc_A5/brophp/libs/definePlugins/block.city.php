<?php
function smarty_block_city($params,$content,&$smarty,&$repeat){
	
	$fixed_params=array("id","is_open","description_len","notice_len","name","is_sort");
	foreach($params as $key=>$var){
		if(!in_array($key,$fixed_params)){
			$message="错误，无此".$key."参数!";
			$repeat=false;
			return $message;
		}
	}
	
	
	extract($params);
	
	if(!isset($name)){
		$name="row";
	}
	if(!isset($is_sort)){
		$is_sort=0;
	}
	if(!isset($id)){
		$id='all';
	}
	if(!isset($is_open)){
		$is_open=1;
	}
	
	if(!isset($smarty->block_data)){          
	  $smarty->block_data = array();
	}
	
	$dataindex = md5(__FUNCTION__.md5(serialize($params)));
	$dataindex = substr($dataindex,0,16);
	
	if(!isset($smarty->block_data[$dataindex])){
		$city=new City();
		$smarty->block_data[$dataindex]=$city->get_city_list($id,$is_open,$is_sort);
	}
	
	
	
	
	if(is_array($smarty->block_data[$dataindex])){
		$item = array_shift($smarty->block_data[$dataindex]);
		if(isset($description_len)){
			$item['description']=Common::g_substr($item['description'],$description_len);
		}
		if(isset($notice_len)){
			$item['notice']=Common::g_substr($item['notice'],$notice_len);
		}
		$smarty->assign($name, $item);
		if(count($smarty->block_data[$dataindex]) == 0){
			$smarty->block_data[$dataindex] = false;
		}
		$repeat = true;
	}else{
		$repeat = false;
	}

	return $content;
}
?>