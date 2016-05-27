<?php
    include_once 'common.inc.php';
    
    $children = array();
    $category=$shop_category->where($condiction)->order('parentid ASC, displayorder DESC')->select();
    foreach ($category as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($category[$index]);
        }
    }
    if($operation=='display'){
        if(IS_POST){
            $datas=I('datas');
            if (!empty($datas)) {
                $datas = json_decode(html_entity_decode($datas), true);
                if (!is_array($datas)) {
                    $this->error("分类保存失败，请重试!",$this->createWebUrl('shop/category', array('op' => 'display')), 'success');
                }
                $displayorder = count($datas);
                foreach ($datas as $row) {
                    $data1= array(
                        'parentid' => 0,
                        "displayorder" => $displayorder,
                        'level' => 1
                    );
                    $shop_category->where(array('id'=>$row['id']))->save($data1);
                    if ($row['children'] && is_array($row['children'])) {
                        $displayorder_child = count($row['children']);
                        foreach ($row['children'] as $child) {
                            $data2= array(
                                'parentid' => $row['id'],
                                "displayorder" => $displayorder_child,
                                'level' => 2
                            );
                            $shop_category->where(array('id'=>$child['id']))->save($data2);
                            $displayorder_child--;
                            if ($child['children'] && is_array($child['children'])) {
                                $displayorder_third = count($child['children']);
                                foreach ($child['children'] as $third) {
                                    $data3= array(
                                        'parentid' => $child['id'],
                                        "displayorder" => $displayorder_third,
                                        'level' => 3
                                    );
                                    $shop_category->where(array('id'=>$third['id']))->save($data3);
                                    $displayorder_third--;
                                }
                            }
                        }
                    }
                    $displayorder--;
                }
                $this->success('分类更新成功！',U('Shop/category?op=display'));
            }
        }
    }elseif ($operation=='post'){
        $parentid = intval(I('parentid'));
        $id = intval(I('id'));
        if (!empty($id)) {
            $item=$shop_category->where("id=".$id)->find();
            $parentid = $item['parentid'];
        } else {
            $item = array('displayorder' => 0);
        }
        if (!empty($parentid)) {
            $parent =$shop_category->where('id='.$parentid)->find();
            if (empty($parent)) {
                $this->redirect('抱歉，上级分类不存在或是已经被删除！',U('post'));
            }
            if (!empty($parent['parentid'])) {
                $parent1=$shop_category->where('id='.$parent['parentid'])->find();
            }
            if (empty($parent)) {
                $level = 1;
            } else {
                if (empty($parent['parentid'])) {
                    $level = 2;
                } else {
                    $level = 3;
                }
            }
        }
        if(IS_POST){
            $data = array(
                'uniacid' => $uniacid,
                'name' => trim(I('catename')),
                'enabled' => intval(I('enabled')),
                'displayorder' => intval(I('displayorder')),
                'isrecommand' => intval(I('isrecommand')),
                'ishome' => intval(I('ishome')),
                'description' => I('description'),
                'parentid' => intval($parentid),
                'thumb' => I('thumb'),
                'advimg' => I('advimg'),
                'advurl' => trim(I('advurl')),
                'level' => $level
            );
            if (!empty($id)) {
                unset($data['parentid']);
                $shop_category->where(array('id'=>$id))->save($data);
                //file_delete($_GET['thumb_old']);
            } else {
                $id=$shop_category->add($data);
            }
            $this->success('更新分类成功！',U('category?op=display'));
        }
        $this->assign('parentid',$parentid);
        $this->assign('item',$item);
        $this->assign('parent',$parent);
    }elseif ($operation=='delete') {
        $id=intval(I('id'));
        $shop_category->where('id='.$id)->delete();
        $this->redirect('category');
    }
    $this->assign('children',$children);
    $this->assign('operation',$operation);
    $this->assign('category',$category);