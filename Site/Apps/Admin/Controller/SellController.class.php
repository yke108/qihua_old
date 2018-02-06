<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/9/14
 * Time: 9:30
 */

namespace Admin\Controller;
use       Think\Controller;

class SellController extends CommonController{
    /*有效的商品列表*/
    public function valid(){
        $this->display();
    }

    /*待审核的商品列表*/
    public function pending(){
        $this->display();
    }

    /*审核不通过的商品列表*/
    public function fail(){
        $this->display();
    }
    /*已撤销的商品列表*/
    public function soldout(){
        $this->display();
    }

    //获取详情页
    public function details(){
        $sell=D('Sell');
        $id=I('get.id');
        if( $id ){
            $arr=$sell->detail($id);
            if( !empty( $arr ) ){
                $states = C( 'PRODUCT.STATE' );
                $expires = C( 'PRODUCT.EXPIRE' );
                $weightUnits = C( 'WEIGHTUNIT' );
                $arr['weightUnitsTip'] = $weightUnits[$arr['weightUnit']];
                if( $arr['currency'] == 1 ){
                    $arr['currencyTip'] = '￥';
                }else{
                    $arr['currencyTip'] = '$';
                }
                $arr['stateTip'] = '';
                $arr['expire'] = isset($arr['expire'])?$arr['expire']:'';
                $arr['expireTip'] = isset($expires[$arr['expire']])?$expires[$arr['expire']]:'';
                switch( $arr['origin_state'] ){
                    case $states['REFUSE']:
                        $arr['stateTip'] = 'Audit not through';
                        break;
                    case $states['ACTIVE']:
                        $arr['stateTip'] = 'valid';
                        $arr['verifyTime'] = isset($arr['verifyTime'])?$arr['verifyTime']:0;
                        $arr['expire'] = isset($arr['expire'])?$arr['expire']:0;
                        $arr['expireTip'] = date( 'Y-m-d H:i:s', ( $arr['verifyTime'] + $arr['expire'] * 24 * 3600 ) );
                        break;
                    case $states['REVIEWING']:
                        $arr['stateTip'] = ' check pending';
                        break;
                    case $states['REVOKE']:
                        $arr['stateTip'] = 'sold out';
                        break;
                    case $states['SELLER_REVOKE']:
                        $arr['stateTip'] = 'Merchants from the shelves';
                        break;
                    case $states['ADMIN_REVOKE']:
                        $arr['stateTip'] = 'The staff from the shelves';
                        break;
                    case $states['SYSTEM_REVOKE']:
                        $arr['stateTip'] = 'System from the shelves';
                        break;
                }
//                $productDepot = D( 'Home/productDepot' )->getDetailByCode( array( 'code' => $arr['productDepotCode'] ) );
//                $arr['productDepot'] = array(
//                    'enName' => $arr['enName'],
//                    'cas' => $arr['cas'],
//                    'productCode' => $arr['productCode'],
//                );
                $arr['priceTip'] = $arr['currencyTip'].$arr['price'].'/'.$arr['weightUnitsTip'];
                $arr['moqTip'] = $arr['moq'].$arr['weightUnitsTip'];
                $arr['inventoryTip'] = '';// $arr['inventory'].$arr['weightUnitsTip'];
                if($arr['inventoryType'] == 1 && $arr['inventoryNum'] == 0){
                    $arr['inventoryTip'] = '有货';
                }elseif($arr['inventoryType'] == 1 && $arr['inventoryNum'] > 0 && $arr['inventory'] == 1){
                    $arr['inventoryTip'] = $arr['inventoryNum'].$arr['weightUnitsTip'];
                }elseif($arr['inventoryType'] == 1 && $arr['inventoryNum'] > 0 && $arr['inventory'] == 0){
                    $arr['inventoryTip'] = '有货';
                }elseif($arr['inventoryType'] == 2){
                    $arr['inventoryTip'] = '缺货';
                }
                $arr['paymentMethodTip'] = '合同约定'/*.'【'.($arr['paymentMethod'] == 1 ?'先货后款':'先款后货').'】'*/;
                $arr['logisticsMethodTip'] = '合同约定';
                if( !empty( $arr['history'] ) ){
                    $history = array();
                    foreach( $arr['history'] as $v ){
                        $history[] = unserialize( $v );
                    }
                    $arr['history'] = $history;
                }
            }
            $this->assign( "vo", $arr );
        }
        $this->display();
    }

