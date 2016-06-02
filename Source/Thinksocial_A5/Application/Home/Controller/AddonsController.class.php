<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 扩展控制器
 * 用于调度各个扩展的URL访问需求
 */
class AddonsController extends HomeController{

	protected $addons = null;

	public function execute($_addons = null, $_controller = null, $_action = null){
		if(C('URL_CASE_INSENSITIVE')){
			$_addons = ucfirst(parse_name($_addons, 1));
			$_controller = parse_name($_controller,1);
		}
		define ( 'ADDON_PUBLIC_PATH', __ROOT__ . '/Addons/' . $_addons . '/View/Public' );
		defined ( '_ADDONS' ) or define ( '_ADDONS', $_addons );
		defined ( '_CONTROLLER' ) or define ( '_CONTROLLER', $_controller );
		defined ( '_ACTION' ) or define ( '_ACTION', $_action );
		if(!empty($_addons) && !empty($_controller) && !empty($_action)){
			$Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
		} else {
			$this->error('没有指定插件名称，控制器或操作！');
		}
	}
		/**
	 * 重写模板显示 调用内置的模板引擎显示方法，
	 *
	 * @access protected
	 * @param string $templateFile
	 *        	指定要调用的模板文件
	 *        	默认为空 由系统自动定位模板文件
	 *        	支持格式: 空, index, UserCenter/index 和 完整的地址
	 * @param string $charset
	 *        	输出编码
	 * @param string $contentType
	 *        	输出类型
	 * @param string $content
	 *        	输出内容
	 * @param string $prefix
	 *        	模板缓存前缀
	 * @return void
	 */
	protected function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
		$templateFile = $this->getAddonTemplate ( $templateFile );
		$this->view->display ( $templateFile, $charset, $contentType, $content, $prefix );
	}
	function getAddonTemplate($templateFile = '') {
		if (file_exists ( $templateFile )) {
			return $templateFile;
		}
		//dump ( $templateFile );
		$oldFile = $templateFile;
		if (empty ( $templateFile )) {
			$templateFile = T ( 'Addons://' . _ADDONS . '@' . _CONTROLLER . '/' . _ACTION );
		} elseif (stripos ( $templateFile, '/Addons/' ) === false && stripos ( $templateFile, THINK_PATH ) === false) {
			
			if (stripos ( $templateFile, '/' ) === false) { // 如index
				$templateFile = T ( 'Addons://' . _ADDONS . '@' . _CONTROLLER . '/' . $templateFile );
			} elseif (stripos ( $templateFile, '@' ) === false) { // // 如 UserCenter/index
				$templateFile = T ( 'Addons://' . _ADDONS . '@' . $templateFile );
			}
		}
		/*if (stripos ( $templateFile, '/Addons/' ) !== false && ! file_exists ( $templateFile )) { 
			$templateFile = !empty($oldFile) && stripos ( $oldFile, '/' )===false ? $oldFile : _ACTION;
		}*/
		//dump ( $templateFile );//exit;
		return $templateFile;
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
     * @author
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(),$order='',$field=true){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        if(is_string($model)){
            $model  =   M($model);
        }
        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);
        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);
        if(empty($where)){
            $where  =   array();
        }
        if( !empty($where)){
            $options['where']   =   $where;
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        $total        =   $model->where($options['where'])->count();

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 5;
        }
        $page = new \Think\Page($total, $listRows, $REQUEST);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $options['limit'] = $page->firstRow.','.$page->listRows;
        $model->setProperty('options',$options);
        return $model->field($field)->select();
    }
/**
     * 禁用条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的 where()方法的参数
     * @param array  $msg   执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！')){
        $data    =  array('status' => 0);
        $this->editRow( $model , $data, $where, $msg);
    }
	/**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     *
     * @param string $model 模型名称,供M函数使用的参数
     * @param array  $data  修改的数据
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function editRow ( $model ,$data, $where , $msg ){
        $id    = array_unique((array)I('id',0));
        $id    = is_array($id) ? implode(',',$id) : $id;
        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
        if( M($model)->where($where)->save($data)!==false ) {
            $this->success($msg['success'],$msg['url'],$msg['ajax']);
        }else{
            $this->error($msg['error'],$msg['url'],$msg['ajax']);
        }
    }
	/**
     * 恢复条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！')){
        $data    =  array('status' => 1);
        $this->editRow(   $model , $data, $where, $msg);
    }
	/**
     * 条目假删除
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function delete ( $model , $where = array() , $msg = array( 'success'=>'删除成功！', 'error'=>'删除失败！')) {
        $data['status']         =   -1;
        $data['update_time']    =   NOW_TIME;
        $this->editRow(   $model , $data, $where, $msg);
    }
}
