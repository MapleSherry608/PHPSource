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
     * @return  | 
     */
    public function receiveWeacher($object,$newsArray){
        //图文回复
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
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], "http://mp.weixin.qq.com/s?__biz=MzAxNDc2NjkwMg==&mid=402034274&idx=1&sn=0be282d856c135dfb2deb9bd25831231#rd");
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
     * 百度百科
     * @param   $object
     */
   public function receiveBaike($object){
        $url = 'http://wapbaike.baidu.com/searchresult/?word=%s';
        $url = sprintf($url, $word);
        $response=$this->request_get($url);
        if ($resp['code'] == 200 && $resp['content']) {
            if(preg_match_all('/<div class="item"><h3><a href="(?P<link>.+?)">(?P<title>.+?)<\/a><\/h3><p>(?P<description>.+?)...<\/p><\/div>/', $resp['content'], $matchs)) {
                $ds = array();
                foreach($matchs['title'] as $key => $v) {
                    $ds[] = array(
                        'title' => str_replace('_百度百科', '', strip_tags($v)),
                        'link' => 'http://wapbaike.baidu.com' . $matchs['link'][$key],
                        'description' => strip_tags($matchs['description'][$key])
                    );
                }
                $news = array();
                $news[] = array('title' => "{$word} 的百科解释如下", 'description' => $ds[0]['description'], 'picurl' => 'http://g.hiphotos.baidu.com/baike/c0%3Dbaike180%2C5%2C5%2C180%2C60/sign=f38225303901213fdb3e468e358e5db4/9358d109b3de9c82afcae8666c81800a18d8bc3eb0356b97.jpg', 'url' => $ds[0]['link']);
                $cnt = min(count($ds), 8);
                for($i = 0; $i < $cnt; $i++) {
                    $news[] = array(
                        'title' => $ds[$i]['title'],
                        'description' => $ds[$i]['description'],
                        'picurl' => '',
                        'url' => $ds[$i]['link']
                    );
                }
                return $this->transmitImage($object,$news);
            }
        }
   }
   /*
    * 发送get请求 
    * 
    */
   private function request_get($url = ''){
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
    * 回复图片消息
    */
   private function transmitImage($object, $imageArray){
       $itemTpl = "<Image>
                        <MediaId><![CDATA[%s]]></MediaId>
                    </Image>";
   
       $item_str = sprintf($itemTpl, $imageArray['MediaId']);
   
       $textTpl = "<xml>
       <ToUserName><![CDATA[%s]]></ToUserName>
       <FromUserName><![CDATA[%s]]></FromUserName>
       <CreateTime>%s</CreateTime>
       <MsgType><![CDATA[image]]></MsgType>
       $item_str
       </xml>";
       $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
       return $result;
   }
}
?>
