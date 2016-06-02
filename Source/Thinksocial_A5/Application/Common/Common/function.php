<?php 
require_once 'class/boot.inc.php';
$script_name=scriptname();
$sitepath = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
$siteroot = htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . $sitepath);
if(substr($siteroot, -1) != '/') {
    $siteroot .= '/';
}
$urls = parse_url($siteroot);
$urls['path'] = str_replace(array('/index.php', '/admin.php'), '', $urls['path']);
$siteroot = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '').$urls['path'];
$siteurl  = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '') . $script_name . (empty($_SERVER['QUERY_STRING'])?'':'?') . $_SERVER['QUERY_STRING'];

define('IA_ROOT', $_SERVER['DOCUMENT_ROOT'].ltrim(substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')),"/")."/");
define('SITEROOT', $siteroot);
define('SITEURL', $siteurl);
define('CLIENT_IP', get_client_ip());
define('ALIPAY_GATEWAY', 'https://mapi.alipay.com/gateway.do');
define('ATTACHMENT_ROOT', IA_ROOT .'Uploads/Picture');
const ONETHINK_ADDON_PATH = './Addons/';
/**
 * 调用指定函数
 * @param string $name
 * @return Ambigous <>|Ambigous <unknown>
 */
function m_m($name = '')
{
    define('OPENID',$_SESSION['OPENID']);
    $_SESSION['openid'] = $_SESSION['OPENID'] = OPENID;
    static $_modules = array();
    if (isset($_modules[$name])) {
        return $_modules[$name];
    }
    $model = APP_PATH . "Home/Common/model/" . strtolower($name) . '.php';
    if (!is_file($model)) {
        die(' Model ' . $name . ' Not Found!');
    }
    require $model;
    $class_name = 'Zxin_Dshop_' . ucfirst($name);
    $_modules[$name] = new $class_name();
    return $_modules[$name];
}

/**
 * 调用系统指定函数
 * @param string $name
 * @return Ambigous <>|Ambigous <unknown>
 */
function mod($name = '')
{
    static $_modules = array();
    if (isset($_modules[$name])) {
        return $_modules[$name];
    }
    $model = APP_PATH . "Common/Common/model/" . strtolower($name) . '.mod.php';
    if (!is_file($model)) {
        die(' Model ' . $name . ' Not Found!');
    }
    require $model;
    $class_name = 'Zxin_Dshop_' . ucfirst($name);
    $_modules[$name] = new $class_name();
    return $_modules[$name];
}
/**
 * 返回json数据
 * @param number $status
 * @param string $return
 */
function show_json($status = 1, $return = null)
{
    $ret = array('status' => $status);
    if ($return) {
        $ret['result'] = $return;
    }
    die(json_encode($ret));
}
/**
 * 生成随机数
 * @param unknown $length
 * @param string $numeric 返回数字或字母
 * @return string
 */
function random($length, $numeric = FALSE) {
    $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    if ($numeric) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}
/**
 * 微信支付参数设置
 * @param unknown $version1
 * @param unknown $version2
 * @return mixed
 */
function ver_compare($version1, $version2) {
    $version1 = str_replace('.', '', $version1);
    $version2 = str_replace('.', '', $version2);
    $oldLength = istrlen($version1);
    $newLength = istrlen($version2);
    if ($oldLength > $newLength) {
        $version2 .= str_repeat('0', $oldLength - $newLength);
    }
    if ($newLength > $oldLength) {
        $version1 .= str_repeat('0', $newLength - $oldLength);
    }
    $version1 = intval($version1);
    $version2 = intval($version2);
    return version_compare($version1, $version2);
}

function istrlen($string, $charset = '') {
    if (empty($charset)) {
        $charset = 'utf8';
    }
    if (strtolower($charset) == 'gbk') {
        $charset = 'gbk';
    } else {
        $charset = 'utf8';
    }
    if (function_exists('mb_strlen')) {
        return mb_strlen($string, $charset);
    } else {
        $n = $noc = 0;
        $strlen = strlen($string);

        if ($charset == 'utf8') {

            while ($n < $strlen) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $n += 2;
                    $noc++;
                } elseif (224 <= $t && $t <= 239) {
                    $n += 3;
                    $noc++;
                } elseif (240 <= $t && $t <= 247) {
                    $n += 4;
                    $noc++;
                } elseif (248 <= $t && $t <= 251) {
                    $n += 5;
                    $noc++;
                } elseif ($t == 252 || $t == 253) {
                    $n += 6;
                    $noc++;
                } else {
                    $n++;
                }
            }

        } else {

            while ($n < $strlen) {
                $t = ord($string[$n]);
                if ($t > 127) {
                    $n += 2;
                    $noc++;
                } else {
                    $n++;
                    $noc++;
                }
            }

        }

        return $noc;
    }
}

function array2xml($arr, $level = 1) {
    $s = $level == 1 ? "<xml>" : '';
    foreach ($arr as $tagname => $value) {
        if (is_numeric($tagname)) {
            $tagname = $value['TagName'];
            unset($value['TagName']);
        }
        if (!is_array($value)) {
            $s .= "<{$tagname}>" . (!is_numeric($value) ? '<![CDATA[' : '') . $value . (!is_numeric($value) ? ']]>' : '') . "</{$tagname}>";
        } else {
            $s .= "<{$tagname}>" . array2xml($value, $level + 1) . "</{$tagname}>";
        }
    }
    $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
    return $level == 1 ? $s . "</xml>" : $s;
}
/**
 * 判断是否成功
 * @param unknown $data
 * @return boolean
 */
