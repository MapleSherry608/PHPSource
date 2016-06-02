<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class OrderAction extends CommonAction {
	public function index(){
        $db=M('order');
        $p = I('get.p') ? I('get.p') : '1';
        import('ORG.Util.Page');
        //搜索
        
        $key = I('get.key');
        $type = I('get.type');
        if($key){
            if($type=='0'){
                $where['out_trade_no']=array('like','%'.$key.'%');
                $where['trade_no']=array('like','%'.$key.'%');
                $where['pay_shop']=array('like','%'.$key.'%');
                $where['user_name']=array('like','%'.$key.'%');
                $where['user_tel']=array('like','%'.$key.'%');
                $where['user_message']=array('like','%'.$key.'%'); 
                $where['openid']=array('like','%'.$key.'%'); 
                $where['_logic']='or';
            }else{
                $where[$type]=array('like','%'.$key.'%');
            }
        }
        $time=I('get.time');
        if($time){
            if($time==1){//取当天
                $jt=strtotime(date('Y-m-d',time()).' 00:00:00');
                $where['s_time']=array('GT',$jt);                
            }elseif($time==2){//取昨天
                $jt=strtotime(date('Y-m-d',time()).' 00:00:00');
                $zt=strtotime(date('Y-m-d',time()-86400).' 00:00:00');
                $where['s_time']=array('GT',$zt);
                $where['s_time']=array('LT',$jt);
                $where['_logic']='and';
            }elseif($time==7){//取前7天
                $t7=strtotime(date('Y-m-d',(time()-86400*7)).' 00:00:00');
                $where['s_time']=array('GT',$t7);
            }elseif($time==30){//取本月
                $t30=strtotime(date('Y-m').'-01'.' 00:00:00');
                $where['s_time']=array('GT',$t30);
            }elseif($time==60){//取上月
                $t61=strtotime(date('Y-m').'-01'.' 00:00:00');
                $t60=strtotime(date('Y-m',(time()-86400*30)).'-01'.' 00:00:00');
                $where['s_time']=array('GT',$t60);
                $where['s_time']=array('LT',$t61);
                $where['_logic']='and';
            }
        }
        
		$count=$db->where($where)->count();
		$Page=new Page($count,10);
		$show=$Page->show();
		$list=$db->where($where)->order('s_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);
        $this->assign("page",$show);
        
        $this->assign("z_trade_no",$db->where($where)->count());
        
        $this->assign("z_price",$db->where($where)->sum('pay_price'));
        $where['ok'] =1;
        $this->assign("f_price",$db->where($where)->sum('pay_price'));
        $where['ok'] =0;
        $this->assign("w_price",$db->where($where)->sum('pay_price'));
        
        $this->assign("count",$count);
        $this->assign("p",$p);
        $this->display();
	}
    public function delnopay(){
        $db=M('order');
        $where['f_time']=0;
        $where['ok']=0;
        $del=$db->where($where)->delete();
        if($del){
            $this->success("成功清理{$del}条订单");
        }else{
            $this->error("清理失败");
        }
	}
    public function xls(){
        $db=M('order');
        $key = I('get.key');
        $type = I('get.type');
        if($key){
            if($type=='0'){
                $where['out_trade_no']=array('like','%'.$key.'%');
                $where['trade_no']=array('like','%'.$key.'%');
                $where['pay_shop']=array('like','%'.$key.'%');
                $where['user_name']=array('like','%'.$key.'%');
                $where['user_tel']=array('like','%'.$key.'%');
                $where['user_message']=array('like','%'.$key.'%'); 
                $where['openid']=array('like','%'.$key.'%'); 
                $where['_logic']='or';
            }else{
                $where[$type]=array('like','%'.$key.'%');
            }
        }
        $time=I('get.time');
        if($time){
            if($time==1){//取当天
                $jt=strtotime(date('Y-m-d',time()).' 00:00:00');
                $where['s_time']=array('GT',$jt);                
            }elseif($time==2){//取昨天
                $jt=strtotime(date('Y-m-d',time()).' 00:00:00');
                $zt=strtotime(date('Y-m-d',time()-86400).' 00:00:00');
                $where['s_time']=array('GT',$zt);
                $where['s_time']=array('LT',$jt);
                $where['_logic']='and';
            }elseif($time==7){//取前7天
                $t7=strtotime(date('Y-m-d',(time()-86400*7)).' 00:00:00');
                $where['s_time']=array('GT',$t7);
            }elseif($time==30){//取本月
                $t30=strtotime(date('Y-m').'-01'.' 00:00:00');
                $where['s_time']=array('GT',$t30);
            }elseif($time==60){//取上月
                $t61=strtotime(date('Y-m').'-01'.' 00:00:00');
                $t60=strtotime(date('Y-m',(time()-86400*30)).'-01'.' 00:00:00');
                $where['s_time']=array('GT',$t60);
                $where['s_time']=array('LT',$t61);
                $where['_logic']='and';
            }
        }
        $list=$db->where($where)->order('s_time desc')->select();
        $this->assign('list',$list);
        $this->display();
	}
}