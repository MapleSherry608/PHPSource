<?php
include_once 'common.inc.php';
if ($operation == 'display') {
    if (!empty($_POST['displayorder'])) {
        foreach ($_POST['displayorder'] as $id => $displayorder) {
            $shop_adv->where(array('id' => $id))->save(array('displayorder' => $displayorder));
        }
        $this->success('分类排序更新成功！', U('Shop/adv', array('op' => 'display')));
    }
    $list =$shop_adv->where('uniacid = '.$uniacid)->order('displayorder DESC')->select();
} elseif ($operation == 'post') {
    $id = intval(I('id'));
    if (IS_POST) {
        $data = array(
            'uniacid' =>        $uniacid,
            'advname' =>        trim($_POST['advname']),
            'link' =>           trim($_POST['link']),
            'enabled' =>        intval($_POST['enabled']),
            'displayorder' =>   intval($_POST['displayorder']),
            'thumb' =>          $_POST['thumb']
        );

        if (!empty($id)) {
            $shop_adv->where(array('id' => $id))->save($data);
        } else {
            $id=$shop_adv->add($data);
        }
        $this->success('更新幻灯片成功！',U('Shop/adv', array('op' => 'display')));
    }
    $item=$shop_adv->where(array('id'=>$id,'uniacid'=>$uniacid))->find();
} elseif ($operation == 'delete') {
    $id = intval(I('id'));
    $item = $shop_adv->where(array('id'=>$id,'uniacid'=>$uniacid))->find();
    if (empty($item)) {
        $this->error('抱歉，幻灯片不存在或是已经被删除！',U('Shop/adv', array('op' => 'display')));
    }
    $shop_adv->where(array('id' => $id))->delete();
    $this->success('幻灯片删除成功！',U('Shop/adv', array('op' => 'display')));
}
$arr=array(
    'operation'=>$operation,
    'item'=>$item,
    'list'=>$list,
);
$this->assign('arr',$arr);