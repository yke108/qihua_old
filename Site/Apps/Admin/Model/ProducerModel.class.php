<?php
namespace Admin\Model;
use Think\Model;

/**
 * 生产商模型
 *
 */

class ProducerModel extends Model{
	protected $_validate = array(
		//array('id','require','缺少id！'),
		array('text','require','选项名称必须填写！'),
	);

	protected $field=array(
			'id',
			'parentId',
			'createTime',
			'text',
			'shorttext',
			'depth',
			'path',
			'status'
	);
	
	private function getProducerMerge($arr){
		$tree_arr = array();
		if($arr){
			foreach($arr as $k=>$v){
				$tree_arr[$arr[$k]['id']]['id'] = $arr[$k]['id'];
				$tree_arr[$arr[$k]['id']]['text'] = $arr[$k]['title'];
				$tree_arr[$arr[$k]['id']]['attributes']['sortName']=$arr[$k]['shortTitle'];
				$tree_arr[$arr[$k]['id']]['attributes']['type'] = $arr[$k]['depth'];
				$tree_arr[$arr[$k]['id']]['attributes']['parentId'] = $arr[$k]['parentId'];
				if($arr[$k]['parentId']!=0){
					$tree_arr[$arr[$k]['id']]['attributes']['sortName']=$arr[$k]['shortTitle'];
				}
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
	//生产商列表
	public function getProducer(){
		$redis = \Think\Cache::getInstance('Redis');
		$idArr = $redis -> sMembers('set:producer:status:1');
		$arr=array();
		if($idArr){
			foreach($idArr as $k=>$v){
				$arr[$v]=$redis->hGetAll('hash:producer:'.$v);
				$arr[$v]['id']=$v;
			}
		}

		$res = $this->getProducerMerge($arr);
		return $res;
	}
	
	//新增生产商
	public function addProducer($data){
		$redis = \Think\Cache::getInstance('Redis');

		$id = $redis->incr('string:producer');//获取自增id
		if($id){
			//插入主要数据到hash表 start
			$Info['id']=$id;
			$Info['title']=$data['text'];
			$Info['shortTitle']=$data['shorttext'];
			$Info['parentId']=$data['id']?$data['id']:0;

			$parentId = $redis->hGet('hash:producer:'.$data['id'],'parentList');
			if(!empty($parentId)){
				$Info['parentList']=$parentId.','.$id;
			}else{
				$Info['parentList']=$id;
			}

			$parentDepth = $redis->hGet('hash:producer:'.$data['id'],'depth');
			if(!empty($parentDepth)){
				$Info['depth']=(int)$parentDepth+1;
			}else{
				$Info['depth']=1;
			}

			$Info['addTime']=time();

			$result = $redis->hMset('hash:producer:'.$id,$Info);
			//end

			if($result){
				$redis->pipeline();//使用管道
				$redis->sAdd('set:producer:status:1',$id);//插入到状态到集合

				//插入到父集合
				if(empty($data['id'])){
					$redis->sAdd('set:producerChild:0',$id);
				}else{
					$redis->sAdd('set:producerChild:'.$data['id'],$id);
				}

				$redis->sAdd('set:producerAllChild:0',$id);
				$parentList = explode(',',$Info['parentList']);
				if($parentList){
					foreach($parentList as $k=>$v){
						$redis->sAdd('set:producerAllChild:'.$v,$id);
					}
				}
                $redis->sAdd( 'set:producer:name', $Info['title'] );

				$redis->sAdd('set:producer:name',$Info['title']);

				$redis->exec();//管道执行
				return $id;
			}
		}
	}
	
	//修改生产商
	public function updateProducer($data){
		$redis = \Think\Cache::getInstance('Redis');

		$id = $data['id'];//获取修改id
		if($id){
			//插入主要数据到hash表 start
			$areaInfo['title']=$data['text'];
			$areaInfo['shortTitle']=$data['shorttext'];
			$areaInfo['updateTime']=time();

			$title = $redis->hGet('hash:producer:'.$data['id'],'title');

			$result = $redis->hMset('hash:producer:'.$id,$areaInfo);
			//end

			if($title)$redis->sRem('set:producer:name',$title);
			$redis->sAdd('set:producer:name',$areaInfo['title']);
			return $result;
		}
	}
	
	//删除生产商
	public function delProducer($id){
		$redis = \Think\Cache::getInstance('Redis');
		if($id){
			//读取子id
			$idArr = $redis->sMembers('set:producerAllChild:'.$id);
			if($idArr){
				//移除集合元素
				foreach($idArr as $k=>$v){
					$redis->sMove('set:producer:status:1','set:producer:status:0',$v);
				}
			}

			return true;
		}else{
			return false;
		}
	}

    /**
     * 检查生产商是否已添加
     * @param $producer producer
     * @return bool
     */
    public function checkProducerIsExist( $producer ){
        $ret = false;
        if( !empty( $producer ) ){
            $redis = \Think\Cache::getInstance('Redis');
            $cacheKey = 'set:producer:name';
            $result = $redis->sIsMember( $cacheKey, $producer );
            if( $result ){
                $ret = true;
            }
        }
        return $ret;
    }

}