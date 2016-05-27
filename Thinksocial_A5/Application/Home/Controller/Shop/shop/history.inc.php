<?php
include_once 'common.inc.php';
if(IS_AJAX){
    $operation = I('op');
    if(empty($operation)){
        $operation = 'display';
    }
    if ($operation == 'display') {
        $pindex = max(1, intval(I('get.p')));
        $psize = 10;
        $total = $shop_member_history->where(array('uniacid'=>$uniacid,'openid'=>$openid,'deleted'=>0))->count();
        $list = array();
        if (!empty($total)) {
            $condition = array(
                'f.uniacid'=>$uniacid,
                'f.openid'=>$openid,
                'f.deleted'=>0,
            );
            $join = " as f left join sx_zxin_shop_goods as g on f.goodsid = g.id ";
            $list = $shop_member_history->field('f.id,f.goodsid,g.title,g.thumb,g.marketprice,g.productprice')->join($join)->where($condition)->order('f.id desc')->page($pindex,$psize)->select();
            foreach ($list as &$item){
                $item['thumb']=tomedia($item['thumb']);
            }
        }
        show_json(1, array('total' => $total, 'list' => $list, 'pagesize' => $psize));
    } else {
        if ($operation == 'remove' && IS_AJAX) {
            $ids = I('ids');
            if (empty($ids) || !is_array($ids)) {
                show_json(0, '参数错误');
            }
            $condition=array(
                'uniacid'=>$uniacid,
                'openid'=>$openid,
                'id'=>array('in',implode(',', $ids))
            );
            $shop_member_history->where($condition)->save(array('deleted'=>1));
            show_json(1);
        }
    }
}