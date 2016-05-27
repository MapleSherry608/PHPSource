<?php
    include_once 'common.inc.php';
    if (IS_AJAX) {
        if ($operation == 'display') {
            $advs = $shop_adv->where('enabled=1')->order('displayorder desc')->select();
            if(!empty($advs)){
                foreach ($advs as &$item){
                    $item['thumb']=tomedia($item['thumb']);
                }
            }else{
                $advs = array();
            }
            show_json(1, array('set' => $shopSet, 'advs' => $advs));
        } else {
            if ($operation == 'goods') {
                $type = I('type');
                $pageindex = max(1, intval(I('get.page')));
                $pagesize  = 6;
                $goods = $shop_goods->page($pageindex,$pagesize)->where(array('isrecommand'=>1,'deleted'=>0,'status'=>1))->order('displayorder desc,createtime desc')->select();
                foreach ($goods as &$item){
                    $item['thumb']=tomedia($item['thumb']);
                }
                if(empty($goods)){
                    $goods = array();
                }
                show_json(1, array('goods' => $goods, 'pagesize' => $pagesize));
            }
        }
    }