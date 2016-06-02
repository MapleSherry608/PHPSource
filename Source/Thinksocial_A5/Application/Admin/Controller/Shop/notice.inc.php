<?php
include_once 'common.inc.php';
if ($operation == 'display') {
    $list = $shop_notice->where(array('uniacid'=>$uniacid))->order('displayorder DESC')->select();
} elseif ($operation == 'post') {
    $id = intval(I('id'));
    if (IS_POST) {
        $data = array(
            'uniacid' =>        $uniacid,
            'displayorder' =>   intval($_POST['displayorder']),
            'title' =>          trim($_POST['title']),
            'thumb' =>          $_POST['thumb'],
            'link' =>           trim($_POST['link']),
            'detail' =>         htmlspecialchars_decode($_POST['detail']),
            'status' =>         intval($_POST['status']),
            'createtime' =>     time()
        );
        if (!empty($id)) {
            $shop_notice->where(array('id' => $id))->save($data);
        } else {
            $id = $shop_notice->add($data);
        }
        $this->success('更新店铺公告成功！',U('Shop/notice', array('op' => 'display')));
    }
    $notice = $shop_notice->where(array('id'=>$id,'uniacid'=>$uniacid))->find();
} elseif ($operation == 'delete') {
    $id = intval(I('id'));
    $notice = $shop_notice->where(array('id'=>$id,'uniacid'=>$uniacid))->find();
    if (empty($notice)) {
        $this->error('抱歉，店铺公告不存在或是已经被删除！',U('Shop/notice', array('op' => 'display')));
    }
    $shop_notice->where(array('id' => $id))->delete();
    $this->error('店铺公告删除成功！',U('Shop/notice', array('op' => 'display')));
}
$arr=array(
    'operation'=>$operation,
    'list'=>$list,
    'notice'=>$notice,
);
$this->assign('arr',$arr);