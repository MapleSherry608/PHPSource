<?php
include_once 'common.php';
$member = $shop_member->where(array('openid'=>$openid))->find();
if (!empty($member['birthyear']) && !empty($member['birthmonth']) && !empty($member['birthday'])) {
    $member['birthday'] = $member['birthyear'] . '-' . (strlen($member['birthmonth']) <= 1 ? '0' . $member['birthmonth'] : $member['birthmonth']) . '-' . (strlen($member['birthday']) <= 1 ? '0' . $member['birthday'] : $member['birthday']);
}
if (empty($member['birthday'])) {
    $member['birthday'] = '';
}
if (IS_AJAX) {
    if (IS_POST) {
        $memberdata = I('memberdata');
        $shop_member->where(array('openid' => $openid, 'uniacid' => $uniacid))->save($memberdata);
        /* if (!empty($member['uid'])) {
         $mcdata = I('mcdata');
         mc_update($member['uid'], $mcdata);
        } */
        show_json(1);
    }
    show_json(1, array('member' => $member));
}