function is_error($data) {
    if (empty($data) || !is_array($data) || !array_key_exists('errno', $data) || (array_key_exists('errno', $data) && $data['errno'] == 0)) {
        return false;
    } else {
        return true;
    }
}
/**
 * 获取当前完整路径
 * @return string
 */
function scriptname() {
    $script_name = basename($_SERVER['SCRIPT_FILENAME']);
    if(basename($_SERVER['SCRIPT_NAME']) === $script_name) {
        $script_name = $_SERVER['SCRIPT_NAME'];
    } else {
        if(basename($_SERVER['PHP_SELF']) === $script_name) {
            $script_name = $_SERVER['PHP_SELF'];
        } else {
            if(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $script_name) {
                $script_name = $_SERVER['ORIG_SCRIPT_NAME'];
            } else {
                if(($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false) {
                    $script_name = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $script_name;
                } else {
                    if(isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0) {
                        $script_name = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
                    } else {
                        $script_name = 'unknown';
                    }
                }
            }
        }
    }
    return htmlspecialchars($script_name);
}

/**
 * 读取Api
 * @param string $filename
 * @param string $name
 * @return Ambigous <>|Ambigous <unknown>
 */
function getApi($filename,$name){
    static $_modules = array();
    if (isset($_modules[$name])) {
        return $_modules[$name];
    }
    $model = APP_PATH . "Common/Api/".$filename."/" . strtolower($name) . '.php';
    if (!is_file($model)) {
        die(' Api ' . $name . ' Not Found!');
    }
    require $model;
    $class_name = 'Zxin_Dshop_' . ucfirst($name);
    $_modules[$name] = new $class_name();
    return $_modules[$name];
}
/**
 * 序列化
 * @param unknown $value
 * @return string
 */
function iserializer($value){
    return serialize($value);
}
/**
 * 反序列化
 * @param unknown $value
 * @return string|unknown|mixed
 */
function iunserializer($value){
    if (empty($value)) {
        return '';
    }
    if (!is_serialized($value)){
        return $value;
    }
    $result = unserialize($value);
    if ($result === false) {
        $temp = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $value);
        return unserialize($temp);
    }
    return $result;
}
/**
 * 序列化判断
 * @param unknown $data
 * @param string $strict
 * @return boolean
 */
function is_serialized($data, $strict = true) {
    if (!is_string($data)) {
        return false;
    }
    $data = trim($data);
    if ('N;' == $data) {
        return true;
    }
    if (strlen($data) < 4) {
        return false;
    }
    if (':' !== $data[1]) {
        return false;
    }
    if ($strict) {
        $lastc = substr($data, -1);
        if (';' !== $lastc && '}' !== $lastc) {
            return false;
        }
    } else {
        $semicolon = strpos($data, ';');
        $brace = strpos($data, '}');
        if (false === $semicolon && false === $brace)
            return false;
        if (false !== $semicolon && $semicolon < 3)
            return false;
        if (false !== $brace && $brace < 4)
            return false;
    }
    $token = $data[0];
    switch ($token) {
        case 's' :
            if ($strict) {
                if ('"' !== substr($data, -2, 1)) {
                    return false;
                }
            } elseif (false === strpos($data, '"')) {
                return false;
            }
        case 'a' :
        case 'O' :
            return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
        case 'b' :
        case 'i' :
        case 'd' :
            $end = $strict ? '$' : '';
            return (bool)preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
    }
    return false;
}
/**
 * 验证http或https是否存在
 * @param unknown $string
 * @param unknown $find
 * @return boolean
 */
function strexists($string, $find) {
    return !(strpos($string, $find) === FALSE);
}
/**
 * 获取网站根目录
 * @return string
 */
function getSiteRoot(){
    $sitepath = substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
    if(strexists($sitepath, 'index.php')){
        $n=strpos($sitepath,'index.php');
        $sitepath=substr($sitepath,0,$n);
    }
    $siteroot = htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . $sitepath);
    return $siteroot;
}
/**
 * 判断是否二维嵌套数组
 * @param unknown $array
 * @return boolean
 */
function is_array2($array)
{
    if (is_array($array)) {
        foreach ($array as $k => $v) {
            return is_array($v);
        }
        return false;
    }
    return false;
}
/**
 * 设置多图插入格式
 * @param unknown $list
 * @param string $fields
 * @return unknown|string
 */
function set_medias($list = array(), $fields = null)
{
    if (empty($fields)) {
        foreach ($list as &$row) {
            $row = tomedia($row);
        }
        return $list;
    }
    if (!is_array($fields)) {
        $fields = explode(',', $fields);
    }
    if (is_array2($list)) {
        foreach ($list as $key => &$value) {
            foreach ($fields as $field) {
                if (is_array($value) && isset($value[$field])) {
                    $value[$field] = tomedia($value[$field]);
                }
            }
        }
        return $list;
    } else {
        foreach ($fields as $field) {
            if (isset($list[$field])) {
                $list[$field] = tomedia($list[$field]);
            }
        }
        return $list;
    }
}
/**
 * 获取图片完整地址自动处理图片url
 * @param unknown $src
 * @return string
 */
