<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	
    public function index(){
        if((empty($alipay)||empty($alipay['switch']))&&(empty($wechat)||empty($wechat['switch']))&&(empty($credit)||empty($credit['switch']))) {
            $this->error('没有有效的支付方式, 请联系网站管理员.');
        }
        $imageList=array(
            './img/1.jpg',
            './img/2.jpg',
            './img/3.jpg',
            './img/4.jpg',
            './img/5.jpg',
            './img/6.jpg',
            './img/7.jpg',
        );
        $path=$path="./Uploads/Refuel_Gif/Img/oTI5Mt1fTYBfeUdX15oRFNxOqKk0/".date('Ymd').'/';
        //创建文件夹
        mkdirs($path);
        $gifUrl=$this->get_img($imageList, 200, $path);
        /* $delPath=strval($imageList[0]);
        $file=substr($delPath,0,strrpos($delPath,'/'));
        $this->deldir($delPath);//删除文件 */
        echo $gifUrl;
        echo '<img src="'.$gifUrl.'"/>';
    }
    /**
     * get_img  图片合并,生成gif动态
     * $filelist  要合并的图片数组
     * $num		  生成的帧数
     * $qian      新文件名前缀
     * $path	  保持路径
     */
    private function get_img($filelist, $num, $path)
    {
        //初始化类
        $animation = new \Imagick();
        //设置生成的格式
        $animation->setFormat('gif');
        foreach ( $filelist as $file ){
           /*  $index=strrpos($file,'.');
            $file=substr($file,0,$index); */
            $image = new \Imagick();
            $image->readImage( $file );  	 //合并图片
            $animation->addImage( $image );  //加入到刚才建立的对象
            $animation->setImageDelay($num); //设定图片的帧数
            unset( $image );  				 //消除内存里的图像资源
        }
        //新图片文件名组合
        $images = 'oTI5Mt1fTYBfeUdX15oRFNxOqKk0' . time(). '.gif';
        //生成图片
        $animation->writeImages($images,true);
        //保存都指定目录
        copy($images, $path . $images);
        //删除最先保存的图片
        unlink($images);
        return $path.$images;
    }
   
}