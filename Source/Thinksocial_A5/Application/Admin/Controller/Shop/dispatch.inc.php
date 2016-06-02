<?php
    include_once 'common.inc.php';
    $operation = !empty($_GET['op']) ? $_GET['op'] : 'display';
    if($operation=='display'){
        if(IS_POST){
            $displayorder=I('displayorder');
            if (!empty($displayorder)) {
                foreach ($displayorder as $id => $displayorder) {
                    $shop_dispatch->where(array('id' => $id))->save(array('displayorder' => $displayorder));
                }
                $this->success('分类排序更新成功！',U('Shop/dispatch', array('op' => 'display')));
            }
        }
        $condiction=array('uniacid'=>$uniacid);
        $list=$shop_dispatch->where($condiction)->order('id asc')->select();
    }elseif ($operation=='post'){
        $id = intval(I('id'));
        if (IS_POST) {
            $randoms = I('random');
            if (is_array($randoms)) {
                foreach ($randoms as $random) {
                    $areas[] = array(
                        'citys' =>        $_POST['citys'][$random],
                        'firstprice' =>   $_POST['firstprice'][$random],
                        'firstweight' =>  $_POST['firstweight'][$random],
                        'secondprice' =>  $_POST['secondprice'][$random],
                        'secondweight' => $_POST['secondweight'][$random]
                    );
                }
            }
            $carriers = array();
            $addresses = $_GET['address'];
            if (is_array($addresses)) {
                foreach ($addresses as $key => $address) {
                    $carriers[] = array(
                        'address' =>  $_POST['address'][$key],
                        'realname' => $_POST['realname'][$key],
                        'mobile' =>   $_POST['mobile'][$key],
                        'content' =>  $_POST['content'][$key]
                    );
                }
            }
            $data = array(
                'uniacid' =>      $uniacid,
                'displayorder' => intval(I('displayorder')),
                'dispatchtype' => intval(I('dispatchtype')),
                'dispatchname' => trim(I('dispatchname')),
                'express' =>      trim(I('express')),
                'firstprice' =>   trim(I('default_firstprice')),
                'firstweight' =>  trim(I('default_firstweight')),
                'secondprice' =>  trim(I('default_secondprice')),
                'secondweight' => trim(I('default_secondweight')),
                'areas' =>        serialize($areas),
                'carriers' =>     serialize($carriers),
                'enabled' =>      intval(I('enabled'))
            );
            if (!empty($id)) {
                $shop_dispatch->where(array('id' => $id))->save($data);
            } else {
                $id=$shop_dispatch->add($data);
            }
            $this->success('更新配送方式成功！',U('Shop/dispatch',array('op' => 'display')));
        }
        //修改
        $dispatch=$shop_dispatch->where('id = '.$id.' and uniacid = '.$uniacid)->find();
        if (!empty($dispatch)) {
            $dispatch_areas = unserialize($dispatch['areas']);
            $dispatch_carriers = unserialize($dispatch['carriers']);
        }
        $areafile = IA_ROOT . "Public/Admin/Js/components/area/areas";
        $areas = json_decode(@file_get_contents($areafile), true);
        /* if (!is_array($areas)) {
         require_once EWEI_SHOP_INC . 'json/xml2json.php';
         $file = IA_ROOT . "/addons/shopping/static/js/dist/area/Area.xml";
         $content = file_get_contents($file);
         $json = xml2json::transformXmlStringToJson($content);
         $areas = json_decode($json, true);
         file_put_contents($areafile, $json);
        } */
    }elseif ($operation == 'delete') {
        $id = intval(I('id'));
        $dispatch=$shop_dispatch->where('id = '.$id.' and uniacid = '.$uniacid)->find();
        if (empty($dispatch)) {
            $this->error('抱歉，配送方式不存在或是已经被删除！',U('Shop/dispatch', array('op' => 'display')));
        }
        $shop_dispatch->where(array('id' => $id))->delete();
        $this->success('配送方式删除成功！',U('Shop/dispatch', array('op' => 'display')));
    }else {
        if ($operation == 'tpl') {
            $random = random(16);
            ob_clean();
            ob_start();
            $this->assign('random',$random);
            $this->display('Shop/tpl/dispatch');
            $contents = ob_get_contents();
            ob_clean();
            die(json_encode(array('random' => $random, 'html' => $contents)));
        } else {
            if ($operation == 'tpl1') {
                $random = random(16);
                ob_clean();
                ob_start();
                $this->assign('random',$random);
                $this->display('Shop/tpl/carrier');
                $contents = ob_get_contents();
                ob_clean();
                die(json_encode(array('random' => $random, 'html' => $contents)));
            }
        }
    }
    $arr=array(
        'operation'=>$operation,
        'list'=>$list,
        'random'=>$random,
        'dispatch'=>$dispatch,
        'item'=>$item,
        'dispatch_areas'=>$dispatch_areas,
        'dispatch_carriers'=>$dispatch_carriers,
        'areas'=>$areas,
    );
    $this->assign('arr',$arr);