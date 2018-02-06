<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 求购控制器
 */

class SupplyController extends CommonController{
	
	
	/**
	 * 首页Supply列表
	 */
	
	public function lists(){
		$model=D('Supply');
		$param='';
		!empty($_GET['p'])?$param['p']=I('p'):$param['p']=1;
		!empty($_GET['keyword'])?$param['title']=I('keyword'):null;
		!empty($_GET['companyModels'])?$param['type']=I('companyModels'):null;
		$res=$model->indexlist($param);
		$type=D("User/Supply")->getSupplyType();
		$this->assign('type',$type);
		$companyName=D('User/Account')->SelectAccountInfo($this->uid,array('companyName'))['companyName'];
		$this->assign('companyName',$companyName);
		$this->assign('list',empty($res['list'])?array():$res['list']);
		$this->assign('show',empty($res['show'])?'':$res['show']);
		if(!empty($param['type'])){
			$gettype=explode(',', $param['type']);
			$str='';
			foreach ($gettype as $v){
				if(empty($str)){
					$str.=$type[$v];
				}else{
					$str.='+'.$type[$v];
				}
				
			}
			$this->assign('gettype',$gettype);
			$this->assign('str',$str);
		}
		$this->assign('pageinfo',empty($res['pageinfo'])?array():$res['pageinfo']);
		$this->assign( 'loginUid', $this->uid  );
        $this->assign( 'cate', getcategory() );
		$this->display('find-goods');
	}
	
	
	/**
	 * Supply详情
	 */
	public function SupplyDetails(){
		$param['id']=I('get.id');
		$model=D('Supply');
		$res=$model->details($param);
		if(empty($res)){
			$this->redirect(U('Supply/lists'));
		}
		$mold=D("User/Supply")->getSupplyType();
		$date=C('FIND_GOOD_EXPIRE');
		$check=C('FIND_GOODS_STATUS');
		$member=D('User/Member')->get($res['Uid']);
		$companyName=D('User/Account')->SelectAccountInfo($this->uid,array('companyName'))['companyName'];
		
		if($res['Uid']==$this->uid){
			$companyName=-1;
		}
		$this->assign('companyName',$companyName);
		$user['username']=$member['username'];
		$user['uid']=$res['Uid'];
		$user['companyName']=$member['companyName'];
		$user['country']=$member['country'];
		$user['img']=$member['img'];
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
		$this->assign('user',$user);
        $this->assign( 'cate', getcategory() );
        
		$this->display('find-goods-detail');
	}
	
	
	
}