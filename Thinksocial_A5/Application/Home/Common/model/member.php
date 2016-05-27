<?php
class Zxin_Dshop_Member
{
	public function getInfo($openid = '')
	{
	    $member    = M('member');
	    $uniacid   = 0;
		$uid = intval($openid);
		if ($uid == 0) {
			$info = $member->where(array('uniacid' => $uniacid, 'openid' => $openid))->find();
		} else {
			$info = $member->where(array('uniacid' => $uniacid, 'id' => $uid))->find();
		}
		return $info;
	}
	public function getMember($openid = '', $getCredit = false)
	{
	    $member    = M('member');
	    $uniacid   = 0;
		$uid = intval($openid);
		if (empty($uid)) {
		    $info = $member->where(array('uniacid' => $uniacid, 'openid' => $openid))->find();
		} else {
		    $info = $member->where(array('uniacid' => $uniacid, 'id' => $uid))->find();
		}
		if ($getCredit) {
			$info['credit1'] = $this->getCredit($openid, 'credit1');
			$info['credit2'] = $this->getCredit($openid, 'credit2');
		}
		return $info;
	}
	public function getMid()
	{
		$openid = m_m('user')->getOpenid();
		$member = $this->getMember($openid);
		return $member['id'];
	}
	public function setCredit($openid = '', $credittype = 'credit1', $credits = 0, $log = array())
	{
		global $_W;
		load()->model('mc');
		$uid = mc_openid2uid($openid);
		if (!empty($uid)) {
			$value = pdo_fetchcolumn("SELECT {$credittype} FROM " . tablename('mc_members') . " WHERE `uid` = :uid", array(':uid' => $uid));
			$newcredit = $credits + $value;
			if ($newcredit <= 0) {
				$newcredit = 0;
			}
			pdo_update('mc_members', array($credittype => $newcredit), array('uid' => $uid));
			if (empty($log) || !is_array($log)) {
				$log = array($uid, '未记录');
			}
			$data = array('uid' => $uid, 'credittype' => $credittype, 'uniacid' => $_W['uniacid'], 'num' => $credits, 'createtime' => TIMESTAMP, 'operator' => intval($log[0]), 'remark' => $log[1]);
			pdo_insert('mc_credits_record', $data);
		} else {
			$value = pdo_fetchcolumn("SELECT {$credittype} FROM " . tablename('ewei_shop_member') . " WHERE  uniacid=:uniacid and openid=:openid limit 1", array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
			$newcredit = $credits + $value;
			if ($newcredit <= 0) {
				$newcredit = 0;
			}
			pdo_update('ewei_shop_member', array($credittype => $newcredit), array('uniacid' => $_W['uniacid'], 'openid' => $openid));
		}
	}
	public function getCredit($openid = '', $credittype = 'score')
	{
		global $_W;
		$uid = mc_openid2uid($openid);
		if (!empty($uid)) {
			return M('member')->where(array('id' => $uid))->getField($credittype);
		} else {
			return M('member')->where(array('openid' => $openid,'uniacid' => $_W['uniacid']))->getField('{$credittype}');
		}
	}
	public function checkMember($openid = '')
	{
		global $_W, $_GPC;
		if (strexists($_SERVER['REQUEST_URI'], '/web/')) {
			return;
		}
		if (empty($openid)) {
			$openid = m('user')->getOpenid();
		}
		if (empty($openid)) {
			return;
		}
		$member = m('member')->getMember($openid);
		$userinfo = m('user')->getInfo();
		$followed = m('user')->followed($openid);
		$uid = 0;
		$mc = array();
		load()->model('mc');
		if ($followed) {
			$uid = mc_openid2uid($openid);
			$mc = mc_fetch($uid, array('realname', 'mobile', 'avatar', 'resideprovince', 'residecity', 'residedist'));
		}
		if (empty($member)) {
			$member = array('uniacid' => $_W['uniacid'], 'uid' => $uid, 'openid' => $openid, 'realname' => !empty($mc['realname']) ? $mc['realname'] : '', 'mobile' => !empty($mc['mobile']) ? $mc['mobile'] : '', 'nickname' => !empty($mc['nickname']) ? $mc['nickname'] : $userinfo['nickname'], 'avatar' => !empty($mc['avatar']) ? $mc['avatar'] : $userinfo['avatar'], 'gender' => !empty($mc['gender']) ? $mc['gender'] : $userinfo['sex'], 'province' => !empty($mc['residecity']) ? $mc['resideprovince'] : $userinfo['province'], 'city' => !empty($mc['residecity']) ? $mc['residecity'] : $userinfo['city'], 'area' => !empty($mc['residedist']) ? $mc['residedist'] : '', 'createtime' => time(), 'status' => 0);
			pdo_insert('ewei_shop_member', $member);
		} else {
			if (!empty($uid)) {
				$upgrade = array();
				if ($userinfo['nickname'] != $member['nickname']) {
					$upgrade['nickname'] = $userinfo['nickname'];
				}
				if ($userinfo['avatar'] != $member['avatar']) {
					$upgrade['avatar'] = $userinfo['avatar'];
				}
				if (empty($member['uid'])) {
					$upgrade['uid'] = $uid;
				}
				if ($member['credit1'] > 0) {
					mc_credit_update($uid, 'credit1', $member['credit1']);
					$upgrade['credit1'] = 0;
				}
				if ($member['credit2'] > 0) {
					mc_credit_update($uid, 'credit2', $member['credit2']);
					$upgrade['credit2'] = 0;
				}
				if (!empty($upgrade)) {
					pdo_update('ewei_shop_member', $upgrade, array('id' => $member['id']));
				}
			}
		}
		if (p('commission')) {
			p('commission')->checkAgent();
		}
		if (p('poster')) {
			p('poster')->checkScan();
		}
	}
	function getLevels()
	{
		global $_W;
		return pdo_fetchall('select * from ' . tablename('ewei_shop_member_level') . ' where uniacid=:uniacid order by level asc', array(':uniacid' => $_W['uniacid']));
	}
	function getLevel($openid)
	{
		global $_W;
		if (empty($openid)) {
			return false;
		}
		$member = m('member')->getMember($openid);
		if (empty($member['level'])) {
			return array('discount' => 10);
		}
		$level = pdo_fetch('select * from ' . tablename('ewei_shop_member_level') . ' where id=:id and uniacid=:uniacid order by level asc', array(':uniacid' => $_W['uniacid'], ':id' => $member['level']));
		if (empty($level)) {
			return array('discount' => 10);
		}
		return $level;
	}
	function upgradeLevel($openid)
	{
		global $_W;
		if (empty($openid)) {
			return;
		}
		$member = m('member')->getMember($openid);
		if (!empty($member)) {
			$ordercount = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_order') . ' where openid=:openid and status=3 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':openid' => $member['openid']));
			$ordermoney = pdo_fetchcolumn('select ifnull( sum(price),0) from ' . tablename('ewei_shop_order') . ' where openid=:openid and status=3 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':openid' => $member['openid']));
			$level = pdo_fetch('select * from ' . tablename('ewei_shop_member_level') . " where uniacid=:uniacid " . " and  ( ({$ordercount} >= ordercount and ordercount>0) or  ({$ordermoney} >= ordermoney and ordermoney>0) ) " . " order by level desc limit 1", array(':uniacid' => $_W['uniacid']));
			if (!empty($level) && $level['id'] != $member['level']) {
				$oldlevel = $this->getLevel($openid);
				pdo_update('ewei_shop_member', array('level' => $level['id']), array('id' => $member['id']));
				m('notice')->sendMemberUpgradeMessage($openid, $oldlevel, $level);
			}
		}
	}
	function getGroups()
	{
		global $_W;
		return pdo_fetchall('select * from ' . tablename('ewei_shop_member_group') . ' where uniacid=:uniacid order by id asc', array(':uniacid' => $_W['uniacid']));
	}
	function getGroup($openid)
	{
		if (empty($openid)) {
			return false;
		}
		$member = m('member')->getMember($openid);
		return $member['groupid'];
	}
	function setRechargeCredit($openid = '', $money = 0)
	{
		if (empty($openid)) {
			return;
		}
		global $_W;
		$credit = 0;
		$set = m('common')->getSysset(array('trade', 'shop'));
		if ($set['trade']) {
			$tmoney = floatval($set['trade']['money']);
			$tcredit = intval($set['trade']['credit']);
			if ($tmoney > 0) {
				if ($money % $tmoney == 0) {
					$credit = intval($money / $tmoney) * $tcredit;
				} else {
					$credit = (intval($money / $tmoney) + 1) * $tcredit;
				}
			}
		}
		if ($credit > 0) {
			$this->setCredit($openid, 'credit1', $credit, array(0, $set['shop']['name'] . '会员充值积分:credit2:' . $credit));
		}
	}
}