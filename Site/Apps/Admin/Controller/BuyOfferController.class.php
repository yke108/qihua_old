<?php 
namespace Admin\Controller;
use Think\Controller;

/**
 * 求购管理控制器
 */
class BuyOfferController extends CommonController{


	public function lists(){
        $model=D('Admin/BuyOffer');
         $ret = $model->BuyOfferBaseData();
		$type=$ret['FIND_GOODS_TYPE'];
        $state=$ret['FIND_GOODS_STATE'];
        $this->assign('state',$state);
		$this->assign('type',$type);
		$this->display('list');
	}
	
	//求购列表
	public function findGoods(){
        $shell=D('Home/Shell');
        $model=D('Admin/BuyOffer');
        $param=I('post.');

        /*接收分页条件*/
        $page = I('post.page',1,'int');
        $rows=I('post.rows',20,'int');
        $offset=($page-1)*$rows;

        $keys = array();
        //信息编号
        if( !empty( $param['number'] ) ){
            $keys[] = $model->GetNumber( $param['number'] );
        }

        //标题
        if( !empty( $param['title'] ) ){
            $keys[] = $shell->search( "buyoffer:title",$param['title'],'set' );
        }

        //类型
        if( !empty( $param['type'] ) ){
            $keys[] = $model->GetType( $param['type'] );
        }

        //用户名
        if( !empty( $param['username'] ) ){
            $usernameKeys = $shell->search( "member:username",$param['username'],'array');
            if( !empty( $usernameKeys ) ){
                 $usernameKeys = explode( ',',$usernameKeys );
                $newUsernameKeys = array();
                foreach($usernameKeys as $v ){
                    $newUsernameKeys[] = "set:buyoffer:member:{$v}";
                }
            }
            $keys[] = $model->GetMember( $newUsernameKeys );
        }

        //状态
        if( isset( $param['state'] ) && $param['state'] !== '' ){
            $param['state'] = intval( $param['state'] );
            $keys[] = $model->GetState( $param['state'] );
        }else{
            //所有状态的并集

            $param = array(
             'set:buyoffer:state:0',
             'set:buyoffer:state:1',
             'set:buyoffer:state:2',
             'set:buyoffer:state:3',
			 'set:buyoffer:state:4',
            );
            $keys[] = $model->GetState( $param );

        }
           $keys[] = 'set:buyoffer:status:1';
         $data = $model->GetAllBuyOffer( $keys,$offset,$rows );
        if( $data ){
            $res['total'] = $data['total'];
            $res['rows'] = $data['rows'];
            $this->ajaxReturn($res);
        }else{
            $res['total'] = 0;
            $res['rows'] = 0;
            $this->ajaxReturn($res);
        }
    }
	
	
	//审核
	public function review(){
         $state = $_POST['state'];
         
         $id  =  $_POST['id'];
        if( $state === false || $id === false)exit;
		$data=I('post.');
		$model=D('Admin/BuyOffer');
        $ret = $model->BuyOfferBaseData();
        $state=$ret['FIND_GOODS_STATE'];
        $param['uid']=$model->details($id)['Uid'];
        if($data['state']==1){
        	$param['content']="Your purchase inquiry [".$model->details($id)['number']."] authentication approved!";
        }elseif($data['state']==0){
        	$param['content']="Your purchase inquiry [".$model->details($id)['number']."] authentication unapproved! [why: {$data['reason']}]";
        }elseif($data['state']==4){
        	$param['content']="Your purchase inquiry [".$model->details($id)['number']."] has been revoked! [why: {$data['reason']}]";
        }
        $param['sender']="Webmaster";
        if(!empty($param['content'])){
        	D("User/Message")->createSystem($param);
        }

        $data['addTime'] = time();
		$data['opera'] = $state[$data['state']];
        $data['otype'] = 'Webmaster';
        $data['oid'] = session('userid');
        $data['state']  = $data['state'];
        $data['reason'] = empty($data['reason'])?'':$data['reason'];
        if($data['state'] == 1){
            //添加审核时间
            $arr = $model->details( $data['id'],array( 'createTime','expire') );
            $times = $arr['createTime'] + 86400*$arr['expire'];
            $model ->editBuyOffer( $data['id'], array( 'times'=>$times ) );
        }
        $ret = $model->editState( $data['id'],array( 'state'=>intval($data['state']) ) );
        $art = $model->insertHistory( $data['id'],$data );
        if( $ret && $art){
            //插入操作历史
            $res['msg'] = '操作成功';
            $res['code'] = '200';
            $this->ajaxReturn($res);
        }else{
            $res['msg'] = '操作失败';
            $res['code'] = '400';
            $this->ajaxReturn($res);
        }
	}
	
	
	//detail
	public function details(){
		$id=I('get.id');
		if(empty($id))exit;
        $data=I('get.');
        $model=D('Admin/BuyOffer');
        $pram = array('id','number','title','type','Uid','content','state',);
		$data=$model->details( $data['id'],$pram );
        $ret = $model->BuyOfferBaseData();
        $type=$ret['FIND_GOODS_TYPE'];
        $state=$ret['FIND_GOODS_STATE'];
        $info = $model->GetMemberInfo( $data['Uid'],array( 'companyName','other' ) );
         $other = unserialize($info['other']);
        $pram = array( 'title' );
         $co  = $model->GetAreaTitle( $other['country'],$pram );
         $s  = $model->GetAreaTitle( $other['area_s'],$pram );
         $c  =$model->GetAreaTitle( $other['area_c'],$pram );
        $info['area'] = ($c['title']=='')?'':$c['title'].',';
        $info['area'] .= $s['title'].','.$co['title'];
        ///返回数据
        $this->assign('info',$info);
        $this->assign('state',$state);
        $this->assign('type',$type);
		$this->assign('data',$data);
		$this->display();
	}

