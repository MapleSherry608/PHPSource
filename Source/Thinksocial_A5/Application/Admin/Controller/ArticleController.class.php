<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 撒哈拉的寂寞 <1032453491@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

/**
 * 后台内容控制器
 * @author 撒哈拉的寂寞 <1032453491@qq.com>
 */
class ArticleController extends AdminController {
	public function __init__(){
		C('LIST_ROWS',20);
	}
    /**
     * 分类文档列表页
     * @param $cate_id 分类id
     * @author 撒哈拉的寂寞 <1032453491@qq.com>
     */
    public function index(){
    	//获取分类
    	$category=M('SiteCate')->field('id,parentid,name')->order('parentid ASC, displayorder ASC, id ASC')->select();
		$parent = array();
		$children = array();
		if (!empty($category)) {
			$children = '';
			foreach ($category as $cid => $cate) {
				if (!empty($cate['parentid'])) {
					$children[$cate['parentid']][] = $cate;
				} else {
					$parent[$cate['id']] = $cate;
				}
			}
		}
		$this->assign('category',$category);
		$this->assign('parent',$parent);
		$this->assign('children',$children);
		$selcate=I('category');
		if(!empty($selcate)){
			if(!empty($selcate['parentid']) && is_numeric($selcate['parentid'])){
				$map['pcate'] = intval($selcate['parentid']);
			}
			if(!empty($selcate['childid']) && is_numeric($selcate['childid'])){
				$map['ccate'] = intval($selcate['childid']);
			}
		}
		$keyword=I('keyword');
		if(!empty($keyword)){
            $map['title']    =   array('like', '%'.(string)$keyword.'%');
        }
		$list=$this->lists('SiteArticle',$map,'displayorder desc, createtime desc','id,pcate,ccate,title,iscommend,ishot,displayorder');
		$this->assign('list',$list);
        $this->display();
    }
    /**
     * 文档编辑页面初始化
     * @author 撒哈拉的寂寞 <1032453491@qq.com>
     */
    public function edit(){
    	$category=M('SiteCate')->field('id,parentid,name')->order('parentid ASC, displayorder ASC, id ASC')->select();
		$parent = array();
		$children = array();
		if (!empty($category)){
			$children = '';
			foreach ($category as $cid => $cate) {
				if (!empty($cate['parentid'])) {
					$children[$cate['parentid']][] = $cate;
				} else {
					$parent[$cate['id']] = $cate;
				}
			}
		}
		$id = intval(I('id'));
		$this->assign('id',$id);
		$pcate = I('pcate');
		$ccate = I('ccate');
		if (!empty($id)) {
			$item=M('SiteArticle')->where(array('id'=>$id))->find();
			$pcate = $item['pcate'];
			$ccate = $item['ccate'];
			if (empty($item)) {
				$this->error('抱歉，文章不存在或是已经删除！');
			}
			$keywords=M('Rule')->where(array('id'=>$item['rid']))->getField('name');
			$this->assign('keywords',$keywords);
			$item['credit'] = iunserializer($item['credit']) ? iunserializer($item['credit']) : array();
			if(!empty($item['credit']['limit'])){
				$credit_num =M('CheckBill')->where(array('credittype'=>'score','model'=>'Article'))->sum('num');
				if(is_null($credit_num)) $credit_num = 0;
				$credit_yu = (($item['credit']['limit'] - $credit_num) < 0) ? 0 : $item['credit']['limit'] - $credit_num;
			}
		} else {
			$item['credit'] = array();
		}
		if (IS_POST){
			if (empty($_REQUEST['title'])) {
				$this->error('标题不能为空，请输入标题');
			}
			$data = array(
				'iscommend' => intval($_REQUEST['option']['commend']),
				'ishot' => intval($_REQUEST['option']['hot']),
				'pcate' => intval($_REQUEST['category']['parentid']),
				'ccate' => intval($_REQUEST['category']['childid']),
				'template' => $_REQUEST['template'],
				'title' => $_REQUEST['title'],
				'description' => $_REQUEST['description'],
				'content' => htmlspecialchars_decode($_REQUEST['content']),
				'incontent' => intval($_REQUEST['incontent']),
				'source' => $_REQUEST['source'],
				'author' => $_REQUEST['author'],
				'displayorder' => intval($_REQUEST['displayorder']),
				'linkurl' => $_REQUEST['linkurl'],
				'createtime' => NOW_TIME,
				'click' => intval($_REQUEST['click'])
			);
			if (!empty($_REQUEST['thumb'])) {
				$data['thumb'] = $_REQUEST['thumb'];
			} elseif (!empty($_REQUEST['autolitpic'])) {
				$match = array();
				preg_match('/attachment\/(.*?)(\.gif|\.jpg|\.png|\.bmp)/', $_REQUEST['content'], $match);
				if (!empty($match[1])) {
					$data['thumb'] = $match[1].$match[2];
				}
			} else {
				$data['thumb'] = '';
			}
			if(!empty($_REQUEST['keyword'])) {
				$keycount=M('Rule')->where(array('name'=>array('like','%'.$_REQUEST['keyword'].'%')))->count();
				if($keycount>0){
					$this->error('该关键字已经被使用过了，请换一个嘛O(∩_∩)O~~！');
				}
				$rule['name'] = $_REQUEST['keyword'];
				$rule['module'] = 'news';
				$rule['status'] = 1;
				
				$reply['title'] = $_REQUEST['title'];
				$reply['description'] = $_REQUEST['description'];
				$reply['thumb'] = $_REQUEST['thumb'];
				if(!empty($_REQUEST['linkurl'])){
					$reply['url'] = $_REQUEST['linkurl'];
				}else{
					$reply['url'] = MURL('index/Article/detail', array('id' => $id),true, true);
				}
			}
			if(!empty($_REQUEST['credit']['status'])) {
				$credit['status'] = intval($_REQUEST['credit']['status']);
				$credit['limit'] = intval($_REQUEST['credit']['limit']) ? intval($_REQUEST['credit']['limit']) : $this->error('请设置积分上限');
				$credit['share'] = intval($_REQUEST['credit']['share']) ? intval($_REQUEST['credit']['share']) : $this->error('请设置分享时赠送积分多少');
				$credit['click'] = intval($_REQUEST['credit']['click']) ? intval($_REQUEST['credit']['click']) : $this->error('请设置阅读时赠送积分多少');
				$data['credit'] = iserializer($credit);
			}else{
				$data['credit'] = iserializer(array('status' => 0, 'limit' => 0, 'share' => 0, 'click' => 0));
			}
			if(empty($id)){
				if(!empty($_REQUEST['keyword'])){
					$rid=M('Rule')->add($rule);
					$reply['rid'] = $rid;
					M('NewsReply')->add($reply);
					$data['rid'] = $rid;
				}
				M('SiteArticle')->add($data);
			}else{
				unset($data['createtime']);
				M('Rule')->where(array('id' => $item['rid']))->delete();
				M('NewsReply')->where(array('rid' => $item['rid']))->delete();
				if(!empty($_REQUEST['keyword'])) {
					$rid=M('Rule')->add($rule);
					$reply['rid'] = $rid;
					M('NewsReply')->add($reply);
					$data['rid'] = $rid;
				} else {
					$data['rid'] = 0;
				}
				M('SiteArticle')->where(array('id' => $id))->save($data);
			}
			$this->success('文章更新成功！',U('Article/index'));
		}else{
			$this->assign('category',$category);
			$this->assign('parent',$parent);
			$this->assign('children',$children);
			$this->assign('pcate',$pcate);
			$this->assign('ccate',$ccate);
			$this->assign('item',$item);
			$this->display();
		}
    }
	/**
	 *文章分类
	 * @author 撒哈拉的寂寞 <1032453491@qq.com>
	 */
	public function siteCate(){
		if(IS_POST){
			$displayorderlist=I('displayorder');
			if (!empty($displayorderlist)) {
				foreach ($displayorderlist as $id => $displayorder) {
					$update = array('displayorder' => $displayorder);
					M('SiteCate')->where(array('id'=>$id))->save($update);
				}
				$this->redirect('Article/siteCate');
			}
		}
		$children = array();
		$category = M('SiteCate')->order('parentid, displayorder DESC, id')->select();
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])){
				$children[$row['parentid']][] = $row;
				unset($category[$index]);
			}
		}
		$this->assign('category',$category);
		$this->assign('children',$children);
        $this->display();
    }
	/**
	 * 添加/修改文章分类
	 * @author 撒哈拉的寂寞 <1032453491@qq.com>
	 */
	public function editCate(){
		if(IS_POST){
			$data=I();
			$data['icon']=serialize($data['icon']);
			if(!empty($data['id'])&&is_numeric($data['id'])){
				$result=M('SiteCate')->save($data);
				if($result>0){
					$this->success('修改成功！',U("Article/siteCate"));
				}
			}else{
				unset($data['id']);
				M('SiteCate')->add($data);
			}
		}else{
			
			$id=I('id');
			$parentid=intval(I('parentid'));
			if(!empty($id)){
				$sitecate=M("SiteCate")->where(array('id'=>intval($id)))->find();
				$icon=unserialize($sitecate['icon']);
				$this->assign('icon',$icon);
				$this->assign('sitecate',$sitecate);
			}
			if(!empty($parentid)){
				$parent=M("SiteCate")->where(array('id'=>intval($parentid)))->field("id,name")->find();
				$this->assign('parent',$parent);
				$this->assign('parentid',$parentid);
			}
			$this->display();
		}
	    
	}
	/**
	 * 删除文章分类
	 */
	public function delCate($id){
		empty($id) && $this->error('非法访问!');
		M('SiteArticle')->where(array('ccate'=>$id))->save(array('ccate'=>0));
		M('SiteArticle')->where(array('pcate'=>$id))->save(array('pcate'=>0));
		M('SiteCate')->where(array('id'=>$id,'parentid'=>$id,'_logic'=>'or'))->delete();
		$this->redirect('Article/siteCate');
	}
	/**
	 * 删除文章
	 * @author 撒哈拉的寂寞 <1032453491@qq.com>
	 */
	public function deledit($id){
		empty($id) && $this->error('非法访问!');
		$rid=M('SiteArticle')->where(array('id'=>$id))->getField('rid');
		if(!empty($rid)){
			M('Rule')->where(array('id'=>$rid))->delete();
			M('NewsReply')->where(array('rid'=>$rid))->delete();
		}
		M('SiteArticle')->where(array('id'=>$id))->delete();
		$this->redirect('Article/index');
	}
}
