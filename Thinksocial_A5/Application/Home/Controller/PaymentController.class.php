<?php
namespace Home\Controller;
/**
 * 支付控制器
 * @author Administrator
 * angualrJs
 */
class PaymentsController extends HomeController{
    private $tb_core_pay_log;//支付记录表
    private $tb_member;//会员表
    public function __init__(){
        $this->tb_core_pay_log=M('CorePaylog');
        $this->tb_member=('member');
    }
    /**
     * 支付回调（支付宝）
     */
    public function alipay_notify(){
        if(IS_POST){
            $params=I();
            if(is_array($params)){
                $tid=strval($params['out_trade_no']);
                $log=$this->tb_core_pay_log->where(array('tid'=>$tid))->find();
                if(!empty($log)&&$log['status']==0){
                    $data['status']=1;
                    $data['transid']=$params['trade_no'];
                    $data['tag']=serialize($params);
                    $this->tb_core_pay_log->where('plid='.$log['plid'])->save($data);
                    $ret = array();
                    $ret['plid']=$plid;
                    $ret['uniacid'] = $log['uniacid'];
                    $ret['result'] = 'success';
                    $ret['type'] = $log['type'];
                    $ret['from'] = 'notify';
                    $ret['tid'] = $log['tid'];
                    $ret['user'] = $log['openid'];
                    $ret['fee'] = $log['fee'];
                    $ret['is_usecard'] = $log['is_usecard'];
                    $ret['card_type'] = $log['card_type'];
                    $ret['card_fee'] = $log['card_fee'];
                    $ret['card_id'] = $log['card_id'];
                    $action=A(strval($log['module']));
                    $action->payResult($ret);
                    exit();
                }
            }
        }
    }   
    
    /**
     * 支付完成（支付宝）
     */
    public function alipay_return(){
        $arr = I();
        if(!empty($arr)){
            $out_trade_no = strval($arr['out_trade_no']);
            $type = $arr['body'];
            $log=$this->tb_core_pay_log->where(array('tid'=>$out_trade_no))->find();
            if (!empty($log)){
                $ret = array();
                $ret['result'] = 'success';
                $ret['type'] = $log['type'];
                $ret['from'] = 'return';
                $ret['tid'] = $log['tid'];
                $ret['user'] = $log['openid'];
                $ret['fee'] = $log['fee'];
                $ret['is_usecard'] = $log['is_usecard'];
                $ret['card_type'] = $log['card_type'];
                $ret['card_fee'] = $log['card_fee'];
                $ret['card_id'] = $log['card_id'];
                $action=A(strval($log['module']));
                $action->payResult($ret);
                exit();
            }
        }
    }
    /**
     * 微信支付回调函数
     */
    public function wechat_notify(){
        $input = file_get_contents('php://input');
        if (!empty($input) && empty($_GET['out_trade_no'])) {
            $obj = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
            $data = json_decode(json_encode($obj), true);
            if (empty($data)) {
                exit('fail');
            }
            if ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS') {
                exit('fail');
            }
            $params = $data;
        } else {
            $params = I();
        }
        if(is_array($params)){
            ksort($params);
            $tid=strval($params['out_trade_no']);
            $log=$this->tb_core_pay_log->where(array('tid'=>$tid))->find();
            if(!empty($log)&&$log['status']==0){
                $data['status']=1;
                $data['transid']=$params['transaction_id'];
                $data['tag']=serialize($params);
                $this->tb_core_pay_log->where('plid='.$log['plid'])->save($data);
                $ret = array();
                $ret['plid']=$plid;
                $ret['uniacid'] = $log['uniacid'];
                $ret['result'] = 'success';
                $ret['type'] = $log['type'];
                $ret['from'] = 'notify';
                $ret['tid'] = $log['tid'];
                $ret['user'] = $log['openid'];
                $ret['fee'] = $log['fee'];
                $ret['is_usecard'] = $log['is_usecard'];
                $ret['card_type'] = $log['card_type'];
                $ret['card_fee'] = $log['card_fee'];
                $ret['card_id'] = $log['card_id'];
                $action=A(strval($log['module']));
                $action->payResult($ret);
                exit();
            }
        }
    }
    
    /**
     * 微信支付返回函数
     */
    public function wechat_return(){
        $pars=I('ps');
        $params = @json_decode(base64_decode($pars), true);
        $out_trade_no = strval($params['ordersn']);
        $log=$this->tb_core_pay_log->where(array('tid'=>$out_trade_no))->find();
        if (!empty($log)){
            $ret = array();
            $ret['uniacid'] = $log['uniacid'];
            $ret['result'] = 'success';
            $ret['type'] = $log['type'];
            $ret['from'] = 'return';
            $ret['tid'] = $log['tid'];
            $ret['user'] = $log['openid'];
            $ret['fee'] = $log['fee'];
            $ret['is_usecard'] = $log['is_usecard'];
            $ret['card_type'] = $log['card_type'];
            $ret['card_fee'] = $log['card_fee'];
            $ret['card_id'] = $log['card_id'];
            $action=A(strval($log['module']));
            $action->payResult($ret);
            exit();
        }
    }
}