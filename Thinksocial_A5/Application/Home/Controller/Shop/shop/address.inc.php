<?php
include_once 'common.inc.php';
//匹配微信地址
/* $trade = m_m('common')->getSysset('trade');
 if (!empty($trade['shareaddress'])) {
 if (IS_AJAX) {
 $shareAddress = m_m('common')->shareAddress();
 if (empty($shareAddress)) {
 die;
 }
 }
 }
 $this->assign('shareAddress',$shareAddress); */
if (IS_AJAX) {
    $operation = I('op');
    if(empty($operation)){
        $operation = 'display';
    }
    if ($operation == 'display') {
        $pindex = max(1, intval(I('get.p')));
        $psize = 20;
        $condition['uniacid']=array('eq',$uniacid);
        $condition['deleted']=array('eq',0);
        $condition['openid']=array('eq',$openid);
        $total = $shop_member_address->where($condition)->count();
        if (!empty($total)) {
            $list = $shop_member_address->where($condition)->order('id desc')->page($pindex,$psize)->select();
        }
        show_json(1, array('list' => $list));
    } else {
        if ($operation == 'new') {
            $member = $shop_member->where(array('openid'=>$openid))->find();
            show_json(1, array('address' => array('province' => $member['resideprovince'], 'city' => $member['residecity'],'area'=>$member['residedist']), 'member' => $member, 'shareAddress' => $shareAddress));
        } else {
            if ($operation == 'get') {
                $id = intval(I('id'));
                $data = $shop_member_address->where(array('uniacid' => $uniacid, 'id' => $id,'deleted'=>0))->find();
                $member = $shop_member->where(array('openid'=>$openid))->find();
                show_json(1, array('address' => $data, 'member' => $member));
            } else {
                if ($operation == 'submit' && IS_AJAX) {
                    $id = intval(I('id'));
                    $data = I('addressdata');
                    $data['openid'] = $openid;
                    $data['uniacid'] = $uniacid;
                    if (empty($id)) {
                        $addresscount = $shop_member_address->where(array('uniacid' => $uniacid, 'openid' => $openid,'deleted'=>0))->count();
                        if ($addresscount <= 0) {
                            $data['isdefault'] = 1;
                        }
                        $id = $shop_member_address->add($data);
                    } else {
                        $shop_member_address->where(array('id' => $id, 'uniacid' => $uniacid, 'openid' => $openid))->save($data);
                    }
                    show_json(1, array('addressid' => $id));
                } else {
                    if ($operation == 'remove' && IS_AJAX) {
                        $id = intval(I('id'));
                        $data = $shop_member_address->field('id,isdefault')->where(array('uniacid' => $uniacid, 'openid' => $openid,'id'=>$id,'deleted'=>0))->find();
                        if (empty($data)) {
                            show_json(0, '地址未找到');
                        }
                        $shop_member_address->where(array('id' => $id))->save(array('deleted' => 1));
                        if ($data['isdefault'] == 1) {
                            $shop_member_address->where(array('uniacid' =>$uniacid,'openid' => $openid,'id' => $id))->save(array('isdefault' => 0));
                            $data2 = $shop_member_address->field('id')->where(array('uniacid' => $uniacid, 'openid' => $openid,'deleted'=>0))->order('id desc')->find();
                            if (!empty($data2)) {
                                $shop_member_address->where(array('uniacid' => $uniacid,'openid' => $openid,'id' => $data2['id']))->save(array('isdefault' => 1));
                                show_json(1, array('defaultid' => $data2['id']));
                            }
                        }
                        show_json(1);
                    } else {
                        if ($operation == 'setdefault' && IS_AJAX) {
                            $id = intval(I('id'));
                            $data = $shop_member_address->field('id')->where(array('id'=>$id,'deleted'=>0,'uniacid'=>$uniacid))->find();
                            if (empty($data)) {
                                show_json(0, '地址未找到');
                            }
                            $shop_member_address->where(array('uniacid' => $uniacid, 'openid' => $openid))->save(array('isdefault' => 0));
                            $shop_member_address->where(array('id' => $id, 'uniacid' => $uniacid, 'openid' => $openid))->save(array('isdefault' => 1));
                            show_json(1);
                        }
                    }
                }
            }
        }
    }
}