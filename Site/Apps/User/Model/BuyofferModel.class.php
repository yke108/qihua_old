<?php
namespace User\Model;
use Think\Model;


class BuyofferModel extends Model{
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
		 * 操作历史
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
		krsort($arr);

		$page=empty($param['p'])?1:intval($param['p']);
		$page=$page<1?1:$page;
		$page=$page>ceil(count($arr)/$limit)?ceil(count($arr)/$limit):$page;
		
		$offset=($page-1)*$limit;
		unset($param['p']);
		$filter=http_build_query($param);
		$res=array_slice($arr, $offset,$limit);
		$state=C('FIND_GOODS_OPERATION');
		for ($i=0;$i<$limit;$i++){
			if(empty($res[$i])){
				break;
			}
			$res[$i]=unserialize($res[$i]);
			$res[$i]['state']=$state[$res[$i]['state']];
		}
		$show=$this->showpage(count($arr), $page,$limit,$filter);
		return array('res'=>$res,'show'=>$show);
		
	}
		
	
	
	/**
	 * 首页求购列表
	 */
	public function indexlist($param=''){
		//条件：有效，未删除
		$pageSize=isset($param['pageSize'])?$param['pageSize']:1;
		$page=isset($param['p'])?$param['p']:1;
		//计算偏移量
		$offset=($page-1)*$pageSize;
		//$limit=$pageSize;
		
		$arr[]=$status=$this->GetKeyStatus(1);
		if($param['type'])$arr[]=$type=$this->GetKeyType($param['type']);
		$arr[]=$state=$this->GetkeyState(1);
		if($param['country'])$arr[]=$country=$this->GetKeyCountry($param['country']);
		if($param['title'])$arr[]=$title=$this->shell->search('buyoffer:title',$param['title'],'set');
		$tmp=uniqid();
		$tmpset=$this->redis->zInter('tmp:set:buyoffer:list:'.$tmp,$arr);
		//var_dump($arr);exit;
		if($tmpset &&$this->redis->expire('tmp:set:buyoffer:list:'.$tmp,60)){
			$count=$this->redis->zcard('tmp:set:buyoffer:list:'.$tmp);
			//分页程序
			$show=$this->showpage($count, $page,$pageSize);
			$pageinfo['count']=$count;
			$pageinfo['page']=$page;
			$pageinfo['pagecount']=ceil($count/$pageSize);
			$id=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->id'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$title=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->title'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$type=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->type'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$expire=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->expire'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$updateTime=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->updateTime'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$times=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->times'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$state=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->state'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$arr=array();
			$mold=C("FIND_GOODS_TYPE");
			$date=C('FIND_GOOD_EXPIRE');
			$check=C('FIND_GOODS_STATUS');
			$j=count($title);
			for($i=0;$i<$j;$i++){
				$arr[$i]['id']=$id[$i];
				$arr[$i]['title']=$title[$i];
				$arr[$i]['type']=$mold[$type[$i]];
				$arr[$i]['expire']=$date[$expire[$i]];
				$arr[$i]['times']=$times[$i];
				$arr[$i]['state']=$check[$state[$i]];
				$arr[$i]['updateTime']=$updateTime[$i];
			}
			$return=array('show'=>$show,'list'=>$arr,'pageinfo'=>$pageinfo);
		}
		return $return;
		
	}
		
		
		/**
		 * 用户查看求购详情
		 * 
		 */
	public function details($param){
		if($param['uid']){
			$member=$this->GetKeyMember($param['uid']);
			//用户只能查看自己的求购
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
		//被删除的求购不可以查看
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

    /*
    * 获取状态为待审核不通过或撤销通过的操作历史
    */
    public function getOneHistory( $id ){
        if( empty($id) ){
            return false;
        }
        $HistoryCacheKeys = $this->GetBuyOfferOperaCacheKeys( $id );
        $arr = $this->redis->hGetAll($HistoryCacheKeys);
        ksort($arr);
        $arr = unserialize(end($arr));
        return $arr;
    }

    public function GetBuyOfferOperaCacheKeys( $id ){
        return "hash:buyoffer:operation:history:{$id}";
    }
		
		
		/**
		 * 新增求购
		 * @array $param
		 * 包含$data
		 */
	public function addBuyoffer($param){
		//获取自增长ID
		$data=$param['data'];
		$data['id']=$this->redis->incr('string:buyoffer');
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
            $this->redis->set("string:buyoffer:{$data['number']}",$data['id']);
			$this->redis->hMset($hash,$data);
			$res=$this->redis->exec();
			if($res){
				$this->shell->index('buyoffer:title',strtolower($data['title']),$data['id']);
				$opera['id']=$this->redis->incr('string:buyoffer:history');
				$history=$this->GetkeyHistory($data['id']);
                $opera['addTime']=time();
                $opera['opera']='You have created buy offer!';
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
	 * 修改求购
	 * @param array $param
	 * $paray
	 */
    public function modify($param) {
        $data = $param['data'];
        $data['state'] = 2;
        $hash = $this->GetHash($data['id']);
        $uid = $this->redis->hget($hash, 'Uid');
        if ($param['uid'] != $uid) {
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
        $this->redis->hmset($hash, $data);

        $this->shell->index('buyoffer:title', strtolower($data['title']), $data['id']);
        $opera['id'] = $this->redis->incr('string:buyoffer:history');
        $history = $this->GetkeyHistory($data['id']);
        $opera['addTime'] = time();
        $opera['opera'] = 'You have updated buy offer!';
        $opera['reason'] = '';
        $opera['otype'] = D('Member')->get($uid)['username'];
        $opera['oid'] = $uid;
        $opera['state'] = 2;
        $str = serialize($opera);
        $this->redis->hset($history, $opera['id'], $str);

        return true;
    }

	/**
	 * 求购列表
	 * @param $param
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
		if(!empty($param['state'])||$param['state']=='0'){
			$arr[]=$this->GetkeyState($param['state']);
			$filter['state']=$param['state'];
		}
		if(!empty($param['country'])){
			$arr[]=$country=$this->GetKeyCountry($param['country']);
			$filter['country']=$param['country'];
		}
		if(!empty($param['title'])){
			$arr[]=$this->shell->search('buyoffer:title',strtolower($param['title']),'set');
			$filter['title']=$param['title'];
		}
		
		if(!empty($param['all'])){
			$filter['uid']=$param['Uid'];
		}
		
		$tmp=uniqid();
		$tmpset=$this->redis->zInter('tmp:set:buyoffer:list:'.$tmp,$arr);
		if($tmpset &&$this->redis->expire('tmp:set:buyoffer:list:'.$tmp,60)){
			$count=$this->redis->zcard('tmp:set:buyoffer:list:'.$tmp);
			$pageinfo['pagecount']=ceil($count/$pageSize);
			if($page>$pageinfo['pagecount']){
				$page=$pageinfo['pagecount'];
				$offset=intval(($page-1)*$pageSize);
			}
			$a='';
			if(!empty($filter)){
			    $a=http_build_query($filter);
			}
			$show= $this->showpage($count,$page,$pageSize,$a);
			
			$pageinfo['count']=$count;
			$pageinfo['page']=$page;
			//$pageinfo['pagecount']=ceil($count/$pageSize);
			
			$id=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->id'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$title=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->title'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$type=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->type'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$expire=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->expire'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$updateTime=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->updateTime'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$times=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->times'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$state=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->state'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			if(!empty($param['all'])){
				$content=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->content'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				$number=$this->redis->sort('tmp:set:buyoffer:list:'.$tmp,array('get'=>array('hash:buyoffer:*->number'),'by'=>'hash:buyoffer:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			}
			
			$arr=array();
			$mold=C("FIND_GOODS_TYPE");
			$date=C('FIND_GOOD_EXPIRE');
			$check=C('FIND_GOODS_STATUS');
			$j=count($title);
			for($i=0;$i<$j;$i++){
				$arr[$i]['id']=$id[$i];
				$arr[$i]['title']=$title[$i];
				$arr[$i]['type']=$mold[$type[$i]];
				$arr[$i]['expire']=isset($date[$expire[$i]]) ?: $date[1];
				$arr[$i]['times']=$times[$i];
				$arr[$i]['state']=$check[$state[$i]];
				$arr[$i]['updateTime']=$updateTime[$i];
				if(!empty($param['all'])){
					$arr[$i]['content']=$content[$i];
					$arr[$i]['uid']=$param["Uid"];
					$arr[$i]['number']=$number[$i];
				}
			}
			$return=array('show'=>$show,'list'=>$arr,'pageinfo'=>$pageinfo);
		}
		return empty($return) ? array() : $return;
	}
	
	
	/**
	 * 删除求购
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
				$opera['id']=$this->redis->incr('string:buyoffer:history');
				$history=$this->GetkeyHistory($opera['id']);
				$opera['addTime']=time();
				$opera['opera']='You have deleted buy offer!';
				$opera['reason']='';
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
	 * 获取某个用户求购的总数
	 * @param $uid;
	 */
	
	public function getCount($uid){
		$buyoffer=$this->GetKeyMember($uid);
		$status=$this->GetKeyStatus(1);
		$res=$this->redis->sInter($buyoffer,$status);
		return count($res);
	}
	
	
	//获取删除状态值
	public function GetKeyStatus($status){
		return "set:buyoffer:status:".$status;
	}
	
	//获取审核状态
	protected function GetkeyState($state){
		return "set:buyoffer:state:".$state;
	}
	
	//获取历史操作记录
	protected function GetkeyHistory($id){
		return "hash:buyoffer:operation:history:".$id;
	}
	
	//获取会员集合的key
	public function GetKeyMember($uid){
		return "set:buyoffer:member:".$uid;
	}
	
	//获取国家集合key
	protected function GetKeyCountry($country){
		return "set:buyoffer:country:".$country;
	}
	
	//获取地区集合key
	protected function GetKeySeat($seat){
		return "set:buyoffer:seat:".$seat;
	}
	
	//获取求购类型key
	protected function GetKeyType($type){
		return "set:buyoffer:type:".$type;
	}
	
	protected function GetHash($id){
		return "hash:buyoffer:".$id;
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