    //获取商品操作历史
    public function getGoodsHistories(){
        $ret = array(
            'rows' => array(),
            'total' => 0,
        );
        $sell = D( 'Sell' );
        $id = I( 'id' );
        if( empty( $id ) ){
            $this->ajaxReturn($ret);
        }
        $data = $sell->getHistory( $id );
        if( !empty( $data ) ){
            $histories = array();
            foreach( $data as $v ){
                $history = unserialize( $v );
                switch( $history['otype'] ){
                    case 'system':
                        $operatorTip = '系统';
                        break;
                    case 'seller':
                        $operatorTip = '商家';
                        break;
                    default:
                        $operatorTip = '网站管理员';
                        break;
                }
                $histories[] = array(
                    'addTimeTip' => date( 'Y-m-d H:i:s', $history['addTime'] ),
                    'operaTip' => $history['opera'].( empty( $history['reason'] )?'':'【原因:'.$history['reason'].'】' ),
                    'operatorTip' => $operatorTip,
                );
            }
            $ret['rows'] = $histories;
            $ret['total'] = count( $histories );
        }
        $this->ajaxReturn($ret);
    }

    //获取详情页

    public function goodsDetails(){
        $store=D('Sell');
        $id=I('get.id');
        if($id){
            $arr=$store->detail($id);
            if( !empty( $arr ) ){
                $states = C( 'PRODUCT.STATE' );
                $expires = C( 'PRODUCT.EXPIRE' );
                $weightUnits = C( 'WEIGHTUNIT' );
                $arr['weightUnitsTip'] = $weightUnits[$arr['weightUnit']];
                if( $arr['currency'] == 1 ){
                    $arr['currencyTip'] = '￥';
                }else{
                    $arr['currencyTip'] = '$';
                }
                $arr['stateTip'] = '';
                switch( $arr['origin_state'] ){
                    case $states['REFUSE']:
                        $arr['stateTip'] = 'Audit not through';
                        break;
                    case $states['ACTIVE']:
                        $arr['stateTip'] = 'valid';
                        break;
                    case $states['REVIEWING']:
                        $arr['stateTip'] = 'check pending';
                        break;
                    case $states['REVOKE']:
                        $arr['stateTip'] = 'sold out';
                        break;
                    case $states['SELLER_REVOKE']:
                        $arr['stateTip'] = 'Merchants from the shelves';
                        break;
                    case $states['ADMIN_REVOKE']:
                        $arr['stateTip'] = 'The staff from the shelves';
                        break;
                    case $states['SYSTEM_REVOKE']:
                        $arr['stateTip'] = 'System from the shelves';
                        break;
                }
                $productDepot = D( 'Home/productDepot' )->getDetailByCode( array( 'code' => $arr['productDepotCode'] ) );
                $arr['productDepot'] = array(
                    'cnName' => $productDepot['cnName'],
                    'cas' => $productDepot['cas'],
                    'productDepotCode' => $arr['productDepotCode'],
                );
                $arr['priceTip'] = $arr['currencyTip'].$arr['price'].'/'.$arr['weightUnitsTip'];
                $arr['moqTip'] = $arr['moq'].$arr['weightUnitsTip'];
                $arr['inventoryTip'] = $arr['inventory'].$arr['weightUnitsTip'];
                $arr['paymentMethodTip'] = '合同约定'.'【'.($arr['paymentMethod'] == 1 ?'先货后款':'先款后货').'】';
                $arr['logisticsMethodTip'] = '合同约定';
                if( !empty( $arr['history'] ) ){
                    $history = array();
                    foreach( $arr['history'] as $v ){
                        $history[] = unserialize( $v );
                    }
                    $arr['history'] = $history;
                }
            }
            $res['msg']='';
            $res['code']=200;
            $res['data']=$arr;
            $this->ajaxReturn($res);
        }else{
            $res['msg']='异常';
            $res['code']=400;
            $this->ajaxReturn($res);
        }

    }