    public function BuyOfferHistory(){
        $id=I('post.id');
        if(empty($id))exit;
        $model=D('Admin/BuyOffer');
        $page = I('post.page',1,'int');
        $rows=I('post.rows',20,'int');
        $offset=($page-1)*$rows;
        $arr = $model->GetHistory( $id,$offset,$rows );
        if( $arr ){
            $res['total'] = $arr['total'];
            $res['rows'] = $arr['rows'];
            $this->ajaxReturn($res);
        }else{
            $res['total'] = 0;
            $res['rows'] = 0;
            $this->ajaxReturn($res);
        }
    }

    //求购数据--导出
    public function expFind(){
        $shell=D('Home/Shell');
        $model=D('Admin/BuyOffer');
        $Account = D('User/Account');
        $redis = \Think\Cache::getInstance('Redis');
        $param=I('get.');

        $keys = array();
        //信息编号
        if( !empty( $param['no'] ) ){
            $keys[] = $model->GetNumber( $param['no'] );
        }

        //标题
        if( !empty( $param['title'] ) ){
            $keys[] = $shell->search( "buyoffer:title",$param['title'],'set' );
        }

        //类型
        if( !empty( $param['type'] ) ){
            $keys[] = $model->GetType( $param['type'] );
        }

        //用户名
        if( !empty( $param['username'] ) ){
            $usernameKeys = $shell->search( "member:username",$param['username'],'array');
            if( !empty( $usernameKeys ) ){
                $usernameKeys = explode( ',',$usernameKeys );
                $newUsernameKeys = array();
                foreach($usernameKeys as $v ){
                    $newUsernameKeys[] = "set:buyoffer:member:{$v}";
                }
            }
            $keys[] = $model->GetMember( $newUsernameKeys );
        }

        //状态
        if( isset( $param['state'] ) && $param['state'] !== '' ){
            $param['state'] = intval( $param['state'] );
            $keys[] = $model->GetState( $param['state'] );
        }else{
            //所有状态的并集

            $param = array(
                'set:buyoffer:state:0',
                'set:buyoffer:state:1',
                'set:buyoffer:state:2',
                'set:buyoffer:state:3',
                'set:buyoffer:state:4',
            );
            $keys[] = $model->GetState( $param );

        }
        $keys[] = 'set:buyoffer:status:1';
        $data = $model->GetAllBuyOffer( $keys);

        $state=array(
            '2'=>'待审核',
            '1'=>'正常',
            '3'=>'已过期',
            '4'=>'已撤销',
            '0'=>'审核不通过',
        );

        foreach($data as $k=>$v){
            $History =$model ->GetHistory($v['id']);
            ksort($History);
            foreach ($History as $k2 => $v2) {
                    if ($v2['states'] == 2) {
                        unset($History[$k2]);
                    }

            }

            $firstDate = array_slice($History,0,1)[0];
            $lastDate = end($History);
            $data[$k]['first']=$firstDate['addTime'];//初审时间
            $data[$k]['firstName']=$firstDate['oid'];//初审人
            $data[$k]['lastName']=$lastDate['oid'];//最新审核人
            $data[$k]['last']=$lastDate['addTime'];//最新审核时间
            $data[$k]['createTime']=$v['createTime'];//创建时间
            $data[$k]['updateTime']=$v['updateTime'];//最新修改时间
            $data[$k]['timeup']=empty($v['Times'])?'':$v['Times'];//有效期截止时间
            $data[$k]['username'] =$redis->hget("hash:member:{$v['Uid']}",'username');
            $data[$k]['companyName']=$v['companyName'];
            $pram=array('other');
            $areaData=$Account->SelectAccountInfo( $v['Uid'],$pram );

            if( !empty( $areaData ) ){
                $area['country'] = $Account->GetAreaTitle( $areaData['other']['country'],array( 'title' ) )['title'];//国家
                $area['area_s']  = $Account->GetAreaTitle( $areaData['other']['area_s'],array( 'title' ) )['title'];//地区
                $area['area_c']  = $Account->GetAreaTitle( $areaData['other']['area_c'],array( 'title' ) )['title'];//城市
            }

            $data[$k]['area']=trim(implode('-',$area),'-');
            if(empty($v['reason'])){
                $data[$k]['review']=$state[$v['state']];
            }else{
                $data[$k]['review']=$state[$v['state']].'【原因：】'.$v['reason'];
            }

            $data[$k]['type']=$v['type'];
        }

        $xlsName  = "求购列表";
        $xlsCell  = array(
            array('number','信息编号'),
            array('title','标题'),
            array('type','信息类型'),
            array('content','求购内容'),
            array('timeup','有效期截止'),
            array('username','用户名'),
            array('companyName','公司名称'),
            array('area','所在地区'),
            array('review','状态'),
            array('createTime','创建时间'),
            array('updateTime','最新修改时间'),
            array('first','初始审核时间'),
            array('firstName','初始审核人'),
            array('last','最新审核时间'),
            array('lastName','最新审核人')

        );
        $xlsData = $data;//读取列表
        exportExcel($xlsName,$xlsCell,$xlsData);

    }

}
