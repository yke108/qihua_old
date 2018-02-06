<?php
namespace User\Controller;
use Think\Controller;


/**
 * 我的消息控制器
 */

class MessageController extends CommonController{
	
	/**
	 * 标记消息
	 */
	
	public  function mark(){
		$read=I('read');
		$id=I('id');
		$id=explode(',', $id);
		$uid=$this->uid;
		$model=D('Message');
		$this->ajaxReturn($model->mark($uid,$id,$read));
	}
	

	
	/**
	 * 系统消息列表
	 */
	
	public function systemList(){
		if(!empty($_GET['keyword']))$param['keyword']=I('keyword');
		$param['uid']=$this->uid;
		$param['p']=empty($_GET['p'])?1:I('p');
		
		$model=D('Message');
		$res=$model->systemList($param);
		$companyName=D('Account')->SelectAccountInfo($this->uid,array('companyName'))['companyName'];
		$this->assign('companyName',$companyName);
		//var_dump($res);exit;
		$this->assign('pageinfo',empty($res['pageinfo']) ? '' : $res['pageinfo']);
		$this->assign('list',empty($res['list'])? array() : $res['list']);
		$this->assign('show',empty($res['show']) ? '' : $res['show']);
		$this->display('member-messages');
	}
	
	
	/**
	 * 站内信列表
	 */
	
	public  function mailBox(){
		$param['send']=empty($_GET['send'])?1:I('send');
		if($param['send']!=1&&$param['send']!=2)$param['send']=2;
		!empty($_GET['read'])?$param['read']=I('read'):null;
		!empty($_GET['keyword'])?$param['keyword']=I('keyword'):null;
		!empty($_GET['p'])?$param['p']=I('p'):null;
		$param['uid']=$this->uid;
		$model=D('Message');
		$res=$model->getMessageList($param);
		$this->assign('pageinfo',empty($res['pageinfo']) ? '' : $res['pageinfo']);
		$this->assign('list',empty($res['list'])? array() : $res['list']);
		$this->assign('show',empty($res['show']) ? '' : $res['show']);
		$this->display('member-mailbox-inbox');
	}
	
	/**
	 * 站内信详情
	 */
	
	public function mailDetail(){
		$send=I('send');
		$param['id']=I('id');
		$param['uid']=$this->uid;
		$model=D('Message');
		if($send==1||empty($send)){
			$res=$model->getReceiveMessage($param);
			if (isset($res['code']) && $res['code'] == 400) {
				$this->redirect('/User/Message/mailBox');
			}
			$member=D('Member')->get($res['msg']['from']);
			$res['msg']['from']=D('Member')->get($res['msg']['from'])['username'];
			$ret['username']=$member['username'];
			$ret['img']=$member['img'];
			$ret['country']=$member['country'];
			$ret['companyName']=$member['companyName'];
			$this->assign('ret',$ret);
			if (isset($res['to'])) {
				$res['msg']['to']=D('Member')->get($res['to'])['username'];
			}else{
				$res['msg']['to'] = '';
			}
			$this->assign('res',$res['msg']);
			$this->assign('reply',$res['reply']);
		}else{
			$res=$model->getSendMessage($param);
			
			$member=D('Member')->get($res['to']);
			$ret['username']=$member['username'];
			$ret['img']=$member['img'];
			$ret['country']=$member['country'];
			$ret['companyName']=$member['companyName'];
			$res['from']=D('Member')->get($res['from'])['username'];
			$res['to']=D('Member')->get($res['to'])['username'];
			$this->assign('ret',$ret);
			$this->assign('res',$res);
		}

		$this->display('member-inbox-details');
		
	}
	
	/**
	 * 回信
	 */
	
