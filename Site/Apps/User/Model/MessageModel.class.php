<?php
namespace User\Model;
use Think\Model;

/**
 * 消息模型
 */

class MessageModel extends Model{
	
	protected $_validate = array(
			array('id',   'require',  'Id cannot be blank!'),
			array('to',    'require',  'Receiver cannot be blank!'),
			array('content', 'require',  'Content cannot be blank!'),
			array('from',  'require',  'Sender cannot be blank!'),
			array('status',  'require',  'Status cannot be blank!'),
			array('sendTime',  'require',  'Sendtime cannot be blank!'),
			array('subject',  'require',  'Subject cannot be blank!')
			//array('read',  'require',  'Period of validity cannot be blank!')
	);
	
	//protected $redis;
	
	public function __construct(){
		//$this->redis = \Think\Cache::getInstance('Redis');
		//$this->shell=D('Shell');
		$this->autoCheckFields = false;
		//parent::__construct();
	}
	
	
	/**
	 * 标记为unread read
	 * @param array $id
	 * @param int $uid
	 * @param int $read=0 标记为未读  $read=1 标记为已读
	 */
	public function mark($uid,$id,$read){
		$receive=$this->getMessageReceive($uid);
		$read0=$this->getMessageRead(0);
		$read1=$this->getMessageRead(1);
		
		$redis = \Think\Cache::getInstance('Redis');
		$arr=$redis->sDiff($receive);
		foreach ($id as $v){
			if(!in_array($v, $arr)){
				return array('code'=>400,'msg'=>'参数错误');
			}
		}
		
		for($i=0;$i<10;$i++){
			$redis->watch($read0,$read1);
			$redis->multi();
			foreach ($id as $v){
				if($read==1){
					$redis->sRemove($read0,$read1,$v);
					$hash=$this->getMessageHash($v);
					$redis->hset($hash,'readTime',time());
				}elseif($read==0){
					$redis->srem($read1,$v);
					$redis->sadd($read0,$v);
					$hash=$this->getMessageHash($v);
					$redis->hset($hash,'readTime','');
				}	
			}
			if($redis->exec()){
				return array('code'=>200,'msg'=>'标记成功');
			}else{
				return array('code'=>400,'msg'=>'标记失败');
			}
			
		}
		
		
		
		
	}
	
	
	
	
	
	
	/**
	 * 创建系统消息
	 * @param array $param
	 * 		int    uid 		接收者ID
	 * 		String content  系统消息内容
	 * 		String sender   消息的发送者
	 */
	public function createSystem($param){
		$redis=\Think\Cache::getInstance('Redis');
		$data['to']=$param['uid'];
		$data['content']=$param['content'];
		$data['from']=$param['sender'];
		$data['status']=1;
		$data['sendTime']=time();
		$data['subject']='System Message';
		$data['readTime']='';
		$id=$redis->incr('string:system');
		$data['id']=$id;
		if($this->create($data)){
			$shell=D('User/Shell');
			$hash=$this->getSystemHash($id);
			$status=$this->getSystemStatus(1);
			$read=$this->getSystemRead(0);
			$setuid=$this->getSystemUid($data['to']);
			for($i=0;$i<10;$i++){
				$redis->watch($hash,$status,$read,$setuid);
				$redis->multi();
				$redis->hMset($hash,$data);
				$redis->sadd($status,$id);
				$redis->sadd($read,$id);
				$redis->sadd($setuid,$id);
				if($redis->exec()){
					return true;
				}else{
					continue;
				}
			}
			if($redis->exec())$shell->index('systemInfo:title',strtolower($data['content']),$id);
			return $redis->exec();
		}else{
			return false;
		}
	}
	
	/**
	 * 删除系统消息
	 * @param array $id
	 */
	public function delSystem($id){
		if(empty($id))return false;
		$return = false;
		$redis=\Think\Cache::getInstance('Redis');
		$statu1=$this->getSystemStatus(1);
		$statu0=$this->getSystemStatus(0);
		for($i=0;$i<10;$i++){
			foreach ($id as $v){
				$hash=$this->getSystemHash($v);
				$redis->watch($hash,$statu0,$statu1);
				$redis->multi();
				$redis->srem($statu1,$v);
				$redis->sadd($statu0,$v);
				$redis->hset($hash,'status','0');
				if(!$redis->exec()){
					$return =array('code'=>400,'msg'=>'fail');
					return $return;
				}
			}
			
		}
		if(!$return)$return=array('code'=>200,'msg'=>'success');
		return $return;
		
	}
	
