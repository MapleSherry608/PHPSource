<?php
include_once 'common.inc.php';
$id = intval(I('id'));
$type = I('type');
$data = intval(I('data'));
if (in_array($type, array('new', 'hot', 'recommand', 'discount', 'time', 'sendfree', 'nodiscount'))) {
    $data = $data == 1 ? '0' : '1';
    $shop_goods->where(array("id" => $id, "uniacid" => $uniacid))->save(array('is' . $type => $data));
    if ($type == 'new') {
        $typestr = "新品";
    } else {
        if ($type == 'hot') {
            $typestr = "热卖";
        } else {
            if ($type == 'recommand') {
                $typestr = "推荐";
            } else {
                if ($type == 'discount') {
                    $typestr = "促销";
                } else {
                    if ($type == 'time') {
                        $typestr = "限时卖";
                    } else {
                        if ($type == 'sendfree') {
                            $typestr = "包邮";
                        } else {
                            if ($type == 'nodiscount') {
                                $typestr = "不参与折扣状态";
                            }
                        }
                    }
                }
            }
        }
    }
    die(json_encode(array('result' => 1, 'data' => $data)));
}
if (in_array($type, array('status'))) {
    $data = $data == 1 ? '0' : '1';
    $shop_goods->where(array("id" => $id, "uniacid" => $uniacid))->save(array($type => $data));
    die(json_encode(array('result' => 1, 'data' => $data)));
}
if (in_array($type, array('type'))) {
    $data = $data == 1 ? '2' : '1';
    $shop_goods->where(array("id" => $id, "uniacid" => $uniacid))->save(array($type => $data));
    die(json_encode(array('result' => 1, 'data' => $data)));
}
die(json_encode(array('result' => 0)));