	public function reply(){
		$model=D('Message');
		if(empty($_POST)){
			$id=I('id');
			//获取接受者的信息
			$model=D('Message');
			$res=$model->getMessage($id);
			if(!$res){
				$this->ajaxReturn(array('code'=>400,'msg'=>"The message you reply can not be null"));
			}
			$member=D('Member')->get($res['from']);
			$ret['username']=$member['username'];
			$ret['img']=$member['img'];
			$ret['country']=$member['country'];
			$ret['companyName']=$member['companyName'];
			$this->assign('ret',$ret);
			$data['to']=$res['from'];
			$data['subject']="Re:".$res['subject'];
			$data['reply']=$id;
			$this->assign('data',$data);
			
			$this->display('member-mailbox-reply');
			return ;
		}else{
            //检查TOKEN
            $this->checkActionToken();
			$param['from']=$this->uid;
			$param['to']=I('to');
			if($param['from']==$param['to']){
				$this->ajaxReturn(array('code'=>400,'msg'=>"Can't reply your information"));
			}
			$param['subject']=I('subject');
			$param['content']=I('content');
			$param['reply']=I('reply');
			$res=$model->sendMessage($param);
			if($res){
				$this->ajaxReturn(array('code'=>200,'msg'=>"send successful",'data'=>array('url'=>'/User/Message/MailBox?send=1')));
			}else{
				$this->ajaxReturn(array('code'=>400,'msg'=>"Send failed",'data'=>array('url'=>'/User/Message/MailBox?send=1')));
			}
			
		}
		
	}
	
	
	/**
	 * 直接发信
	 */
	
	public function sendMessage(){
		if(empty($_POST)){
			$id=I('id');
			if(empty($id)){
				$this->ajaxReturn(array('code'=>400,'msg'=>"The message you reply can not be null"));
			}
			$companyName=D('User/Account')->SelectAccountInfo($this->uid,array('companyName'))['companyName'];
			//判读是否完善资料
			if(!$companyName){
				$this->error('',"\User/Account/info",0);return;
			}
			$member=D('Member')->get($id);
			$ret['username']=$member['username'];
			$ret['img']=$member['img'];
			$ret['country']=$member['country'];
			$ret['companyName']=$member['companyName'];
			$this->assign('ret',$ret);
			$this->assign('id',$id);
			$this->display('contact-sendMsg');
		}else{
			$param['from']=$this->uid;
			$param['to']=I('to');
			/*
				此处需要判断接收方是否存在
			 */
			$companyName=D('User/Account')->SelectAccountInfo($param['to'],array('companyName'))['companyName'];
			if(!$companyName){
				$this->error('','/Home/Index');
				return ;
			}
			if($param['from']==$param['to']){
				$this->error('','/Home/Index');
				return ;
			}
			
			$param['subject']=I('subject');
			$param['content']=I('content');
			$model=D("Message");
			$res=$model->sendMessage($param);
			//var_dump($param);exit;
			if($res['code']==200){
				$this->ajaxReturn(array('code'=>200,'msg'=>"send successful",'data'=>array('url'=>'/Home/Index')));
			}else{
				$this->ajaxReturn(array('code'=>400,'msg'=>"Send failed",'data'=>array('url'=>'/Home/Index')));
			}
		}
		
		
	}
	
	
	/**
	 * 删除系统消息
	 * 
	 */
	
	public function delSystem(){
		$ids=I('id');
		$id=explode(',', $ids);
		$model=D('Message');
		$this->ajaxReturn($model->delSystem($id));	
		
	}
	
	/**
	 * 删除站内信
	 */
	
	public function delMail(){
		$param['uid']=$this->uid;
		$ids=I('id');
		$id=explode(',', $ids);
		$param['id']=$id;
		empty(I('send'))?$param['send']=1:$param['send']=I('send');
		$model=D('Message');
		if($param['send']==1){
			$this->ajaxReturn($model->delinbox($param));
		}else{
			$this->ajaxReturn($model->delSend($param));
		}
		
	}
	
	/**
	 * 
	 */
	
	
	
}