<?php
namespace Admin\Model;

use Think\Model;


/**
 * 
 * @author Administrator
 *品牌控制器
 */
class BrandModel extends Model{
	protected $_validate = array(
		array('id','require','缺少id！'),
		array('text','require','选项名称必须填写！'),
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
	
	
	private function getBrandMerge($arr){
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
	
	//获取子项
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
	public function getBrand(){
		$redis = \Think\Cache::getInstance('Redis');
		$idArr = $redis -> sMembers('set:brand:status:1');
		$arr=array();
		if($idArr){
			foreach($idArr as $k=>$v){
				$arr[$v]=$redis->hGetAll('hash:brand:'.$v);
				$arr[$v]['id']=$v;
			}
		}

		$res = $this->getBrandMerge($arr);

		return $res;
	}
	
	//新增品牌
	public function addBrand($data){
		$redis = \Think\Cache::getInstance('Redis');

		$id = $redis->incr('string:brand');//获取自增id
		if($id){
			//插入主要数据到hash表 start
			$Info['id']=$id;
			$Info['title']=$data['text'];
			$Info['parentId']=$data['id']?$data['id']:0;

			$parentId = $redis->hGet('hash:brand:'.$data['id'],'parentList');
			if(!empty($parentId)){
				$Info['parentList']=$parentId.','.$id;
			}else{
				$Info['parentList']=$id;
			}

			$parentDepth = $redis->hGet('hash:brand:'.$data['id'],'depth');
			if(!empty($parentDepth)){
				$Info['depth']=(int)$parentDepth+1;
			}else{
				$Info['depth']=1;
			}

			$Info['addTime']=time();

			$result = $redis->hMset('hash:brand:'.$id,$Info);
			//end

			if($result){
				$redis->pipeline();//使用管道
				$redis->sAdd('set:brand:status:1',$id);//插入到状态到集合

				//插入到父集合
				if(empty($data['id'])){
					$redis->sAdd('set:brandChild:0',$id);
				}else{
					$redis->sAdd('set:brandChild:'.$data['id'],$id);
				}

				$redis->sAdd('set:brandAllChild:0',$id);
				$parentList = explode(',',$Info['parentList']);
				if($parentList){
					foreach($parentList as $k=>$v){
						$redis->sAdd('set:brandAllChild:'.$v,$id);
					}
				}
                $redis->sAdd( 'set:brand:name', $Info['title'] );

				$redis->sAdd('set:brand:name',$Info['title']);

				$redis->exec();//管道执行
				return $id;
			}
		}
	}
	//修改品牌
	public function modify($data){
		$redis = \Think\Cache::getInstance('Redis');

		$id = $data['id'];//获取修改id
		if($id){
			//插入主要数据到hash表 start
			$info['title']=$data['text'];
			$info['updateTime']=time();
			$title = $redis->hGet('hash:brand:'.$data['id'],'title');
			$result = $redis->hMset('hash:brand:'.$id,$info);
			//end


			if($title)$redis->sRem('set:brand:name',$title);
			$redis->sAdd('set:brand:name',$info['title']);

			return $result;
		}
	}
	
	
	//删除品牌
	public function delBrand($id){
		$redis = \Think\Cache::getInstance('Redis');
		if($id){
			//读取子id
			$idArr = $redis->sMembers('set:brandAllChild:'.$id);
			if($idArr){
				//移除集合元素
				foreach($idArr as $k=>$v){
					$redis->sMove('set:brand:status:1','set:brand:status:0',$v);
				}
			}

			return true;
		}else{
			return false;
		}
	}

    /**
     * 检查品牌名是否已添加
     * @param $brand brand
     * @return bool
     */
	public function checkBrandIsExist( $brand ){
        $ret = false;
        if( !empty( $brand ) ){
            $redis = \Think\Cache::getInstance('Redis');
            $cacheKey = 'set:brand:name';
            $result = $redis->sIsMember( $cacheKey, $brand );
            if( $result ){
                $ret = true;
            }
        }
        return $ret;
    }
}