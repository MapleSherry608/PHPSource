<?php
include_once 'common.inc.php';
$operation = !empty($_GET['op']) ? $_GET['op'] : 'display';
if ($operation == 'display') {
    $pindex = max(1, intval(I('get.p')));
    $psize = 20;
    $condition['c.uniacid']=$uniacid;
    $condition['c.deleted']=0;
    if (!empty($_POST['keyword'])) {
        $_POST['keyword'] = trim($_POST['keyword']);
        $where['o.ordersn']=array('like',$_POST['keyword']);
        $where['g.title']=array('like',$_POST['keyword']);

        $where['_logic'] = 'OR';
        $condition['_complex'] = $where;
    }
    $starttime =0;
    $endtime   =0;
    if (empty($starttime) || empty($endtime)) {
        $starttime = strtotime('-1 month');
        $endtime = time();
    }
    if (!empty($_POST['searchtime'])) {
        $starttime = strtotime($_POST['time']['start']);
        $endtime = strtotime($_POST['time']['end']);
        $condition['c.createtime']=array('between',array($starttime,$endtime));
    }
    if ($_POST['fade'] != '') {
        if (empty($_POST['fade'])) {
            $condition['c.openid']=array('eq','');
        } else {
            $condition['c.openid']=array('neq','');
        }
    }
    if ($_POST['replystatus'] != '') {
        if (empty($_POST['replystatus'])) {
            $condition['c.reply_content']=array('eq','');
        } else {
            $condition['c.append_content']=array('eq','');
            $condition['c.append_reply_content']=array('eq','');
        }
    }
    $list = $shop_comment_view->page($pindex,$psize)->where($condition)->order('createtime desc')->select();
    $total = $shop_comment_view->where($condition)->select();
    /* $page = new Page($total,$psize);
     $page->rollPage=10;
    $pageHtml=$page->show(); */
    $pageHtml=$this->lists($shop_comment_view,$condition,'id desc');
} elseif ($operation == 'delete') {
    $id = intval(I('id'));
    $item = $shop_order_comment->where(array('id'=>$id))->find();
    if (empty($item)) {
        $this->error('抱歉，评价不存在或是已经被删除！',U('shop/comment', array('op' => 'display')));
    }
    $shop_order_comment->where(array('id' => $id, 'uniacid' => $uniacid))->save(array('deleted' => 1));
    $goods = $shop_goods->where(array('id' => $item['goodsid'], 'uniacid' => $uniacid))->find();
    $this->success('删除成功！', U('shop/comment', array('op' => 'display')));
} elseif ($operation == 'add') {
    $id = intval(I('id'));
    $item = $shop_order_comment->where(array(':id' => $id, ':uniacid' => $uniacid))->find();
    $goodsid = intval($_POST['goodsid']);
    if (IS_POST) {
        $goods = $shop_goods->where(array('id' => $goodsid, 'uniacid' => $uniacid))->find();
        $data = array(
            'uniacid' => $uniacid,
            'level' => intval($_POST['level']),
            'goodsid' => intval($_POST['goodsid']),
            'nickname' => trim($_POST['nickname']),
            'headimgurl' => trim($_POST['headimgurl']),
            'content' => $_POST['content'],
            'images' => is_array($_POST['images']) ? iserializer($_POST['images']) : iserializer(array()),
            'reply_content' => $_POST['reply_content'],
            'reply_images' => is_array($_POST['reply_images']) ? iserializer($_POST['reply_images']) : iserializer(array()),
            'append_content' => $_POST['append_content'],
            'append_images' => is_array($_POST['append_images']) ? iserializer($_POST['append_images']) : iserializer(array()),
            'append_reply_content' => $_POST['append_reply_content'],
            'append_reply_images' => is_array($_POST['append_reply_images']) ? iserializer($_POST['append_reply_images']) : iserializer(array()),
            'createtime' => time()
        );
        if (empty($data['nickname'])) {
            $data['nickname'] = $member->where("nickname<>''")->order('rand()')->limit(1)->getField('nickname');
        }
        if (empty($data['headimgurl'])) {
            $data['headimgurl'] = $member->where("avatar<>''")->order('rand()')->limit(1)->getField('avatar');
        }
        if (!empty($id)) {
            $shop_order_comment->where(array('id'=>$id))->save($data);
        } else {
            $id=$shop_order_comment->add($data);
        }
        $this->success('更新评价成功!',U('shop/comment'));
    }
    if (empty($goodsid)) {
        $goodsid = intval($item['goodsid']);
    }
    $goods = $shop_goods->where(array('id' => $goodsid, 'uniacid' => $uniacid))->find();

} elseif ($operation == 'post') {
    $id = intval(I('id'));
    $item  = $shop_order_comment->where(array(':id' => $id, ':uniacid' => $uniacid))->find();
    $goods = $shop_goods->where(array('id' => $item['goodsid'], 'uniacid' => $uniacid))->find();
    $order = $shop_order->where(array('id' => $item['orderid'], 'uniacid' => $uniacid))->find();
    if (IS_POST) {
        $data = array(
            'uniacid' => $uniacid,
            'reply_content' => $_POST['reply_content'],
            'reply_images' => is_array($_POST['reply_images']) ? iserializer($_POST['reply_images']) : iserializer(array()),
            'append_reply_content' => $_POST['append_reply_content'],
            'append_reply_images' => is_array($_POST['append_reply_images']) ? iserializer($_POST['append_reply_images']) : iserializer(array()));
        $shop_order_comment->where(array('id' => $id))->save($data);
        $this->success('更新评价成功!',U('shop/comment'));
    }
}
$arr=array(
    'operation'=>$operation,
    'list'=>$list,
    'total'=>$total,
    'item'=>$item,
    'goods'=>$goods,
    'order'=>$order,
);
$this->assign('arr',$arr);