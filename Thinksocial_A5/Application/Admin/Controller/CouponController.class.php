<?php
namespace Admin\Controller;
class CouponController extends AdminController{
    public function _empty(){
        global $_W, $_GPC;
        $uniacid= intval($_W['uniacid']);
        $shop_category          = M('zxin_shop_category');//分类
        $shop_coupon            = M('zxin_shop_coupon');//优惠券
        
        $action=array('coupon','couponRule');
        
        if(in_array(ACTION_NAME,$action)){
            /**
             * 优惠券
             */
            if(ACTION_NAME == 'coupon'){
                $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'showTopCoupon';
                $category = $shop_category->where('parentid != 0')->select();
                //生成随机数
                function randomkeys($length) {
                    $returnStr='';
                    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
                    for($i = 0; $i < $length; $i ++) {
                        $returnStr .= $pattern {mt_rand ( 0, 61 )}; //生成php随机数
                    }
                    return $returnStr;
                }
                
                if($operation=="addCoupon"){
                    if($_GPC['couponsn']!=null){ 
                        $starttime=$_GPC['starttime'].' 00:00:00';
                        $endtime=$_GPC['endtime'].' 23:59:59';
                        $condimoney=$_GPC['condimoney'];
                        $ucateid=$_GPC['ucateid'];
                        if($ucateid!=null){
                            foreach($ucateid as $item){
                                $cateid.=','.$item;
                            }
                        }
                        $time=strtotime("now");
                        $num=$_GPC['num'];
                        $couponsn=$_GPC['couponsn'].$times;
                        for($i=0;$i<$num;$i++){
                            if($i<10){
                                $times=substr($time,3);
                                $couponsn=$_GPC['couponsn'].$times;
                                $result=pdo_insert("shopping_coupon",array('couponsn'=>$couponsn.$i.randomkeys(3),'createtime'=>TIMESTAMP,'ucateid'=>$cateid,'starttime'=>$starttime,'endtime'=>$endtime,'condimoney'=>$condimoney,'cprice'=>$_GPC['cprice'],'type'=>intval($_GPC['type'])));
                            }else if($i<100){
                                $times=substr($time,4);
                                $couponsn=$_GPC['couponsn'].$times;
                                $result=pdo_insert("shopping_coupon",array('couponsn'=>$couponsn.$i.randomkeys(3),'createtime'=>TIMESTAMP,'ucateid'=>$cateid,'starttime'=>$starttime,'endtime'=>$endtime,'condimoney'=>$condimoney,'cprice'=>$_GPC['cprice'],'type'=>intval($_GPC['type'])));
                            }elseif($i<1000){
                                $times=substr($time,5);
                                $couponsn=$_GPC['couponsn'].$times;
                                $result=pdo_insert("shopping_coupon",array('couponsn'=>$couponsn.$i.randomkeys(3),'createtime'=>TIMESTAMP,'ucateid'=>$cateid,'starttime'=>$starttime,'endtime'=>$endtime,'condimoney'=>$condimoney,'cprice'=>$_GPC['cprice'],'type'=>intval($_GPC['type'])));
                            }elseif($i<10000){
                                $times=substr($time,6);
                                $couponsn=$_GPC['couponsn'].$times;
                                $result=pdo_insert("shopping_coupon",array('couponsn'=>$couponsn.$i.randomkeys(3),'createtime'=>TIMESTAMP,'ucateid'=>$cateid,'starttime'=>$starttime,'endtime'=>$endtime,'condimoney'=>$condimoney,'cprice'=>$_GPC['cprice'],'type'=>intval($_GPC['type'])));
                            }elseif($i<100000){
                                $times=substr($time,7);
                                $couponsn=$_GPC['couponsn'].$times;
                                $result=pdo_insert("shopping_coupon",array('couponsn'=>$couponsn.$i.randomkeys(3),'createtime'=>TIMESTAMP,'ucateid'=>$cateid,'starttime'=>$starttime,'endtime'=>$endtime,'condimoney'=>$condimoney,'cprice'=>$_GPC['cprice'],'type'=>intval($_GPC['type'])));
                            }
                        }
                        if($result){
                            if($_GPC['type']=='1'){
                                message('线上优惠券添加成功！', $this->createWebUrl('coupon', array('op' => 'showTopCoupon')), 'success');
                            }else{
                                message('线下优惠券添加成功！', $this->createWebUrl('coupon', array('op' => 'showButtomCoupon')), 'success');
                            }
                        }else{
                            message("添加失败");
                            exit();
                        }
                    }
                }elseif($operation=="showTopCoupon"){
                
                    if (!empty($_GPC['keyword'])) {
                        $cprice=$_GPC['keyword'];
                        $condition.=" AND cprice={$cprice}   ";
                    }
                    if (!empty($_GPC['status'])) {
                        $status=intval($_GPC['status']);
                        $condition .= " AND `status` ={$status}";
                    }
                
                    if (!empty($_GPC['ifuser'])) {
                        $ifuser=$_GPC['ifuser'];
                        if($ifuser=="yes"){
                            //查询已经绑定的优惠券
                            $condition .= " and uid !=0 ";
                        }
                        if($ifuser=="no"){
                            //查询已经绑定的优惠券
                            $condition .= " and uid =0 ";
                        }
                    }
                
                    $pindex = max(1, intval($_GPC['page']));
                    $psize = 15;
                    $TopcouponList=pdo_fetchall("select * from ".tablename('shopping_coupon')." where type=1  and deleted=0   $condition  ORDER BY id DESC LIMIT ". ($pindex - 1) * $psize . ',' . $psize);
                    foreach($TopcouponList as &$items){
                        //  $items['ucateid']=pdo_fetchcolumn("select name from ".tablename('shopping_category')." where id=".$items['ucateid']);
                        $s=strtotime($items['starttime']);
                        $e=strtotime($items['endtime']);
                        $items['starttime']=date("Y-m-d",$s);
                        $items['endtime']=date("Y-m-d",$e);
                        //设置优惠券可用商品分类
                        $pstr=$items['ucateid'];
                        $count=strlen($pstr);
                        $goodList=array();
                        $goodInfoList=array();
                        $cateStr='';
                        for($i=0;$i<=$count;$i++){
                            if(strrpos($pstr,",")>=0){
                                $p=strrpos($pstr,",");
                                $id=substr($pstr,$p+1,strlen($pstr));
                                $goodList[$i]=$id;
                                $size=strrpos($pstr,$id);
                                $pstr=substr($pstr,0,$size-1);
                            }else{
                                break;
                            }
                        }
                        for($j=0;$j<count($goodList);$j++){
                            if($goodList[$j]!=null){
                                $good=pdo_fetch("select * from ".tablename("shopping_category")." where id={$goodList[$j]} ");
                                // print_R("select * from ".tablename("shopping_category")." where id={$goodList[$j]} ");
                                $name=$good['name'];
                                $cateStr.=$name.',';
                            }
                             
                        }
                        $items['ucateid']=$cateStr;
                         
                    }
                    $total=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')." where type=1 and deleted=0 $condition ");
                    $pager = pagination($total, $pindex, $psize);
                }elseif($operation=="showButtomCoupon"){
                    if (!empty($_GPC['keyword'])) {
                        $cprice=$_GPC['keyword'];
                        $condition.=" AND cprice={$cprice}   ";
                    }
                    if (!empty($_GPC['status'])) {
                        $status=intval($_GPC['status']);
                        $condition .= " AND `status` ={$status}";
                    }
                
                    if (!empty($_GPC['ifuser'])) {
                        $ifuser=$_GPC['ifuser'];
                        if($ifuser=="yes"){
                            //查询已经绑定的优惠券
                            $condition .= " and uid !=0 ";
                        }
                        if($ifuser=="no"){
                            //查询已经绑定的优惠券
                            $condition .= " and uid =0 ";
                        }
                    }
                
                    $pindex = max(1, intval($_GPC['page']));
                    $psize = 15;
                    $ButtomcouponList=pdo_fetchall("select * from ".tablename('shopping_coupon')." where type=2 and deleted=0  $condition ORDER BY id DESC LIMIT ". ($pindex - 1) * $psize . ',' . $psize);
                    foreach($ButtomcouponList as &$items){
                        $s=strtotime($items['starttime']);
                        $e=strtotime($items['endtime']);
                        $items['starttime']=date("Y-m-d",$s);
                        $items['endtime']=date("Y-m-d",$e);
                        //设置优惠券可用商品分类
                        $pstr=$items['ucateid'];
                        $count=strlen($pstr);
                        $goodList=array();
                        $goodInfoList=array();
                        $cateStr='';
                        for($i=0;$i<=$count;$i++){
                            if(strrpos($pstr,",")>=0){
                                $p=strrpos($pstr,",");
                                $id=substr($pstr,$p+1,strlen($pstr));
                                $goodList[$i]=$id;
                                $size=strrpos($pstr,$id);
                                $pstr=substr($pstr,0,$size-1);
                            }else{
                                break;
                            }
                        }
                        for($j=0;$j<count($goodList);$j++){
                            if($goodList[$j]!=null){
                                $good=pdo_fetch("select * from ".tablename("shopping_category")." where id={$goodList[$j]} ");
                                // print_R("select * from ".tablename("shopping_category")." where id={$goodList[$j]} ");
                                $name=$good['name'];
                                $cateStr.=$name.',';
                            }
                             
                        }
                        $items['ucateid']=$cateStr;
                         
                    }
                    $total=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')." where type=2 and deleted=0  $condition ");
                    $pager = pagination($total, $pindex, $psize);
                }elseif($operation=="delete"){
                    $id=$_GPC['id'];
                    $data['deleted']=1;
                    $result=pdo_update("shopping_coupon",$data,array('id'=>$id));
                    if($result){
                        message('优惠券删除成功！', $this->createWebUrl('coupon', array('op' => 'showTopCoupon')), 'success');
                    }
                }elseif($operation=="excel"){
                    /*   message("op==excel");
                     $psize = 15;
                     $page=$_GPC['excelPage']; */
                    $type=$_GPC['type'];
                    if($type=="线上"){
                        $condition=" type=1";
                    }else{
                        $condition=" type=2";
                    }
                
                    if (!empty($_GPC['cprice'])) {
                        $cprice=$_GPC['cprice'];
                        $condition.=" AND cprice={$cprice}";
                    }
                    if (!empty($_GPC['status'])) {
                        $status=intval($_GPC['status']);
                        $condition .= " AND `status` ={$status}";
                    }
                    $title='<tr><td>优惠券号</td><td>面额</td><td>限额</td><td>开始时间</td><td>结束时间</td><td>优惠券类型</td></tr>';
                    $excellist=pdo_fetchall("select couponsn,cprice,condimoney,starttime,endtime,type  from ".tablename("shopping_coupon")." where $condition ");
                    foreach($excellist as &$item){
                        $s=strtotime($item['starttime']);
                        $e=strtotime($item['endtime']);
                        $item['starttime']=date("Y-m-d",$s);
                        $item['endtime']=date("Y-m-d",$e);
                        $item['cprice']=$item['cprice']."元";
                        $item['condimoney']="购物满".$item['condimoney']."元可用";
                        if($item['type']=='1'){
                            $item['type']="线上";
                        }else{
                            $item['type']="线下";
                        }
                    }
                    $name='coupon';
                    $this->outputXlsHeader($excellist,$name,$title);
                }elseif($operation=="sendDetail"){
                    //获取关注送券  购物送券发放状态
                    //关注送券
                    $ruleList=pdo_fetchall("select * from ".tablename("shopping_sendcouponrule_item")." where  ruletype=1");
                    $account1=0;//关注送券总张数
                    $usednum1=0;//关注送券已送张数
                    $unusenum1=0;//关注送券未送张数
                    if($ruleList!=null){
                        foreach($ruleList as $rule){
                            $cprice=intval($rule['cprice']);
                            $condimoney=intval($rule['condimoney']);
                            $count=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney}  AND deleted=0 and type=1");
                            $used=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney} and uid!=0 AND deleted=0 and type=1");
                            $unuse=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney} and uid=0 AND deleted=0 and type=1");
                            $account1+=$count;
                            $usednum1+=$used;
                            $unusenum1+=$unuse;
                        }
                    }
                    //购物送券
                    $ruleList=pdo_fetchall("select * from ".tablename("shopping_sendcouponrule_item")." where  ruletype=2");
                    $account2=0;//关注送券总张数
                    $usednum2=0;//关注送券已送张数
                    $unusenum2=0;//关注送券未送张数
                    if($ruleList!=null){
                        foreach($ruleList as $rule){
                            $cprice=intval($rule['cprice']);
                            $condimoney=intval($rule['condimoney']);
                            $count=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney}  AND deleted=0 and type=1");
                            $used=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney} and uid!=0 AND deleted=0 and type=1");
                            $unuse=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney} and uid=0 AND deleted=0 and type=1");
                            $account2+=$count;
                            $usednum2+=$used;
                            $unusenum2+=$unuse;
                        }
                    }
                }elseif($operation=='couponAccount'){
                    $condimoney=$_GPC['condimoney'];
                    $cprice=$_GPC['cprice'];
                    if(empty($condimoney)){
                        $condimoney=0;
                    }
                    if(empty($cprice)){
                        $cprice=0;
                    }
                    $count=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney}  AND deleted=0 and type=1");
                    $used=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney} and uid!=0 AND deleted=0 and type=1");
                    $unuse=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney} and uid=0 AND deleted=0 and type=1");
                    $overdue=pdo_fetchcolumn("select count(id) from ".tablename('shopping_coupon')."  where cprice={$cprice} and condimoney={$condimoney} AND endtime <NOW()  AND deleted=0 and type=1");
                    //清理过期优惠券
                }elseif($operation=='clear'){
                    $sql="delete from ims_shopping_coupon where  endtime<now() and uid=0";
                    pdo_query($sql);//this->createWebUrl('coupon', array('op' => 'clear'))
                    message('清理成功', $this->createWebUrl('coupon', array('op' => 'showTopCoupon')), 'success');
                }
            }
            /**
             * 送券规则
             */
            if(ACTION_NAME == 'couponRule'){
                
            }
            $this->display(ACTION_NAME);
        }else{
            $this->display('coupon');
        }
    }
}