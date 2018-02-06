<?php 
namespace User\Model;
use Think\Model;

/**
 * Supply 模型
 */

class SupplyModel extends Model{
	protected $_validate = array(
			array('title',   'require',  'The title cannot be blank!'),
			array('type',    'require',  'The type cannot be blank!'),
			array('content', 'require',  'Description of buy offer cannot be blank!'),
			array('expire',  'require',  'Period of validity cannot be blank!')
	);
	
	public function __construct(){
		$this->autoCheckFields = false;
		$this->redis = \Think\Cache::getInstance('Redis');
		$this->shell=D('User/shell');
	}
	
	/**
	 * 修改求购
	 * @param array $param
	 * $paray
	 */
	public function modify($param){
		$data=$param['data'];
		$data['state']=2;
		$hash=$this->GetHash($data['id']);
		$uid=$this->redis->hget($hash,'Uid');
		if($param['uid']!=$uid){
			return 403;
		}

        //修改之前先设置
        $state1 = $this->GetkeyState($this->redis->hget($hash, 'state'));
        $typeOrigin = $this->GetKeyType($this->redis->hget($hash, 'type'));
        if (!empty($data['state']) && $state1 != $data['state']) {
            $state2 = $this->GetkeyState($data['state']);
            $this->redis->srem($state1, $data['id']);
            $this->redis->sadd($state2, $data['id']);
        }
        if (!empty($data['type']) && $typeOrigin != $data['type']) {
            $type = $this->GetKeyType($data['type']);
            $this->redis->srem($typeOrigin, $data['id']);
            $this->redis->sadd($type, $data['id']);
        }

        $this->redis->srem($state1, $data['id']);
        $this->redis->sadd($state2, $data['id']);
        $this->redis->hmset($hash, $data);

        $this->shell->index('supply:title', strtolower($data['title']), $data['id']);
        $opera['id'] = $this->redis->incr('string:supply:history');
        $history = $this->GetkeyHistory($data['id']);
        $opera['addTime'] = time();
        $opera['opera'] = 'You have update your supply offer!';
        $opera['reason'] = '';
        $opera['otype'] = D('Member')->get($uid)['username'];
        $opera['oid'] = $uid;
        $opera['state'] = 2;
        $str = serialize($opera);
        $this->redis->hset($history, $opera['id'], $str);

        return true;
	}
	
	
	
	
	/**
	 * 用户查看求购详情
	 *如果传用户ID，则表明在工作台查看，否则为列表查看
	 */
	public function details($param){
		if(!empty($param['uid'])){
			$member=$this->GetKeyMember($param['uid']);
			//用户只能查看自己的Supply
			$res=$this->redis->sDiff($member);
			if(!in_array($param['id'], $res)){
				return false;
			}
		}else{
			//只能查看有效的
			$state=$this->GetkeyState(1);
			$res=$this->redis->sDiff($state);
			if(!in_array($param['id'], $res)){
				return false;
			}
		}
		//被删除的Supply不可以查看
		$status=$this->GetKeyStatus(1);
		$res=$this->redis->sDiff($status);
		if(!in_array($param['id'], $res)){
			return false;
		}
		//取出hash
		$hash=$this->GetHash($param['id']);
		$res=$this->redis->hGetAll($hash);
		return $res;
	
	}
	
	
	
