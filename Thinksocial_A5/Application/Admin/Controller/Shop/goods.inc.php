<?php
include_once 'common.inc.php';
use Think\Page;

$category = $shop_category->where(array('uniacid'=>$uniacid))->order('parentid,displayorder DESC')->select();
$parent = $children = array();
if (!empty($category)) {
    foreach ($category as $cid => $cate) {
        if (!empty($cate['parentid'])) {
            $children[$cate['parentid']][] = $cate;
        } else {
            $parent[$cate['id']] = $cate;
        }
    }
}
if (!empty($category)) {
    foreach ($category as $key => &$row) {
        if (isset($row['id'])) {
            $rs[$row['id']] = $row;
        } else {
            $rs[] = $row;
        }
    }
}
$category=$rs;

if($operation=='post'){
    $id = intval(I('id'));
    $levels = 0;
    $groups = 0;
    if (!empty($id)) {
        $item = $shop_goods->find($id);
        if (empty($item)) {
            $this->error('抱歉，商品不存在或是已经删除！');
        }
        $noticetype = explode(',', $item['noticetype']);
        $allspecs = $shop_goods_spec->where(array("goodsid" => $id))->order('displayorder asc')->select();
        foreach ($allspecs as &$s) {
            $s['items'] = $shop_goods_spec_item->where(array("specid" => $s['id']))->order('displayorder asc')->select();
        }
        unset($s);
        $params = $shop_goods_param->where(array('goodsid' => $id))->order('displayorder asc')->select();
        $piclist1 = unserialize($item['thumb_url']);
        $piclist = array();
        if (is_array($piclist1)) {
            foreach ($piclist1 as $p) {
                //$piclist[] = is_array($p) ? $p['attachment'] : $p;
                $piclist[] = tomedia($p);
            }
        }
        $html = "";
        $options = $shop_goods_option->where(array('goodsid' => $id))->order('id asc')->select();
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
            $html = '';
            $html .= '<table class="table table-bordered table-condensed">';
            $html .= '<thead>';
            $html .= '<tr class="active">';
            $len = count($specs);
            $newlen = 1;
            $h = array();
            $rowspans = array();
            for ($i = 0; $i < $len; $i++) {
                $html .= "<th style='width:80px;'>" . $specs[$i]['title'] . "</th>";
                $itemlen = count($specs[$i]['items']);
                if ($itemlen <= 0) {
                    $itemlen = 1;
                }
                $newlen *= $itemlen;
                $h = array();
                for ($j = 0; $j < $newlen; $j++) {
                    $h[$i][$j] = array();
                }
                $l = count($specs[$i]['items']);
                $rowspans[$i] = 1;
                for ($j = $i + 1; $j < $len; $j++) {
                    $rowspans[$i] *= count($specs[$j]['items']);
                }
            }
            $canedit = 1;//权限判断
            if ($canedit) {
                $html .= '<th class="info" style="width:130px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">库存</div><div class="input-group"><input type="text" class="form-control option_stock_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_stock\');"></a></span></div></div></th>';
                $html .= '<th class="success" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">销售价格</div><div class="input-group"><input type="text" class="form-control option_marketprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_marketprice\');"></a></span></div></div></th>';
                $html .= '<th class="warning" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">市场价格</div><div class="input-group"><input type="text" class="form-control option_productprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_productprice\');"></a></span></div></div></th>';
                $html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">成本价格</div><div class="input-group"><input type="text" class="form-control option_costprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_costprice\');"></a></span></div></div></th>';
                $html .= '<th class="primary" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">商品编码</div><div class="input-group"><input type="text" class="form-control option_goodssn_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_goodssn\');"></a></span></div></div></th>';
                $html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">商品条码</div><div class="input-group"><input type="text" class="form-control option_productsn_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_productsn\');"></a></span></div></div></th>';
                $html .= '<th class="info" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">重量（克）</div><div class="input-group"><input type="text" class="form-control option_weight_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_weight\');"></a></span></div></div></th>';
                $html .= '</tr></thead>';
            }
            for ($m = 0; $m < $len; $m++) {
                $k = 0;
                $kid = 0;
                $n = 0;
                for ($j = 0; $j < $newlen; $j++) {
                    $rowspan = $rowspans[$m];
                    if ($j % $rowspan == 0) {
                        $h[$m][$j] = array("html" => "<td rowspan='" . $rowspan . "'>" . $specs[$m]['items'][$kid]['title'] . "</td>", "id" => $specs[$m]['items'][$kid]['id']);
                    } else {
                        $h[$m][$j] = array("html" => "", "id" => $specs[$m]['items'][$kid]['id']);
                    }
                    $n++;
                    if ($n == $rowspan) {
                        $kid++;
                        if ($kid > count($specs[$m]['items']) - 1) {
                            $kid = 0;
                        }
                        $n = 0;
                    }
                }
            }
            $hh = "";
            for ($i = 0; $i < $newlen; $i++) {
                $hh .= "<tr>";
                $ids = array();
                for ($j = 0; $j < $len; $j++) {
                    $hh .= $h[$j][$i]['html'];
                    $ids[] = $h[$j][$i]['id'];
                }
                $ids = implode("_", $ids);
                $val = array("id" => "", "title" => "", "stock" => "", "costprice" => "", "productprice" => "", "marketprice" => "", "weight" => "");
                foreach ($options as $o) {
                    if ($ids === $o['specs']) {
                        $val = array("id" => $o['id'], "title" => $o['title'], "stock" => $o['stock'], "costprice" => $o['costprice'], "productprice" => $o['productprice'], "marketprice" => $o['marketprice'], "goodssn" => $o['goodssn'], "productsn" => $o['productsn'], "weight" => $o['weight']);
                        break;
                    }
                }
                if ($canedit) {
                    $hh .= '<td class="info">';
                    $hh .= '<input name="option_stock_' . $ids . '[]"  type="text" class="form-control option_stock option_stock_' . $ids . '" value="' . $val['stock'] . '"/>';
                    $hh .= '<input name="option_id_' . $ids . '[]"  type="hidden" class="form-control option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
                    $hh .= '<input name="option_ids[]"  type="hidden" class="form-control option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
                    $hh .= '<input name="option_title_' . $ids . '[]"  type="hidden" class="form-control option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
                    $hh .= '</td>';
                    $hh .= '<td class="success"><input name="option_marketprice_' . $ids . '[]" type="text" class="form-control option_marketprice option_marketprice_' . $ids . '" value="' . $val['marketprice'] . '"/></td>';
                    $hh .= '<td class="warning"><input name="option_productprice_' . $ids . '[]" type="text" class="form-control option_productprice option_productprice_' . $ids . '" " value="' . $val['productprice'] . '"/></td>';
                    $hh .= '<td class="danger"><input name="option_costprice_' . $ids . '[]" type="text" class="form-control option_costprice option_costprice_' . $ids . '" " value="' . $val['costprice'] . '"/></td>';
                    $hh .= '<td class="primary"><input name="option_goodssn_' . $ids . '[]" type="text" class="form-control option_goodssn option_goodssn_' . $ids . '" " value="' . $val['goodssn'] . '"/></td>';
                    $hh .= '<td class="danger"><input name="option_productsn_' . $ids . '[]" type="text" class="form-control option_productsn option_productsn_' . $ids . '" " value="' . $val['productsn'] . '"/></td>';
                    $hh .= '<td class="info"><input name="option_weight_' . $ids . '[]" type="text" class="form-control option_weight option_weight_' . $ids . '" " value="' . $val['weight'] . '"/></td>';
                    $hh .= '</tr>';
                } else {
                    $hh .= '<td class="info">' . $val['stock'] . '</td>';
                    $hh .= '<td class="success">' . $val['marketprice'] . '</td>';
                    $hh .= '<td class="warning">' . $val['productprice'] . '</td>';
                    $hh .= '<td class="danger">' . $val['costprice'] . '</td>';
                    $hh .= '<td class="primary">' . $val['goodssn'] . '</td>';
                    $hh .= '<td class="danger">' . $val['productsn'] . '</td>';
                    $hh .= '<td class="info">' . $val['weight'] . '</td>';
                    $hh .= '</tr>';
                }
            }
            $html .= $hh;
            $html .= "</table>";
        }
        if ($item['showlevels'] != '') {
            $item['showlevels'] = explode(',', $item['showlevels']);
        }
        if ($item['buylevels'] != '') {
            $item['buylevels'] = explode(',', $item['buylevels']);
        }
        if ($item['showgroups'] != '') {
            $item['showgroups'] = explode(',', $item['showgroups']);
        }
        if ($item['buygroups'] != '') {
            $item['buygroups'] = explode(',', $item['buygroups']);
        }
        $stores = array();
        if (!empty($item['storeids'])) {
            $condiction['id']=array('in',$item['storeids']);
            $stores = $shop_store->where($condiction)->select();
        }
        if (!empty($item['noticeopenid'])) {
            //$saler = m('member')->getMember($item['noticeopenid']); //获取积分等会员信息
        }
    }
    if (empty($category)) {
        $this->error('抱歉，请您先添加商品分类！',U('Shop/category', array('op' => 'post')));
    }
    if (IS_POST) {
        if (empty($_POST['goodsname'])) {
            $this->error('请输入商品名称！');
        }
        if (empty($_POST['category']['parentid'])) {
            $this->error('请选择商品分类！');
        }
        if (empty($_POST['thumbs'])) {
            $_POST['thumbs'] = array();
        }
        $data = array(
            'uniacid' => $uniacid,
            'displayorder' => intval($_POST['displayorder']),
            'title' => trim($_POST['goodsname']),
            'pcate' => intval($_POST['category']['parentid']),
            'ccate' => intval($_POST['category']['childid']),
            'tcate' => intval($_POST['category']['thirdid']),
            'thumb' => $_POST['thumb'],
            'thumb_url' => $_POST['thumb_url'],
            'type' => intval($_POST['type']),
            'isrecommand' => intval($_POST['isrecommand']),
            'ishot' => intval($_POST['ishot']),
            'isnew' => intval($_POST['isnew']),
            'isdiscount' => intval($_POST['isdiscount']),
            'issendfree' => intval($_POST['issendfree']),
            'isnodiscount' => intval($_POST['isnodiscount']),
            'istime' => intval($_POST['istime']),
            'timestart' => strtotime($_POST['timestart']),
            'timeend' => strtotime($_POST['timeend']),
            'description' => trim($_POST['description']),
            'goodssn' => trim($_POST['goodssn']),
            'unit' => trim($_POST['unit']),
            'createtime' => NOW_TIME,
            'total' => intval($_POST['total']),
            'totalcnf' => intval($_POST['totalcnf']),
            'marketprice' => $_POST['marketprice'],
            'weight' => $_POST['weight'],
            'costprice' => $_POST['costprice'],
            'productprice' => trim($_POST['productprice']),
            'productsn' => trim($_POST['productsn']),
            'credit' => intval($_POST['credit']),
            'maxbuy' => intval($_POST['maxbuy']),
            'usermaxbuy' => intval($_POST['usermaxbuy']),
            'hasoption' => intval($_POST['hasoption']),
            'sales' => intval($_POST['sales']),
            'share_icon' => trim($_POST['share_icon']),
            'share_title' => trim($_POST['share_title']),
            'cash' => intval($_POST['cash']),
            'status' => intval($_POST['status']),
            'showlevels' => is_array($_POST['showlevels']) ? implode(",", $_POST['showlevels']) : '',
            'buylevels' => is_array($_POST['buylevels']) ? implode(",", $_POST['buylevels']) : '',
            'showgroups' => is_array($_POST['showgroups']) ? implode(",", $_POST['showgroups']) : '',
            'buygroups' => is_array($_POST['buygroups']) ? implode(",", $_POST['buygroups']) : '',
            'isverify' => intval($_POST['isverify']),
            'storeids' => is_array($_POST['storeids']) ? implode(',', $_POST['storeids']) : '',
            'noticeopenid' => trim($_POST['noticeopenid']),
            'noticetype' => is_array($_POST['noticetype']) ? implode(",", $_POST['noticetype']) : '',
            'needfollow' => intval($_POST['needfollow']),
            'followurl' => trim($_POST['followurl']),
            'followtip' => trim($_POST['followtip']),
            'deduct' => $_POST['deduct']
        );
        $content = htmlspecialchars_decode($_POST['content']);
        preg_match_all('/<img.*?src=[\\\'| \\"](.*?(?:[\\.gif|\\.jpg|\\.png|\\.jpeg]?))[\\\'|\\"].*?[\\/]?>/', $content, $imgs);
        $images = array();
        if (isset($imgs[1])) {
            foreach ($imgs[1] as $img) {
                $im = array("old" => $img, "new" => $img);
                $images[] = $im;
            }
        }
        foreach ($images as $img) {
            $content = str_replace($img['old'], $img['new'], $content);
        }
        $data['content'] = $content;
        /*  if (p('commission')) {
         $cset = p('commission')->getSet();
         if (!empty($cset['level'])) {
         $data['hascommission'] = intval($_POST['hascommission']);
         $data['commission1_rate'] = $_POST['commission1_rate'];
         $data['commission2_rate'] = $_POST['commission2_rate'];
         $data['commission3_rate'] = $_POST['commission3_rate'];
         $data['commission1_pay'] = $_POST['commission1_pay'];
         $data['commission2_pay'] = $_POST['commission2_pay'];
         $data['commission3_pay'] = $_POST['commission3_pay'];
         $data['commission_thumb'] = $_POST['commission_thumb'];
         }
         } */ //TODO
        if ($data['total'] === -1) {
            $data['total'] = 0;
            $data['totalcnf'] = 2;
        }
        if (is_array($_POST['thumbs'])) {
            $thumbs = $_POST['thumbs'];
            $thumb_url = array();
            foreach ($thumbs as $th) {
                $thumb_url[] = $th;
            }
            $data['thumb_url'] = serialize($thumb_url);
        }
        if (empty($id)) {
            $id = $shop_goods->add($data);
        } else {
            unset($data['createtime']);
            $shop_goods->where(array('id' => $id))->save($data);
        }
        $totalstocks = 0;
        $param_ids = $_POST['param_id'];
        $param_titles = $_POST['param_title'];
        $param_values = $_POST['param_value'];
        $param_displayorders = $_POST['param_displayorder'];
        $len = count($param_ids);
        $paramids = array();
        for ($k = 0; $k < $len; $k++) {
            $param_id = "";
            $get_param_id = $param_ids[$k];
            $a = array(
                "uniacid" => $uniacid,
                "title" => $param_titles[$k],
                "value" => $param_values[$k],
                "displayorder" => $k,
                "goodsid" => $id
            );
            if (!is_numeric($get_param_id)) {
                $param_id = $shop_goods_param->add($a);
            } else {
                $shop_goods_param->where(array('id' => $get_param_id))->save();
                $param_id = $get_param_id;
            }
            $paramids[] = $param_id;
        }
        if (count($paramids) > 0) {
            $Map['id']=array('not in',implode(',', $paramids));
            $shop_goods_param->where($Map)->delete();
        } else {
            $shop_goods_param->where(array('goodsid'=>$id))->delete();
        }
        $files = $_FILES;
        $spec_ids = $_POST['spec_id'];
        $spec_titles = $_POST['spec_title'];
        $specids = array();
        $len = count($spec_ids);
        $specids = array();
        $spec_items = array();
        for ($k = 0; $k < $len; $k++) {
            $spec_id = "";
            $get_spec_id = $spec_ids[$k];
            $a = array(
                "uniacid" => $uniacid,
                "goodsid" => $id,
                "displayorder" => $k,
                "title" => $spec_titles[$get_spec_id]
            );
            if (is_numeric($get_spec_id)) {
                $shop_goods_spec->where(array("id" => $get_spec_id))->save($a);
                $spec_id = $get_spec_id;
            } else {
                $spec_id=$shop_goods_spec->add($a);
            }
            $spec_item_ids = $_POST["spec_item_id_" . $get_spec_id];
            $spec_item_titles = $_POST["spec_item_title_" . $get_spec_id];
            $spec_item_shows = $_POST["spec_item_show_" . $get_spec_id];
            $spec_item_thumbs = $_POST["spec_item_thumb_" . $get_spec_id];
            $spec_item_oldthumbs = $_POST["spec_item_oldthumb_" . $get_spec_id];
            $itemlen = count($spec_item_ids);
            $itemids = array();
            for ($n = 0; $n < $itemlen; $n++) {
                $item_id = "";
                $get_item_id = $spec_item_ids[$n];
                $d = array(
                    "uniacid" => $uniacid,
                    "specid" => $spec_id,
                    "displayorder" => $n,
                    "title" => $spec_item_titles[$n],
                    "show" => $spec_item_shows[$n],
                    "thumb" => $spec_item_thumbs[$n]
                );
                $f = "spec_item_thumb_" . $get_item_id;
                if (is_numeric($get_item_id)) {
                    $shop_goods_spec_item->where(array("id" => $get_item_id))->save($d);
                    $item_id = $get_item_id;
                } else {
                    $item_id = $shop_goods_spec_item->add($d);
                }
                $itemids[] = $item_id;
                $d['get_id'] = $get_item_id;
                $d['id'] = $item_id;
                $spec_items[] = $d;
            }
            if (count($itemids) > 0) {
                $Map['uniacid']=$uniacid;
                $Map['specid'] =$spec_id;
                $Map['id']     =array('not in',implode(",", $itemids));
                $shop_goods_spec_item->where($Map)->delete();
            } else {
                $Map['uniacid']=$uniacid;
                $Map['specid'] =$spec_id;
                $shop_goods_spec_item->where($Map)->delete();
            }
            $shop_goods_spec->where(array("id" => $spec_id))->save(array('content' => serialize($itemids)));
            $specids[] = $spec_id;
        }
        if (count($specids) > 0) {
            $Map['uniacid']=$uniacid;
            $Map['goodsid'] =$id;
            $Map['id']     =array('not in',implode(",", $specids));
            $shop_goods_spec->where($Map)->delete();
        } else {
            $Map['uniacid']=$uniacid;
            $Map['goodsid'] =$id;
            $shop_goods_spec->where($Map)->delete();
        }
        $option_idss = $_POST['option_ids'];
        $option_productprices = $_POST['option_productprice'];
        $option_marketprices = $_POST['option_marketprice'];
        $option_costprices = $_POST['option_costprice'];
        $option_stocks = $_POST['option_stock'];
        $option_weights = $_POST['option_weight'];
        $option_goodssns = $_POST['option_goodssn'];
        $option_productssns = $_POST['option_productsn'];
        $len = count($option_idss);
        $optionids = array();
        for ($k = 0; $k < $len; $k++) {
            $option_id = "";
            $ids = $option_idss[$k];
            $get_option_id = $_POST['option_id_' . $ids][0];
            $idsarr = explode("_", $ids);
            $newids = array();
            foreach ($idsarr as $key => $ida) {
                foreach ($spec_items as $it) {
                    if ($it['get_id'] == $ida) {
                        $newids[] = $it['id'];
                        break;
                    }
                }
            }
            $newids = implode("_", $newids);
            $a = array(
                "uniacid" => $uniacid,
                "title" => $_POST['option_title_' . $ids][0],
                "productprice" => $_POST['option_productprice_' . $ids][0],
                "costprice" => $_POST['option_costprice_' . $ids][0],
                "marketprice" => $_POST['option_marketprice_' . $ids][0],
                "stock" => $_POST['option_stock_' . $ids][0],
                "weight" => $_POST['option_weight_' . $ids][0],
                "goodssn" => $_POST['option_goodssn_' . $ids][0],
                "productsn" => $_POST['option_productsn_' . $ids][0],
                "goodsid" => $id,
                "specs" => $newids
            );
            $totalstocks += $a['stock'];
            if (empty($get_option_id)) {
                $option_id = $shop_goods_option->add($a);
            } else {
                $shop_goods_option->where(array('id' => $get_option_id))->save($a);
                $option_id = $get_option_id;
            }
            $optionids[] = $option_id;
        }
        if (count($optionids) > 0) {
            $Map['goodsid'] =$id;
            $Map['id']     =array('not in',implode(",", $optionids));
            $shop_goods_option->where($Map)->delete();
        } else {
            $Map['goodsid'] =$id;
            $shop_goods_option->where($Map)->delete();
        }
        if ($totalstocks > 0 && $data['totalcnf'] != 2) {
            $shop_goods->where(array("id" => $id))->save(array("total" => $totalstocks));
        }
        $this->success('商品更新成功！',U('Shop/goods', array('op' => 'post', 'id' => $id)));
    }
}elseif($operation=='display'){
    if (!empty($_POST['displayorder'])) {
        foreach ($_POST['displayorder'] as $id => $displayorder) {
            $shop_goods->where(array('id' => $id))->save(array('displayorder' => $displayorder));
        }
        $this->success('商品排序更新成功！',U('Shop/goods', array('op' => 'display')));
    }
    $pindex = max(1, intval(I('get.p')));
    $psize = 20;
    $condiction['uniacid']=$uniacid;
    $condiction['deleted']=0;
    if (!empty($_POST['keyword'])) {
        $_POST['keyword'] = trim($_POST['keyword']);
        $condiction['title']=array('like','%'.$_POST['keyword'].'%');
    }
    if (!empty($_POST['category']['thirdid'])) {
        $condiction['tcate']=intval($_POST['category']['thirdid']);
    }
    if (!empty($_POST['category']['childid'])) {
        $condiction['ccate']=intval($_POST['category']['childid']);
    }
    if (!empty($_POST['category']['parentid'])) {
        $condiction['pcate'] = intval($_POST['category']['parentid']);
    }
    if (isset($_POST['status'])) {
        $condiction['status'] = intval($_POST['status']);
    }
    $total = $shop_goods->where($condiction)->count();
    if (!empty($total)) {
        $list = $shop_goods->page($pindex,$psize)->where($condiction)->order('status DESC,displayorder DESC,id DESC')->select();
    }
    //自定义分页
    $page = new Page($total,$psize);
    $page->rollPage=10;
    $pageHtml=$page->show();
    $this->assign('pageHtml',$pageHtml);
}elseif ($operation == 'delete') {
    $id = intval(I('id'));
    $row = $shop_goods->where(array('id' => $id))->find();
    if (empty($row)) {
        $this->error('抱歉，商品不存在或是已经被删除！');
    }
    $shop_goods->where(array('id' => $id))->save(array('deleted' => 1));
    $this->success('删除成功！');
}
$arr=array(
    'id'=>$id,
    'item'=>$item,
    'noticetype'=>$noticetype,
    'condition'=>$condiction,
    'category' =>$category,
    'allspecs'=>$allspecs,
    'parent'=>$parent,
    'children'=>$children,
    'operation'=>$operation,
    'total'    =>$total,
    'list'     =>$list,
    'levels'=>$levels,
    'groups'=>$groups,
    'params'=>$params,
    'piclist'=>$piclist,
    'options'=>$options,
    'rowspans'=>$rowspans,
    'stores'=>$stores,
    'html'=>$html,
);
$this->assign('arr',$arr);