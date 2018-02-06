<?php
namespace Admin\Model;
use Think\Model;

/**
 * 公司信息模型
 */

class CompanydataModel extends Model{
	protected $_validate = array(
		array('text','require','行业名称不能为空！'),
	);
	protected $field=array(
			'id',
			'type',
			'parentId',
			'text',
			'createTime',
			'status'
	);

	 public function __construct(){
        $this->autoCheckFields = false;
       // $this->redis = \Think\Cache::getInstance('Redis');
    }
	
	private function getCompanyMerge($arr){
		$tree_arr = array();
		if($arr){
			foreach($arr as $k=>$v){
				$tree_arr[$arr[$k]['id']]['id'] = $arr[$k]['id'];
				$tree_arr[$arr[$k]['id']]['text'] = $arr[$k]['title'];
			}
		}
		$tree = $this->getChild($tree_arr);
		return $tree;
	}
	
	private function getChild($items)
	{
		$tree = array(); //格式化好的树
		foreach ($items as $item) {
			if(!empty($item)){
			    if (isset($item['attributes']['parentId'])){
			        $items[$item['attributes']['parentId']]['children'][] = &$items[$item['id']];
			    }else{
			        $tree[] = &$items[$item['id']];
			    }
			}
		}
		return $tree;
	}
	
	
	//获取公司信息列表
	public function getList($type){
		$redis = \Think\Cache::getInstance('Redis');

		if(empty($type) || !in_array($type,array('trade','property','model','turnover','employees')))return false;

		$idArr = $redis -> sMembers('set:'.$type.':status:1');
		$arr=array();
		if($idArr){
			foreach($idArr as $k=>$v){
				$arr[$v]=$redis->hGetAll('hash:'.$type.':'.$v);
				$arr[$v]['id']=$v;
			}
		}
		$res = $this->getCompanyMerge($arr);

		//重新组合前端对接数组
		foreach($res as $key=>$vo){
			$res[$key]['attributes']['type']=2;
			$res[$key]['attributes']['type']=2;
		}
		$returnArr[0]['id']=0;
		switch ($type)
		{
			case 'trade':
				$returnArr[0]['text']="所在行业";
				break;
			case 'property':
				$returnArr[0]['text']="单位性质";
				break;
			case 'model':
				$returnArr[0]['text']="经营模式";
				break;
			case 'turnover':
				$returnArr[0]['text']="年营业额";
				break;
			case 'employees':
				$returnArr[0]['text']="单位人数";
				break;
		}

		$returnArr[0]['attributes']['type']=1;
		$returnArr[0]['children']=$res;

		return $returnArr;
	}
	
	//新增公司信息
	
	public function companyDataInsert($data){
		$redis = \Think\Cache::getInstance('Redis');

		if(empty($data['type']) || !in_array($data['type'],array('trade','property','model','turnover','employees')))return false;

		$id = $redis->incr('string:'.$data['type']);//获取自增id
		if($id){
			//插入主要数据到hash表 start
			$info['id']=$id;
			$info['title']=$data['text'];

			$info['addTime']=time();

			$result = $redis->hMset('hash:'.$data['type'].':'.$id,$info);
			//end

			if($result){
				$redis->sAdd('set:'.$data['type'].':status:1',$id);//插入到状态到集合
				return $result;
			}
		}
		
	}

	//修改公司数据信息
	public function companyDataUpdate($data){
		$redis = \Think\Cache::getInstance('Redis');

		if(empty($data['type']) || !in_array($data['type'],array('trade','property','model','turnover','employees')))return false;

		$id = $data['id'];//获取修改id
		if($id){
			//插入主要数据到hash表 start
			$info['title']=$data['text'];
			$info['updateTime']=time();

			$result = $redis->hMset('hash:'.$data['type'].':'.$id,$info);
			//end

			return $result;
		}
	}

	//公司数据删除
	public function companyDataDelete($id,$type){
		$redis = \Think\Cache::getInstance('Redis');

		if(empty($type) || !in_array($type,array('trade','property','model','turnover','employees')))return false;

		if($id){
			//移除集合元素
			$redis->sMove('set:'.$type.':status:1','set:'.$type.':status:0',$id);
			return true;
		}else{
			return false;
		}
	}

	//根据id读取名字
	public function getCompanyDataName($id,$type){
		$redis = \Think\Cache::getInstance('Redis');
		if(empty($type) || !in_array($type,array('trade','property','model','turnover','employees')))return false;

		if($id){
			//读取名字
			$title = $redis->hGet('hash:'.$type.':'.$id,'title');
			return $title;
		}else{
			return '';
		}
	}
	
}