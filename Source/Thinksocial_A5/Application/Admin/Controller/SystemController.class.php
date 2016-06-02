<?php 
namespace Admin\Controller;
class SystemController extends AdminController{
    /**
     * 菜单列表
     */
	public function index(){
        $pid  = I('get.pid',0);
        if($pid){
            $data = M('Menus')->where("id={$pid}")->field(true)->find();
            $this->assign('data',$data);
        }
        $title      =   trim(I('get.title'));
        $type       =   C('CONFIG_GROUP_LIST');
        $all_menu   =   M('Menus')->getField('id,title');
        $map['pid'] =   $pid;
        if($title){
            $map['title'] = array('like',"%{$title}%");
		}
        $list       =   M("Menus")->where($map)->field(true)->order('sort asc,id asc')->select();
        int_to_string($list,array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用'),'hide'=>array(1=>'是',0=>'否'),'is_dev'=>array(1=>'是',0=>'否')));
        if($list) {
            foreach($list as &$key){
                if($key['pid']){
                    $key['up_title'] = $all_menu[$key['pid']];
                }
            }
            $this->assign('list',$list);
        }
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
		$this->assign('do','listInfo');
		$this->assign('where',$map);
		$this->display('index');
	}
	/**
	 * 添加菜单
	 */
	public function addMenu(){
	    $menu=I("menu");
	    $this->assign('do','listDetail');
	    if(IS_POST){
	    	$menuInfo=M("Menus");
	        $menuInfo->add($menu);
	       $this->success('新增成功', Cookie('__forward__'));
	    }else{
	    	$this->assign('info',array('pid'=>I('pid')));
            $menus = M('Menus')->field(true)->select();
            $menus = D('Tree')->toFormatTree($menus);
            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            $this->assign('Menus', $menus);
            $this->display('edit');
	    }
	}
	/**
	 * 删除菜单
	 *  shai5100660@163.com
	 */
	public function delMenu(){
		$id    = array_unique((array)I('id',0));
        $id    = is_array($id) ? implode(',',$id) : $id;
        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
        if( M("menus")->where($where)->delete()!==false ) {
            $this->success($msg['success'],$msg['url'],$msg['ajax']);
        }else{
            $this->error($msg['error'],$msg['url'],$msg['ajax']);
        }
	}
	/**
     * 编辑配置
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function edit($id = 0){
        if(IS_POST){
        	$men=I("menu");
            $Menu = M('Menus');
            $Menu->save($men);
            $this->success('更新成功', Cookie('__forward__'));
        }else{
            $info = array();
            /* 获取数据 */
            $info = M('Menus')->field(true)->find($id);
            $menus = M('Menus')->field(true)->select();
            $menus = D('Tree')->toFormatTree($menus);

            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            $this->assign('Menus', $menus);
            if(false === $info){
                $this->error('获取后台菜单信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑后台菜单';
            $this->display();
        }
    }
	/**
	 * 支付参数配置管理后台
	 */
	public function payment(){
		if(IS_POST){
			$wechat=I('wechat');
			$wechat= self::parse($wechat);
			M('Config')->where(array('name'=>'WECHAT'))->save(array('value'=>$wechat));
			$alipay=I('alipay');
			$alipay= self::parse($alipay);
			M('Config')->where(array('name'=>'ALIPAY'))->save(array('value'=>$alipay));
			S('DB_CONFIG_DATA',null);
			$this->success('更新成功！',U('System/payment'));
		}else{
			$alipay=C('ALIPAY');//支付宝配置参数
			$credit=C('CREDIT');//余额支付配置参数
			$wechat=C('WECHAT');//微信支付配置参数
			$this->assign('alipay',$alipay);
			$this->assign('credit',$credit);
			$this->assign('wechat',$wechat);
			$this->display();
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
	 * 热搜词管理
	 */
	public function searchHot(){
		$list=$this->lists('Searchhot',$map,'rank DESC,scorehit ASC,createtime DESC');
		int_to_string($list);
		int_to_string($list,array('type'=>C('SEARCHHOTTYPE')));
		$this->assign('list',$list);
		$this->display();
	}
	public function addSearchHot(){
        if(IS_POST){
        	$men=I("menu");
            $Menu = M('Searchhot');
            if($Menu->save($men)){
                $this->success('更新成功', Cookie('__forward__'));
            } else {
                $this->error('更新失败');
            }
        }else{
            $info = array();
            /* 获取数据 */
            $info = M('Searchhot')->field(true)->find($id);
            $menus = M('Searchhot')->field(true)->select();
            $menus = D('Tree')->toFormatTree($menus);

            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            $this->assign('Searchhot', $menus);
            if(false === $info){
                $this->error('获取后台菜单信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑后台菜单';
            $this->display();
        }
    }
	/**
     * 订单审核
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if(in_array(C('Member_ADMINISTRATOR'),$id)){$this->error("不允许对超级管理员执行该操作!");}
        $id = is_array($id) ? implode(',',$id) : $id;
        if(empty($id)){$this->error('请选择要操作的数据!');}
        $map['id'] =   array('in',$id);
        switch (strtolower($method)){
            case 'forbidmenus':
                $this->forbid('Menus',$map);
                break;
            case 'resumemenus':
                $this->resume('Menus',$map);
                break;
			case 'forbidsearchhot':
                $this->forbid('Searchhot',$map);
                break;
            case 'resumesearchhot':
                $this->resume('Searchhot',$map);
                break;
            default:
                $this->error('参数非法');
        }
    }
}

?>