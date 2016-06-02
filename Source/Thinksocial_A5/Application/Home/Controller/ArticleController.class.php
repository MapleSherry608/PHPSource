<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 撒哈拉的寂寞 <1032453491@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
/**
 * 后台内容控制器
 * @author 撒哈拉的寂寞 <1032453491@qq.com>
 */
class ArticleController extends HomeController {
	public function __init__(){
		C('LIST_ROWS', 20);
	}
	/**
	 * 分类文档列表页
	 * @param $cate_id 分类id
	 * @author 撒哈拉的寂寞 <1032453491@qq.com>
	 */
	public function indSite() {
		$cid = intval($_REQUEST['cid']);
		$category = M('SiteCate')->where(array('id' => $cid))->find();
		if (empty($category)) {
			$this -> error('分类不存在或是已经被删除！');
		}
		if (!empty($category['linkurl'])) {
			header('Location: ' . $category['linkurl']);
			exit ;
		}
		$topcate = M('SiteCate') -> where(array('parentid' => 0)) -> select();
		$this -> assign('topcate', $topcate);

		$navs = M('SiteCate') -> where(array('parentid' => $cid)) -> order('displayorder DESC,id DESC') -> select();
		if (!empty($navs)) {
			foreach ($navs as &$row) {
				if (!empty($row['icontype']) && $row['icontype'] == 1) {
					$row['icon'] = iunserializer($row['icon']);
					$row['icon']['icon']['style'] = "color:" . $row['icon']['icon']['color'] . ";font-size:" . $row['icon']['icon']['font-size'] . "px;";
					$row['icon']['name'] = "color:{$row['icon']['name']['color']};";
				}
			}
			$this -> assign('navs', $navs);
		}
		if (empty($category['parentid'])) {

		}
		
		$articlelist = $this -> lists('SiteArticle', array('_string' => " (pcate=" . $cid . " or ccate=" . $cid . " )"), '', 'id,pcate,ccate,title,thumb,createtime,linkurl');
		$this -> assign('articlelist', $articlelist);
		$title = $category['name'];
		$this -> assign('title', $title);
		$this -> display();
	}

	/**
	 * 内容详情页面
	 * @author 撒哈拉的寂寞 <1032453491@qq.com>
	 */
	public function detail(){
		$id = intval($_REQUEST['id']);
		$detail =M('SiteArticle')->where(array('id'=>$id))->find();
		if (!empty($detail['linkurl'])) {
			if (strtolower(substr($detail['linkurl'], 0, 4)) != 'tel:' && !strexists($detail['linkurl'], 'http://') && !strexists($detail['linkurl'], 'https://')) {
				$detail['linkurl'] = $_REQUEST['siteroot'] . 'app/' . $detail['linkurl'];
			}
			header('Location: ' . $detail['linkurl']);
			exit ;
		}
		$detail = istripslashes($detail);
		$detail['content'] = preg_replace("/<img(.*?)(http[s]?\:\/\/mmbiz.qpic.cn[^\?]*?)(\?[^\"]*?)?\"/i", '<img $1$2"', $detail['content']);
		
		if (!empty($detail['incontent'])) {//封面图片显示在文章中
			$detail['content'] = '<p><img src="' . tomedia($detail['thumb']) . '" title="' . $detail['title'] . '" /></p>' . $detail['content'];
		}
		if (!empty($detail['thumb'])) {//处理文章图片
			$detail['thumb'] = tomedia($detail['thumb']);
		} else {
			$detail['thumb'] = '';
		}
		$title = $detail['title'];
		$membPub =M('MemberPublic')->field('public_name,subscribeurl')->limit("0,1")->find();
		M('SiteArticle')->where(array('id'=>$id))->setInc('click');
		$_share = array('desc' => $detail['description'], 'title' => $detail['title'], 'imgUrl' => $detail['thumb']);
		$topcate = M('SiteCate') -> where(array('parentid' => 0)) -> select();
		$signPackage=$this->getSignPackage();//获取分享信息
		$this -> assign('title',$title);
		$this -> assign('membPub',$membPub);
		$this -> assign('detail',$detail);
		$this -> assign('share',$_share);
		$this -> assign('topcate', $topcate);
		$this -> assign("signPackage",$signPackage);
		$this -> display();
	}

	/**
	 * 通用分页列表数据集获取方法
	 *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
	 *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
	 *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
	 * @param sting|Model  $model   模型名或模型实例
	 * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
	 * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
	 *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
	 *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
	 * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
	 * @author 撒哈拉的寂寞 <1032453491@qq.com>
	 * @return array|false
	 * 返回数据集
	 */
	protected function lists($model, $where = array(), $order = '', $field = true) {
		$options = array();
		$REQUEST = (array)I('request.');
		if (is_string($model)) {
			$model = M($model);
		}
		$OPT = new \ReflectionProperty($model, 'options');
		$OPT -> setAccessible(true);
		$pk = $model -> getPk();
		if ($order === null) {
			//order置空
		} else if (isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']), array('desc', 'asc'))) {
			$options['order'] = '`' . $REQUEST['_field'] . '` ' . $REQUEST['_order'];
		} elseif ($order === '' && empty($options['order']) && !empty($pk)) {
			$options['order'] = $pk . ' desc';
		} elseif ($order) {
			$options['order'] = $order;
		}
		unset($REQUEST['_order'], $REQUEST['_field']);
		if (empty($where)) {
			$where = array('status' => array('egt', 0));
		}
		if (!empty($where)) {
			$options['where'] = $where;
		}
		$options = array_merge((array)$OPT -> getValue($model), $options);
		$total = $model -> where($options['where']) -> count();

		if (isset($REQUEST['r'])) {
			$listRows = (int)$REQUEST['r'];
		} else {
			$listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 5;
		}
		$page = new \Think\Page($total, $listRows, $REQUEST);
		$page -> rollPage = 5;
		if ($total > $listRows) {
			$page -> setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		}
		$p = $page -> show();
		$this -> assign('_page', $p ? $p : '');
		$this -> assign('_total', $total);
		$options['limit'] = $page -> firstRow . ',' . $page -> listRows;
		$model -> setProperty('options', $options);
		return $model -> field($field) -> select();
	}
	/**
	 * 分享文章增加积分
	 */
    public function shareArticle(){
        $uid=MEMBID;
        $score=intval(C('SHARESCORE'));
        $res=setScoreOrDeposit($uid,$score,'score','分享文章给朋友获取积分','Article');
        if($res){
            $result=array(
                'code'=>200,
                'msg'=>'分享成功'
            );
        }else{
            $result=array(
                'code'=>201,
                'msg'=>'网络异常'
            );
        }
        $this->ajaxReturn($result);
    }
}
