<?php
namespace Home\Model;
use Think\Model;


class SupplyModel extends Model{
	
	public function __construct(){
		    $this->autoCheckFields = false;
			$this->redis = \Think\Cache::getInstance('Redis');
			$this->shell=D('shell');
		}
	
		/**
		 * 带详情的求购列表
		 */
		public function detailList($pageSize=6){
			$offset=0;
			$arr[]=$status=$this->GetKeyStatus(1);
			$arr[]=$state=$this->GetkeyState(1);
			$tmp=uniqid();
			$tmpset=$this->redis->zInter('tmp:set:supply:list:'.$tmp,$arr);
			if($tmpset &&$this->redis->expire('tmp:set:supply:list:'.$tmp,60)){
				$uid=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->Uid'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				$id=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->id'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				$title=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->title'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				//$type=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->type'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				//$expire=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->expire'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				$updateTime=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->updateTime'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				//$times=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->times'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				//$state=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->state'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				$content=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->content'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				$uid=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->Uid'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
				$arr=array();
				//$mold=C("FIND_GOODS_TYPE");
				//$date=C('FIND_GOOD_EXPIRE');
				//$check=C('FIND_GOODS_STATUS');
				$j=count($title);
				for($i=0;$i<$j;$i++){
					$arr[$i]['id']=$id[$i];
					$arr[$i]['title']=$title[$i];
					//$arr[$i]['type']=$mold[$type[$i]];
					//$arr[$i]['expire']=$date[$expire[$i]];
					//$arr[$i]['times']=$times[$i];
					//$arr[$i]['state']=$check[$state[$i]];
					$other=D('User/Account')->SelectAccountInfo($uid[$i],array('other'))['other'];
					$arr[$i]['location']=D('User/Account')->GetAreaTitle($other['area_c'],array('title'))['title'].' '.D('User/Account')->GetAreaTitle($other['area_s'],array('title'))['title'].' '.
							D('User/Account')->GetAreaTitle($other['country'],array('title'))['title'];
					$arr[$i]['updateTime']=$updateTime[$i];
					$arr[$i]['content']=$content[$i];
					$arr[$i]['img']=D('User/Member')->get($uid[$i])['img'];
					$arr[$i]['username']=D('User/Member')->get($uid[$i])['username'];
				}
				$return=array('list'=>$arr);
			}
			return $return;
		}

		
		
	/**
	 * 首页求购列表
	 */
	public function indexlist($param=''){
		
		//条件：有效，未删除
		$pageSize=isset($param['pageSize'])?$param['pageSize']:10;
		$page=isset($param['p'])?$param['p']:1;
		//计算偏移量
		$offset=($page-1)*$pageSize;
		$arr[]=$status=$this->GetKeyStatus(1);
		if(!empty($param['type'])){
			$filter['type']=$param['type'];
			$type=explode(',', $param['type']);
			foreach ($type as $v){
				$tarr[]=$this->GetKeyType($v);
			}
			$t=uniqid();
			$this->redis->zUnion("tmp:set:supply:Union:".$t,$tarr);
			$arr[]="tmp:set:supply:Union:".$t;
		}
		$arr[]=$state=$this->GetkeyState(1);
		if(!empty($param['country']))$arr[]=$country=$this->GetKeyCountry($param['country']);
		if(!empty($param['title'])){
			$filter['title']=$param['title'];
			$arr[]=$title=$this->shell->search('supply:title',strtolower($param['title']),'set');
		}
		
		$tmp=uniqid();
		$tmpset=$this->redis->zInter('tmp:set:supply:list:'.$tmp,$arr);
		if($tmpset &&$this->redis->expire('tmp:set:supply:list:'.$tmp,60)){
			$count=$this->redis->zcard('tmp:set:supply:list:'.$tmp);
			//分页程序
			$a='';
			if(!empty($filter))$a=http_build_query($filter);
			$show=$this->showpage($count, $page,$pageSize,$a);
			$pageinfo['count']=$count;
			$pageinfo['page']=$page;
			$pageinfo['pagecount']=ceil($count/$pageSize);
			$uid=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->Uid'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$id=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->id'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$title=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->title'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$type=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->type'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$expire=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->expire'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$updateTime=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->updateTime'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$times=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->times'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$state=$this->redis->sort('tmp:set:supply:list:'.$tmp,array('get'=>array('hash:supply:*->state'),'by'=>'hash:supply:*->updateTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$arr=array();
			$mold=D("User/Supply")->getSupplyType();
			$date=C('FIND_GOOD_EXPIRE');
			$check=C('FIND_GOODS_STATUS');
			$j=count($title);
			for($i=0;$i<$j;$i++){
				$arr[$i]['id']=$id[$i];
				$other=D('User/Account')->SelectAccountInfo($uid[$i],array('other'))['other'];
				//var_dump($other);exit;
				$arr[$i]['location']=D('User/Account')->GetAreaTitle($other['area_c'],array('title'))['title'].' '.D('User/Account')->GetAreaTitle($other['area_s'],array('title'))['title'].' '.
						D('User/Account')->GetAreaTitle($other['country'],array('title'))['title'];
				$arr[$i]['title']=$title[$i];
				$arr[$i]['type']=$mold[$type[$i]];
				$arr[$i]['expire']=$date[$expire[$i]];
				$arr[$i]['times']=$times[$i];
				$arr[$i]['state']=$check[$state[$i]];
				$arr[$i]['updateTime']=$updateTime[$i];
			}
			$return=array('show'=>$show,'list'=>$arr,'pageinfo'=>$pageinfo);
		}
		return empty($return)?array():$return;
		
	}
		
		
		/**
		 * 用户查看求购详情
		 * 
		 */
	public function details($param){
		if(!empty($param['uid'])){
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
		$other=D('User/Account')->SelectAccountInfo($res['Uid'],array('other'))['other'];
		$res['location']=D('User/Account')->GetAreaTitle($other['area_c'],array('title'))['title'].' '.D('User/Account')->GetAreaTitle($other['area_s'],array('title'))['title'].' '.
				D('User/Account')->GetAreaTitle($other['country'],array('title'))['title'];
		return $res;
		
	}
		
	
	
	
	//获取删除状态值
	protected function GetKeyStatus($status){
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
	protected function GetKeyMember($uid){
		return "set: supply:member:".$uid;
	}
	
	//获取国家集合key
	protected function GetKeyCountry($country){
		return "set: supply:country:".$country;
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
					$show.="<a href='".U(ACTION_NAME)."?p=".$i.'&'.$filter." 'class='current'>{$i}</a>";
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