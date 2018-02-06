<?php
namespace Home\Model;
use Think\Model;


class ContactModel extends Model{
	
	//取出联系我们数据
	public function company(){
		$where['title']=array('in','佛山总公司,广州分公司');
		$res=$this->where($where)->select();
		//将数组反序列化回来
		$arr=array();
		foreach ($res as $v){
			$other=unserialize($v['other']);
			unset($v['other']);
			$arr[]=array_merge($v,$other);
		}
		return $arr;
	}
	
	//取出商务合作内容
	public function contact(){
		$where['title']=array('in','供应商合作,采购合作,品牌推广,投资洽谈,客户服务,投诉建议');
		$res=$this->where($where)->select();
		$arr=array();
		foreach ($res as $v){
			$other=unserialize($v['other']);
			unset($v['other']);
			$arr[]=array_merge($v,$other);
		}
		return $arr;
	}
}