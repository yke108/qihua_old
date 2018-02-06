<?php
namespace Home\Model;
use Think\Model;

/**
 * 
 * @author Administrator
 *type:1.收藏商品,2.收藏抢购,3.收藏求购
 */

class CollectModel extends Model{
	public function __construct(){
		$this->redis=\Think\Cache::getInstance('Redis');
	}
	
	//新增收藏
	public function addcollect($type,$uid,$id){
		switch ($type){
			case 3:
				$times='zset:collect:seekbuy';
				$collect='zset:collect:seekbuy:'.$uid;
				break;
			case 2:
				$times='zset:collect:seckill';
				$collect='zset:collect:seckill:'.$uid;
				break;
			default:
				$times='zset:collect:goods';
				$collect='zset:collect:goods:'.$uid;
				break;
		}
		//先判断有没有收藏过这个
		if($this->redis->zrank ($collect,$id)){
			return array('code'=>400,'msg'=>'您已收藏过','data'=>'');
		}
		//事务处理
		for($i=0;$i<10;$i++){
			//加入收藏集合
			$this->redis->watch($collect,$times);
			$this->redis->multi();
			$this->redis->zadd($collect,time(),$id);
			$this->redis->zIncrBy($times,1,$id);
			if($this->redis->exec()){
				return array('code'=>200,'msg'=>'收藏成功','data'=>'');
			}
		}
		return array('code'=>400,'msg'=>'收藏失败','data'=>'');	
	}
	
	
	//删除收藏
	public function delcollect($type,$id){
		switch ($type){
			case 3:
				$times='zset:collect:seekbuy';
				$collect='zset:collect:seekbuy:'.$uid;
				break;
			case 2:
				$times='zset:collect:seckill';
				$collect='zset:collect:seckill:'.$uid;
				break;
			default:
				$times='zset:collect:goods';
				$collect='zset:collect:goods:'.$uid;
				break;
		}
		//先判断有没有收藏过这个
		if($this->redis->zrank ($collect,$id)){
			return array('code'=>400,'msg'=>'您没有收藏过','data'=>'');
		}
		for($i=0;$i<10;$i++){
			//加入收藏集合
			$this->redis->watch($collect,$times);
			$this->redis->multi();
			$this->redis->zrem($collect,$id);
			$this->redis->zIncrBy($times,-1,$id);
			if($this->redis->exec()){
				return array('code'=>200,'msg'=>'删除成功','data'=>'');
			}
		}
		return array('code'=>400,'msg'=>'删除失败','data'=>'');
	}
	
	//搜索收藏,收藏列表
	
	public function search($type,$data=''){
		switch ($type){
				case 3:
					$hash='';
					$collect='zset:collect:seekbuy:'.$uid;
					break;
				case 2:
					$hash='';
					$collect='zset:collect:seckill:'.$uid;
					break;
				default:
					$hash='';
					$collect='zset:collect:goods:'.$uid;
					break;
			}
		isset($data['start'])?$start=$data['start']:$start='-inf';
		isset($data['end'])?$end=$data['start']:$end='+inf';
		if($data['title'])$title=$data['title'];
		$tmp=uniqid();
		//$tempset=$this->redis
		$res=$this->redis->zRangeByScore($collect,$start,$end);
		$arr=array();
		for($i=0;$i<count($res);$i++){
			$arr[$i]['title']=$this->redis->hGet($hash.$res[$i],'title');
			$arr[$i]['time']=date('Y-m-d H:i:s',$this->redis->zrank ($collect,$res[$i]));	
		}
		return $arr;
	}
	
	//获得收藏数量
	public function getcount($id){
		switch ($type){
			case 3:
				$times='zset:collect:seekbuy';
				break;
			case 2:
				$times='zset:collect:seckill';
				break;
			default:
				$times='zset:collect:goods';
				break;
		}
		return $this->redis->zrank($times,$id);
	}	
}