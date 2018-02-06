<?php
namespace Home\Model;
use Think\Model;

class AreaModel extends CommonModel{
	
	public function __construct(){
		$this->autoCheckFields = false;
	}
	
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
	
	//根据ID取
	function getChildArea($id){
		$redis = \Think\Cache::getInstance('Redis');
		if($id){
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


    /**
     * 获取详情
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return array
     */
    public function detail( $param ){
        $ret 	= array();
        $id 	= intval( $param['id'] );
        if( empty( $id ) ){
            return $ret;
        }
        $redis = \Think\Cache::getInstance('Redis');
        $cacheKey = $this->getDetailCacheKey( $id );
        $ret = $redis->hgetall( $cacheKey );
        return $ret;
    }

    /**
     * 获取详情缓存 Cachekey
     * @param int $id D
     * @return string
     */
    protected function getDetailCacheKey( $id ){
        return 'hash:area:'.$id;
    }
}