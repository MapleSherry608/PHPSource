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
        $condition = array(
            'uniacid'=>$uniacid,
            'status'=>1,
        );
        $total = $shop_notice->where($condition)->count();
        $list = $shop_notice->where($condition)->order('displayorder desc,createtime desc')->page($pindex,$psize)->select();
        foreach ($list as &$row) {
            $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
            $row['thumb'] = tomedia($row['thumb']);
        }
        show_json(1, array('list' => $list, 'pagesize' => $psize));
    } else {
        if ($operation == 'get') {
            $id = intval(I('id'));
            $condition = array(
                'id'=>$id,
                'uniacid'=>$uniacid,
                'status'=>1,
            );
            $data = $shop_notice->where($condition)->find();
            if (!empty($data)) {
                $data['createtime'] = date('Y-m-d H:i', $data['createtime']);
            }
            show_json(1, array('notice' => $data));
        }
    }
}