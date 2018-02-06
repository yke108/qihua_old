<?php
namespace Admin\Model;
use Think\Model;

/**
 * 联系我们模型
 */

class ContactModel extends Model{
	protected $field=array(
			'id',
			'title',
			'phone',
			'mail',
			'other'
	);
	//新增或者更新
	public function contact($con){
		$id=$con['id'];
		if($id){
			$where="id='$id'";
		}else{
			$where['title']=$con['title'];
			unset($con['id']);
		}
		$res=$this->where($where)->find();
		if(!empty($res)){
			if($this->where($where)->data($con)->save()){
				return array('code'=>200,'msg'=>'更新成功','data'=>'');
			}else{
				return array('code'=>400,'msg'=>'更新失败','data'=>'');
			}
		}else{
			if($this->data($con)->add()){
				return array('code'=>200,'msg'=>'新增成功','data'=>'');
			}else{
				return array('code'=>400,'msg'=>'新增失败','data'=>'');
			}
		}
	}
	
	//获取列表
	public function getContact(){
		$res=$this->select();
		return $res;
	}
	
	//批量修改或者插入
	public function cooperate($array){
		$return=array();
		foreach ($array as $v){
			$title=$v['title'];
			$where="title='$title'";
			$res=$this->where($where)->find();

			if(!empty($res)){
				if($this->where($where)->data($v)->save() !== false){
					$return[] =array('code'=>200,'msg'=>$title."更新成功",'data'=>'');
				}else{
					$return[] =array('code'=>400,'msg'=>$title."更新失败",'data'=>'');
				}
			}else{
				if($this->where($where)->data($v)->add()){
					$return[] =array('code'=>200,'msg'=>$title."新增成功",'data'=>'');
				}else{
					$return[] =array('code'=>400,'msg'=>$title."新增失败",'data'=>'');
				}
			}
		}
		return $return;
	}
	
	
	
	
}