	/**
	 * 操作历史
	 * @param array $param 
	 * 包含uid，被查看的supply ID id
	 */
	public function Opera($param,$limit=10){
		$member=$this->GetKeyMember($param['uid']);
		//用户只能查看自己的求购
		$res=$this->redis->sDiff($member);
		if(!in_array($param['id'], $res)){
			return false;
		}
		//被删除的求购不可以查看
		$status=$this->GetKeyStatus(1);
		$res=$this->redis->sDiff($status);
		if(!in_array($param['id'], $res)){
			return false;
		}
	
	
	
		$history=$this->GetkeyHistory($param['id']);
		$arr=$this->redis->hGetAll($history);
		$newArray = array();
		foreach ( $arr as $k => $v ){
			$v1 = unserialize( $v );
			$newArray[$v1['addTime'].'_'.$k] = $v;
		}
		$arr = $newArray;	
		krsort($arr);
	
		$page=empty($param['p'])?1:intval($param['p']);
		$page=$page<1?1:$page;
		$page=$page>ceil(count($arr)/$limit)?ceil(count($arr)/$limit):$page;
	
		$offset=($page-1)*$limit;
		unset($param['p']);
		$filter=http_build_query($param);
		$res=array_slice($arr, $offset,$limit);
		$state=C('FIND_GOODS_OPERATION');
		foreach( $res as &$v ){
			$v = unserialize( $v );
			$v['state']=isset($state[$v['state']])?$state[$v['state']]:'';
		}
		
		$show=$this->showpage(count($arr), $page,$limit,$filter);
		return array('res'=>$res,'show'=>$show);
	
	}
	

