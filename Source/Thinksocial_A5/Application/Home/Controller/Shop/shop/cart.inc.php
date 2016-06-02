<?php
include_once 'common.inc.php';
if (IS_AJAX) {
    $operation = I('op');
    if(empty($operation)){
        $operation = 'display';
    }
    if ($operation == 'display') {
        $condition['f.uniacid'] =array('eq',$uniacid);
        $condition['f.openid']  =array('eq',$openid);
        $condition['f.deleted'] =array('eq',0);
        $list = array();
        $total = 0;
        $totalprice = 0;
        $cloumn = "f.id,f.total,f.goodsid,g.total as stock, o.stock as optionstock, g.maxbuy,g.title,g.thumb,o.marketprice omarketprice, g.marketprice gmarketprice,g.productprice,o.title as optiontitle,f.optionid,o.specs";
        $join = " as f left join sx_zxin_shop_goods as g on f.goodsid = g.id left join sx_zxin_shop_goods_option as o on f.optionid = o.id ";
        $list = $shop_member_cart->field($cloumn)->join($join)->where($condition)->order('id desc')->select();
        foreach ($list as &$r) {
            if (!empty($r['optionid'])) {
                $r['stock'] = $r['optionstock'];
            }
            $omarketprice=floatval($r['omarketprice']);
            if(!empty($omarketprice)){
                $r['marketprice']=$r['omarketprice'];
            }else{
                $r['marketprice']=$r['gmarketprice'];
            }
            $totalprice += $r['marketprice'] * $r['total'];
            $total += $r['total'];
        }
        unset($r);
        foreach ($list as &$item){
            $item['thumb']=tomedia($item['thumb']);
        }
        $totalprice = number_format($totalprice, 2);
        show_json(1, array('total' => $total, 'list' => $list, 'totalprice' => $totalprice));
    } else {
        if ($operation == 'add' && IS_AJAX) {
            $id = intval(I('id'));
            $total = intval(I('total'));
            empty($total) && ($total = 1);
            $optionid = intval(I('optionid'));
            $goods = $shop_goods->field('id,marketprice')->where(array('uniacid' => $uniacid, 'id' => $id))->find();
            if (empty($goods)) {
                show_json(0, '商品未找到');
            }
            $data = $shop_member_cart->field('id')->where(array('uniacid' => $uniacid,'deleted'=>0, 'openid' => $openid, 'optionid' => $optionid, 'goodsid' => $id))->find();
            $cartcount = $shop_member_cart->where(array('uniacid' => $uniacid,'deleted'=>0, 'openid' => $openid))->getField('count(id)');
            if (empty($data)) {
                $data = array(
                    'uniacid' => $uniacid,
                    'openid' => $openid,
                    'goodsid' => $id,
                    'optionid' => $optionid,
                    'marketprice' => $goods['marketprice'],
                    'total' => $total,
                    'createtime' => time()
                );
                $shop_member_cart->add($data);
                $cartcount += $total;
                show_json(1, array('message' => '添加成功', 'cartcount' => $cartcount));
            }
            show_json(1, array('message' => '已在购物车', 'cartcount' => $cartcount));
        } else {
            if ($operation == 'selectoption' && IS_AJAX) {
                $id = intval(I('id'));
                $goodsid = intval(I('goodsid'));
                $cartdata = $shop_member_cart->field('id,optionid,total')->where(array('id' => $id, 'uniacid' => $uniacid, 'openid' => $openid))->find();
                $cartoption = $shop_goods_option->field('id,title,thumb,marketprice,productprice,costprice,stock,weight,specs')->where(array('id' => $cartdata['optionid'], 'uniacid' => $uniacid, 'goodsid' => $goodsid))->find();
                $cartoption['thumb']=tomedia($cartoption['thumb']);
                $cartspecs = explode('_', $cartoption['specs']);
                $goods = $shop_goods->field('id,title,thumb,total,marketprice')->where(array('id' => $goodsid))->find();
                $goods['thumb']=tomedia($goods['thumb']);
                $allspecs = $shop_goods_spec->where(array('goodsid' => $goodsid))->order('displayorder asc')->select();
                foreach ($allspecs as &$s) {
                    $s['items'] = $shop_goods_spec_item->where(array('specid' => $s['id'],'show'=>1))->order('displayorder asc')->select();
                    foreach ($s['items'] as &$i){
                        $i['thumb'] = tomedia($i['thumb']);
                    }
                }
                unset($s);
                $options = $shop_goods_option->field('id,title,thumb,marketprice,productprice,costprice, stock,weight,specs')->where(array('goodsid' => $goodsid))->order('id asc')->select();
                foreach ($options as &$item){
                    $item['thumb']=tomedia($item['thumb']);
                }
                $specs = array();
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
                show_json(1, array('cartdata' => $cartdata, 'cartoption' => $cartoption, 'cartspecs' => $cartspecs, 'goods' => $goods, 'options' => $options, 'specs' => $specs));
            } else {
                if ($operation == 'setoption' && IS_AJAX) {
                    $id = intval(I('id'));
                    $goodsid = intval(I('goodsid'));
                    $optionid = intval(I('optionid'));
                    $option = $shop_goods_option->field('id,title,thumb,marketprice,productprice,costprice, stock,weight,specs')->where(array('id' => $optionid, 'uniacid' => $uniacid, 'goodsid' => $goodsid))->find();
                    $option['thumb']=tomedia($option['thumb']);
                    if (empty($option)) {
                        show_json(0, '规格未找到');
                    }
                    $shop_member_cart->where(array('id' => $id, 'uniacid' => $uniacid, 'goodsid' => $goodsid))->save(array('optionid' => $optionid));
                    show_json(1, array('optionid' => $optionid, 'optiontitle' => $option['title']));
                } else {
                    if ($operation == 'updatenum' && IS_AJAX) {
                        $id = intval(I('id'));
                        $goodsid = intval(I('goodsid'));
                        $total = intval(I('total'));
                        empty($total) && ($total = 1);
                        $data = $shop_member_cart->field('id,total')->where(array('uniacid' => $uniacid, 'goodsid' => $goodsid, 'openid' => $openid, 'id' => $id))->select();
                        if (empty($data)) {
                            show_json(0, '购物车数据未找到');
                        }
                        $shop_member_cart->where(array('id' => $id, 'uniacid' => $uniacid, 'goodsid' => $goodsid))->save(array('total' => $total));
                        show_json(1);
                    } else {
                        if ($operation == 'tofavorite' && IS_AJAX) {
                            $ids = I('ids');
                            if (empty($ids) || !is_array($ids)) {
                                show_json(0, '参数错误');
                            }
                            foreach ($ids as $id) {
                                $goodsid = $shop_member_cart->where(array('id' => $id, 'uniacid' => $uniacid, 'openid' => $openid))->getField('goodsid');
                                if (!empty($goodsid)) {
                                    $fav = $shop_member_favorite->where(array('deleted'=>0,'goodsid' => $goodsid, 'uniacid' => $uniacid, 'openid' => $openid))->count();
                                    if ($fav <= 0) {
                                        $fav = array('uniacid' => $uniacid, 'goodsid' => $goodsid, 'openid' => $openid, 'deleted' => 0, 'createtime' => time());
                                        $shop_member_favorite->add($fav);
                                    }
                                }
                            }
                            $condition['uniacid']  =array('eq',$uniacid);
                            $condition['openid']   =array('eq',$openid);
                            $condition['id']       =array('in',implode(',', $ids));
                            $shop_member_cart->where($condition)->save(array('deleted'=>1));
                            show_json(1);
                        } else {
                            if ($operation == 'remove' && IS_AJAX) {
                                $ids = I('ids');
                                if (empty($ids) || !is_array($ids)) {
                                    show_json(0, '参数错误');
                                }
                                $condition['uniacid']  =array('eq',$uniacid);
                                $condition['openid']   =array('eq',$openid);
                                $condition['id']       =array('in',implode(',', $ids));
                                $shop_member_cart->where($condition)->save(array('deleted'=>1));
                                show_json(1);
                            }
                        }
                    }
                }
            }
        }
    }
}