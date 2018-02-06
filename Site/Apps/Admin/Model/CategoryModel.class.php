<?php
namespace Admin\Model;
use Think\Model;


class CategoryModel extends Model {
	protected $_validate = array(
		array('text','require','选项名称不能为空！'),
	);
	protected $field=array(
			'id',
			'parentId',
			'createTime',
			'text',
			'depth',
			'path',
			'status'
	);
	private function getCategoryMerge($arr){
		$tree_arr = array();
		if($arr){
			foreach($arr as $k=>$v){
				$tree_arr[$arr[$k]['id']]['id'] = $arr[$k]['id'];
				$tree_arr[$arr[$k]['id']]['text'] = $arr[$k]['title'];
				$tree_arr[$arr[$k]['id']]['attributes']['type'] = $arr[$k]['depth'];
				$tree_arr[$arr[$k]['id']]['attributes']['parentId'] = $arr[$k]['parentId'];
			}
		}
		$tree = $this->getChild($tree_arr);
		return $tree;
	}
	
	private function updatecache($str){
		$redis = \Think\Cache::getInstance('Redis');
		$redis->hset('hash:category:cache','cache',$str);
	}
	private function getChild($items){
		$tree = array(); //格式化好的树
		foreach ($items as $item) {
				
			if (isset($items[$item['attributes']['parentId']]))
				$items[$item['attributes']['parentId']]['children'][] = &$items[$item['id']];
				else
					$tree[] = &$items[$item['id']];
		}
		return $tree;
	}
	//获取品牌列表
	public function getCategory(){
		$redis = \Think\Cache::getInstance('Redis');
		$idArr = $redis -> sMembers('set:category:status:1');
		$arr=array();
		if($idArr){
			foreach($idArr as $k=>$v){
				$arr[$v]=$redis->hGetAll('hash:category:'.$v);
				$arr[$v]['id']=$v;
			}
		}
		$res = $this->getCategoryMerge($arr);

		return $res;
	}
	
	//新增商品类型
	public function addCategory($data){
		$redis = \Think\Cache::getInstance('Redis');

		$id = $redis->incr('string:category');//获取自增id
		if($id){
			//插入主要数据到hash表 start
			$info['id']=$id;
			$info['title']=$data['text'];
			$info['parentId']=$data['id']?$data['id']:0;

			$parentId = $redis->hGet('hash:category:'.$data['id'],'parentList');
			if(!empty($parentId)){
				$info['parentList']=$parentId.','.$id;
			}else{
				$info['parentList']=$id;
			}

			$parentDepth = $redis->hGet('hash:category:'.$data['id'],'depth');
			if(!empty($parentDepth)){
				$info['depth']=(int)$parentDepth+1;
			}else{
				$info['depth']=1;
			}

			$info['addTime']=time();

			$result = $redis->hMset('hash:category:'.$id,$info);
			//end

			if($result){
				$redis->pipeline();//使用管道
				$redis->sAdd('set:category:status:1',$id);//插入到状态到集合

				//插入到父集合
				if(empty($data['id'])){
					$redis->sAdd('set:categoryChild:0',$id);
				}else{
					$redis->sAdd('set:categoryChild:'.$data['id'],$id);
				}

				$redis->sAdd('set:categoryAllChild:0',$id);
				$parentList = explode(',',$info['parentList']);
				if($parentList){
					foreach($parentList as $k=>$v){
						$redis->sAdd('set:categoryAllChild:'.$v,$id);
					}
				}

				$redis->exec();//管道执行
				$this->updatecache(serialize($this->getCategory()));
				return $id;
			}
		}
	}
	
	//更新商品类别
	public function updateCategory($data){
		$redis = \Think\Cache::getInstance('Redis');

		$id = $data['id'];//获取修改id
		if($id){
			//插入主要数据到hash表 start
			$areaInfo['title']=$data['text'];
			$areaInfo['updateTime']=time();

			$result = $redis->hMset('hash:category:'.$id,$areaInfo);
			//end
			$this->updatecache(serialize($this->getCategory()));
			return $result;
		}
	}
	
	//删除商品类别
	public function delCategory($id){
		$redis = \Think\Cache::getInstance('Redis');
		if($id){
			//读取子id
			$idArr = $redis->sMembers('set:categoryAllChild:'.$id);
			if($idArr){
				//移除集合元素
				foreach($idArr as $k=>$v){
					$redis->sMove('set:category:status:1','set:category:status:0',$v);
				}
			}
			$this->updatecache(serialize($this->getCategory()));
			return true;
		}else{
			return false;
		}
	}
	
	
}