<?php

namespace Admin\Controller;

class PunchAdminController extends AddonsController{
	/**
	 * 打卡审核列表
	 */
	public function punchClockList(){
		$title=I('title');
		$status=I('status');
		$map=array();
		if($title){
			$where['title']=array('like','%'.$title.'%');
			$where['content']=array('like','%'.$title.'%');
			$where['_logic'] = 'or';
			$map['_complex'] = $where;
		}
		if(is_numeric($status)){
			$map['status']=$status;	
		}
		$punclick=$this->lists('PunchClock',$map);
		int_to_string($punclick);
		$this->assign('list',$punclick);
		$this->display();
	}
	/**
	 * 查看打卡详情
	 */
	public function selectPunch(){
		$id=I('id');
		$punchclock=M('PunchClock')->where(array('id'=>$id))->find();
		$member=M('Member')->where(array('id'=>$punchclock['membid']))->getField('id,realname,nickname');
		int_to_string($punchclock);
		$punchclock['imglist']=$punchclock['imglist']?unserialize($punchclock['imglist']):'';
		$this->assign('punchclock',$punchclock);
		$this->assign('member',$member);
		$this->display();
	}
	/**
	 * 修改状态
	 */
	public function changeStatus(){
		$method=I('method',null);
		$id = array_unique((array)I('id',0));
        if( in_array(C('Member_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['id'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbidpunchclick':
                $this->forbid('PunchClock', $map );
                break;
            case 'resumepunchclick':
                $this->resume('PunchClock', $map );
                break;
            case 'deletepunchclick':
                $this->delete('PunchClock', $map );
                break;
			case 'forbidpunchcomment':
                $this->forbid('PunchComment', $map );
                break;
            case 'resumepunchcomment':
                $this->resume('PunchComment', $map );
                break;
            case 'deletepunchcomment':
				M('PunchComment')->where($map)->delete();
				$msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
		        if( M('PunchComment')->where($map)->delete()!==false ) {
		            $this->success($msg['success'],$msg['url'],$msg['ajax']);
		        }else{
		            $this->error($msg['error'],$msg['url'],$msg['ajax']);
		        }
                break;
            default:
                $this->error('参数非法');
        }
	}
	/**
	 * 评论审核
	 */
	public function punchComment(){
		$title=I('title');
		$status=I('status');
		$map=array();
		if($title){
			$map['content'] = array('like','%'.$title.'%');
		}
		if(is_numeric($status)){
			$map['status']=$status;	
		}
		$punclick=$this->lists('PunchComment',$map);
		int_to_string($punclick);
		$this->assign('list',$punclick);
		$this->display();
	}
}
