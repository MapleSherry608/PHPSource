<?php
include_once 'common.inc.php';
$operation = !empty($_GET['op']) ? $_GET['op'] : 'display';
if (IS_AJAX) {
    if ($operation == 'category') {
        $allcategory = array();
        $category = $shop_category->where(array('uniacid'=>$uniacid,'enabled'=>1))->order('parentid ASC, displayorder DESC')->select();
        foreach ($category as &$c) {
            $c['thumb']=tomedia($c['thumb']);
            $c['advimg']=tomedia($c['advimg']);
            if (empty($c['parentid'])) {
                $children = array();
                foreach ($category as $c1) {
                    if ($c1['parentid'] == $c['id']) {
                        $c1['thumb']=tomedia($c1['thumb']);
                        $c1['advimg']=tomedia($c1['advimg']);
                        $children[] = $c1;
                    }
                }
                $c['children'] = $children;
                $allcategory[] = $c;
            }
        }
        show_json(1, array('category' => $allcategory));
    } else {
        if ($operation == 'areas') {
            getApi('json','xml2json');
            $file = IA_ROOT . "Public/Home/Js/components/area/Area.xml";

            $content = file_get_contents($file);
            $json = xml2json::transformXmlStringToJson($content);
            die(json_encode($json));
        } else {
            if ($operation == 'search') {
                $keywords = trim(I('keywords'));
                $condition['deleted']=array('eq',0);
                $condition['status']=array('eq',1);
                $condition['title']=array('like','%'.$keywords.'%');
                $goods = $shop_goods->where($condition)->order('displayorder desc,createtime desc')->select();
                show_json(1, array('list' => $goods));
            } else {
                //TODO
                if ($operation == 'comment') {
                    $goodsid = intval(I('goodsid'));
                    $pindex = max(1, intval(I('page')));
                    $psize = 5;
                    $condition = ' and uniacid = :uniacid and goodsid=:goodsid and deleted=0';
                    $condition = array(
                        'uniacid'=>$uniacid,
                        'goodsid'=>$goodsid,
                        'deleted'=>0,
                    );
                    $params = array(':uniacid' => $uniacid, ':goodsid' => $goodsid);
                    $cloumn = " id,nickname,headimgurl,level,content,createtime, images,append_images,append_content,reply_images,reply_content,append_reply_images,append_reply_content ";
                    $list = $shop_order_comment->field($cloumn)->order('id desc')->where($condition)->page($pindex,$psize)->select();
                    foreach ($list as &$row) {
                        $row['headimgurl'] = tomedia($row['headimgurl']);
                        $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
                        $images = unserialize($row['images']);
                        foreach ($images as &$item){
                            tomedia($item);
                        }
                        $row['images'] = is_array($images) ? $images : array();
                        $append_images = unserialize($row['append_images']);
                        foreach ($append_images as &$item){
                            tomedia($item);
                        }
                        $row['append_images'] = is_array($append_images) ? $append_images : array();
                        $reply_images = unserialize($row['reply_images']);
                        foreach ($reply_images as &$item){
                            tomedia($item);
                        }
                        $row['reply_images'] = is_array($reply_images) ? $reply_images : array();
                        $append_reply_images = unserialize($row['append_reply_images']);
                        foreach ($append_reply_images as &$item){
                            tomedia($item);
                        }
                        $row['append_reply_images'] = is_array($append_reply_images) ? $append_reply_images : array();
                    }
                    unset($row);
                    show_json(1, array('list' => $list, 'pagesize' => $psize));
                } else {
                    if ($operation == 'recommand') {
                        $psize  = 4;
                        $goods = $shop_goods->where(' isrecommand=1 and status = 1 ')->order('rand()')->limit($psize)->select();
                        foreach ($goods as &$item){
                            $item['thumb']=tomedia($item['thumb']);
                        }
                        show_json(1, array('list' => $goods));
                    }
                }
            }
        }
    }
}