<?php
    include_once 'common.inc.php';
    $operation = !empty($_GET['op']) ? $_GET['op'] : 'index';
    $current_category = false;
    if (!empty($_GET['tcate'])) {
        $current_category = $shop_category->field('id,parentid,name,level')->where(array('id' => intval($_GET['tcate']), 'uniacid' => $uniacid))->order('displayorder DESC')->find();
    } else {
        if (!empty($_GET['ccate'])) {
            $current_category = $shop_category->field('id,parentid,name,level')->where(array('id' => intval($_GET['ccate']), 'uniacid' => $uniacid))->order('displayorder DESC')->find();
        } else {
            if (!empty($_GET['pcate'])) {
                $current_category = $shop_category->field('id,parentid,name,level')->where(array('id' => intval($_GET['pcate']), 'uniacid' => $uniacid))->order('displayorder DESC')->find();
            }
        }
    }
    $parent_category = $shop_category->field('id,parentid,name,level')->where(array('id' => $current_category['parentid'], 'uniacid' => $uniacid))->order('displayorder DESC')->find();
    
    $arr = array(
        'current_category'=>$current_category,
        'parent_category'=>$parent_category,
    );
    $this->assign('arr',$arr);
    
    if (IS_AJAX) {
        $args = array(
            'pagesize' => 10,
            'page' => I('page'),
            'order' => I('order'),
            'by' => I('by'),
    
            'uniacid'=>$uniacid,
            'deleted'=>0,
            'status'=>1,
        );
        $isnew=I('isnew');
        if(!empty($isnew)){
            $args['isnew']=$isnew;
        }
    
        $ishot=I('ishot');
        if(!empty($ishot)){
            $args['ishot']=$ishot;
        }
    
        $isrecommand=I('isrecommand');
        if(!empty($isrecommand)){
            $args['isrecommand']=$isrecommand;
        }
    
        $isdiscount=I('isdiscount');
        if(!empty($isdiscount)){
            $args['isdiscount']=$isdiscount;
        }
    
        $istime=I('istime');
        if(!empty($istime)){
            $args['istime']=$istime;
        }
    
        $keywords=I('keywords');
        if(!empty($keywords)){
            $args['keywords']=$keywords;
        }
    
        $pcate=I('pcate');
        if(!empty($pcate)){
            $args['pcate']=$pcate;
        }
    
        $ccate=I('ccate');
        if(!empty($ccate)){
            $args['ccate']=$ccate;
        }
    
        $tcate=I('tcate');
        if(!empty($tcate)){
            $args['tcate']=$tcate;
        }
        if(!empty($args['order'])){
            $order="{$args['order']}"." "."{$args['by']}";
        }else{
            $order=" displayorder desc,createtime desc ";
        }
        $goods = $shop_goods->field('id,title,thumb,marketprice,productprice,sales,total')->where($args)->order($order)->page($args['page'],$args['pagesize'])->select();
        foreach ($goods as &$item){
            $item['thumb']=tomedia($item['thumb']);
        }
        $category = false;
        if (intval($args['page']) <= 1) {
            if (!empty($args['tcate'])) {
                $parent_category1 = $shop_category->field('id,parentid,name,level')->where(array('id' => $parent_category['parentid'], 'uniacid' => $uniacid))->find();
                $category = $shop_category->field('id,name,level')->where(array('parentid' => $parent_category['id'], 'uniacid' => $uniacid))->order('level asc, isrecommand desc, displayorder DESC')->select();
                $category = array_merge(array(array('id' => 0, 'name' => '全部分类', 'level' => 0), $parent_category1, $parent_category), $category);
            } else {
                if (!empty($args['ccate'])) {
                    if (intval($shopSet['catlevel']) == 3) {
                        $Map['parentid'] =  array('eq',intval($args['ccate']));
                        $Map['id'] =  array('eq',intval($args['ccate']));
    
                        $Map['_logic'] = 'OR';
                        $condition['_complex'] = $Map;
    
                        $condition['enabled'] = array('eq',1);
                        $condition['uniacid'] = array('eq',$uniacid);
                        $category = $shop_category->field('id,name,level')->where($condition)->order('level asc, isrecommand desc, displayorder DESC')->select();
                    } else {
                        $category = $shop_category->field('id,name,level')->where(array('parentid' => $parent_category['id'], 'uniacid' => $uniacid,'enabled'=>1))->order('level asc, isrecommand desc, displayorder DESC')->select();
                    }
                    $category = array_merge(array(array('id' => 0, 'name' => '全部分类', 'level' => 0), $parent_category), $category);
                } else {
                    if (!empty($args['pcate'])) {
                        $Map['parentid'] =  array('eq',intval($args['pcate']));
                        $Map['id'] =  array('eq',intval($args['pcate']));
    
                        $Map['_logic'] = 'OR';
                        $condition['_complex'] = $Map;
    
                        $condition['enabled'] = array('eq',1);
                        $condition['uniacid'] = array('eq',$uniacid);
                        $category = $shop_category->field('id,name,level')->where($condition)->order('level asc, isrecommand desc, displayorder DESC')->select();
                        $category = array_merge(array(array('id' => 0, 'name' => '全部分类', 'level' => 0)), $category);
                    } else {
                        $category = $shop_category->field('id,name,level')->where(array('parentid' => 0, 'uniacid' => $uniacid,'enabled'=>1))->order('level asc, isrecommand desc, displayorder DESC')->select();
                    }
                }
            }
            foreach ($category as &$c) {
                if ($current_category['id'] == $c['id']) {
                    $c['on'] = true;
                }
            }
            unset($c);
        }
        show_json(1, array('goods' => $goods, 'pagesize' => $args['pagesize'], 'category' => $category, 'current_category' => $current_category));
    }