    /*列表*/
    public function productList(){
        $shell=D('Home/Shell');
        $store=D('Sell');
        $param=I('post.');

        /*接收分页条件*/
        $page = I('post.page',1,'int');
        $rows=I('post.rows',20,'int');
        $offset=($page-1)*$rows;

        $status=isset($_POST['status'])?$_POST['status']:'2';
        $param['categoryThird'] = isset($param['categoryThird'])?$param['categoryThird']:'';
        $param['categorySecond']= isset($param['categorySecond'])?$param['categorySecond']:'';
        $param['categoryFirst'] = isset($param['categoryFirst'])?$param['categoryFirst']:'';

        /*接收非空的分类id*/
        $categoryArr=array('categoryThird'=>$param['categoryThird'], 'categorySecond'=>$param['categorySecond'], 'categoryFirst'=>$param['categoryFirst']);
        foreach($categoryArr as $k=>$v){
            if(!empty($categoryArr[$k])){
                $param['category']=$v;
                break;
            }
        }

        $keys=array();
        /*获取符合分类的集合*/
        if(!empty($param['category'])){
            //$productArr=$this->redis->getProductDepot($param['category']);
            /*取得键名*/
            $keys[]=$store->getCategoryKeys($param['category']);
        }

        /*获取公司名称的集合*/
        if(!empty($param['companyName'])){
            $CompanyKeys=$shell->search('member:companyName',trim(strtolower($param['companyName'])),'array');
            if(!empty($CompanyKeys)){
                $CompanyKeys =explode(',',$CompanyKeys);
                $newCompanyKeys = array();
                foreach($CompanyKeys as $companyId){
                    $newCompanyKeys[] = 'set:product:member:'.$companyId;
                }
            }
            $keys[]=D('Admin/Store')->getAllData($newCompanyKeys);

        }

        /*获取下架情况的集合*/
        if( !empty( $param['operateStatus'] ) ){
            $keys[] = D( 'Home/Product' )->getStateCacheKey( intval( $param['operateStatus'] ) );
        }

        /*判断是否为空*/
        //$param['keyword']='SC201609145';
        if(!empty($param['keyword'])){
            /*判断是否为信息编号*/
            $ret=$store->is_Code($param['keyword']);
            if($ret){
                /*是code号*/
                $keys[]=$store->getCodeKeys($param['keyword']);
            }else{
                /*是商品名称*/
                $keys[]=$shell->search('product:title',strtolower($param['keyword']),'set');
            }
        }

        /*获取当前状态的所有交集*/
        $keys[]=$store->getListKeys($status);

        /*交集之后取最后的列表结果*/
        $count=$store->getCount($keys);
        $listArrKeys=$store->getSinterstore($keys,$offset,$rows);

        if(!empty($listArrKeys)){
            $res['total']=$count;
            $res['rows']=$listArrKeys;
        }else{
            $res['total']=0;
            $res['rows']=0;
        }
        $this->ajaxReturn($res);
    }

    /*获取分类列表*/
    public function getCategory(){
        $id=I('get.id');
        $store=D('Sell');
        if(!empty($id)){
            $data=$store->getChildCategory($id);
        }else{
            $data=$store->getCategory();
        }
        $ret['code'] = '200';
        $ret['data'] = $data;
        $this->ajaxReturn( $ret );
    }

    /*
     * 修改用户状态--下架*/
    public function changeOff(){
        $data=I('post.');
        $store=D('Sell');

        $data=array(
            'id'=>$data['id'],
            'oid'=>$_SESSION['userid'],
            'opera'=>'The staff from the shelves',
            'addTime' => time(),
            'state'=>'5',
            'otype'=>'admin',
            'reason'=>$data['reason']
        );
        $ret= $store->changeOff($data['id'],$data);
        if($ret){
            $res['code']=200;
            $res['msg']='操作成功';
            $this->ajaxReturn($res);
        }else{
            $res['code']=400;
            $res['msg']='操作失败';
            $this->ajaxReturn($res);
        }
    }