	/**
	 * 系统信息列表
	 * @param array $param
	 * time,keyword,p,pageSize,uid
	 */
	
	public function systemList($param){
		$shell=D('Shell');
		$redis=\Think\Cache::getInstance('Redis');
		$page=empty($param['p'])?1:intval($param['p']);
		$pageSize=empty($param['pageSize'])?10:intval($page['pageSize']);
		$offset=($page-1)*$pageSize;
		$arr[]=$this->getSystemUid($param['uid']);
		$arr[]=$this->getSystemStatus(1);
		if(!empty($param['keyword'])){
			$filter['keyword']=$param['keyword'];
			$arr[]=$shell->search('systemInfo:title',strtolower($param['keyword']),'set');
		}
		//$hash=$this->getSystemHash($id);
		$tmp=uniqid();
		$tmpset=$redis->zInter("tmp:set:system:list:".$tmp,$arr);
		if($tmpset&&$redis->expire("tmp:set:system:list:".$tmp,20)){
			$count=$redis->zCard("tmp:set:system:list:".$tmp);
			$pageinfo['pagecount']=ceil($count/$pageSize);
			if($page>$pageinfo['pagecount']){
				$page=$pageinfo['pagecount'];
				$offset=intval(($page-1)*$pageSize);
			}
			if(!empty($filter)){
			    $a=http_build_query($filter);
			}else{
			    $a = '';
			}
			$show=$this->showpage($count, $page,$pageSize,$a);
			$pageinfo['count']=$count;
			$pageinfo['page']=$page;
			$id		 =$redis->sort('tmp:set:system:list:'.$tmp,array('get'=>array('hash:systemInfo:*->id'),'by'=>'hash:systemInfo:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$content =$redis->sort('tmp:set:system:list:'.$tmp,array('get'=>array('hash:systemInfo:*->content'),'by'=>'hash:systemInfo:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$from	 =$redis->sort('tmp:set:system:list:'.$tmp,array('get'=>array('hash:systemInfo:*->from'),'by'=>'hash:systemInfo:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$sendTime=$redis->sort('tmp:set:system:list:'.$tmp,array('get'=>array('hash:systemInfo:*->sendTime'),'by'=>'hash:systemInfo:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$arr=array();
			$i=count($id);
			for($k=0;$k<$i;$k++){
				$arr[$k]['id']=$id[$k];
				$arr[$k]['content']=$content[$k];
				$arr[$k]['from']=$from[$k];
				$arr[$k]['sendTime']=$sendTime[$k];
			}
			$read0=$this->getSystemRead(0);
			$read1=$this->getSystemRead(1);
			foreach ($id as $v){
				$hash=$this->getSystemHash($v);
				$redis->srem($read0,$v);
				$redis->sadd($read1,$v);
				$redis->hset($hash,"readTime",time());
			}
			
			$return=array('pageinfo'=>$pageinfo,'list'=>$arr,'show'=>$show);
		}
		
		return empty($return) ? array() : $return;
		
	}
	
	
	/**
	 * 创建站内信
	 * @param array $param
	 * from,to,content,subject,reply
	 */
	
	public function sendMessage($param){
		$redis=\Think\Cache::getInstance('Redis');
		$param['id']=$redis->incr('string:message');
		$param['status']=1;
		$param['sendTime']=time();
		if($this->create($param)){
			$status=$this->getMessageStatus($param['from'],1);
            $to_status=$this->getMessageStatus($param['to'],1);
			$read=$this->getMessageRead(0);
			$setuid=$this->getMessageUid($param['from']);
			$receive=$this->getMessageReceive($param['to']);
			$hash=$this->getMessageHash($param['id']);
			for($i=0;$i<10;$i++){
				$redis->watch($status,$read,$setuid,$receive,$hash);
				$redis->multi();
				$redis->sadd($status,$param['id']);
				$redis->sadd($setuid,$param['id']);
                $redis->sadd($to_status,$param['id']);
				$redis->sadd($read,$param['id']);
				$redis->sadd($receive,$param['id']);
				$redis->hMset($hash,$param);
				if($redis->exec()){
					$shell=D('Shell');
					$shell->index('Mailbox:title',$param['subject'],$param['id']);
					return array('code'=>200,'msg'=>'success');
				}
				
			}
			return array('code'=>400,'msg'=>'fail');
		}else{
			return array('code'=>400,'msg'=>'fail');
		}
		
	}
	
	/**
	 * 查看收到的站内信
	 * @param $param
	 * uid,id
	 */
	
	public function getReceiveMessage($param){
		$redis=\Think\Cache::getInstance('Redis');
		$setuid=$this->getMessageReceive($param['uid']);
		$status=$this->getMessageStatus($param['uid'],1);
		$res=$redis->sInter($setuid,$status);
		if(!in_array($param['id'], $res))return array('code'=>400,'msg'=>'The message is not exists!');
		$hash=$this->getMessageHash($param['id']);
		//设置成已读
		$read=$redis->sdiff($this->getMessageRead(0));
		//var_dump($read);exit;
		if(in_array($param['id'], $read)){
			$read1=$this->getMessageRead(1);
			$read0=$this->getMessageRead(0);
			for($i=0;$i<10;$i++){
				$redis->watch($hash,$read0,$read1);
				$redis->multi();
				$redis->sadd($read1,$param['id']);
				$redis->srem($read0,$param['id']);
				$redis->hset($hash,'readTime',time());
				if($redis->exec())break;
			}
		}
		$res=$redis->hGetAll($hash);
		$reply='';
		if(isset($res['reply'])){
			$hash=$this->getMessageHash($res['reply']);
			$reply=$redis->hGet($hash,'content');
		}
	
		return array('msg'=>$res,'reply'=>$reply);
	}
	
	/**
	 * 查看已发送的站内信
	 * @param array $param
	 * uid id
	 */
	public function getSendMessage($param){
		$redis=\Think\Cache::getInstance('Redis');
		$setuid=$this->getMessageUid($param['uid']);
		$status=$this->getMessageStatus($param['uid'],1);
		$res=$redis->sInter($setuid,$status);
		if(!in_array($param['id'], $res))return array('code'=>400,'msg'=>'The message is not exists!');
		$hash=$this->getMessageHash($param['id']);
		$res=$redis->hGetAll($hash);
		return $res;	
	}
	
	/**
	 * 获取未读的站内信数量
	 * @param int $uid
	 */
	public function getUnReadMessage($uid){
		$redis=\Think\Cache::getInstance('Redis');
		$read=$this->getMessageRead(0);
		$receive=$this->getMessageReceive($uid);
		$status=$this->getMessageStatus($uid,1);
		//$setuid=$this->getMessageUid($uid);//获取的是已发送的
		
		$res=$redis->sinter($read,$receive,$status);
		return count($res);
	}
	
	
	/**
	 * 获取未读的系统信息
	 * @param  $uid
	 */
	public function getUnReadSystem($uid){
		$redis=\Think\Cache::getInstance('Redis');
		$read=$this->getSystemRead(0);
		$setuid=$this->getSystemUid($uid);
		$status=$this->getSystemStatus(1);
		$res=$redis->sinter($read,$setuid,$status);
		return count($res);
	}
	
	/**
	 * 获取message列表
	 * @param array $param
	 * uid  send:1、inbox,2、send  read:1、readed,2、unread   keyword p  pageSize
	 */
	public function getMessageList($param){
		$page=empty($param['p'])?1:intval($param['p']);
		$pageSize=empty($param['pageSize'])?10:intval($param['pageSize']);
		$offset=($page-1)*$pageSize;
		$arr=array();
		if(!isset($param['uid']))return array();
		isset($param['send'])?$param['send']:'';
		$arr[]=$this->getMessageStatus($param['uid'],1);
		if(!empty($param['send']) && $param['send']!=1){
		    if($param['send']==2){
    			$arr[]=$this->getMessageUid($param['uid']);
    			$filter['send']=2;
		    }
		}else{
			$arr[]=$this->getMessageReceive($param['uid']);
		}

		if(!empty($param['read'])){
			if($param['read']==1){
				$filter['read']=1;
				$arr[]=$this->getMessageRead(1);
			}elseif($param['read']==2){
				$filter['read']=2;
				$arr[]=$this->getMessageRead(0);
			}
		}
		if(!empty($param['keyword'])){
			$filter['keyword']=$param['keyword'];
			$arr[]=D('Shell')->search('Mailbox:title',$param['keyword'],'set');
		}
		$redis=\Think\Cache::getInstance('Redis');
		$tmp=uniqid();
		
		$tmpset=$redis->zInter('tmp:set:message:list:'.$tmp,$arr);

		if($tmpset&&$redis->expire('tmp:set:message:list:'.$tmp,60)){
			$count=$redis->zCard("tmp:set:message:list:".$tmp);
			if($page>ceil($count/$pageSize))$page=ceil($count/$pageSize);
			if(!empty($filter)){
			    $a=http_build_query($filter);
			}else{
			    $a = '';
			}
			$show=$this->showpage($count, $page,$pageSize,$a);
			$pageinfo['count']=$count;

			$pageinfo['pagecount']=ceil($count/$pageSize);
			if($page>$pageinfo['pagecount']){
				$page=$pageinfo['pagecount'];
				$offset=intval(($page-1)*$pageSize);
			}
			$pageinfo['page']=$page;
			$id		 =$redis->sort('tmp:set:message:list:'.$tmp,array('get'=>array('hash:message:*->id'),'by'=>'hash:message:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));	
			$from	 =$redis->sort('tmp:set:message:list:'.$tmp,array('get'=>array('hash:message:*->from'),'by'=>'hash:message:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$subject =$redis->sort('tmp:set:message:list:'.$tmp,array('get'=>array('hash:message:*->subject'),'by'=>'hash:message:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$send	 =$redis->sort('tmp:set:message:list:'.$tmp,array('get'=>array('hash:message:*->sendTime'),'by'=>'hash:message:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$read	 =$redis->sort('tmp:set:message:list:'.$tmp,array('get'=>array('hash:message:*->readTime'),'by'=>'hash:message:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$to	 =$redis->sort('tmp:set:message:list:'.$tmp,array('get'=>array('hash:message:*->to'),'by'=>'hash:message:*->sendTime','sort'=>'desc','limit'=>array($offset,$pageSize)));
			$arr=array();
			$i=count($id);
			for($k=0;$k<$i;$k++){
				$arr[$k]['id']=$id[$k];
				$arr[$k]['from']=D('Member')->get($from[$k])['username'];
				//$arr[$k]['from']=$from[$k];
				$arr[$k]['subject']=$subject[$k];
				$arr[$k]['send']=$send[$k];
				$arr[$k]['read']=$read[$k];
				$arr[$k]['to']=D('Member')->get($to[$k])['username'];
			}
			return array('pageinfo'=>$pageinfo,'list'=>$arr,'show'=>$show);
		}else{
			return array();
		}
		
	}
	
	/**
	 * 根据站内信的ID获取详细信息
	 * $param $uid,$id
	 */
	
	public function getMessage($id){
		$hash=$this->getMessageHash($id);
		$redis=\Think\Cache::getInstance('Redis');
		$res=$redis->hGetAll($hash);
		if($res){
			return $res;
		}else{
			return false;
		}
	}

	/**
	 * 删除站内信
	 * @param array $param
	 * uid int
	 * id array
	 */
	public function delinbox($param){
		$return = '';
		$receive=$this->getMessageReceive($param['uid']);
		$status0=$this->getMessageStatus($param['uid'],0);
		$status1=$this->getMessageStatus($param['uid'],1);
		$redis=\Think\Cache::getInstance('Redis');
		$arr=$redis->sDiff($receive);
		foreach ($param['id'] as $v){
			if(!in_array($v, $arr)){
				$return =array('code'=>400,'msg'=>'fail');
				return $return;
			}
		}
		
		for($i=0;$i<10;$i++){
			foreach ($param['id'] as $v){
				$hash=$this->getMessageHash($v);
				$redis->watch($hash,$status0,$status1);
				$redis->multi();
				$redis->srem($status1,$v);
				$redis->sadd($status0,$v);
				$redis->hset($hash,'status','0');
				if(!$redis->exec()){
					$return =array('code'=>400,'msg'=>'fail');
					return $return;
				}
			}
		}
		if(!$return)$return=array('code'=>200,'msg'=>'success','data'=>array('url'=>U('/User/Message/mailBox',array('send'=>1))));
		return $return;
	}
	

	/**
	 * 删除已发送的站内信
	 * @param array $param
	 * uid int
	 * id array
	 */
	public function delSend($param){
		$return = '';
		$setuid=$this->getMessageUid($param['uid']);
		$status0=$this->getMessageStatus($param['uid'],0);
		$status1=$this->getMessageStatus($param['uid'],1);
		$redis=\Think\Cache::getInstance('Redis');
		$arr=$redis->sDiff($setuid);
		foreach ($param['id'] as $v){
			if(!in_array($v, $arr)){
				$return =array('code'=>400,'msg'=>'fail');
				return $return;
			}
		}
		
		$redis=\Think\Cache::getInstance('Redis');
		for($i=0;$i<10;$i++){
			$redis->multi();
			foreach ($param['id'] as $v){
				$hash=$this->getMessageHash($v);
				$redis->watch($hash,$status0,$status1);
				$redis->srem($status1,$v);
				$redis->sadd($status0,$v);
				$redis->hset($hash,'status','0');
			}
			if(!$redis->exec()){
				$return =array('code'=>400,'msg'=>'fail');
				return $return;
			}
		}
		if(!$return)$return=array('code'=>200,'msg'=>'success','data'=>array('url'=>U('/User/Message/mailBox',array('send'=>2))));
		return $return;
	}
	
	
	/**
	 * 获取系统信息hash
	 */
	protected function getSystemHash($id){
		return "hash:systemInfo:".$id;
	}
	
	/**
	 * 获取系统信息状态
	 */
	protected function getSystemStatus($status){
		return "set:system:status:".$status;
	}
	
	/**
	 * 获取系统信息的阅读状态
	 */
	protected function getSystemRead($state){
		return "set:system:read:".$state;
	}
	
	/**
	 * 获取系统信息的用户关联集合
	 */
	protected function getSystemUid($uid){
		return "set:system:".$uid;
	}
	
	/**
	 * 获取站内信的hash
	 */
	protected function getMessageHash($id){
		return "hash:message:".$id;
	}

    /*
     * $id =>id
     * $pram =>array()
     * */
      public function GetSendUid( $id,$pram ){
           $ret = false;
          if( empty( $id ) ){
              return $ret;
          }
          $redis=\Think\Cache::getInstance('Redis');
          $cacheKeys = $this->getMessageHash( $id );
          $ret = $redis->hmget( $cacheKeys,$pram );
          return $ret;
      }
	/**
	 * 获取站内信阅读状态
	 */
	protected function getMessageRead($status){
		return "set:message:read:".$status;
	}
	
	/**
	 * 获取站内信删除状态
	 */
	protected function getMessageStatus($uid,$status){
		return "set:message:status:{$uid}:".$status;
	}
	
	/**
	 * 获取站内信的发送情况
	 */
	
	protected function getMessageReceive($state){
		return "set:message:receive:".$state;
	}
	
	/**
	 * 获取站内信与用户关联
	 */
	protected function getMessageUid($uid){
		return "set:message:".$uid;
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
				//$show.="<a href='javascript:void(0);'&nbsp class='prev'><i class='icon-prev'></i>Previous Page</a>";
			}else {
				$show.="<a href='".U(ACTION_NAME)."?p=".($page-1).'&'.$filter."'&nbsp class='prev'><i class='icon-prev'></i>Previous Page</a>";
			}
				
		}
	
		if($total>$showPage){
			if($page>$offset+1){
				$show.="<a href='javascript:void(0);'&nbsp class='num'>……</a>";
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
						$show.="<a href='".U(ACTION_NAME)."?p=".$i.'&'.$filter." '&nbsp class='current'>{$i}</a>";
					}else{
						$show.="<a href='".U(ACTION_NAME)."?p=".$i.'&'.$filter." '&nbsp class='num'>{$i}</a>";
					}
		}
		if($total>$showPage&&$total>$page+$offset){
			$show.="<a href='javascript:void(0);'&nbsp class='num'>……</a>";
			//$show.="……";
		}
	
		if($page<=$total){
			if($page+$offset<$total){
				if($end!=$total){
					$show.="<a href='".U(ACTION_NAME)."?p=".$total.'&'.$filter." '&nbsp class='num'>{$total}</a>";
				}
	
			}
			if($page==$total){
				//$show.="<a href='javascript:void(0);'&nbsp class='next'>Next Page<i class='icon-next'></i></a>";
			}else{
				$show.="<a href='".U(ACTION_NAME)."?p=".($page+1).'&'.$filter." '&nbsp class='next'>Next Page<i class='icon-next'></i></a>";
			}
		}
		return $show;
	
	}
	
	
	
	
}