function tomedia($src){
    if (empty($src)) {
        return '';
    }
    $t = strtolower($src);
    if (!strexists($t, 'http://') && !strexists($t, 'https://')) {
		$da=substr(trim($src),0,1);
		if($da==='/'){
			$src = getSiteRoot().$src;
		}else{
    		$src = getSiteRoot().'/'.$src;
		}
    }
    return $src;
}
/**自动处理图片url
 * function Cimg_url($str='/Uploads/notExist/img_404.jpg'){
	$reg = '/http:|https:/';
    $matches = array();
    preg_match_all($reg,trim($str),$matches);
	if(!empty($matches[0])||!empty($matches[0][0])){
		return $str;
	}else{
		$da=substr(trim($str),0,1);
		if($da==='/'){
			return __ROOT__.$str;
		}else{
    		return __ROOT__.'/'.$str;
		}
	}
}
 */

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data){
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])){
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}
/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_memb_login(){
    $user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['id'] : 0;
    }
}

/**
 * 根据微信openid自动登入
 *  return  -1.用户不存在或被禁用    0.参数错误    大于0 正常状态
 */
function weixin_memb_login(){
	$openid=session('OPENID');
	if(!empty($openid)){
		$memb=is_login(false);
		if($memb['id']>0&&is_numeric($memb['id'])){
			$membmodel=api('Member/Member/getModel');
			$membmodel->updateLogin($memb['id']);
			$memb['logintime']=NOW_TIME;
			session('memb_auth',$memb);
			return $memb['id'];
		}else{
			//action_log('memb_login', 'Member', $memb['id'],$memb['id']);
			$fans=M('MemberFans')->where(array('openid'=>$openid))->find();
			if(is_numeric($fans['membid'])&&$fans['membid']>0){
				$membmodel=api('Member/Member/getModel');
				$result=$membmodel->login($fans['membid'],null,5);
				if($result>0){
					$memb=$membmodel->info($fans['membid']);
					session('memb_auth',$memb);
				}
				return $result;
			}else{
				$membmodel=api('Member/Member/getModel');
				$uid=$membmodel->register('',md5($openid), md5($openid).'@baguatan.com', null,null,$openid);
				$memb=$membmodel->info($uid);
				$fans=array(
					'membid'=>$uid,
    	        	'openid'=>strval($openid),
    	        	'nickname'=>$memb['nickname'],
    	        	'groupid'=>0,
    	        	'follow'=>1,
    	        	'followtime'=>time()
				);
				$fanid=M('member_fans')->where(array('openid'=>strval($openid)))->getField('id');
				if($fanid){
					$result=M("member_fans")->where(array('id'=>$fanid))->save($fans);
				}else{
    	        	$result=M("member_fans")->add($fans);
				}
				if($result>0){
					$membmodel->login($fans['membid'],null,5);
					session('memb_auth',$memb);
					return $result;
				}else{
					return -1;
				}
			}
		}
	}else{
		return -1;
	}
}
/**
 * 检测用户是否登录
 * @param  array  $key  当$key=true 的时候返回用户id 当$key=false的时候返回用户详细信息
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_login($key=true){
    $memb = session('memb_auth');
	/* if(NOW_TIME-$memb['logintime']>1800){
		 return 0;
	} */
    if (empty($memb)) {
        return 0;
    } else {
        return $key? $memb['id'] : $memb;
    }
}

/**
 * 检测管理员是否登录
 * @param  array  $key  当$key=true 的时候返回用户id 当$key=false的时候返回用户详细信息
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_admin_login($key=true){
    $memb = session('admin_auth');
	/*if(NOW_TIME-$memb['last_login_time']>1800){
		 return 0;
	}*/
    if (empty($memb)) {
        return 0;
    } else {
        return $key? $memb['id'] : $memb;
    }
}

/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}
/**
 * select返回的数组进行整数映射转换
 *
 * @param array $map  映射关系二维数组  array(
 *                                          '字段名1'=>array(映射关系数组),
 *                                          '字段名2'=>array(映射关系数组),
 *                                           ......
 *                                       )
 * @author zhengpegndong <1032453491@qq.com>
 * @return  array(
 *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *      ....
 *  )
 *
 */
function int_to_string(&$data,$map=array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))) {
    if($data === false || $data === null ){
        return $data;
    }
	$arrlevi=arrayLevel($data);
	if(intval($arrlevi)==2){
	    $data = (array)$data;
	    foreach ($data as $key => $row){
	        foreach ($map as $col=>$pair){
	            if(isset($row[$col]) && isset($pair[$row[$col]])){
	                $data[$key][$col.'_text'] = $pair[$row[$col]];
	            }
	        }
	    }
	}elseif(intval($arrlevi)==1){
		foreach ($map as $col=>$pair){
            if(isset($data[$col]) && isset($pair[$data[$col]])){
                $data[$col.'_text'] = $pair[$data[$col]];
            }
        }
	}
    return $data;
}

/**
 * 返回数组的维度
 * @param  [type] $arr [description]
 * @return [type]      [description]
 */
function arrayLevel($arr){
    $al = array(0);
    aL($arr,$al);
    return max($al);
}
function aL($arr,&$al,$level=0){
    if(is_array($arr)){
        $level++;
        $al[] = $level;
        foreach($arr as $v){
            aL($v,$al,$level);
        }
    }
}
/**
 * 生成不重复字符串
 */
function norepeatestr(){
	return buildOrderNo('SX');
}
/**
 * 调用系统的API接口方法（静态方法）
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 * api('Admin/User/getName','id=5');  调用Admin模块的User接口
 * @param  string  $name 格式 [模块名]/接口名/方法名
 * @param  array|string  $vars 参数
 */
function api($name,$vars=array()){
    $array     = explode('/',$name);
    $method    = array_pop($array);
    $classname = array_pop($array);
    $module    = $array? array_pop($array) : 'Common';
    $callback  = $module.'\\Api\\'.$classname.'Api::'.$method;
    if(is_string($vars)) {
        parse_str($vars,$vars);
    }
    return call_user_func_array($callback,$vars);
}
/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 * @author 沙哈拉的寂寞 <1032453491@qq.com>
 */
