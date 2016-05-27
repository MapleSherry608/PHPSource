<?php
    include_once 'common.inc.php';
    
    $goodsid = intval(I('id'));
    $mid = intval(I('mid'));
    $arr = array(
        'goodsid'=>$goodsid,
        'mid'=>$mid,
        'openid'=>$openid,
        'set'=>$shopSet,
    );
    
    $this->assign('arr',$arr);
    
    if(IS_AJAX){
        $goods = $shop_goods->where(array('id' => $goodsid))->find();
    
        $goods['thumb']=tomedia($goods['thumb']);
        $html = $goods['content'];
        preg_match_all('/<img.*?src=[\\\'| \\"](.*?(?:[\\.gif|\\.jpg]?))[\\\'|\\"].*?[\\/]?>/', $html, $imgs);
        if (isset($imgs[1])) {
            foreach ($imgs[1] as $img) {
                //这里简单处理下路径 本没有kuangjia这个文件 出现图片显示不正确可以删除
                $new = tomedia($img);
                $new = str_replace('kuangjia/', '',$new);
                $im = array("old" => $img, "new" => $new);
                $images[] = $im;
            }
            if (isset($images)) {
                foreach ($images as $img) {
                    $html = str_replace($img['old'], $img['new'], $html);
                }
            }
            $goods['content'] = $html;
        }
        $goods['canbuy'] = !empty($goods['status']) && empty($goods['deleted']);
        $goods['timestate'] = '';
        $goods['userbuy'] = '1';
        if ($goods['usermaxbuy'] > 0) {
            $condition = array('og.goodsid' => $goodsid, 'o.uniacid' => $uniacid, 'o.openid' => $openid);
            $order_goodscount = $shop_order_goods->where($condition)->join('as og left join sx_zxin_shop_order as o on og.orderid = o.id ')->getField('sum(og.total)');
            if(empty($order_goodscount)){
                $order_goodscount=0;
            }
            if ($order_goodscount >= $goods['usermaxbuy']) {
                $goods['userbuy'] = 0;
            }
        }
        //获取会员等级  TODO
        $member=array(
            'levelid'=>0,
            'groupid'=>0,
        );
        $levelid = $member['level'];
        $groupid = $member['groupid'];
        $goods['levelbuy'] = '1';
        if ($goods['buylevels'] != '') {
            $buylevels = explode(',', $goods['buylevels']);
            if (!in_array($levelid, $buylevels)) {
                $goods['levelbuy'] = 0;
            }
        }
        $goods['groupbuy'] = '1';
        if ($goods['buygroups'] != '') {
            $buygroups = explode(',', $goods['buygroups']);
            if (!in_array($groupid, $buygroups)) {
                $goods['groupbuy'] = 0;
            }
        }
    
        $goods['timebuy'] = '0';
        if ($goods['istime'] == 1) {
            if (time() < $goods['timestart']) {
                $goods['timebuy'] = '-1';
                $goods['timestate'] = "before";
                $goods['buymsg'] = "限时购活动未开始";
            } else {
                if (time() > $goods['timeend']) {
                    $goods['timebuy'] = '1';
                    $goods['buymsg'] = '限时购活动已经结束';
                } else {
                    $goods['timestate'] = 'after';
                }
            }
        }
        $pics = array($goods['thumb']);
        $thumburl = unserialize($goods['thumb_url']);
        if (is_array($thumburl)) {
            $pics = array_merge($pics, $thumburl);
        }
        unset($thumburl);
        $pics = set_medias($pics);
        $marketprice = $goods['marketprice'];
        $productprice = $goods['productprice'];
        $maxprice = $marketprice;
        $minprice = $marketprice;
        $stock = $goods['total'];
    
        $allspecs = array();
        if (!empty($goods['hasoption'])) {
            $allspecs = $shop_goods_spec->where(array('goodsid' => $goodsid))->order('displayorder asc')->select();
            foreach ($allspecs as &$s) {
                $items = $shop_goods_spec_item->where(array("specid" => $s['id'],'show'=>1))->order('displayorder asc')->select();
                foreach ($items as &$i){
                    $i['thumb'] = tomedia($i['thumb']);
                }
                $s['items']=$items;
            }
        }
    
        $options = array();
        if (!empty($goods['hasoption'])) {
    
            $options = $shop_goods_option->field('id,title,thumb,marketprice,productprice,costprice, stock,weight,specs')->where(array('goodsid' => $goodsid))->order('id asc')->select();
            foreach ($options as &$o) {
                $o['thumb']=tomedia($o['thumb']);
                if ($maxprice < $o['marketprice']) {
                    $maxprice = $o['marketprice'];
                }
                if ($minprice > $o['marketprice']) {
                    $minprice = $o['marketprice'];
                }
            }
            $goods['maxprice'] = $maxprice;
            $goods['minprice'] = $minprice;
        }
    
        $specs = array();
        if (!empty($goods['hasoption'])) {
            if (count($options) > 0) {
                $specitemids = explode("_", $options[0]['specs']);
                foreach ($specitemids as $itemid) {
                    foreach ($allspecs as $ss) {
                        $items = $ss['items'];
                        foreach ($items as $it) {
                            if ($it['id'] == $itemid) {
                                $specs[] = $ss;
                                break;
                            }
                        }
                    }
                }
            }
        }
    
        $params = $shop_goods_param->where(array(':uniacid' => $uniacid, ":goodsid" => $goods['id']))->order('displayorder asc')->select();
        $fcount = $shop_member_favorite ->where(array('uniacid' => $uniacid, 'openid' => $openid, 'goodsid' => $goods['id'],'deleted'=>0))->count();
        $viewcount = $shop_goods->where(array("id" => $goodsid,'uniacid'=>$uniacid))->getField('viewcount');
        $shop_goods->where(array("id" => $goodsid,'uniacid'=>$uniacid))->save(array('viewcount'=>intval($viewcount)+1));
        $history = $shop_member_history->where(array('goodsid' => $goodsid, 'uniacid' => $uniacid, 'openid' => $openid))->count();
        if ($history <= 0) {
            $history = array('uniacid' => $uniacid, 'openid' => $openid, 'goodsid' => $goodsid, 'deleted' => 0, 'createtime' => time());
            $shop_member_history->add($history);
        }
        $level =$member['levelid'];
        $stores = array();
        if ($goods['isverify'] == 2) {
            $storeids = array();
            if (!empty($goods['storeids'])) {
                $storeids = array_merge(explode(',', $goods['storeids']), $storeids);
            }
            $condition['uniacid']=array('eq',$uniacid);
            $condition['status']=array('eq',1);
            if (empty($storeids)) {
                $stores = $shop_store->where($condition)->select();
            } else {
                $condition['id']=array('in',implode(',', $storeids));
                $stores = $shop_store->where($condition)->select();
            }
        }
    
        $followed = $shop_member->where(array('openid'=>$openid))->getField('followed');
        //$followurl = empty($goods['followurl']) ? $shop['followurl'] : $goods['followurl'];
        $followurl = $goods['followurl'];
        $followtip = empty($goods['followtip']) ? '如果您想要购买此商品，需要您关注我们的公众号，点击【确定】关注后再来购买吧~' : $goods['followtip'];
        /* $sale_plugin = p('sale');
         $saleset = false;
         if ($sale_plugin) {
         $saleset = $sale_plugin->getSet();
        } */
    
        /* 'saleset' => $saleset, */
        /* 'commission' => $opencommission,
         'commission_text' => $commission_text, */
        /* 'shop' => $shop, */
        $ret = array(
            'goods' => $goods,
            'followed' => intval($followed) ? 1 : 0,
            'followurl' => $followurl,
            'followtip' => $followtip,
            'pics' => $pics,
            'options' => $options,
            'specs' => $specs,
            'params' => $params,
            'level' => $level,
            'shopset' => array('name'=>'搜雪商城'),
            'goodscount' => $shop_goods->where(array('status'=>1,'deleted'=>0,'uniacid'=>$uniacid))->count(),
            'cartcount' => $shop_member_cart->where(array('deleted'=>0,'uniacid'=>$uniacid,'openid'=>$openid))->getField('sum(total)'),
            'isfavorite' => $fcount > 0,
            'stores' => $stores
        );
        show_json(1,$ret);
    }