	/**
	 * Supply列表
	 * @param unknown $param
	 * @return number[]|string[]|void[]
	 */
	public function lists($param){
		$page=!empty($param['p'])?$param['p']:1;
		$pageSize=!empty($page['pageSize'])?$param['pageSize']:10;
		$offset=($page-1)*$pageSize;
		$arr[]=$member=$this->GetKeyMember($param['Uid']);
		$arr[]=$status=$this->GetKeyStatus(1);
		if(!empty($param['type'])){
			$arr[]=$type=$this->GetKeyType($param['type']);
			$filter['type']=$param['type'];
		}
		if(!empty($param['state']||$param['state']=='0')){
			$arr[]=$this->GetkeyState(intval($param['state']));
			$filter['state']=$param['state'];
		}
		if(!empty($param['country'])){
			$arr[]=$country=$this->GetKeyCountry($param['country']);
			$filter['country']=$param['country'];
		}
		if(!empty($param['title'])){
			$arr[]=$this->shell->search('supply:title',strtolower($param['title']),'set');
			$filter['title']=$param['title'];
		}
		if(!empty($param['all'])){
			$filter['uid']=$param['Uid'];
		}
        if(!empty($filter)){
            $a=md5(http_build_query($filter));
        }else{
            $a='';
        }
		
		if($this->redis->exists('tmp:set:supply:list:'.$a)){
			$tmpset=1;
		}else{
			$tmpset=$this->redis->zInter('tmp:set:supply:list:'.$a,$arr);
		}

		if($tmpset &&$this->redis->expire('tmp:set:supply:list:'.$a,3)){
			$count=$this->redis->zcard('tmp:set:supply:list:'.$a);
			$pageinfo['pagecount']=ceil($count/$pageSize);
			if($page>$pageinfo['pagecount']){
				$page=$pageinfo['pagecount'];
				$offset=intval(($page-1)*$pageSize);
			}
			$id=$this->redis->sort('tmp:set:supply:list:'.$a,array('get'=>array('hash:supply:*->id'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$title=$this->redis->sort('tmp:set:supply:list:'.$a,array('get'=>array('hash:supply:*->title'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$type=$this->redis->sort('tmp:set:supply:list:'.$a,array('get'=>array('hash:supply:*->type'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$expire=$this->redis->sort('tmp:set:supply:list:'.$a,array('get'=>array('hash:supply:*->expire'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$updateTime=$this->redis->sort('tmp:set:supply:list:'.$a,array('get'=>array('hash:supply:*->updateTime'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$times=$this->redis->sort('tmp:set:supply:list:'.$a,array('get'=>array('hash:supply:*->times'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$state=$this->redis->sort('tmp:set:supply:list:'.$a,array('get'=>array('hash:supply:*->state'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			if(!empty($param['all'])){
				$content=$this->redis->sort('tmp:set:supply:list:'.$a,array('get'=>array('hash:supply:*->content'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				$number=$this->redis->sort('tmp:set:supply:list:'.$a,array('get'=>array('hash:supply:*->number'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			}

			$arr=array();
			$mold=$this->getSupplyType();
			$date=C('FIND_GOOD_EXPIRE');
			$check=C('FIND_GOODS_STATUS');
			$j=count($title);
			for($i=0;$i<$j;$i++){
				$arr[$i]['id']=$id[$i];
				$arr[$i]['title']=$title[$i];
				$arr[$i]['type']=$mold[$type[$i]];
				$arr[$i]['expire']=isset($date[$expire[$i]])?$date[$expire[$i]]:'';
				$arr[$i]['times']=$times[$i];
				$arr[$i]['state']=$check[$state[$i]];
				$arr[$i]['updateTime']=$updateTime[$i];
				if(!empty($param['all'])){
					$arr[$i]['content']=$content[$i];
					$arr[$i]['uid']=$param["Uid"];
					$arr[$i]['number']=$number[$i];
				}
			}
			$a='';
			if(!empty($filter)){
			    $a=http_build_query($filter);
			}
			$show= $this->showpage($count,$page,$pageSize,$a);
			$pageinfo['count']=$count;
			$pageinfo['page']=$page;
			
				
			
			$return=array('show'=>$show,'list'=>$arr,'pageinfo'=>$pageinfo);
		}
		return empty($return) ? array() : $return;
		
	}
	
	
	
	
	public function addSupply($param){
		//获取自增长ID
		$data=$param['data'];
		$data['id']=$this->redis->incr('string:supply');
		$member=$this->GetKeyMember($data['Uid']);
		$status=$this->GetKeyStatus(1);
		$state=$this->GetkeyState(2);
		//$country=$this->GetKeyCountry($param['country']);
		$hash=$this->GetHash($data['id']);
		$type=$this->GetKeyType($data['type']);
		for($i=0;$i<10;$i++){
			$this->redis->watch($member,$state,$status,$hash,$type);
			$this->redis->multi();
			$this->redis->sadd($member,$data['id']);
			$this->redis->sadd($state,$data['id']);
			$this->redis->sadd($status,$data['id']);
			$this->redis->sadd($type,$data['id']);
			$this->redis->set("string:supply:{$data['number']}",$data['id']);
			$this->redis->hMset($hash,$data);
			$res=$this->redis->exec();
			if($res){
				$this->shell->index('supply:title',strtolower($data['title']),$data['id']);
				$opera['id']=$this->redis->incr('string:supply:history');
				$history=$this->GetkeyHistory($data['id']);
				$opera['addTime']=time();
				$opera['opera']='You have created your supply offer!';
				$opera['reason']='';
				$opera['otype']=D('Member')->get($data['Uid'])['username'];
				$opera['oid']=$data['Uid'];
				$opera['state']=2;
				$str=serialize($opera);
				$this->redis->hset($history,$opera['id'],$str);
				break;
			}
		}
		return $res;
	}
	
	/**
	 * 删除supply 
	 */
	public function del($param){
		$member=$this->GetKeyMember($param['Uid']);
		$res=$this->redis->sDiff($member);
	
		for($i=0;$i<count($param['id']);$i++){
			if(!in_array($param['id'][$i], $res))return false;
		}
	
		$status0=$this->GetKeyStatus(0);
		$status1=$this->GetKeyStatus(1);
		//删除 status=1内的集合，新增status=0的集合
		for($i=0;$i<10;$i++){
			$this->redis->watch($status0,$status1);
			$this->redis->multi();
			foreach ($param['id'] as $v){
				$this->redis->srem($status1,$v);
				$this->redis->sadd($status0,$v);
			}
			$res=$this->redis->exec();
			if($res){
				$opera['id']=$this->redis->incr('string:supply:history');
				$history=$this->GetkeyHistory($opera['id']);
				$opera['addTime']=time();
				$opera['opera']='You have deleted your Supply offer!';
				$opera['reason']='';
				$opera['oid']=$param['Uid'];
				$opera['state']=2;
				$str=serialize($opera);
				$this->redis->hset($history,$opera['id'],$str);
				break;
			}
		}
		return $res;
	}
	
	
	
	/**
	 * 获取Supply Offer的类型
	 */
	public function getSupplyType(){
		$ret=array(
				'1' => 'Product Supply',
				'2' => 'Formula Supply',
				'3' => 'Patent Supply',
				'4' => "Technology Supply"
		);
		return $ret;
	}
	
	/**
	 * 获取某个用户Supply的总数
	 * @param $uid;
	 */
	
	public function getCount($uid){
		$supply=$this->GetKeyMember($uid);
		$status=$this->GetKeyStatus(1);
		$res=$this->redis->sInter($supply,$status);
		return count($res);
	}
	
	
	//获取删除状态值
	public function GetKeyStatus($status){
		return "set:supply:status:".$status;
	}
	
	//获取审核状态
	protected function GetkeyState($state){
		return "set:supply:state:".$state;
	}
	
	//获取历史操作记录
	protected function GetkeyHistory($id){
		return "hash:supply:operation:history:".$id;
	}
	
	//获取会员集合的key
	Public function GetKeyMember($uid){
		return "set:supply:member:".$uid;
	}
	
	//获取国家集合key
	protected function GetKeyCountry($country){
		return "set:supply:country:".$country;
	}
	
	//获取地区集合key
	protected function GetKeySeat($seat){
		return "set:supply:seat:".$seat;
	}
	
	//获取求购类型key
	protected function GetKeyType($type){
		return "set:supply:type:".$type;
	}
	
	protected function GetHash($id){
		return "hash:supply:".$id;
	}
	
	protected function showpage($count,$page,$pageSize=6,$filter='',$showPage=5){
		//计算总页数
		$total=ceil($count/$pageSize);
		if($total<=1)return ;
		$offset=($showPage-1)/2;
		//起始页和结束页
		$start=1;
		$end=$total;
		//分页代码
		$show='';
		if($page>=1){
			if($page==1){
				//$show.="<a href='javascript:void(0);' class='prev'><i class='icon-prev'></i>Previous Page</a>";
			}else {
				$show.="<a href='".U(ACTION_NAME)."?p=".($page-1).'&'.$filter."'class='prev'><i class='icon-prev'></i>Previous Page</a>";
			}
	
		}
	
		if($total>$showPage){
			if($page>$offset+1){
				$show.="<a href='javascript:void(0);' class='num'>……</a>";
				//$show.="……";
			}
	
			if($page>$offset){
				$start=$page-$offset;
				$end=$total>$page+$offset?$page+$offset:$total;
			}else{
				$start=1;
				$end=$total>$showPage?$showPage:$total;
			}
	
			if($page+$offset>$total){
				$start=$start-($page+$offset-$end);
			}
	
		}
		for ($i=$start;$i<=$end;$i++){
			if($i==$page){
				$show.="<a href='".U(ACTION_NAME)."?p=".$i.'&'.$filter."' class='current'>{$i}</a>";
			}else{
				$show.="<a href='".U(ACTION_NAME)."?p=".$i.'&'.$filter."' class='num'>{$i}</a>";
			}
				
		}
		if($total>$showPage&&$total>$page+$offset){
			$show.="<a href='javascript:void(0);' class='num'>……</a>";
			//$show.="……";
		}
	
		if($page<=$total){
			if($page+$offset<$total){
				if($end!=$total){
					$show.="<a href='".U(ACTION_NAME)."?p=".$total.'&'.$filter."' class='num'>{$total}</a>";
				}
	
			}
			if($page==$total){
				//$show.="<a href='javascript:void(0);' class='next'>Next Page<i class='icon-next'></i></a>";
			}else{
				$show.="<a href='".U(ACTION_NAME)."?p=".($page+1).'&'.$filter."' class='next'>Next Page<i class='icon-next'></i></a>";
			}
		}
		return $show;
	
	}
	
}



?>