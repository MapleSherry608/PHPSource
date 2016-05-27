<?php
//---------------------------------
//开发: 小曾
//扣扣: 839024615
//本程序只作为测试学习使用
//正式版请登陆pay.php127.com
//---------------------------------
class PayAction extends Action {
    //生成订单并支付
    public function index(){
        header("Content-Type:text/html;charset=utf-8");
        if(IS_POST){
            $post=I('post.');
            if($post['pay_fee']=='' and $post['pay_fee_qt']==''){
                $this->error('请输入付款金额');
            }
            if(!$post['pay_type']){
                $this->error('请选择一种支付方式');
            }
            if($post['pay_shop']=="请选择"){
                $this->error('付款对象未选择');
            }
            //取付款金额
            $pay_price=$post['pay_fee'];
            if($post['pay_fee_qt']){
                $pay_price=$post['pay_fee_qt'];
            }
            $pay_price = $pay_price*100;
            //生成订单号
            $out_trade_no="M".Date('YmdHis').rand(1000,9999);
            
            $data['pay_shop'] = $post['pay_shop'];
            $data['pay_type'] = $post['pay_type'];
            $data['pay_price'] = $pay_price;
            $data['user_name'] = $post['user_name'];
            $data['user_tel'] = $post['user_tel'];
            $data['user_message'] = $post['user_message'];
            $data['s_time']=time();
            $data['out_trade_no']=$out_trade_no;
            if($id=M('order')->add($data)){
                //$this->success("正在提交",);
                Header("Location: ".U($post['pay_type'].'/start','id='.$id));
            }else{
                $this->error('订单生成失败');
            }
            
        }else{
            $this->error('非法操作');
        }
    }
}