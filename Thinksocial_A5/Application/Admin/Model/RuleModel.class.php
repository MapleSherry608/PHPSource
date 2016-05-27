<?php
namespace Admin\Model;
use Think\Model;
/**
 */
class RuleModel  extends Model{
    /**
     * 天气预报
     * @param   $object
     * @param     $newsArray
     * @return   
     */
    public function receiveWeacher($object,$newsArray){
         //图文回复
        if(!is_array($newsArray)){
            $result= $this->transmitText($object,"城市不存在！");
            return $result;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
        }else{
            $result=$this->transmitImage($object,$newsArray);
            return $result;
        }
    }
    /**
     * 百度百科
     * @param   $object
     */
   public function receiveBaike($word,$object){
       $url = 'http://wapbaike.baidu.com/searchresult/?word=%s';
       $url = sprintf($url,$word);
       $response=$this->request_get($url);  
       if(preg_match_all('/<div class="item"><h3><a href="(?P<link>.+?)">(?P<title>.+?)<\/a><\/h3><p>(?P<description>.+?)...<\/p><\/div>/', $response, $matchs)) {
              $news=$this->getBaiKeReply($word,$matchs);
              $result= $this->transmitImage($object,$news); 
              return $result;
           } else{
               $result= $this->transmitText($object,"没有找到结果, 要不换个词试试?");
               return $result;
           } 
   }
   /**
    * 即时翻译
    * @param   $word
    * @param   $object
    */
   public function receiveTranslate($word,$object){
       $word=substr($word,1,strlen($word)-1);
       $url = 'http://dict.youdao.com/search?q=%s';
       $url = sprintf($url, $word);
       $response=$this->request_get($url); 
        if(preg_match('/(?P<block><h2 class="wordbook-js">.+<\/h2>)/s',$response, $block) && preg_match('/<div class="trans-container">.*?(?P<trans><ul>.+?<\/ul>).*?<\/div>/s',$response, $trans)){
            $block = $block['block'];
            if(preg_match('/<span class="keyword">(?P<keyword>.+?)<\/span>/', $block, $keyword)) {
                $reply=$this->getTranslateReply($block,$keyword,$trans);
                $result=$this->transmitText($object,$reply);
                return $result;
            }else{
                $result= $this->transmitText($object,"没有找到结果, 要不换个词试试?");
                return $result;
            }
       }else{
           $result= $this->transmitText($object,"没有找到结果, 要不换个词试试?");
           return $result;
       }
   }
   /**
    * 日历 
    * @param   $word
    * @param   $object
    */
   public function receiveCalendar($word,$object){
       $url = 'http://www.zdic.net/nongli/' . date('Y-n-j') . '.htm';
       $week = array();
       $week[0] = '日';
       $week[1] = '一';
       $week[2] = '二';
       $week[3] = '三';
       $week[4] = '四';
       $week[5] = '五';
       $week[6] = '六';
       
       $reply = '今天是 ' . date('Y年n月j日') . ' 星期' . $week[date('w')]."\n";
       $response =$this->request_get($url);
       $reply .= "==================\n";
      if(preg_match('/<td colspan="2" class="l3">(?P<block>.+?)<\/td>/s', $response, $block)) {
           $date = explode('<br>', $block['block']);
           array_pop($date);
           if(count($date) < 4) {
               $shift = array_shift($date);
               $year = substr($shift, -9);
               array_unshift($date, $year);
               array_unshift($date, str_replace($year, '', $shift));
           }
           $reply .= '农历: ' . implode(' ', $date);
       }
       if(preg_match('/<td colspan="2" class="ly2">(?P<block>.+?)<\/td>/s', $response, $block)) {
           if(preg_match_all('/title=\'(?P<line>.+?)\'/', $block['block'], $lines)) {
               $reply .= "\n==================\n";
               $reply .= "宜: \n";
               foreach($lines['line'] as $l) {
                   $reply .= "{$l}\n";
               }
           }
       }
       if(preg_match('/<td colspan="4" class="lj2">(?P<block>.+?)<\/td>/s', $response, $block)) {
           if(preg_match_all('/title=\'(?P<line>.+?)\'/', $block['block'], $lines)) {
               $reply .= "\n==================\n";
               $reply .= "忌: \n";
               foreach($lines['line'] as $l) {
                   $reply .= "{$l}\n";
               }
           }
       }  
       $result= $this->transmitText($object,$reply);
       return $result;
   }
   /**
    * 新闻
    * @param   $word
    * @param   $object
    */
   public function receiveNews($object){
       $url = 'http://news.163.com/special/00011K6L/rss_newstop.xml';
       $response =$this->request_get($url);
       $obj = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
       $news = array();
       $news[] = array('Title' => '网易头条新闻', 'Description' => '网易门户新闻中心', 'Url' => 'http://news.163.com', 'PicUrl' => 'http://cimg.163.com/news/0408/20/netease-logo.gif');
       $cnt = min(count($obj->channel->item), 8);
        for($i = 0; $i < $cnt; $i++) {
           $row =$obj->channel->item[$i];
           $news[] = array(
               'Title' =>"11",
               'Description'=>'sdfasdfasd',
               'PicUrl' => 'http://kj.baguatan.cn/kuangjia/Public/Admin/Images/baguatan.jpg',
               'Url' =>$row["link"] 
           );
       }  
       
       if(count($news)>0){
           $result=$this->transmitImage($object,$news);
           return $result;
       }else{
           $result=$this->transmitText($object,"没有找到结果, 要不过一会再试试?");
           return $result;
       }
   }
   /**
    * 查快递
    * @param   $word
    * @param   $object
    */   
   public function receiveExpress($word,$object){
        $matchs = array();
       $ret = preg_match('/^(?P<express>申通|圆通|中通|汇通|韵达|顺丰|ems) *(?P<sn>[a-z\d]{1,})$/i', $word, $matchs);
       if(!$ret) {
           return $this->transmitText($object,'请输入合适的格式, 快递公司+空格+单号(当前仅支持申通,圆通,中通,汇通,韵达,顺丰,EMS), 例如: 申通 2309381801');
       }   
         $express = $matchs['express'];
       $sn = $matchs['sn'];
       $mappings = array(
           '申通' => 'shentong',
           '圆通' => 'yuantong',
           '中通' => 'zhongtong',
           '汇通' => 'huitongkuaidi',
           '韵达' => 'yunda',
           '顺丰' => 'shunfeng',
           'ems' => 'ems',
       );
       $code = $mappings[$express];
     
       $url = "http://www.kuaidi100.com/query?type={$code}&postid={$sn}";
       $dat = $this->request_get($url);
       $msg ='';  
        if(!empty($dat)){
           $traces = json_decode($dat, true);
           if(is_array($traces)) {
               if($traces['message']) {
                   $msg = $traces['message'];
               }
               $traces = $traces['data'];
               if(is_array($traces)) {
                   $traces = array_reverse($traces);
                   $reply = '';
                   foreach($traces as $trace) {
                       $reply .= "{$trace['time']} - {$trace['context']}\n";
                   }
                   if(!empty($reply)) {
                       $replys = array();
                       $replys[] = array(
                           'title' => '已经为你查到相关快递记录',
                           'picurl' => '',
                           'description' => $reply,
                       );
                       return $this->transmitImage($replys);
                       $reply = "已经为你查到相关快递记录: \n" . $reply;
                       return $this->transmitText($object,$reply);
                   }
               }
           }
       }  
        return $this->transmitText($object,'没有查找到相关的数据,请检查您的输入格式, 正确格式为: 快递公司+空格+单号, 例如: 申通 2309381801');
   }
   /**
    * 获取百科的数据
    * @param   $matchs 数据
    * @return  
    */
   public function getBaiKeReply($word,$matchs){
       $ds = array();
       foreach($matchs['title'] as $key => $v) {
           $ds[] = array(
               'title' => str_replace('_百度百科', '', strip_tags($v)),
               'link' => 'http://wapbaike.baidu.com' . $matchs['link'][$key],
               'description' => strip_tags($matchs['description'][$key])
           );
       }
       $news = array();
       $news[] = array('Title' => "{$word} 的百科解释如下", 'Description' => $ds[0]['description'], 'PicUrl' =>"http://".$_SERVER['SERVER_NAME'].'/kuangjia/Public/Admin/Images/baike.jpg', 'url' => $ds[0]['link']);
       $cnt = min(count($ds), 8);
       for($i = 0; $i < $cnt; $i++) {
           $news[] = array(
               'Title' => $ds[$i]['title'],
               'Description' => $ds[$i]['description'],
               'PicUrl' =>'',
               'url' => $ds[$i]['link']
           );
       }
       return $news;
   }
   /**
    * 获取翻译数据
    * @param   $block
    * @param   $keyword
    * @param   $trans
    * @return string
    */
   public function getTranslateReply($block,$keyword,$trans){
       $keyword = $keyword['keyword'];
       $rs = array();
       $ds = array();
       if(preg_match_all('/<span class="(pronounce|phonetic)">(?P<tic>.+?)<\/span>/s', $block, $tics)) {
           foreach($tics['tic'] as $line) {
               $line = trim(strip_tags($line));
               if($line) {
                   $rs[] = preg_replace('/\s+/', ' ', $line);
               }
           }
       }
       $trans = $trans['trans'];
       if(preg_match_all('/<p class="wordGroup">(?P<line1>.+?)<\/p>|<li>(?P<line2>.+?)<\/li>/s', $trans, $lines)) {
           foreach($lines['line1'] as $line) {
               $line = trim(strip_tags($line));
               if($line) {
                   $ds[] = $line;
               }
           }
           foreach($lines['line2'] as $line) {
               $line = trim(strip_tags($line));
               if($line) {
                   $ds[] = $line;
               }
           }
       }
       $reply = "{$keyword}\n==================\n";
       if($rs) {
           $reply .= "发音:\n";
           foreach($rs as $row) {
               $reply .= "{$row}\n";
           }
           $reply .= "==================\n";
       }
       if($rs) {
           $ds = preg_replace("/\s+/", "", $ds);
           foreach($ds as $row) {
               $reply .= "{$row}\n";
           }
       }
       return $reply;
   }
    
   /**
    * 发送图文消息
    * @param   $object
    * @param   $newsArray
    * @return  
    */
   public function transmitImage($object,$newsArray){
       $subscribeurl=M("member_public")->field("subscribeurl")->select();
       $subscribeurl=$subscribeurl[0]['subscribeurl'];
     if(!is_array($newsArray)){
            return "";                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
        }
        $itemTpl = "    <item>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <PicUrl><![CDATA[%s]]></PicUrl>
                            <Url><![CDATA[%s]]></Url>
                      </item>
                    ";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'],$subscribeurl);
        }
        $newsTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <Content><![CDATA[]]></Content>
        <ArticleCount>%s</ArticleCount>
        <Articles>
        $item_str</Articles>
        </xml>";
        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;  
   }
  /**
   * 发送get请求 
   * @param   $url 请求url
   * @return  
   */
   public function request_get($url = ''){
       if (empty($url)) {
           return false;
       }
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       $data = curl_exec($ch);
       curl_close($ch);
       return $data;
   }
  /**
   * 回复文本消息
   * @param   $object
   * @param   $content 
   * @return  
   */
   private function transmitText($object, $content){
       $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
       $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
       return $result;
   }
   
}
?>
