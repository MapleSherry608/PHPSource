<?php
include_once 'common.inc.php';
if (IS_AJAX) {
    if ($operation == 'display') {
        $pindex = max(1, intval(I('page')));
        $psize = 5;
        $status = I('status');
        $condition = array(
            'openid'=>$openid,
            'userdeleted'=>0,
            'deleted'=>0,
            'uniacid'=>$uniacid,
        );
        if ($status != '') {
            if ($status != 4) {
                $condition['status']=array('eq',intval($status));
            } else {
                $condition['refundid']=array('neq',0);
            }
        } else {
            $condition['status']=array('neq',-1);
        }
        $column = " id,ordersn,price,status,refundid,expresscom,express,expresssn ";
        $list = $shop_order->field($column)->where($condition)->order('createtime desc')->page($pindex,$psize)->select();
        $total= $shop_order->where($condition)->count();
        $tradeset = array('refunddays'=>7);//获取退款设置 TODO
        $refunddays = intval($tradeset['refunddays']);
        $refunddays == 0 && ($refunddays = 7);
        foreach ($list as &$row) {
            $column = " og.goodsid,og.total,g.title,g.thumb,og.price,og.optionname as optiontitle,og.optionid ";
            $condition = array('og.orderid'=>$row['id']);
            $join = " og left join sx_zxin_shop_goods g on og.goodsid = g.id ";
            $row['goods'] = $shop_order_goods->field($column)->join($join)->where($condition)->select();
            foreach ($row['goods'] as &$item){
                $item['thumb']=tomedia($item['thumb']);
            }
            $row['goodscount'] = count($row['goods']);
            switch ($row['status']) {
                case "-1":
                    $status = "已取消";
                    break;
                case "0":
                    $status = "待付款";
                    break;
                case "1":
                    $status = "待发货";
                    break;
                case "2":
                    $status = "待收货";
                    break;
                case "3":
                    if (empty($row['iscomment'])) {
                        $status = "待评价";
                    } else {
                        $status = "交易完成";
                    }
                    break;
            }
            $row['statusstr'] = $status;
            if (!empty($row['refundid'])) {
                $row['statusstr'] = '待退款';
            }
            $canrefund = false;
            if ($row['status'] == 1) {
                $canrefund = true;
            } else {
                if ($row['status'] == 3) {
                    $days = intval((time() - $row['finishtime']) / 3600 / 24);
                    if ($days <= $refunddays) {
                        $canrefund = true;
                    }
                }
            }
            $row['canrefund'] = $canrefund;
        }
        unset($row);
        show_json(1, array('total' => $total, 'list' => $list, 'pagesize' => $psize));
    }
}