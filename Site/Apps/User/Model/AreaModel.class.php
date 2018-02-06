<?php
namespace User\Model;
use Think\Model;


/**
 * 地区控制器
 */

class AreaModel extends Model{
	protected $_validate = array(
		array('text','require','地区名不能为空！'),
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

	public function getArea(){
		$redis = \Think\Cache::getInstance('Redis');
		$tmpId = uniqid();
		$count = $redis->SINTERSTORE('tmp:set:area:list:'.$tmpId,'set:area:status:1','set:areaChild:0');
		if($count && $redis->expire('tmp:set:area:list:'.$tmpId,60)){
			$area_sort_option=array(
				'get'=>array('hash:area:*->id','hash:area:*->title','hash:area:*->depth','hash:area:*->parentId'),
			);
			$areaTmpArr = $redis->sort('tmp:set:area:list:'.$tmpId,$area_sort_option);
			$areaArr=array();
			$num=0;

			//数组整合
			if($areaTmpArr){
				foreach($areaTmpArr as $key=>$vo){
					if($key%4==0){
						$areaArr[$num]['id']=$vo;
						if($redis->sMembers('set:areaChild:'.$vo))$areaArr[$num]['state']='closed';
					}elseif($key%4==1){
						$areaArr[$num]['text']=$vo;
					}elseif($key%4==2){
						$areaArr[$num]['attributes']['type']=$vo;
					}elseif($key%4==3){
						$areaArr[$num]['attributes']['parentId']=$vo;
                        $num++;
					}
				}
			}
//			print_r($areaArr);exit;
			return $areaArr;
		}

	}

//	public function getArea(){
//		$redis = \Think\Cache::getInstance('Redis');
//		$idArr = $redis -> sMembers('set:area:status:1');
//		$arr=array();
//		if($idArr){
//			foreach($idArr as $k=>$v){
//				$arr[$v]=$redis->hGetAll('hash:area:'.$v);
//				$arr[$v]['id']=$v;
//			}
//		}
//		$res = $this->getAreaMerge($arr);
//		return $res;
//	}

	private function getAreaMerge($arr){
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
	
	private function getChild($items)
			{
				$tree = array(); //格式化好的树
				foreach ($items as $item) {
			
					if (isset($items[$item['attributes']['parentId']]))
						$items[$item['attributes']['parentId']]['children'][] = &$items[$item['id']];
						else
							$tree[] = &$items[$item['id']];
				}
				return $tree;
			}
		
			
		//新增地区
	public function areaAdd($data){
		$redis = \Think\Cache::getInstance('Redis');

		$id = $redis->incr('string:area');//获取自增id
		if($id){
			//插入主要数据到hash表 start
			$areaInfo['id']=$id;
			$areaInfo['title']=$data['text'];
			$areaInfo['parentId']=$data['id']?$data['id']:0;

			$parentId = $redis->hGet('hash:area:'.$data['id'],'parentList');
			if(!empty($parentId)){
				$areaInfo['parentList']=$parentId.','.$id;
			}else{
				$areaInfo['parentList']=$id;
			}

			$parentDepth = $redis->hGet('hash:area:'.$data['id'],'depth');
			if(!empty($parentDepth)){
				$areaInfo['depth']=(int)$parentDepth+1;
			}else{
				$areaInfo['depth']=1;
			}

			$areaInfo['addTime']=time();

			$result = $redis->hMset('hash:area:'.$id,$areaInfo);
			//end

			if($result){
				$redis->pipeline();//使用管道
				$redis->sAdd('set:area:status:1',$id);//插入到状态到集合

				//插入到父集合
				if(empty($data['id'])){
					$redis->sAdd('set:areaChild:0',$id);
				}else{
					$redis->sAdd('set:areaChild:'.$data['id'],$id);
				}

				$redis->sAdd('set:areaAllChild:0',$id);
				$parentList = explode(',',$areaInfo['parentList']);
				if($parentList){
					foreach($parentList as $k=>$v){
						$redis->sAdd('set:areaAllChild:'.$v,$id);
					}
				}

				$redis->exec();//管道执行
				return $result;
			}
		}

	}

	public function updateArea($data){
		$redis = \Think\Cache::getInstance('Redis');

		$id = $data['id'];//获取修改id
		if($id){
			//插入主要数据到hash表 start
			$areaInfo['title']=$data['text'];
			$areaInfo['updateTime']=time();

			$result = $redis->hMset('hash:area:'.$id,$areaInfo);
			//end

			return $result;
		}
	}

		//删除地区
		function areaDelete($id){
			$redis = \Think\Cache::getInstance('Redis');
			if($id){
				//读取子id
				$idArr = $redis->sMembers('set:areaAllChild:'.$id);
				if($idArr){
					//移除集合元素
					foreach($idArr as $k=>$v){
						$redis->sMove('set:area:status:1','set:area:status:0',$v);
					}
				}

				return true;
			}else{
				return false;
			}
		}

	//获取子ID
	function getChildArea($id){
		$redis = \Think\Cache::getInstance('Redis');
		if($id!==false){
			//读取子id
			$tmpId = uniqid();
			$count = $redis->SINTERSTORE('tmp:set:area:list:'.$tmpId,'set:area:status:1','set:areaChild:'.$id);
			if($count && $redis->expire('tmp:set:area:list:'.$tmpId,60)){
				$area_sort_option=array(
					'get'=>array('hash:area:*->id','hash:area:*->title','hash:area:*->depth','hash:area:*->parentId'),
				);
				$areaTmpArr = $redis->sort('tmp:set:area:list:'.$tmpId,$area_sort_option);

				$areaArr=array();
				$num=0;

				//数组整合
				if($areaTmpArr){
					foreach($areaTmpArr as $key=>$vo){
						if($key%4==0){
							$areaArr[$num]['id']=$vo;
							if($redis->sMembers('set:areaChild:'.$vo))$areaArr[$num]['state']='closed';
						}elseif($key%4==1){
							$areaArr[$num]['text']=$vo;
						}elseif($key%4==2){
							$areaArr[$num]['attributes']['type']=$vo;
						}elseif($key%4==3){
							$areaArr[$num]['attributes']['parentId']=$vo;
							$num++;
						}
					}
				}

				return $areaArr;
			}
		}else{
			return false;
		}
	}

	//根据id读取名字
	public function getAreaName($id){
		$redis = \Think\Cache::getInstance('Redis');
		if($id){
			//读取名字
			$title = $redis->hGet('hash:area:'.$id,'title');
			return $title;
		}else{
			return '';
		}
	}

}