function time_format($time = NULL,$format='Y-m-d H:i:s'){
	if($time === NULL){
	    return "未设置";
	}else{
		 return date($format, intval($time));
	}
}
//-----------------------------------------------------------------------------------------------------
// 防超时的file_get_contents改造函数
function wp_file_get_contents($url) {
    $context = stream_context_create ( array (
        'http' => array (
            'timeout' => 30
        )
    ) ) // 超时时间，单位为秒

    ;

    return file_get_contents ( $url, 0, $context );
}
// 获取当前用户的Token
function get_token($token = NULL) {
	if ($token !== NULL) {
		session ( 'token', $token );
	} elseif (! empty ( $_REQUEST ['token'] )) {
		session ( 'token', $_REQUEST ['token'] );
	}
	$token = session ( 'token' );
	
	if (empty ( $token )) {
		return - 1;
	}
	
	return $token;
}

function addWeixinLog($data, $data_post = '') {
    $log ['cTime'] = time ();
    $log ['cTime_format'] = date ( 'Y-m-d H:i:s', $log ['cTime'] );
    $log ['data'] = is_array ( $data ) ? serialize ( $data ) : $data;
    $log ['data_post'] = is_array ( $data_post ) ? serialize ( $data_post ) : $data_post;
    M ( 'weixin_log' )->add ( $log );
}
/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param int $record_id 触发行为的记录id
 * @param int $membid 执行行为的粉丝id 
 * @param int $userid 执行行为的用户id
 * @param int $is_user 执行行为的是前台还是后台
 * @return boolean
 * @author zhengpengdong <1032453491@qq.com>
 */
function action_log($action = null, $model = null, $record_id = null,$membid=null, $userid = null,$issystem=0){
    //参数检查
    if(empty($action) || empty($model) || empty($record_id)){
        return '参数不能为空';
    }
    if(empty($membid)){
        $membid = is_login();
    }

    //查询行为,判断是否执行
    $action_info = M('Action')->getByName($action);
    if($action_info['status'] != 1){
        return '该行为被禁用或删除';
    }
    //插入行为日志
    $data['action_id']      =   $action_info['id'];
    $data['userid']        =   $userid;
	$data['membid']        =   $membid;
    $data['action_ip']      =   ip2long(get_client_ip());
    $data['model']          =   $model;
    $data['record_id']      =   $record_id;
    $data['create_time']    =   NOW_TIME;
	$data['issystem']     =     $issystem;
    //解析日志规则,生成日志备注
    if(!empty($action_info['log'])){
        if(preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)){
        	$log['membid']    =   $membid;
            $log['userid']    =   $userid;
			$log['score']    =   $action_info['score'];
            $log['record']  =   $record_id;
            $log['model']   =   $model;
            $log['time']    =   NOW_TIME;
            $log['data']    =   array('membid'=>$membid,'model'=>$model,'record'=>$record_id,'time'=>NOW_TIME);
            foreach ($match[1] as $value){
                $param = explode('|', $value);
                if(isset($param[1])){
                    $replace[] = call_user_func($param[1],$log[$param[0]]);
                }else{
                    $replace[] = $log[$param[0]];
                }
            }
            $data['remark'] =   str_replace($match[0], $replace, $action_info['log']);
        }else{
            $data['remark'] =   $action_info['log'];
        }
    }else{
        //未定义日志规则，记录操作url
        $data['remark']     =   '操作url：'.$_SERVER['REQUEST_URI'];
    }
	$sign=true;
    if(!empty($action_info['rule'])){
        $rules = parse_action($action_info, $membid);//解析行为
        if($rules){
        	$model = new \Think\Model();
			$model->startTrans();
		 	$res = execute_action($rules, $action_info, $membid,$data);//执行行为
			if(!empty($res)){
				$model->commit();
				$data['ischeck']=$res;
			}else{
				$model->rollback();
				$sign = false;
			}
    	}
       
    }
	$sign = M('ActionLog')->add($data);
	return $sign;
}

//----------------------------zpd-----------------------------------------------------------------------------------
/**
 * 解析行为规则
 * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 *              field->要操作的字段；
 *              condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 *              rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 *              cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 *              max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 * 单个行为后可加 ； 连接其他规则
 * @param string $action 行为id或者name
 * @param int $self 替换规则里的变量为执行用户的id
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 * @author zhengpengdong <1032453491@qq.com>
 */
function parse_action($action = null, $self){
    if(empty($action)){
        return false;
    }
    //参数支持id或者name
    if(is_numeric($action)){
        $map = array('id'=>$action);
    }elseif(is_string($action)){
        $map = array('name'=>$action);
    }
	if(is_array($action)){
		$info=$action;
	}else{
	    //查询行为信息
	    $info = M('Action')->where($map)->find();
	}
    if(!$info || $info['status'] != 1){
        return false;
    }
    //解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
    $rules = $info['rule'];
    $rules = str_replace('{$self}', $self, $rules);
	if(!empty($action['score'])&&is_numeric($action['score'])){
		$rules = str_replace('{$score}', '('.$action['score'].')', $rules);
	}
	if(!empty($action['deposit'])&&is_numeric($action['deposit'])){
		$rules = str_replace('{$deposit}', '('.$action['deposit'].')', $rules);
	}
    $rules = explode(';', $rules);
    $return = array();
    foreach ($rules as $key=>&$rule){
        $rule = explode('|', $rule);
        foreach ($rule as $k=>$fields){
            $field = empty($fields) ? array() : explode(':', $fields);
            if(!empty($field)){
                $return[$key][$field[0]] = $field[1];
            }
        }
        //cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
        if(!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])){
            unset($return[$key]['cycle'],$return[$key]['max']);
        }
    }
    return $return;
}

