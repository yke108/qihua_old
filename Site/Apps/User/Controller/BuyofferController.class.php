<?php
namespace User\Controller;
use Think\Controller;

/**
 * 求购控制器
 */

class BuyofferController extends CommonController{
	
	/**
	 * 首页求购列表
	 */
	
	/**public function indexlist(){
		$model=D('Buyoffer');
		$param='';
		if($_GET){
			$param=I('get.');
		}
		$res=$model->indexlist($param);
		$this->assign('list',$res['list']);
		$this->assign('show',$res['show']);
		$this->display('find-goods');
	}*/
	
	public function beforeRelease(){
		$username=D('Member')->get(12)['username'];
		if(empty($username)){
			$this->ajaxReturn(array('code'=>400,'msg'=>'incomplete'));
		}else{
			$url="\User\Buyoffer\BuyOfferRelease";
			$this->ajaxReturn(array('code'=>200,'msg'=>'complete','data'=>array('url'=>$url)));
		}
		
	}
	
	
	
	/**
	 * 发布求购
	 */
	
	public function BuyOfferRelease(){
		if(!$_POST){
			//检查TOKEN
          	// $this->checkActionToken();
			$type=C("FIND_GOODS_TYPE");
			$date=C('FIND_GOOD_EXPIRE');
            $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );

            if(!$IsCompleteInfo){
                $this->redirect( 'BuyOffer/BuyOfferList' );
            }

			$this->assign('type',$type);
			$this->assign('date',$date);
			$this->display('member-findGoods-pub');
			return ;
		}
        $shell=D('Home/Shell');
		$data=I('post.');
		$data['Uid']=$this->uid;

		$data['createTime']=$data['updateTime']=time();
		$data['state']=2;
		$data['times']='';
        //        //生成求购编号,  1 QGYL 2 QGPF  3 QGZL 5 QGJS
        if($data['type'] == 1){
            $type='QGYL';
        }else if($data['type'] == 2){
            $type='QGPF';
        }else if($data['type'] == 3){
            $type='QGZL';
        }else if($data['type'] == 4){
            $type='QGJS';
        }
        $data['number']=$type.date('ymdH').rand(1,99999);
        $param['data']=$data;
		$model=D('Buyoffer');
		$res=$model->addBuyoffer($param);
		if($res){
			$this->ajaxReturn(array('code'=>200,'msg'=>'success'));
		}else{
			$this->ajaxReturn(array('code'=>400,'msg'=>'failed'));
		}
	}
	
	/**
	 * 修改求购
	 * 
	 */
	public function modify(){
		$model=D('Buyoffer');
		if(!$_POST){
			//检查TOKEN
//          	$this->checkActionToken();
			$param['id']=I("get.id");
			$param['uid']=$this->uid;
			if(!$param['id'])return ;
			$res=$model->details($param);
			$this->assign('res',$res);
			if(!$res){
				$this->error('','BuyofferList',0);
			}
            $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
            if(!$IsCompleteInfo){
                $this->redirect( 'Buyoffer/BuyOfferList' );
            }

			if($res['state']==0||$res['state']==4){
				$data['uid']=$this->uid;
				$data['id']=$res['id'];
//				$op['reason']=$model->Opera($data,1)[0]['reason'];
                $op['reason'] = $model->getOneHistory($res['id'])['reason'];
				if($res['state']==0)$op['msg']='Your offer have not been Disapproved !';
				if($res['state']==4)$op['msg']='Your offer have not been revoke !';
				$this->assign('op',$op);
			}
			$mold=C("FIND_GOODS_TYPE");
			$date=C('FIND_GOOD_EXPIRE');
			//$check=C('FIND_GOODS_STATUS');
			$this->assign('mold',$mold);
			$this->assign('date',$date);
			$this->display('member-findGoods-detail');
			return ;
		}
		$param['data']=I('post.');
		$param['uid']=$this->uid;
		$param['data']['updateTime']=time();
		$res=$model->modify($param);
		if($res){
			$this->ajaxReturn(array('code'=>200,'msg'=>'success','data'=>array('url'=>'BuyOfferList')));
		}else{
			$this->ajaxReturn(array('code'=>400,'msg'=>'failed'));
		}
	}
	
	
	/**
	 * 求购列表
	 */
	public function BuyOfferList(){
		$param['title']=I('title');
		$param['type']=I('type');
		$param['state']=I('state');
		$model=D('Buyoffer');
		$param['p']=I('p');
		$param['Uid']=$this->uid;
		$res=$model->lists($param);
		$type=C('FIND_GOODS_TYPE');
		$state=C('FIND_GOODS_STATUS');
		$companyName=D('Account')->SelectAccountInfo($this->uid,array('companyName'))['companyName'];
        //判断是否完成资料
        $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
        $this->assign('IsCompleteInfo',intval( $IsCompleteInfo ) );
		$this->assign('companyName',$companyName);
		$this->assign('list',empty($res['list']) ? array() : $res['list']);
		$this->assign('show',empty($res['show']) ? '' : $res['show']);
		$this->assign('pageinfo',empty($res['pageinfo']) ? '' : $res['pageinfo']);
		$this->assign('type',$type);
		$this->assign('state',$state);
		$this->display('member-findGoods-list');
	}
	
	/**
	 * 删除求购
	 */
	public function delBuyOffer(){
		$id=I('id');
		//暂时指定uid
		$param['Uid']=$this->uid;
		$param['id']=explode(',', $id);
		$model=D('Buyoffer');
        $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
        if(!$IsCompleteInfo){
            $this->ajaxReturn( array('code'=>'400','msg'=>'Basic information has not been completed') );
        }
		if($model->del($param)){
			$this->ajaxReturn(array('code'=>200,'msg'=>'success'));
		}else{
			$this->ajaxReturn(array('code'=>400,'msg'=>'failed'));
		}
	}
	
	/**
	 * 求购详情
	*/
	public function BuyOfferDetails(){
		$param['id']=I('get.id');
		$model=D('Buyoffer');
		$res=$model->details($param);
		$mold=C("FIND_GOODS_TYPE");
		$date=C('FIND_GOOD_EXPIRE');
		$check=C('FIND_GOODS_STATUS');
		$res['state']=$check[$res['state']];
		$res['type']=$mold[$res['type']];
		$res['expire']=$date[$res['expire']];

        //收藏数
        $collectType = 0;
        $collectCount = intval( D( 'User/Collect' )->getCount( array( 'id' => $param['id'], 'type' => $collectType ) ) );
        $isCollect = false;
        if( !empty( $this->uid ) ){
            $isCollect = D( 'User/Collect' )->getIsCollect( array( 'uid' => $this->uid, 'type' => $collectType , 'id' => $param['id'] ) );
        }
        $this->assign( 'loginUid', $this->uid  );
        $this->assign( 'collectCount', $collectCount );
        $this->assign( 'isCollect', $isCollect );
		$this->assign('mold',$mold);
		$this->assign('date',$date);
		$this->assign('res',$res);
		$this->display('find-goods-detail');
	}
	
	
	/**
	 * 获取操作历史
	 * 
	 */
	public function Operation(){
		$param['id']=I('id');
		$param['uid']=$this->uid;
		$param['p']=I('p');
		$model=D('Buyoffer');
		$arr=$model->Opera($param);
		
		$this->assign('opera',$arr['res']);
		$this->assign('show',$arr['show']);
		$this->display('member-findGoods-operHistory');
	}
	
	
	
	
}