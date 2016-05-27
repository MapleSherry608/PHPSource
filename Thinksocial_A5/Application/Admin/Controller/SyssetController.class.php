<?php
namespace Admin\Controller;
class SyssetController extends AdminController{
    public function index(){
        header("Content-Type:text/html;charset=utf-8");
        $isfounder = true;//默认开启(自动收货执行间隔时间)
        $uniacid            = 0;
        $shop_sysset        = M('zxin_shop_sysset');
        $member             = M('member');
        
        function upload_cert($fileinput)
        {
            $uniacid = 0;
            $path = IA_ROOT . "Uploads/data/cert";
            mkdirs($path, '0777');
            $f = $fileinput . '_' . $uniacid . '.pem';
            $outfilename = $path . "/" . $f;
            $filename = $_FILES[$fileinput]['name'];
            $tmp_name = $_FILES[$fileinput]['tmp_name'];
            if (!empty($filename) && !empty($tmp_name)) {
                $ext = strtolower(substr($filename, strrpos($filename, '.')));
                if ($ext != '.pem') {
                    echo '证书文件格式错误: ' . $fileinput . "!";
                    /* die('证书文件格式错误: ' . $fileinput . "!"); */
                }
                $result = move_uploaded_file($tmp_name, $outfilename);
                if ($result) {
                    return "../Uploads/data/cert/" . $f;
                }
            }
        }
        
        $op = I('op');
        if(empty($op)){
            $op = 'shop';
        }
        $action = array(
            'shop','follow','notice','trade','pay','template','member','category','contact'
        );
        $setdata = $shop_sysset->where(array('uniacid' => $uniacid))->find();
        $set = unserialize($setdata['sets']);
        if(in_array($op,$action)){
            if ($op == 'template') {
                $styles = array();
                $dir = IA_ROOT . "Application/Home/View/Shop/";
                if ($handle = opendir($dir)) {
                    while (($file = readdir($handle)) !== false) {
                        if ($file != ".." && $file != ".") {
                            if (is_dir($dir . "/" . $file)) {
                                $styles[] = $file;
                            }
                        }
                    }
                    closedir($handle);
                }
            } else {
                if ($op == 'notice') {
                    $salers = array();
                    if (isset($set['notice']['openid'])) {
                        if (!empty($set['notice']['openid'])) {
                            $openids = array();
                            $strsopenids = explode(",", $set['notice']['openid']);
                            foreach ($strsopenids as $openid) {
                                $openids[] = "'" . $openid . "'";
                            }
                            $salers = $member->field(' id,nickname,avatar,openid ')->where(array('openid'=>array('in',implode(",", $openids))))->select();
                        }
                    }
                    $newtype = explode(',', $set['notice']['newtype']);
                }
            }
            
            if (IS_POST) {
                if ($op == 'shop') {
                    $shop = I('shop');
                    $shop = is_array($shop) ? $shop : array();
                    $set['shop']['name'] = trim($shop['name']);
                    $set['shop']['img'] = $shop['img'];
                    $set['shop']['logo'] = $shop['logo'];
                    $set['shop']['signimg'] = $shop['signimg'];
                } elseif ($op == 'follow') {
                    $share = I('share');
                    $set['share'] = is_array($share) ? $share : array();
                    $set['share']['icon'] = $set['share']['icon'];
                } else {
                    if ($op == 'notice') {
                        $notice = I('notice');
                        $set['notice'] = is_array($notice) ? $notice : array();
                        $openids = I('openids');
                        if (is_array($openids)) {
                            $set['notice']['openid'] = implode(",", $openids);
                        }
                        $set['notice']['newtype'] = $notice['newtype'];
                        if (is_array($set['notice']['newtype'])) {
                            $set['notice']['newtype'] = implode(",", $set['notice']['newtype']);
                        }
                    } elseif ($op == 'trade') {
                        $trade = I('trade');
                        $set['trade'] = is_array($trade) ? $trade : array();
                        if (!$isfounder) {
                            unset($set['trade']['receivetime']);
                        } else {
                            file_put_contents(IA_ROOT . 'Uploads/data/receive_time', $set['trade']['receivetime']);
                        }
                    } elseif ($op == 'pay') {
                        $pay = I('pay');
                        $set['pay'] = is_array($pay) ? $pay : array();
                        $weixin_cert = upload_cert('weixin_cert_file');
                        if (!empty($weixin_cert)) {
                            $set['pay']['weixin_cert'] = $weixin_cert;
                        }
                        $weixin_key = upload_cert('weixin_key_file');
                        if (!empty($weixin_key)) {
                            $set['pay']['weixin_key'] = $weixin_key;
                        }
                        $weixin_root = upload_cert('weixin_root_file');
                        if (!empty($weixin_root)) {
                            $set['pay']['weixin_root'] = $weixin_root;
                        }
                        $wechat=C('WECHAT');//微信支付配置参数
                        $alipay=C('ALIPAY');//支付宝配置参数
                        $credit=C('CREDIT');//余额支付配置参数
                        
                        $wechat['switch'] = $set['pay']['weixin'];
                        $alipay['switch'] = $set['pay']['alipay'];
                        $credit['switch'] = $set['pay']['credit'];
                        
                        $wechat= self::parse($wechat);
                        M('Config')->where(array('name'=>'WECHAT'))->save(array('value'=>$wechat));
                        
                        $alipay= self::parse($alipay);
                        M('Config')->where(array('name'=>'ALIPAY'))->save(array('value'=>$alipay));
                        
                        $credit= self::parse($credit);
                        M('Config')->where(array('name'=>'CREDIT'))->save(array('value'=>$credit));
                        
                        //清文件缓存
                        $path = IA_ROOT.'Runtime/';
                        $this->del_cache($path);
                    } elseif ($op == 'template') {
                        $shop = I('shop');
                        $shop = is_array($shop) ? $shop : array();
                        $set['shop']['style'] = $shop['style'];
                        $datapath = IA_ROOT . "Uploads/data/template";
                        //清空之前缓存文件
                        $this->del_cache($datapath);
                        if (!is_dir($datapath)) {
                            @mkdirs($datapath, "777");
                        }
                        file_put_contents($datapath . "/shop_" . $uniacid, $set['shop']['style']);
                    } elseif ($op == 'member') {
                        $shop = I('shop');
                        $shop = is_array($shop) ? $shop : array();
                        $set['shop']['levelname'] = trim($shop['levelname']);
                        $set['shop']['levelurl'] = trim($shop['levelurl']);
                    } elseif ($op == 'category') {
                        $shop = I('shop');
                        $shop = is_array($shop) ? $shop : array();
                        $set['shop']['catlevel'] = trim($shop['catlevel']);
                        $set['shop']['catshow'] = intval($shop['catshow']);
                        $set['shop']['catadvimg'] = $shop['catadvimg'];
                        $set['shop']['catadvurl'] = trim($shop['catadvurl']);
                    } elseif ($op == 'contact') {
                        $shop = I('shop');
                        $shop = is_array($shop) ? $shop : array();
                        $set['shop']['qq'] = trim($shop['qq']);
                        $set['shop']['address'] = trim($shop['address']);
                        $set['shop']['phone'] = trim($shop['phone']);
                        $set['shop']['description'] = trim($shop['description']);
                    }
                }
                $data = array('uniacid' => $uniacid, 'sets' => iserializer($set));
                if (empty($setdata)) {
                    $shop_sysset->add($data);
                } else {
                    $shop_sysset->where(array('uniacid' => $uniacid))->save($data);
                }
                //清空之前缓存文件
                $path = IA_ROOT . "Uploads/data/sysset";
                $this->del_cache($path);
                $setdata = $shop_sysset->where(array('uniacid'=>$uniacid))->find();
                $cachefile = $path . "/sysset_" . $uniacid;
                if (!is_dir($path)) {
                    @mkdirs($path);
                }
                file_put_contents($cachefile, iserializer($setdata));
                $this->success('设置保存成功!');
            }
            $arr = array(
                'op'=>$op,
                'set'=>$set,
                'newtype'=>$newtype,
                'isfounder'=>$isfounder,
                'styles'=>$styles,
            );
            $this->assign('arr',$arr);
            $this->display($op);
        }
    }
    /**
     * 根据配置类型解析配置
     * @param  integer $type  配置类型
     * @param  string  $value 配置值
     */
    private static function parse($data){
        $value  = array();
        foreach ($data as $key=>$val) {
            $value[]=$key.':'.$val;
        }
        $string='';
        foreach ($value as $val) {
            $string=$string.$val."\r";
        }
        return $string;
    }
    
    /**
     * 清空缓存
     */
    public function del_cache($path) {
        header("Content-type: text/html; charset=utf-8");
        $dirs = array($path);
        @mkdir('Runtime',0777,true);
        //清理缓存
        foreach($dirs as $value) {
            $this->rmdirr($value);
        }
    }
    /**
     * 清空方法
     * @param unknown $dirname
     * @return boolean
     */
    public function rmdirr($dirname) {
        if (!file_exists($dirname)) {
            return false;
        }
        if (is_file($dirname) || is_link($dirname)) {
            return unlink($dirname);
        }
        $dir = dir($dirname);
        if($dir){
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                //递归
                $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
            }
        }
        $dir->close();
        return rmdir($dirname);
    }
    
    public function _empty(){
        $this->redirect('index');
    }
}