/**
 * 执行行为
 * @param array $rules 解析后的规则数组
 * @param int $action_id 行为id
 * @param array $membid 执行的用户id
 * @return boolean false 失败 ， true 成功
 * @author zhengpengdong <1032453491@qq.com>
 */
function execute_action($rules = false, $action = null, $membid = null,$actionlog=null){
    if(!$rules || empty($actionlog) || empty($membid)||empty($action)){
    	
        return false;
    }
    $return = true;
    foreach ($rules as $rule){
        //检查执行周期
        $map = array('action_id'=>$action['id'], 'membid'=>$membid);
        $map['create_time'] = array('gt', NOW_TIME - intval($rule['cycle']) * 3600);
        $exec_count = M('ActionLog')->where($map)->count();
        if($exec_count > $rule['max']){
            continue;
        }
        //执行数据库操作
        $Model = M(ucfirst($rule['table']));
        $field = explode(',', $rule['field']);
		$result = explode(',', $rule['rule']);
		$condition = explode(',', $rule['condition']);
		$data=array();
		$resu='';
		foreach ($field as $key => $value) {
			if(!empty($result[$key])){
				$data=array($value=>array('exp', $result[$key]));
				$res = $Model->where($condition[$key])->setField($data);
				if($res>0){
				 	if( $value == 'score' || $value=='deposit'){
				 		$d=array(
							'membid'=>$actionlog['membid'],
							'type'=>intval($action[$value]) < 0?2:1,
							'credittype'=>$value,
							'num'=>$action[$value],
							'operator'=>$actionlog['userid'],
							'model'=>$actionlog['model'],
							'createtime' => NOW_TIME,
						);
				 		switch ( strtolower($value) ){
							case 'score':
								$d['remark']='系统在操作“'.$action['title'].'”时，添加了'.$action[$value].'的积分';
							 	$r=M('CheckBill')->add($d);
								if($r>0){
									setCheckBillLog($d,'score');
								}
								$resu=$resu.$r.',';
							 	break;
			            	case 'deposit':
								$d['remark']='系统在操作“'.$action['title'].'”时，添加了'.$action[$value].'的金额';
							 	$r=M('CheckBill')->add($d);
								if($r>0){
									setCheckBillLog($d,'deposit');
								}
								$resu=$resu.$r.',';
								break;
						}
				 	}
		        }else{
		            return false;
		        }
			}
		}
		if(!empty($turn)){
			$return=$turn;
		}
    }
    return $return;
}

/**
 * 计入日志到.log文件上
 * @access public
 * @param string $log 日志信息
 * @param string $destination  写入目标
 * @return void
 */
function setCheckBillLog($log,$filname='') {
	if(!is_string($log)){
		$log=json_encode($log);
	}
	if (!is_dir(IA_ROOT.'/Uploads')) {
        mkdir(IA_ROOT.'/Uploads');
    }
    $destination = IA_ROOT.'/Uploads/ModifyDetail/'.$filname.'_'.date('y_m_d').'.log';
    $now = date("Y-m-d H:i:s",NOW_TIME);
    // 自动创建日志目录
    $log_dir = dirname($destination);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    //检测日志文件大小，超过配置大小则备份日志文件重新生成
    if(is_file($destination) && floor(2097152) <= filesize($destination) )
          rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
	$datalog="================================================\r\n Creation time:[{$now}] "."\r\n{$log}\r\n================================================ \r\n";
    error_log($datalog, 3,$destination);
}
/**
 * 根据用户ID获取用户昵称
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_nickname($uid = 0){
    static $list;
   	if($uid && is_numeric($uid) && is_login() == $uid){ //获取当前登录用户名
        return session('memb_auth.nickname');
    }
    if(empty($list)){
        $list = S('sys_user_nickname_list');
    }
    $key = "u{$uid}";
    if(isset($list[$key])){ //已缓存，直接使用
        $name = $list[$key];
    } else { //调用接口获取用户信息
        $info = M('Member')->field('nickname')->find($uid);
        if($info !== false && $info['nickname'] ){
            $nickname = $info['nickname'];
            $name = $list[$key] = $nickname;
            /* 缓存用户 */
            $count = count($list);
            $max   = C('USER_MAX_CACHE');
            while ($count-- > $max) {
                array_shift($list);
            }
            S('sys_user_nickname_list', $list);
        } else {
            $name = '';
        }
    }
    return $name;
}
/**
 * 根据ID获取管理员名称
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_admin($uid = 0){
    static $list;
	if(empty($uid)){
		return '系统';
	}
   	if($uid && is_numeric($uid) && is_admin_login() == $uid){ //获取当前登录用户名
        return session('admin_auth.username');
    }
    if(empty($list)){
        $list = S('sys_user_username_list');
    }
    $key = "u{$uid}";
    if(isset($list[$key])){ //已缓存，直接使用
        $name = $list[$key];
    } else { //调用接口获取用户信息
        $info = M('User')->field('username')->find($uid);
        if($info !== false && $info['username'] ){
            $nickname = $info['username'];
            $name = $list[$key] = $nickname;
            /* 缓存用户 */
            $count = count($list);
            $max   = C('USER_MAX_CACHE');
            while ($count-- > $max) {
                array_shift($list);
            }
            S('sys_user_username_list', $list);
        } else {
            $name = '';
        }
    }
    return $name;
}

