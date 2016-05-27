<?php
namespace Common\Api;
use Think\Controller;
abstract class WeAccount extends  Controller {
	
	const TYPE_WEIXIN = '1';
	const TYPE_YIXIN = '2';
	const TYPE_ALIPAY = '3';
	
	
	public static function create($account) {
		return new WeiXinAccount($account);
	}
	
	static public function token($type = 1) {
		$classname = self::includes($type);
		$obj = new $classname();
		return $obj->fetch_available_token();
	}
	
	static public function includes($type = 1) {
		if($type == '1') {
			return 'WeiXinAccount';
		}
		if($type == '2') {
			return 'YiXinAccount';
		}
		if($type == '3') {
			return 'AlipayAccount';
		}
	}
	
	
	public function __construct($account){
		parent::__construct();
	}

	
	public function checkSign() {
		trigger_error('not supported.', E_USER_WARNING);
	}

	
	public function fetchAccountInfo() {
		trigger_error('not supported.', E_USER_WARNING);
	}

	
	public function queryAvailableMessages() {
		return array();
	}
	
	
	public function queryAvailablePackets() {
		return array();
	}
	
	
	public function parse($message) {
		$packet = array();
		if (!empty($message)){
			$obj = simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
			if($obj instanceof SimpleXMLElement) {
				$packet['from'] = strval($obj->FromUserName);
				$packet['to'] = strval($obj->ToUserName);
				$packet['time'] = strval($obj->CreateTime);
				$packet['type'] = strval($obj->MsgType);
				$packet['event'] = strval($obj->Event);
				
				foreach ($obj as $variable => $property) {
					$packet[strtolower($variable)] = (string)$property;
				}
				
				if($packet['type'] == 'text') {
					$packet['content'] = strval($obj->Content);
					$packet['redirection'] = false;
					$packet['source'] = null;
				}
				if($packet['type'] == 'image') {
					$packet['url'] = strval($obj->PicUrl);
				}
				if($packet['type'] == 'voice') {
					$packet['media'] = strval($obj->MediaId);
					$packet['format'] = strval($obj->Format);
				}
				if($packet['type'] == 'video') {
					$packet['media'] = strval($obj->MediaId);
					$packet['thumb'] = strval($obj->ThumbMediaId);
				}
				if($packet['type'] == 'location') {
					$packet['location_x'] = strval($obj->Location_X);
					$packet['location_y'] = strval($obj->Location_Y);
					$packet['scale'] = strval($obj->Scale);
					$packet['label'] = strval($obj->Label);
				}
				if($packet['type'] == 'link') {
					$packet['title'] = strval($obj->Title);
					$packet['description'] = strval($obj->Description);
					$packet['url'] = strval($obj->Url);
				}
				if($packet['event'] == 'subscribe') {
										$scene = strval($obj->EventKey);
					if(!empty($scene)) {
						$packet['scene'] = str_replace('qrscene_', '', $scene);
						$packet['ticket'] = strval($obj->Ticket);
					}
				}
				if($packet['event'] == 'unsubscribe') {
									}
				if($packet['event'] == 'SCAN') {
										$packet['type'] = 'qr';
					$packet['scene'] = strval($obj->EventKey);
					$packet['ticket'] = strval($obj->Ticket);
				}
				if($packet['event'] == 'LOCATION') {
										$packet['type'] = 'trace';
					$packet['location_x'] = strval($obj->Latitude);
					$packet['location_y'] = strval($obj->Longitude);
					$packet['precision'] = strval($obj->Precision);
				}
				if (in_array($packet['event'], array('pic_photo_or_album', 'pic_weixin', 'pic_sysphoto'))) {
					$packet['sendpicsinfo'] = array();
					$packet['sendpicsinfo']['count'] = strval($obj->SendPicsInfo->Count);
					if (!empty($obj->SendPicsInfo->PicList)) {
						foreach ($obj->SendPicsInfo->PicList->item as $item) {
							if (!empty($item)) {
								$packet['sendpicsinfo']['piclist'][] = strval($item->PicMd5Sum);
							}
						}
					}
				}
				if (in_array($packet['event'], array('scancode_push', 'scancode_waitmsg'))) {
					$packet['scancodeinfo'] = array();
					$packet['scancodeinfo']['scanresult'] = strval($obj->ScanCodeInfo->ScanResult);
					$packet['scancodeinfo']['scantype'] = strval($obj->ScanCodeInfo->ScanType);
					$packet['scancodeinfo']['eventkey'] = strval($obj->ScanCodeInfo->EventKey);
				}
				
				if (in_array($packet['event'], array('location_select'))) {
					$packet['sendlocationinfo'] = array();
					$packet['sendlocationinfo']['location_x'] = strval($obj->SendLocationInfo->Location_X);
					$packet['sendlocationinfo']['location_y'] = strval($obj->SendLocationInfo->Location_Y);
					$packet['sendlocationinfo']['scale'] = strval($obj->SendLocationInfo->Scale);
					$packet['sendlocationinfo']['label'] = strval($obj->SendLocationInfo->Label);
					$packet['sendlocationinfo']['poiname'] = strval($obj->SendLocationInfo->Poiname);
					$packet['sendlocationinfo']['eventkey'] = strval($obj->SendLocationInfo->EventKey);
				}
				if($packet['type'] == 'ENTER') {
					$packet['type'] = 'enter';
				}
			}
		}
		return $packet;
	}
	
	
	public function response($packet) {
		if (is_error($packet)) {
			return '';
		}
		if (!is_array($packet)) {
			return $packet;
		}
		if(empty($packet['CreateTime'])) {
			$packet['CreateTime'] = time();
		}
		if(empty($packet['MsgType'])) {
			$packet['MsgType'] = 'text';
		}
		if(empty($packet['FuncFlag'])) {
			$packet['FuncFlag'] = 0;
		} else {
			$packet['FuncFlag'] = 1;
		}
		return array2xml($packet);
	}

	
	public function isPushSupported() {
		return false;
	}
	
	
	public function push($uniid, $packet) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function isBroadcastSupported() {
		return false;
	}
	
	
	public function broadcast($packet, $targets = array()) {
		trigger_error('not supported.', E_USER_WARNING);
	}

	
	public function isMenuSupported() {
		return false;
	}
	
	
	public function menuCreate($menu) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function menuDelete() {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function menuModify($menu) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function menuQuery() {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function queryFansActions() {
		return array();
	}
	
	
	public function fansGroupAll() {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function fansGroupCreate($group) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function fansGroupModify($group) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function fansMoveGroup($uniid, $group) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function fansQueryGroup($uniid) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function fansQueryInfo($uniid, $isPlatform) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function fansAll() {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function queryTraceActions() {
		return array();
	}
	
	
	public function traceCurrent($uniid) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function traceHistory($uniid, $time) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function queryBarCodeActions() {
		return array();
	}
	
	
	public function barCodeCreateDisposable($barcode) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	
	public function barCodeCreateFixed($barcode) {
		trigger_error('not supported.', E_USER_WARNING);
	}
	
	public function downloadMedia($media){
		trigger_error('not supported.', E_USER_WARNING);
	}
}

