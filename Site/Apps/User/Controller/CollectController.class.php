<?php
namespace User\Controller;
use Think\Controller;


/**
 * 我的收藏
 */

class CollectController extends CommonController{
	
	
	//收藏列表
	public function index(){
		$param=I('get.');
		$model=D('Collect');
		$data=I('get.');
		$data['uid']=$this->uid;
		if(empty($data['start']))$data['start']="-inf";
		if(empty($data['end']))$data['end']="+inf";
		$res=$model->lists($data);
	}
	
	
	//加入收藏
	
	public function addCollect(){
		//接受参数，ID，uid，还有是求购还是商品
		$data['id']=intval(I('id'));
		$data['type']=intval(I('type'));
		$data['uid']=$this->uid;
		if(empty($data['id']||$data['type'])){
			$this->ajaxReturn(array('code'=>400,'msg'=>'failed'));
		}
		$model=D('Collect');
		$res=$model->addcollect($data);
		if($res){
			if($res['code']==400)$this->ajaxReturn($res);
			$this->ajaxReturn(array('code'=>200,'msg'=>'success'));
		}else{
			$this->ajaxReturn(array('code'=>400,'msg'=>'failed'));
		}
	}
	
	
	//删除收藏
	public function  delCollect(){
		
		$data['id']=explode(',',I('id'));
		$data['type']=I('type');
		$data['uid']=$this->uid;
		$model=D('Collect');
		$res=$model->delcollect($data);
		if($res){
			if($res['code']==400)$this->ajaxReturn($res);
			$return=array('code'=>200,'msg'=>'remove','data'=>array('url'=>U( '/User/Member/favorites', array( 'type' => intval( $data['type'] ) ) ) ));
		}else{
			$return=array('code'=>400,'msg'=>'fail');
		}
		
		$this->ajaxReturn($return);
		
	}
	
	
	
	
	
}