/**
 * 获取行为数据
 * @param string $id 行为id
 * @param string $field 需要获取的字段
 * @author 赵璇 <312171706@qq.com>
 */
function get_action($id = null, $field = null){
	if(empty($id) && !is_numeric($id)){
		return false;
	}
	$list = S('action_list');
	if(empty($list[$id])){
		$map = array('status'=>array('gt', -1), 'id'=>$id);
		$list[$id] = M('Action')->where($map)->field(true)->find();
		S('action_list',$list);
	}
	return empty($field) ? $list[$id] : $list[$id][$field];
}
/**
 * 根据用户ID获取用户昵称
 * @param  integer $id 用户ID
 * @return string       用户昵称
 */
function get_member_group($id = 0){
    static $list;
    if(empty($list)){
        $list = S('sys_memb_groupname_list');
    }
    $key = "grou_{$id}";
    if(isset($list[$key]['thisvalue'])&& intval(time()) - intval($list[$key]['thistime'])<3600){ //已缓存，直接使用
        $name = $list[$key]['thisvalue'];
    } else { //调用接口获取用户信息
        $info = M('MemberGroups')->find($id);
        if($info !== false && $info['title'] ){
            $nickname = $info['title'];
            $name = $list[$key]['thisvalue'] = $nickname;
			$list[$key]['thistime']=time();
            /* 缓存用户 */
            $count = count($list);
            $max   = C('USER_MAX_CACHE');
            while ($count-- > $max) {
                array_shift($list);
            }
            S('sys_memb_groupname_list', $list);
        } else {
            $name = '普通会员';
        }
    }
    return $name;
}
/**
 * 获取行为类型
 * @param intger $type 类型
 * @param bool $all 是否返回全部类型
 * @author 沙哈拉的寂寞 <1032453491@qq.com>
 */
function get_action_type($type, $all = false){
	$list = array(
		1=>'系统',
		2=>'用户',
	);
	if($all){
		return $list;
	}
	return $list[$type];
}
/**
 * 更改积分或金额
 * @param intger $membid 用户id
 * @param intger $num 添加或减少的数量
 * @param string $item 添加的类型 score 标示积分 deposit标示金额 默认是积分
 * @param string $remark 对积分或金额进行修改的备注备注
 * @param bool $all 是否返回全部类型
 */
function setScoreOrDeposit($membid=0,$num=0.00,$item='score',$remark,$mod){
	if(!is_string($item)||empty($membid)||empty($num)){
		return false;
	}
	if(empty($remark)){
		$remark='当前用户ID为：'.intval($membid).' , 改变的字段为：'.$item.' , 改变额度为：'.floatval($num).'。';
	}
	$model = new \Think\Model();
	$model->startTrans();
	try{
		$condition="id=".intval($membid)." AND status>-1 AND ".$item." + ".floatval($num)." >= 0";
		$scodep=array($item=>array('exp',$item.'+('.floatval($num).')'));
		$res = M('Member')->where($condition)->setField($scodep);
		if($res>0){
			$data=array(
				'membid'=>$membid,
				'type'=>intval($num) < 0?2:1,
				'credittype'=>$item,
				'num'=>$num,
				'operator'=>intval(USERID),
				'createtime' => NOW_TIME,
				'model'=>$mod,
				'remark'=>$remark,
			);
			$r=M('CheckBill')->add($data);
			if($r>0){
				setCheckBillLog($data,$item);
				$model->commit();
				return true;
			}else{
				$model->rollback();
				return false;
			}
		}else{
			$model->rollback();
			return false;
		}
	}catch(\Exception $e){
		$model->rollback();
		return false;
	}
}
/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string 
 */
function think_md5($str, $key = 'Think'){
	return '' === $str ? '' : md5(sha1($str) . $key);
}

/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_administrator($uid = null){
    $uid = is_null($uid) ? is_admin_login() : $uid;
    return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}
/**
 * 创建目录
 * @param unknown $path 路径
 * @return boolean
 */
function mkdirs($path) {
    if (!is_dir($path)) {
        mkdirs(dirname($path));
        mkdir($path);
    }
    return is_dir($path);
}

/**
 * 根据数字获取对应字母
 */
function getLetter($num){
	$ret = null;
	$arr = array(
		'1' => 'A',
		'2' => 'B',
		'3' => 'C',
		'4' => 'D',
		'5' => 'E',
		'6' => 'F',
		'7' => 'G',
		'8' => 'H',
		'9' => 'I',
		'10' => 'J',
		'11' => 'K',
		'12' => 'L',
		'13' => 'M',
		'14' => 'N',
		'15' => 'O',
		'16' => 'P',
		'17' => 'Q',
		'18' => 'R',
		'19' => 'S',
		'20' => 'T',
		'21' => 'U',
		'22' => 'V',
		'23' => 'W',
		'24' => 'X',
		'25' => 'Y',
		'26' => 'Z'
	);
	foreach($arr as $k => $v){
		if(intval($k) == $num){
			$ret = $v;
			break;
		}
	}
	return $ret;
}

/**
 * 自动生成前台访问的url
 * URL组装 支持不同URL模式
 * @param string $url URL表达式，格式：'[模块/控制器/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string|boolean $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $domain 是否显示域名
 * @return string
 * @author 沙哈拉的寂寞 <1032453491@qq.com>
 */
