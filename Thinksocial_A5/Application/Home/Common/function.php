<?php
include_once 'class/boot.inc.php';
/**
 * 获取粉丝表uid
 * @param unknown $openid
 * @return unknown|multitype:|boolean
 */
function mc_openid2uid($openid) {
    $uniacid = UNIACID;
    $fans = M('member_fans');
    if (is_numeric($openid)) {
        return $openid;
    }
    if (is_string($openid)) {
        $pars = array();
        $pars['uniacid'] = $uniacid;
        $pars['openid'] = $openid;
        $uid = $fans->where($pars)->getField('membid');
        return $uid;
    }
    if (is_array($openid)) {
        $uids = array();
        foreach ($openid as $k => $v) {
            if (is_numeric($v)) {
                $uids[] = $v;
            } elseif (is_string($v)) {
                $fans[] = $v;
            }
        }
        if (!empty($fans)) {
            $pars = array(
                'uniacid' => $uniacid,
                'openid'  => array('in',implode("','", $fans))  
            );
            $fans = $fans->field('membid,openid')->where($pars)->select();
            $fans = array_keys($fans);
            $uids = array_merge((array)$uids, $fans);
        }
        return $uids;
    }
    return false;
}

/**
 * 返回错误信息
 * @param unknown $errno
 * @param string $message
 * @return multitype:unknown string
 */
function refule_time_format($time = NULL){
    if(empty($time)){
        return '未设置';
    }
    $date=date('y年m月d日',intval($time));
    $week=return_week($time);
    return $date."\t".$week;
}
function return_week($time=null){
    if(empty($time)){
        return '未设置';
    }
    $week=intval(date('N',intval($time)));
    switch ($week){
        case 0:
            $week='日';
            break;
        case 1:
            $week='一';
            break;
        case 2:
            $week='二';
            break;
        case 3:
            $week='三';
            break;
        case 4:
            $week='四';
            break;
        case 5:
            $week='五';
            break;
        case 6:
            $week='六';
            break;
         default:
             $week='日';
             break;
    }
    return '周'.$week;
}
/**
 * 截取显示字符串
 */
function sub_str($string, $length){
   if(strlen($string)>=8){
       $string=substr($string,0,$length)."...";
   }
   return $string;
}

/**
 * 获取缩略图路径
 * @param unknown $imgList
 */
function getThumbUrl($imgUrl=''){
    $imgUrl=str_replace("Picture","Thumb",$imgUrl);
    return $imgUrl;
}
?>