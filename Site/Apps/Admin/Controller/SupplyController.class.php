<?php 
namespace Admin\Controller;
use Think\Controller;

/**
 * 供应管理控制器
 */
class SupplyController extends CommonController{


	public function lists(){
        $model=D('Admin/Supply');
        $ret = $model->SupplyBaseData();

		$type=$ret['FIND_GOODS_TYPE'];
        $state=$ret['FIND_GOODS_STATE'];
        $this->assign('state',$state);
		$this->assign('type',$type);
		$this->display('list');
	}
	
	//供应列表
	public function findGoods(){
        $shell=D('Home/Shell');
        $model=D('Admin/Supply');
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
            $keys[] = $shell->search( "supply:title",$param['title'],'set' );
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
                    $newUsernameKeys[] = "set:supply:member:{$v}";
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
             'set:supply:state:0',
             'set:supply:state:1',
             'set:supply:state:2',
             'set:supply:state:3',
			 'set:supply:state:4',
            );
            $keys[] = $model->GetState( $param );

        }
           $keys[] = 'set:supply:status:1';

         $data = $model->GetAllSupply( $keys,$offset,$rows );
// var_dump($data);
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
		$model=D('Admin/Supply');
        $ret = $model->SupplyBaseData();
        $state=$ret['FIND_GOODS_STATE'];

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
            $model ->editSupply( $data['id'], array( 'times'=>$times ) );
        }
        $ret = $model->editState( $data['id'],array( 'state'=>intval($data['state']) ) );
        $art = $model->insertHistory( $data['id'],$data );
        //申请结果通知用户
        $mess_re = D('Admin/Supply')->set_mess($data['id'],$data['state'],$data['reason']);

        if(!$mess_re){
            //通知失败
            $res['msg'] = '通知写入失败';
            $res['code'] = '400';
            $this->ajaxReturn($res);
        }
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
        $model=D('Admin/Supply');
        $pram = array('id','number','title','type','Uid','content','state',);
		$data=$model->details( $data['id'],$pram );
        $ret = $model->SupplyBaseData();
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

    public function SupplyHistory(){
        $id=I('post.id');
        if(empty($id))exit;
        $model=D('Admin/Supply');
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

}
