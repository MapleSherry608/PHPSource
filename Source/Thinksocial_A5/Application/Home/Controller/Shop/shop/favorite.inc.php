<?php
include_once 'common.inc.php';
if (IS_AJAX) {
    $operation = I('op');
    if(empty($operation)){
        $operation = 'display';
    }
    if ($operation == 'display') {
        $pindex = max(1, intval(I('get.p')));
        $psize = 10;
        $condition['f.uniacid']=array('eq',$uniacid);
        $condition['f.openid']=array('eq',$openid);
        $condition['f.deleted']=array('eq',0);
        $total = $shop_member_favorite->where(array('uniacid'=>$uniacid,'openid'=>$openid,'deleted'=>0))->count();
        if (!empty($total)) {
            $list = $shop_member_favorite->page($pindex,$psize)->where($condition)->join('as f left join sx_zxin_shop_goods as g on f.goodsid = g.id ')->order('f.id desc')->select();
            foreach ($list as &$item){
                $item['thumb']=tomedia($item['thumb']);
            }
        }
        show_json(1, array('total' => $total, 'list' => $list, 'pagesize' => $psize));
    } else {
        if ($operation == 'set') {
            $id = intval(I('id'));
            $goods = $shop_goods->find($id);
            if (empty($goods)) {
                show_json(0, '商品未找到');
            }
            $data = $shop_member_favorite->where(array('uniacid' => $uniacid, 'openid' => $openid, 'goodsid' => $id))->find();
            if (empty($data)) {
                $data = array('uniacid' => $uniacid, 'openid' => $openid, 'goodsid' => $id, 'createtime' => time());
                $shop_member_favorite->add($data);
                show_json(1, array('isfavorite' => true));
            } else {
                if (empty($data['deleted'])) {
                    $shop_member_favorite->where(array('id' => $data['id'], 'uniacid' => $uniacid, 'openid' => $openid))->save(array('deleted' => 1));
                    show_json(1, array('isfavorite' => false));
                } else {
                    $shop_member_favorite->where(array('id' => $data['id'], 'uniacid' => $uniacid, 'openid' => $openid))->save(array('deleted' => 0));
                    show_json(1, array('isfavorite' => true));
                }
            }
        } else {
            if ($operation == 'remove' && IS_AJAX) {
                $ids = I('ids');
                if (empty($ids) || !is_array($ids)) {
                    show_json(0, '参数错误');
                }
                $condition['uniacid']=array('eq',$uniacid);
                $condition['openid']=array('eq',$openid);
                $condition['goodsid']=array('in',implode(',', $ids));
                $shop_member_favorite->where($condition)->save(array('deleted' => 1));
                show_json(1);
            }
        }
    }
}