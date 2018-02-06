<?php
namespace Admin\Model;
use Think\Model;
use Think\Cache\Driver\Redis;

class MemberSignModel extends Model {
	protected $_validate = array(
		array('code','require','合同编号必须填写'),
		array('cooperation','require','合作年度必须填写'),
		array('signatory','require','签约人必须填写'),
		array('contractTime ','require','合同签约时间必须填写'),
		array('expireTime ','require','合同到期时间必须填写'),
	);

	//sign->供应商签约记录
	//读取历史操作记录
	public function getHistoryList($id){
		$redis = new redis();
		//读取历史操作
		$historyInfo = $redis->hGetAll('hash:member:sign:operation:history:'.$id);
		if($historyInfo){
			$user=D('User');
			foreach($historyInfo as $key=>$vo){
				$info[$key]=unserialize($vo);
				$info[$key]['operaName']=$user->getUserName($info[$key]['oid']);
			}
			return $info;
		}
	}

	//读取公司信息详情页
	public function getSignDetail($id){
		$redis = new redis();
		$companyInfo = $redis->hMGet('hash:member:info:'.$id,array(
			'id',
			'companyName',
		));

		$signInfo = $redis->hMGet('hash:member:sign:'.$id,array(
			'id',
			'code',
			'signatory',
			'cooperation',
			'contractTime',
			'expireTime',
			'content',
			'attachment',
		));

		$info['id'] = $companyInfo['id'];
		$info['companyName'] = $companyInfo['companyName'];
		$info['code'] = $signInfo['code'];
		$info['signatory'] = $signInfo['signatory'];
		$info['cooperation'] = $signInfo['cooperation'];
		$info['contractTime'] = $signInfo['contractTime'];
		$info['expireTime'] = $signInfo['expireTime'];
		$info['content'] = $signInfo['content'];
		$tmpAttachmentArr = unserialize($signInfo['attachment']);
		$info['attachment'] = $tmpAttachmentArr;

		$user=D('User');

		//去除历史操作
		$signHistoryInfo = $redis->hGetAll('hash:member:sign:operation:history:'.$id);
		if($signHistoryInfo){
			foreach($signHistoryInfo as $key=>$vo){
				$info['history'][$key]=unserialize($vo);
				$info['history'][$key]['operaName']=$user->getUserName($info['history'][$key]['oid']);
			}
		}
//		print_r($info);exit;
		return $info;
	}

	//根据公司名读取uid
	public function companyGetUid($companyName){
		$redis = new redis();
		$uid = $redis->get('string:company:'.$companyName);
		return $uid;
	}

	//添加签约信息
	public function memberSignAdd($data){
		$redis=new redis();

		if(!empty($data['attachment'])){
			$attachmentArr = explode(',',$data['attachment']);
			$attachment = serialize($attachmentArr);
		}else{
			$attachment='';
		}

		$memberSignData=$redis->hmset("hash:member:sign:{$data['uid']}",array(
			'id'=>$data['uid'],
			'code'=>$data['code'],
			'cooperation'=>$data['cooperation'],
			'contractTime'=>$data['contractTime'],
			'expireTime'=>$data['expireTime'],
			'signatory'=>$data['signatory'],
			'content'=>$data['content'],
			'attachment'=>$attachment,
			'addTime'=>$data['addTime'],
			'state'=>2));
		if($memberSignData){
			$redis->sadd('set:member:sign:state:2',$data['uid']);
			$redis->sadd('set:member:sign:status:1',$data['uid']);
			$redis->sadd('set:member:sign:code:'.$data['code'],$data['uid']);
			$redis->zadd('zset:member:sign:cooperation',$data['cooperation'],$data['uid']);
			$redis->zadd('zset:member:sign:contractTime',$data['contractTime'],$data['uid']);
			$redis->zadd('zset:member:sign:expireTime',$data['expireTime'],$data['uid']);

			$historyId = $redis->hLen("hash:member:sign:operation:history:".$data['uid'])+1;
			$historyArr=array('id'=>$historyId,'addTime'=>time(),'opera'=>'录入商家签约信息','oid'=>session('userid'),'uid'=>$data['uid']);
			$memberSignHistory = $redis->hset("hash:member:sign:operation:history:".$data['uid'],$historyId,serialize($historyArr));
			if($memberSignHistory)return true;
		}
	}