function MURL($url='',$vars='',$suffix=true,$domain=false){
	
    // 解析URL
    $info   =  parse_url($url);
    $url    =  !empty($info['path'])?$info['path']:ACTION_NAME;
    if(isset($info['fragment'])) { // 解析锚点
        $anchor =   $info['fragment'];
        if(false !== strpos($anchor,'?')) { // 解析参数
            list($anchor,$info['query']) = explode('?',$anchor,2);
        }        
        if(false !== strpos($anchor,'@')) { // 解析域名
            list($anchor,$host)    =   explode('@',$anchor, 2);
        }
    }elseif(false !== strpos($url,'@')) { // 解析域名
        list($url,$host)    =   explode('@',$info['path'], 2);
    }
    // 解析子域名
    if(isset($host)) {
        $domain = $host.(strpos($host,'.')?'':strstr($_SERVER['HTTP_HOST'],'.'));
    }elseif($domain===true){
        $domain = $_SERVER['HTTP_HOST'];
        if(C('APP_SUB_DOMAIN_DEPLOY') ) { // 开启子域名部署
            $domain = $domain=='localhost'?'localhost':'www'.strstr($_SERVER['HTTP_HOST'],'.');
            // '子域名'=>array('模块[/控制器]');
            foreach (C('APP_SUB_DOMAIN_RULES') as $key => $rule) {
                $rule   =   is_array($rule)?$rule[0]:$rule;
                if(false === strpos($key,'*') && 0=== strpos($url,$rule)) {
                    $domain = $key.strstr($domain,'.'); // 生成对应子域名
                    $url    =  substr_replace($url,'',0,strlen($rule));
                    break;
                }
            }
        }
    }

    // 解析参数
    if(is_string($vars)) { // aaa=1&bbb=2 转换成数组
        parse_str($vars,$vars);
    }elseif(!is_array($vars)){
        $vars = array();
    }
    if(isset($info['query'])) { // 解析地址里面参数 合并到vars
        parse_str($info['query'],$params);
        $vars = array_merge($params,$vars);
    }
   
    // URL组装
    $depr       =   C('URL_PATHINFO_DEPR');
    $urlCase    =   C('URL_CASE_INSENSITIVE');
    if($url) {
        if(0=== strpos($url,'/')) {// 定义路由
            $route      =   true;
            $url        =   substr($url,1);
            if('/' != $depr) {
                $url    =   str_replace('/',$depr,$url);
            }
        }else{
            if('/' != $depr) { // 安全替换
                $url    =   str_replace('/',$depr,$url);
            }
            // 解析模块、控制器和操作
            $url        =   trim($url,$depr);	
            $path       =   explode($depr,$url);
            $var        =   array();
            $varModule      =   C('VAR_MODULE');
            $varController  =   C('VAR_CONTROLLER');
            $varAction      =   C('VAR_ACTION');
            $var[$varAction]       =   !empty($path)?array_pop($path):ACTION_NAME;
            $var[$varController]   =   !empty($path)?array_pop($path):CONTROLLER_NAME;
            if($maps = C('URL_ACTION_MAP')) {
                if(isset($maps[strtolower($var[$varController])])) {
                    $maps    =   $maps[strtolower($var[$varController])];
                    if($action = array_search(strtolower($var[$varAction]),$maps)){
                        $var[$varAction] = $action;
                    }
                }
            }
            if($maps = C('URL_CONTROLLER_MAP')) {
            	
                if($controller = array_search(strtolower($var[$varController]),$maps)){
                    $var[$varController] = $controller;
                }
            }
            if($urlCase) {
                $var[$varController]   =   parse_name($var[$varController]);
            }
            $module =   '';
            
            if(!empty($path)) {
                $var[$varModule]    =   implode($depr,$path);
            }else{
                if(C('MULTI_MODULE')) {
                    if(MODULE_NAME != C('DEFAULT_MODULE') || !C('MODULE_ALLOW_LIST')){
                        $var[$varModule]=   MODULE_NAME;
                    }
                }
            }
            if($maps = C('URL_MODULE_MAP')) {
                if($_module = array_search(strtolower($var[$varModule]),$maps)){
                    $var[$varModule] = $_module;
                }
            }
            if(isset($var[$varModule])){
                $module =   $var[$varModule];
                unset($var[$varModule]);
            }
        }
    }
   if(C('URL_MODEL') == 0) { // 普通模式URL转换
   		if(empty($module)){
        	$url = __APP__.'?'.C('VAR_MODULE')."={$module}&".http_build_query(array_reverse($var));
		}else{
			$url = __ROOT__.'/'.$module.'.php'.'?'.C('VAR_MODULE')."={$module}&".http_build_query(array_reverse($var));
		}
	    if($urlCase){
            $url    =   strtolower($url);
        }        
        if(!empty($vars)) {
            $vars   =   http_build_query($vars);
            $url   .=   '&'.$vars;
        }
    }else{ // PATHINFO模式或者兼容URL模式 
        if(isset($route)) {
        	if(empty($module)){
        		$pos = strrpos(__APP__, "b");
				$app=substr(__APP__, 0,$pos);
            	$url    =   $app.'/'.rtrim($url,$depr);
			}else{
				$url    =   __ROOT__.$module.'.php?s=/'.rtrim($url,$depr);
			}
        }else{
            $module =   (defined('BIND_MODULE') && BIND_MODULE==$module )? '' : $module;
			if(empty($module)){
            	$url    =   __APP__.'/Home/'.($module?MODULE_PATHINFO_DEPR:'').implode($depr,array_reverse($var));
			}else{
				$url    =   __ROOT__.'/'.$module.'.php?s=/Home'.($module?MODULE_PATHINFO_DEPR:'').implode($depr,array_reverse($var));
			}
		}
        if($urlCase){
            $url    =   strtolower($url);
        }
        if(!empty($vars)) { // 添加参数
            foreach ($vars as $var => $val){
                if('' !== trim($val))   $url .= $depr . $var . $depr . urlencode($val);
            }
        }
        if($suffix) {
            $suffix   =  $suffix===true?C('URL_HTML_SUFFIX'):$suffix;
            if($pos = strpos($suffix, '|')){
                $suffix = substr($suffix, 0, $pos);
            }
            if($suffix && '/' != substr($url,-1)){
                $url  .=  '.'.ltrim($suffix,'.');
            }
        }
    }
    if(isset($anchor)){
        $url  .= '#'.$anchor;
    }
    if($domain) {
        $url   =  (is_ssl()?'https://':'http://').$domain.$url;
    }
    return $url;
}
/**
 * 生成唯一机器码
 * @author 沙哈拉的寂寞 <1032453491@qq.com>
 */
