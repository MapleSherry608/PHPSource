<?php
class RequestHandler {
	
	/** 网关url地址 */
	var $gateUrl;
	
	/** 密钥 */
	var $key;
	
	/** 请求的参数 */
	var $parameters;
	
	/** debug信息 */
	var $debugInfo;
	
	function __construct() {
		$this->RequestHandler();
	}
	
	function RequestHandler() {
		$this->gateUrl = "https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi";
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
	}
	
	/**
	*初始化函数。
	*/
	function init() {
		//nothing to do
	}

	
	/**
	*获取入口地址,不包含参数值
	*/
	function getGateURL() {
		return $this->gateUrl;
	}
	
	/**
	*设置入口地址,不包含参数值
	*/
	function setGateURL($gateUrl) {
		$this->gateUrl = $gateUrl;
	}
	
	/**
	*获取密钥
	*/
	function getKey() {
		return $this->key;
	}
	
	/**
	*设置密钥
	*/
	function setKey($key) {
		$this->key = $key;
	}
	
	/**
	*获取参数值
	*/
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	
	/**
	*设置参数值
	*/
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}
	
	/**
	*获取所有请求的参数
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}
	
	/**
	*获取带参数的请求URL
	*/
	function getRequestURL() {
	
		$this->createSign();
		
		$reqPar = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			$reqPar .= $k . "=" . urlencode($v) . "&";
		}
		
		//去掉最后一个&
		$reqPar = substr($reqPar, 0, strlen($reqPar)-1);
		
		$requestURL = $this->getGateURL() . "?" . $reqPar;
		
		return $requestURL;
		
	}
		
	/**
	*获取debug信息
	*/
	function getDebugInfo() {
		return $this->debugInfo;
	}
	
	/**
	*重定向到财付通支付
	*/
	function doSend() {
		header("Location:" . $this->getRequestURL());
		exit;
	}
	
	/**
	*创建md5摘要,规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
	*/
	function createSign() {
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			if("" != $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$this->setParameter("sign", $sign);
		
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign);
		
	}	
	
	/**
	*设置debug信息
	*/
	function _setDebugInfo($debugInfo) {
		$this->debugInfo = $debugInfo;
	}

}
class ResponseHandler  {
	
	/** 密钥 */
	var $key;
	
	/** 应答的参数 */
	var $parameters;
	
	/** debug信息 */
	var $debugInfo;
	
	function __construct() {
		$this->ResponseHandler();
	}
	
	function ResponseHandler() {
		$this->key = "";
		$this->parameters = array();
		$this->debugInfo = "";
		
		/* GET */
		foreach($_GET as $k => $v) {
			$this->setParameter($k, $v);
		}
		/* POST */
		foreach($_POST as $k => $v) {
			$this->setParameter($k, $v);
		}
	}
		
	/**
	*获取密钥
	*/
	function getKey() {
		return $this->key;
	}
	
	/**
	*设置密钥
	*/	
	function setKey($key) {
		$this->key = $key;
	}
	
	/**
	*获取参数值
	*/	
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	
	/**
	*设置参数值
	*/	
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}
	
	/**
	*获取所有请求的参数
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}	
	
	/**
	*是否财付通签名,规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
	*true:是
	*false:否
	*/	
	function isTenpaySign() {
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
				
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;
		
	}
	
	/**
	*获取debug信息
	*/	
	function getDebugInfo() {
		return $this->debugInfo;
	}
	
	/**
	*显示处理结果。
	*@param $show_url 显示处理结果的url地址,绝对url地址的形式(http://www.xxx.com/xxx.php)。
	*/	
	function doShow($show_url) {
		$strHtml = "<html><head>\r\n" .
			"<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">" .
			"<script language=\"javascript\">\r\n" .
				"window.location.href='" . $show_url . "';\r\n" .
			"</script>\r\n" .
			"</head><body></body></html>";
			
		echo $strHtml;
		
		exit;
	}
	
	/**
	 * 是否财付通签名
	 * @param signParameterArray 签名的参数数组
	 * @return boolean
	 */	
	function _isTenpaySign($signParameterArray) {
	
		$signPars = "";
		foreach($signParameterArray as $k) {
			$v = $this->getParameter($k);
			if("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}			
		}
		$signPars .= "key=" . $this->getKey();
		
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
				
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;		
		
	
	}
	
	/**
	*设置debug信息
	*/	
	function _setDebugInfo($debugInfo) {
		$this->debugInfo = $debugInfo;
	}
	
}