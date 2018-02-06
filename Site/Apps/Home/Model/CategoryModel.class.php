<?php
// +----------------------------------------------------------------------
// | Keywa Inc.
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.keywa.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: vii
// +----------------------------------------------------------------------
namespace Home\Model;
use Think\Model;
use Think\Page;

class CategoryModel extends CommonModel{

    protected $redis;

	public function __construct(){
	    $this->autoCheckFields = false;
		$this->redis = \Think\Cache::getInstance('Redis');
	}
		
	private function getCategoryMerge($arr){
		$tree_arr = array();
		if($arr){
			foreach($arr as $k=>$v){
				$tree_arr[$arr[$k]['id']]['id'] = $arr[$k]['id'];
				$tree_arr[$arr[$k]['id']]['text'] = $arr[$k]['title'];
				$tree_arr[$arr[$k]['id']]['attributes']['parentId'] = $arr[$k]['parentId'];
			}
		}
		$tree = $this->getChild($tree_arr);
		return $tree;
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
        $cacheKey = $this->getDetailCacheKey( $id );
        $ret = $this->redis->hgetall( $cacheKey );
        return $ret;
    }

    /**
     * 获取详情缓存 Cachekey
     * @param int $id D
     * @return string
     */
    protected function getDetailCacheKey( $id ){
        return 'hash:category:'.$id;
    }
    
    /**
     * 获取所有的商品分类
     */
    public function getCategory(){
    	//$redis = \Think\Cache::getInstance('Redis');
    	$idArr = $this->redis -> sMembers('set:category:status:1');
    	$arr=array();
    	if($idArr){
    		foreach($idArr as $k=>$v){
    			$arr[$v]=$this->redis->hGetAll('hash:category:'.$v);
    			$arr[$v]['id']=$v;
    		}
    	}
    	$res = $this->getCategoryMerge($arr);
    	//将结果写入缓存
    	$this->redis->hset('hash:category:cache','cache',serialize($res));
    	return $res;
    }
    
    /**
     * 先从缓存总取出
     */
    public function getcategorycache(){
    	$str=$this->redis->hGet('hash:category:cache','cache');
    	if(!$str){
    		return $this->getCategory();
    	}
    	return unserialize($str);
    }
}