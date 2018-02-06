<?php 
namespace User\Controller;
use Think\Controller;

	/**
	 * Supply Offer Controller
	 */

class SupplyController extends CommonController{
	
	/**
	* 修改求购
	*
	*/
	public function modify(){
		$model=D('Supply');
		if(empty($_POST)){
			$param['id']=I("get.id");
			$param['uid']=$this->uid;
			if(!$param['id'])return ;
			$res=$model->details($param);
			$this->assign('res',$res);
			if(empty($res)){
				$this->redirect('Supply/lists');
			}
			$IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
			if(!$IsCompleteInfo){
				$this->redirect( 'Supply/lists' );
			}
	
			if($res['state']==0||$res['state']==4){
				$data['uid']=$this->uid;
				$data['id']=$res['id'];
				$reason = $model->Opera($data,1);
				if (!empty($reason['res'])) {
				    $reason = array_shift($reason['res'])['reason'];
                } else {
				    $reason = '';
                }
				$op['reason']=$reason;
				if($res['state']==0)$op['msg']='Your offer have not been Disapproved !';
				if($res['state']==4)$op['msg']='Your offer have not been revoke !';
				$this->assign('op',$op);
			}
				
			// $mold=C("FIND_GOODS_TYPE");
			$date=C('FIND_GOOD_EXPIRE');
			$mold=D("Supply")->getSupplyType();
			//$check=C('FIND_GOODS_STATUS');
			$this->assign('mold',$mold);
			$this->assign('date',$date);
			$this->display('member-supply-detail');
			return ;
		}
        //检查TOKEN
        $this->checkActionToken();
		$param['data']=I('post.');
		$param['uid']=$this->uid;
		$param['data']['updateTime']=time();
		$res=$model->modify($param);
		if($res){
			$this->ajaxReturn(array('code'=>200,'msg'=>'success','data'=>array('url'=>'lists')));
		}else{
			$this->ajaxReturn(array('code'=>400,'msg'=>'failed','data'=>array('url'=>"lists")));
		}
	}
	
	

	/**
	 * 获取操作历史
	 *
	 */
	public function Operation(){
		$param['id']=I('id');
		$param['p']=I('p');
		$param['uid']=$this->uid;
		$model=D('Supply');
		$arr=$model->Opera($param);
		$this->assign('opera',$arr['res']);
		$this->assign('show',$arr['show']);
		$this->display('member-findGoods-operHistory');
	}
	
	
	
	/**
	 * Supply 详情
	 */

	public function SupplyDetails(){
		$param['id']=I('get.id');
		$model=D('Supply');
		$res=$model->details($param);
		$mold=$model->getSupplyType();
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
		var_dump($res);exit;
		$this->display('find-goods-detail');
	}
	
	
	/**
	 * 列表页
	 */
	
	public function lists(){
		$param['title']=I('title');
		$param['type']=I('type');
		$param['state']=I('state');
		$model=D('Supply');
		$param['p']=I('p');
		$param['Uid']=$this->uid;
		$res=$model->lists($param);
		$type=$model->getSupplyType();
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
		$this->display("member-supply-list");
	}
	
	
	/**
	 * 新增SupplyOffer
	 */
	
	public function addSupply(){
		
		if(empty($_POST)){
			//检查TOKEN
          	// $this->checkActionToken();
			$type=D("Supply")->getSupplyType();
			$date=C('FIND_GOOD_EXPIRE');
			$IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
			
			/*if(!$IsCompleteInfo){
				$this->redirect( 'Supply/lists' );
			}*/
			
			$this->assign('type',$type);
			$this->assign('date',$date);
			$this->display("member-supply-pub");
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
            $type='GYYL';
        }else if($data['type'] == 2){
            $type='GYPF';
        }else if($data['type'] == 3){
            $type='GYZL';
        }else if($data['type'] == 4){
            $type='GYJS';
        }else if($data['type'] == 5){
        	$type="GYQT";
        }
        $data['number']=$type.date('ymdH').rand(1,99999);
        $param['data']=$data;
		$model=D('Supply');
		$res=$model->addSupply($param);
		if($res){
			$this->ajaxReturn(array('code'=>200,'msg'=>'success','data'=>array('url'=>'lists')));
		}else{
			$this->ajaxReturn(array('code'=>400,'msg'=>'failed','data'=>array('url'=>'lists')));
		}
		
	}
	
	
	/**
	 * 删除求购
	 */
	public function delSupplyOffer(){
		$id=I('id');
		//暂时指定uid
		$param['Uid']=$this->uid;
		$param['id']=explode(',', $id);
		$model=D('Supply');
		$IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
		/* if(!$IsCompleteInfo){
			$this->ajaxReturn( array('code'=>'400','msg'=>'Basic information has not been completed') );
		} */
		if($model->del($param)){
			$this->ajaxReturn(array('code'=>200,'msg'=>'success'));
		}else{
			$this->ajaxReturn(array('code'=>400,'msg'=>'failed'));
		}
	}
}









?>