function getRandOnlyId() {
	$newtime = date('Ymdhms').substr(str_replace("0.","",str_replace(" ","",microtime())),0,5);
    $rand=rand(0,999);//两位随机
    $all=$newtime.$rand;
    $onlyid=base_convert($all,10,36);//把10进制转为36进制的唯一ID
    return $onlyid;
}
/**
 * 生成数字唯一订单号
 * @param string $sn 订单前缀
 * @author 沙哈拉的寂寞 <1032453491@qq.com>
 */
function buildOrderNo($sn=''){
	$rand=rand(0,999);//两位随机
    return $sn.date('Ymdhms').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 5).$rand;
}
/**
 * 保证string类型数据不被html直接读取
 * 本函数可去掉字符串中的反斜线字符。若是连续二个反斜线，则去掉一个，留下一个。若只有一个反斜线，就直接去掉。
 * @author 沙哈拉的寂寞 <1032453491@qq.com>
 */
function istripslashes($var) {
	if (is_array($var)) {
		foreach ($var as $key => $value) {
			$var[stripslashes($key)] = istripslashes($value);
		}
	} else {
		$var = stripslashes($var);
	}
	return $var;
}
/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

 // 分析枚举类型字段值 格式 a:名称1,b:名称2
 // 暂时和 parse_config_attr功能相同
 // 但请不要互相使用，后期会调整
function parse_field_attr($data=array()) {
	  switch (intval($data['control']['type'])) {
	  	case 1:
	  		$value=$data['control']['option'];
	  		break;
	  	default:
	  		$value=array();
	  		break;
	  }
    return $value;
}
/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook,$params=array()){
    \Think\Hook::listen($hook,$params);
}
/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function addons_url($url, $param = array()){
    $url        = parse_url($url);
    $case       = C('URL_CASE_INSENSITIVE');
    $addons     = $case ? parse_name($url['scheme']) : $url['scheme'];
    $controller = $case ? parse_name($url['host']) : $url['host'];
    $action     = trim($case ? strtolower($url['path']) : $url['path'], '/');

    /* 解析URL带的参数 */
    if(isset($url['query'])){
        parse_str($url['query'], $query);
        $param = array_merge($query, $param);
    }

    /* 基础参数 */
    $params = array(
        '_addons'     => $addons,
        '_controller' => $controller,
        '_action'     => $action,
    );
    $params = array_merge($params, $param); //添加额外参数

    return U('Addons/execute', $params);
}
/**
* 对查询结果集进行排序
* @access public
* @param array $list 查询结果
* @param string $field 排序的字段名
* @param array $sortby 排序类型
* asc正向排序 desc逆向排序 nat自然排序
* @return array
*/
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}
/**
 * 获取插件类的类名
 * @param strng $name 插件名
 */
function get_addon_class($name){
    $class = "Addons\\{$name}\\{$name}Addon";
    return $class;
}
/**
 * 执行SQL文件
 */
function execute_sql_file($sql_path) {
	// 读取SQL文件
	$sql = file_get_contents ( $sql_path );
	$sql = str_replace ( "\r", "\n", $sql );
	$sql = explode ( ";\n", $sql );
	
	// 替换表前缀
	$orginal = 'wp_';
	$prefix = C ( 'DB_PREFIX' );
	$sql = str_replace ( "{$orginal}", "{$prefix}", $sql );
	
	// 开始安装
	foreach ( $sql as $value ) {
		$value = trim ( $value );
		if (empty ( $value ))
			continue;
		
		$res = M ()->execute ( $value );
		// dump($res);
		// dump(M()->getLastSql());
	}
}
/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function str2arr($str, $glue = ','){
    return explode($glue, $str);
}
/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 *
 * @param array $arr
 *        	要连接的数组
 * @param string $glue
 *        	分割符
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function arr2str($arr, $glue = ',') {
	return implode ( $glue, $arr );
}
// 基于数组创建目录和文件
function create_dir_or_files($files) {
	foreach ( $files as $key => $value ) {
		if (substr ( $value, - 1 ) == '/') {
			mkdir ( $value );
		} else {
			@file_put_contents ( $value, '' );
		}
	}
}
/**
 * 获取公众平台
 */
function uni_accounts($acid = 150) {
	$accountlist =M('MemberPublic')->where(array('id'=>$acid))->find();
	return $accountlist;
}
function error($errno, $message = '') {
	return array(
		'errno' => $errno,
		'message' => $message,
	);
}
/**
 * 表名函数
 */
function tablename($data=null){
	empty($data)&& $this->error('表名函数异常！');
	$dbprefix=C("DB_PREFIX");
	return $dbprefix.$data;
};
?>