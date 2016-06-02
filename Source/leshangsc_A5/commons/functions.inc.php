<?php
	//全局可以使用的通用函数声明在这个文件中.
	
		//==========================================
		// 函数: get_ip()
		// 功能: 返回IP地址
		// 参数: 无
		//==========================================
		function get_ip() 
		{
			if(getenv('HTTP_CLIENT_IP')){
				$onlineip=getenv('HTTP_CLIENT_IP');
			}else if(getenv('HTTP_X_FORWARDED_FOR')){
				$onlineip=getenv('HTTP_X_FORWARDED_FOR');
			}else if(getenv('REMOTE_ADDR')){
				$onlineip=getenv('REMOTE_ADDR');
			}else{
				$onlineip=$_SERVER['REMOTE_ADDR'];
			}
			return $onlineip;
		}
		
		//==========================================
		// 函数: time_ago()		// 功能: 返回距现在多长时间
		// 参数: time 时间戳
		//==========================================
		function time_ago($time){
			$t=time()-$time;
			$f=array(
				'31536000'=>'年',
				'2592000'=>'个月',
				'604800'=>'星期',
				'86400'=>'天',
				'3600'=>'小时',
				'60'=>'分钟',
				'1'=>'秒'
			);
			foreach ($f as $k=>$v)    {
				if (0 !=$c=floor($t/(int)$k)) {
					return $c.$v.'前';
				}
			}
		}
		
		
		
		
		
		/* 
		Utf-8、gb2312都支持的汉字截取函数 
		cut_str(字符串, 截取长度, 开始长度, 编码); 
		编码默认为 utf-8 
		开始长度默认为 0 
		*/ 

		function cut_str($string, $sublen, $start = 0, $code = 'UTF-8'){ 
			if($code == 'UTF-8') { 
				$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
				preg_match_all($pa, $string, $t_string); 
				
				if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."..."; 
				return join('', array_slice($t_string[0], $start, $sublen)); 
				} 
				else 
				{ 
				$start = $start*2; 
				$sublen = $sublen*2; 
				$strlen = strlen($string); 
				$tmpstr = ''; 
				
				for($i=0; $i< $strlen; $i++) 
				{ 
				if($i>=$start && $i< ($start+$sublen)) 
				{ 
				if(ord(substr($string, $i, 1))>129) 
				{ 
				$tmpstr.= substr($string, $i, 2); 
				} 
				else 
				{ 
				$tmpstr.= substr($string, $i, 1); 
				} 
				} 
				if(ord(substr($string, $i, 1))>129) $i++; 
				} 
				if(strlen($tmpstr)< $strlen ) $tmpstr.= "..."; 
				return $tmpstr; 
			} 
		} 

		
		//==========================================
		// 函数: expression()
		// 功能: 显示QQ表情包
		// 参数: 无
		//==========================================
		
		function expression(){
			$s = '"0","微笑"],["1","撇嘴"],["2","色"],["3","发呆"],["4","得意"],["5","流泪"],["6","害羞"],["7","闭嘴"],["8","睡"],["9","大哭"],["10","尴尬"],["11","发怒"],["12","调皮"],["13","呲牙"],["14","惊讶"],["15","难过"],["16","酷"],["17","冷汗"],["18","抓狂"],["19","吐"],["20","偷笑"],["21","可爱"],["22","白眼"],["23","傲慢"],["24","饥饿"],["25","困"],["26","惊恐"],["27","流汗"],["28","憨笑"],["29","大兵"],["30","奋斗"],["31","咒骂"],["32","疑问"],["33","嘘..."],["34","晕"],["35","折磨"],["36","衰"],["37","骷髅"],["38","敲打"],["39","再见"],["40","擦汗"],["41","抠鼻"],["42","鼓掌"],["43","糗大了"],["44","坏笑"],["45","左哼哼"],["46","右哼哼"],["47","哈欠"],["48","鄙视"],["49","委屈"],["50","快哭了"],["51","阴险"],["52","亲亲"],["53","吓"],["54","可怜"],["55","菜刀"],["56","西瓜"],["57","啤酒"],["58","篮球"],["59","乒乓"],["60","咖啡"],["61","饭"],["62","猪头"],["63","玫瑰"],["64","凋谢"],["65","示爱"],["66","爱心"],["67","心碎"],["68","蛋糕"],["69","闪电"],["70","炸弹"],["71","刀"],["72","足球"],["73","瓢虫"],["74","便便"],["75","月亮"],["76","太阳"],["77","礼物"],["78","拥抱"],["79","强"],["80","弱"],["81","握手"],["82","胜利"],["83","抱拳"],["84","勾引"],["85","拳头"],["86","差劲"],["87","爱你"],["88","NO"],["89","OK"],["90","爱情"],["91","飞吻"],["92","跳跳"],["93","发抖"],["94","怄火"],["95","转圈"],["96","磕头"],["97","回头"],["98","跳绳"],["99","挥手"],["100","激动"],["101","街舞"],["102","献吻"],["103","左太极"],["104","右太极"';
			$a = explode('],[', $s);
			$html="<div class='qq_expression'><ul>";
			foreach($a as $v) {
				$b = explode(',', $v);
				$vv = str_replace('"', '', $b[0]);
				$f = 'http://res.mail.qq.com/zh_CN/images/mo/DEFAULT2/' . $vv . '.gif';
				$img_html='[exp]'.$b[1].'[/exp]';
				$html.="<li class='expression left' id='".$img_html."' style='cursor:pointer'><img src='".$f."' title=".$b[1]." /></li>";
			}
			
			$html.="</ul></div>";
			$datas=array("value"=>$a,"html"=>$html);
			return $datas;
		}
		
		function get_exp_num($str){
			$exp_datas=expression();
			foreach($exp_datas['value'] as $v) {
				$v=str_replace('"','',$v);
				$b = explode(',', $v);
				if($str==$b[1]){
					$vv = str_replace('"', '', $b[0]);
					return $vv;
					
				}
			}
			
			
		}