	//修改签约信息
	public function memberSignSave($data){
		$redis=new redis();
		if(!empty($data['attachment'])){
			$attachmentArr = explode(',',$data['attachment']);
			$attachment = serialize($attachmentArr);
		}else{
			$attachment='';
		}

		$memberSignData=$redis->hmset("hash:member:sign:{$data['uid']}",array(
			'id'=>$data['uid'],
			'code'=>$data['code'],
			'cooperation'=>$data['cooperation'],
			'contractTime'=>$data['contractTime'],
			'expireTime'=>$data['expireTime'],
			'signatory'=>$data['signatory'],
			'content'=>$data['content'],
			'attachment'=>$attachment,
			'addTime'=>$data['addTime'],
			'state'=>2));
		if($memberSignData){
		    //删除旧签约信息集合
            $redis->srem('set:member:sign:state:0',$data['uid']);
            $redis->srem('set:member:sign:state:1',$data['uid']);
            $redis->srem('set:member:sign:state:2',$data['uid']);
            $redis->srem('set:member:sign:state:3',$data['uid']);
            $redis->srem('set:member:sign:state:4',$data['uid']);

			$redis->sadd('set:member:sign:state:2',$data['uid']);
			$redis->sadd('set:member:sign:status:1',$data['uid']);
			$redis->sadd('set:member:sign:code:'.$data['code'],$data['uid']);
			$redis->zadd('zset:member:sign:cooperation',$data['cooperation'],$data['uid']);
			$redis->zadd('zset:member:sign:contractTime',$data['contractTime'],$data['uid']);
			$redis->zadd('zset:member:sign:expireTime',$data['expireTime'],$data['uid']);

			$historyId = $redis->hLen("hash:member:sign:operation:history:".$data['uid'])+1;
			$historyArr=array('id'=>$historyId,'addTime'=>time(),'opera'=>'录入商家签约信息','oid'=>session('userid'),'uid'=>$data['uid']);
			$memberSignHistory = $redis->hset("hash:member:sign:operation:history:".$data['uid'],$historyId,serialize($historyArr));
			if($memberSignHistory)return true;
		}
	}

	public function signVerifyUpdate($id,$prevState,$state,$reason=''){
		$redis = new redis();
//			$redis->pipeline();
		$redis->hSet('hash:member:sign:'.$id,'state',$state);
        $redis->srem('set:member:sign:state:0',$id);
        $redis->srem('set:member:sign:state:1',$id);
        $redis->srem('set:member:sign:state:2',$id);
        $redis->srem('set:member:sign:state:3',$id);
        $redis->srem('set:member:sign:state:4',$id  );
		//$redis->sRem('set:member:sign:state:'.$prevState,$id);
		$redis->sAdd('set:member:sign:state:'.$state,$id);

		$insertId = $redis->hLen('hash:member:sign:operation:history:'.$id)+1;
		$arr['id']=$insertId;
		$arr['addTime']=time();
		if($prevState==1)$arr['opera']='撤销通过签约认证';
		elseif($prevState==2 && $state==1)$arr['opera']='审核通过签约认证';
		elseif($prevState==2 && $state==0)$arr['opera']='审核不通过签约认证';
		elseif($prevState==3)$arr['opera']='恢复通过签约认证';
		elseif($prevState==0)$arr['opera']='审核通过签约认证';
		$arr['oid']=session('userid');
		$arr['state']=$state;
		if(!empty($reason))$arr['reason']=$reason;
		$redis->hSet('hash:member:sign:operation:history:'.$id,$insertId,serialize($arr));

//			$result = $redis->exec();
		return true;
	}

	public function companyVerifyUpdate($id,$prevState,$state,$reason=''){
		$redis = new redis();
		if($id && $prevState && $state){
//			$redis->pipeline();
			$redis->hSet('hash:member:sign:'.$id,'state',$state);
			$redis->sRem('set:member:sign::state:'.$prevState,$id);
			$redis->sAdd('set:member:sign::state:'.$state,$id);

			$insertId = $redis->hLen('hash:member:sign:operation:history:'.$id)+1;
			$arr['id']=$insertId;
			$arr['addTime']=time();
			if($prevState==1)$arr['opera']='撤销通过签约认证';
			elseif($prevState==2 && $state==1)$arr['opera']='审核通过签约认证';
			elseif($prevState==2 && $state==0)$arr['opera']='审核不通过签约认证';
			elseif($prevState==3)$arr['opera']='恢复通过签约认证';
			elseif($prevState==0)$arr['opera']='审核通过签约认证';
			$arr['oid']=session('userid');
			$arr['state']=3;
			if(!empty($reason))$arr['reason']=$reason;
			$redis->hSet('hash:company:operation:history:'.$id,$insertId,serialize($arr));
//			$companyName=$redis->hGet('hash:member:info:'.$id,'companyName');
//			$redis->del('string:company:'.$companyName);
//			$result = $redis->exec();
			return true;
		}
	}

}