    /*
     * 单条修改
     * 批量修改
     * 修改用户状态--审核通过*/
    public function examStatus(){
        $id=I('post.');
        $store=D('Sell');
        $uid=$_SESSION['userid'];
        $ret= $store->examStatus($id,$uid);

        if($ret === '2'){
            $res['code']=400;
            $res['msg']='用户企业认证未通过，不能修改';
            $this->ajaxReturn($res);
        }
        if($ret){
            $res['code']=200;
            $res['msg']='操作成功';
            $this->ajaxReturn($res);
        }else{
            $res['code']=400;
            $res['msg']='操作失败';
            $this->ajaxReturn($res);
        }
    }

    /*修改用户状态--审核不通过*/
    public function failStatus(){
        $data=I('post.');
        $store=D('Sell');
        $data=array(
            'id'=>$data['id'],
            'oid'=>$_SESSION['userid'],
            'opera'=>'Audit not through',
            'addTime' => time(),
            'state'=>'0',
            'otype'=>'admin',
            'reason'=>$data['reason']
        );
        $ret= $store->failStatus($data['id'],$data);
        if($ret){
            $res['code']=200;
            $res['msg']='操作成功';
            $this->ajaxReturn($res);
        }else{
            $res['code']=400;
            $res['msg']='操作失败';
            $this->ajaxReturn($res);
        }
    }

    //判断商品仓库状态  $id  商品id
    public function CheckDepotState($id){
        $id=I('id');
        $sell=D('Sell');
        $res=$sell->getDepotState($id);
        if($res!=1){
            $ret['msg']='商品仓库未通过审核';
            $ret['code']='400';
            $this->ajaxReturn($ret);
        }
    }

    /*上架*/
    public function renewStatus(){
        $data=I('post.');
        $store=D('Sell');
        $uid=$_SESSION['userid'];
        $data=array(
            'id'=>$data['id'],
            'oid'=>$uid,
            'opera'=>'staff-shelves',
            'addTime' => time(),
            'state'=>'1',
            'otype'=>'admin',
            'reason'=>$data['reason']
        );
        $ret= $store->renewStatus($data['id'],$data);
        if($ret === '2'){
            $res['code']=400;
            $res['msg']='用户企业认证未通过，不能修改';
            $this->ajaxReturn($res);
        }elseif($ret){
            $res['code']=200;
            $res['msg']='操作成功';
            $this->ajaxReturn($res);
        }else{
            $res['code']=400;
            $res['msg']='操作失败';
            $this->ajaxReturn($res);
        }
    }

    /*重审通过*/
    public function rStatus(){
        $data=I('post.');
        $store=D('Sell');
        $uid=$_SESSION['userid'];
        $data=array(
            'id'=>$data['id'],
            'oid'=>$uid,
            'opera'=>'The review by',
            'addTime' => time(),
            'state'=>'1',
            'otype'=>'admin',
            'reason'=>isset($data['reason'])?$data['reason']:''
        );
        $ret= $store->rStatus($data['id'],$data);
        if($ret === '2'){
            $res['code']=400;
            $res['msg']='用户企业认证未通过，不能修改';
            $this->ajaxReturn($res);
        }
        if($ret){
            $res['code']=200;
            $res['msg']='操作成功';
            $this->ajaxReturn($res);
        }else{
            $res['code']=400;
            $res['msg']='操作失败';
            $this->ajaxReturn($res);
        }
    }

    /*批量删除*/
    public function del(){
        $id=I('post.');
        $store=D('Sell');
        $uid=$_SESSION['userid'];
        $ret= $store->del($id,$uid);
        if($ret){
            $res['code']=200;
            $res['msg']='操作成功';
            $this->ajaxReturn($res);
        }else{
            $res['code']=400;
            $res['msg']='操作失败';
            $this->ajaxReturn($res);
        }
    }

    //获取下架情况
    public function getRevoke(){
        $ret = array(
            'code' => 200,
            'msg' => '操作成功',
            'data' => '',
        );
        $states = C( 'PRODUCT.STATE' );
        $masterType = array(
            array( 'text' => '全部下架', 'id' => 0 ),
            array( 'text' => '商家下架', 'id' => $states['SELLER_REVOKE'] ),
            array( 'text' => '工作人员下架', 'id' => $states['ADMIN_REVOKE'] ),
            array( 'text' => '系统下架', 'id' => $states['SYSTEM_REVOKE'] ),
        );
        $ret['data'] = $masterType;
        $this->ajaxReturn( $ret );
    }
} 