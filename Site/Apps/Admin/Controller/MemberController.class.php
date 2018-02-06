<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Cache\Driver\Redis;

//use Think\Controller;
class MemberController extends CommonController {
    //用户列表
    public function index(){
        $this->display();
    }

     /*
     * 企业认证操作历史
     * $id => id
     * */
    public function MemberCompanyDetail(){
        $id=I('get.id','','int');
         if(!empty($id)){
             $member = D('Member');
             $companyHistory=$member->getCompanyHistory($id);
             if(!empty($companyHistory)){
                 $res['total'] = count($companyHistory);
                 $res['rows'] = array_values( $companyHistory );
             }else{
                 $res['total'] = 0;
                 $res['rows'] = array();
             }
             $this->ajaxReturn($res);
         }else{
             $res['msg']='数据异常';
             $res['code'] = '400';
             $res['data']['error']='数据异常';
             $this->ajaxReturn($res);
         }
    }

     /*
     * 企业签约操作历史
     * $id=>id
     * */
    public function MemberSignDetail(){
        $id=I('get.id','','int');
        if(!empty($id)){
            $memberSign = D('MemberSign');
            $sign = $memberSign->getSignDetail($id);
            if(!empty($sign)){
                $res['total']  = count($sign['history']);
                $res['rows'] = array_values($sign['history']);
            }else{
                $res['total'] = 0;
                $res['rows'] = array();
            }
            $this->ajaxReturn($res);
        }else{
            $res['msg']='数据异常';
            $res['code'] = '400';
            $res['data']['error']='数据异常';
            $this->ajaxReturn($res);
        }
    }

    //会员详情
    public function memberDetail(){
        $id=I('get.id','','int');
        if(empty($id))exit;
        $member = D('Member');

        //会员详情数据
        $info = $member->getMemberDetail($id);
        //公司详情数据
        $company = $member->getCompanyDetail($id);
        $company['type'] = isset($company['type'])?$company['type']:'';
        //判断是否完成资料
        $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$id ) );
        //根据用户的资料完善度、所有国家、企业认证来判断用户的认证是否通过。
        if(!$IsCompleteInfo)$info['company']['state']='0';
        elseif($info['personal']['country'] != 'CN')$info['company']['state']='1';
        //认证历史记录
        //签约详情数据
        $memberSign = D('MemberSign');
        $sign = $memberSign->getSignDetail($id);
        $this->assign('info',$info);
        $this->assign('company',$company);
        $this->assign('sign',$sign);
        $this->display();
//        $this->ajaxReturn($data);
    }


    //重置密码
    public function restPass(){
        $id=I('id');
        if($id){
            $member = D('Member');
            //修改密码
            $rest=$member->resetPassword($id);
            if($rest){
                $res['msg']='重置成功';
                $res['code']='200';
                $res['data']['password']=C('REST_PASS');
                $this->ajaxReturn($res);
            }else{
                $res['msg']='重置失败';
                $res['code']='400';
                $this->ajaxReturn($res);
            }
        }
    }

    //历史记录
    //type:member->商家历史记录;2company->企业历史记录；3sign->供应商签约记录
    public function historyrList(){
        $id=I('post.id','','int');
        $type=I('get.type','','string');
        if(empty($id))exit;
        if(empty($type))exit;

        if($type=='member' || $type=='company'){
            $member = D('Member');
            $data = $member->getHistoryList($type,$id);
        }elseif($type=='sign'){
            $memberSign = D('MemberSign');
            $data = $memberSign->getHistoryList($id);
        }

        $this->ajaxReturn($data);
    }



//    //公司详情
//    public function companyDetail(){
//        $id=I('get.id','','int');
//        if(empty($id))exit;
//        $member = D('Member');
//        $info = $member->getCompanyDetail($id);
//        if(!empty($info)){
//            $data=$info;
//        }
//        $this->ajaxReturn($data);
//    }
//
//    //签约详情
//    public function signDetail(){
//        $id=I('get.id','','int');
//        if(empty($id))exit;
//        $member = D('MemberSign');
//        $info = $member->getSignDetail($id);
//        if(!empty($info)){
//            $data=$info;
//        }
//        $this->ajaxReturn($data);
//    }

    //企业列表
    public function memberList(){
        //搜索条件
        $username=I('post.username','','string');//用户名
        $phone=I('post.phone','','string');//手机
        $companyName=I('post.companyName','','string');//公司名称
        $startDate=I('post.startDate','','string');//注册开始时间
        $endDate=I('post.endDate','','string');//注册结束时间
        $startDate=!empty($startDate)?strtotime($startDate):'';
        $endDate=!empty($endDate)?strtotime($endDate):'';
        $provinceId=I('post.provinceId','','int');//省份
        $cityId=I('post.cityId','','int');//市份
        $districtId=I('post.districtId','','int');//区份
        $companyState=I('post.companyState');//企业认证
        $status = I('post.status','','int');//状态
        $signState=I('post.signState','','int');//签约认证
        $source = I( 'post.source' );//来源
        $type = I( 'post.type' );//类型

        $page = I('post.page',1,'int');//页数
        $rows=I('post.rows',20,'int');//条数
        $offset=($page-1)*$rows;//位置

        $shell = D('Home/Shell');

        $whereArr=array();
        if(!empty($startDate) || !empty($endDate)){
            $endDate = $endDate + 24*3600-1;  //搜索的结束时间为当前日期的23时59分59秒。
            $whereArr[]='zset:member:addTime';//时间有序集合
        }

        !empty($username)?$whereArr[]=$shell->search('member:username',$username,'set'):'';//用户名全文搜索
        !empty($companyName)?$whereArr[]=$shell->search('member:companyName',strtolower($companyName),'set'):'';//公司名称全文搜索

        $areaId='';
        if(!empty($districtId))$areaId=$districtId;//省
        elseif(!empty($cityId))$areaId=$cityId;//市
        elseif(!empty($provinceId))$areaId=$provinceId;//区

        !empty($areaId)?$whereArr[]='set:area:member:'.$areaId:'';//地区id
        if( $companyState !== '' ){
            $whereArr[]='set:company:state:'.intval( $companyState );
        }
        !empty($signState)?$whereArr[]='set:member:sign:state:'.$signState:'';//签约认证

        $redis = new Redis();

        //账户状态start
        if(empty($status)){
            $statusWhereArr[]='set:member:status:1';
            $statusWhereArr[]='set:member:status:2';
            $randomkey = rand(0,9999);
            $tmpStatusZset='tmp:zset:member:status:'.$randomkey;
            $count = $redis->zUnion($tmpStatusZset,$statusWhereArr);
            if($count)$whereArr[]=$tmpStatusZset;
        }else{
            $whereArr[]='set:member:status:'.$status;
            $randomkey = rand(0,9999);
            $tmpStatusZset='tmp:zset:member:status:'.$randomkey;
        }
        //end
        $tmpUidPhoneSet = '';
        if(!empty($phone)){
            $uid = $redis->get('string:phone:'.$phone);//电话号码搜索
            if($uid>0){
                $arr=explode(',',$uid);
                $tmpUidPhoneSet = 'tmp:set:uid:phone:'.rand(0,9999);
                foreach( $arr as $v){
                    $redis->sadd($tmpUidPhoneSet,$v);
                    $whereArr[]=$tmpUidPhoneSet;
                }
            }else{
                $whereArr[]= false;
            }
        }

        if( !empty( $source ) ){
            $whereArr[] = 'set:member:source:'.$source;
        }
        if( !empty( $type ) ){
            $whereArr[] = 'set:member:type:'.$type;
        }

        $whereRandKey = rand(0,9999);//随机数
//        print_r($whereArr);exit;
        $tmpSet='tmp:zset:member:search:'.$whereRandKey;//交集临时集合
        $redis->zInter($tmpSet,$whereArr);//条件交集
//        print_r($redis->zRange($tmpSet,0,-1));

        $limit = array($offset, $rows);//分页数组

        $info=array();
        if(!empty($startDate) || !empty($endDate)){
            $count=$redis->zCount($tmpSet,$startDate,$endDate);
            $idArr = $redis->zRevRangeByScore($tmpSet,$endDate,$startDate,array('limit' => array($offset, $rows)));
            if($idArr){
                $country = $redis->hgetAll("hash:country:name");
                foreach($idArr as $key=>$vo){
                    $memberAll = $redis->hGetAll('hash:member:'.$vo);
//                print_r($memberAll);
                    $memberInfoAll = $redis->hGetAll('hash:member:info:'.$vo);
                    $memberSignAll = $redis->hGetAll('hash:member:sign:'.$vo);
                    $info[$key]['id']=$memberAll['id'];
                    $info[$key]['username']=$memberAll['username'];
                    $info[$key]['phone']=empty($memberAll['phone'])?'未填写':$memberAll['phone'];
                    $info[$key]['email']=empty($memberAll['email'])?'未填写':$memberAll['email'];
                    $info[$key]['companyName']=isset($memberInfoAll['companyName'])?$memberInfoAll['companyName']:'';
                    $info[$key]['contact']=isset($memberInfoAll['contact'])?$memberInfoAll['contact']:'';
                    if(!empty($memberInfoAll['companyName']))$info[$key]['perfectInformation']=1;
                    else $info[$key]['perfectInformation']=0;
                    //获取省市区start
                    if (isset($memberInfoAll['other'])) {
                        $tmpMemberArr = unserialize($memberInfoAll['other']);
                        $tmpMemberArr['area_x'] = isset($tmpMemberArr['area_x'])?$tmpMemberArr['area_x']:'';
                        $info[$key]['area']=$tmpMemberArr['area_s']?getAreaName($tmpMemberArr['area_s']):'';
                        $info[$key]['area'].=$info[$key]['area']?'-'.getAreaName($tmpMemberArr['area_c']):'';
                        $info[$key]['area'].=$info[$key]['area']?'-'.getAreaName($tmpMemberArr['area_x']):'';
                        $info[$key]['area'] = trim($info[$key]['area'],'-');
                    }else{
                        $info[$key]['area'] = '';
                    }
                    
                    //end
                    $info[$key]['companyState']=isset($memberInfoAll['state'])?$memberInfoAll['state']:'';
                    $info[$key]['intention']=isset($memberInfoAll['intention'])?$memberInfoAll['intention']:'';
                    $info[$key]['signState']=isset($memberSignAll['state'])?$memberSignAll['state']:'';
                    $info[$key]['addTime']=$memberAll['addTime'];
                    $info[$key]['country']=$country[$memberAll['country']];
                    $info[$key]['status']=$memberAll['status'];
                    $info[$key]['type']=$memberAll['type'];
                    $info[$key]['source']=$memberAll['source'];
                    $info[$key]['typeTip'] = '正常';
                    $info[$key]['sourceTip'] = '电脑';
                    switch( $info[$key]['type'] ){
                        case 'normal':
                            $info[$key]['typeTip'] = '正常';
                            break;
                        case 'india-show':
                            $info[$key]['typeTip'] = '印度会展';
                            break;
                    }
                    switch( $info[$key]['source'] ){
                        case 'wap':
                            $info[$key]['sourceTip'] = '移动端';
                            break;
                        case 'pc':
                            $info[$key]['sourceTip'] = '电脑';
                            break;
                    }
                }
            }
        }else{
            $count=$redis->zCount($tmpSet,0,999999);

            $sort_option['member_id']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:*->id'),
            );
            $sort_option['member_username']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:*->username'),
            );
            $sort_option['member_phone']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:*->phone'),
            );
            $sort_option['member_email']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:*->email'),
            );
            $sort_option['member_companyName']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:info:*->companyName'),
            );
            $sort_option['member_contact']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:info:*->contact'),
            );
            $sort_option['member_other']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:info:*->other'),
            );
            $sort_option['intention']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:info:*->intention'),
            );
            $sort_option['member_state']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:info:*->state'),
            );
            $sort_option['member_sign_state']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:sign:*->state'),
            );
            $sort_option['member_addTime']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:*->addTime'),
            );
            $sort_option['member_status']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:*->status'),
            );
            $sort_option['member_country']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:*->country'),
            );
            $sort_option['source']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:*->source'),
            );
            $sort_option['type']=array(
                'by'=>'hash:member:*->id',
                'limit' => $limit,
                'sort'=>'desc',
                'get'=>array('hash:member:*->type'),
            );

            $tmpKey=array();
            foreach($sort_option as $key=>$vo){
                $tmpKey[$key] = $redis->sort($tmpSet,$vo);
            }

            //删除临时集合
            $redis->del($tmpStatusZset,$tmpUidPhoneSet,$tmpSet);

            $country = $redis->hgetAll("hash:country:name");
            $info=array();
            for($i=0;$i<count($tmpKey['member_id']);$i++){
                $info[$i]['id']=$tmpKey['member_id'][$i];
                $info[$i]['username']=$tmpKey['member_username'][$i];
                $info[$i]['phone']=empty($tmpKey['member_phone'][$i])?'未填写':$tmpKey['member_phone'][$i];
                $info[$i]['email']=empty($tmpKey['member_email'][$i])?'未填写':$tmpKey['member_email'][$i];
                $info[$i]['companyName']=$tmpKey['member_companyName'][$i];
                $info[$i]['contact']=$tmpKey['member_contact'][$i];
                if(!empty($tmpKey['member_companyName'][$i]))$info[$i]['perfectInformation']=1;
                else $info[$i]['perfectInformation']=0;
                //获取省市区start
                $tmpStrArr = unserialize($tmpKey['member_other'][$i]);
                $info[$i]['area']=$tmpStrArr['area_s']?getAreaName($tmpStrArr['area_s']):'';
                $info[$i]['area'].=isset($tmpStrArr['area_c'])?'-'.getAreaName($tmpStrArr['area_c']):'';
                $info[$i]['area'].=isset($tmpStrArr['area_x'])?'-'.getAreaName($tmpStrArr['area_x']):'';
                $info[$i]['area'] = trim($info[$i]['area'],'-');
                //end
                $info[$i]['companyState']=$tmpKey['member_state'][$i];
                $info[$i]['intention']=$tmpKey['intention'][$i];
                $info[$i]['signState']=$tmpKey['member_sign_state'][$i];
                $info[$i]['addTime']=$tmpKey['member_addTime'][$i];
                $info[$i]['status']=$tmpKey['member_status'][$i];
                $info[$i]['country']=$country[$tmpKey['member_country'][$i]];
                $info[$i]['type']=$tmpKey['type'][$i];
                $info[$i]['source']=$tmpKey['source'][$i];
                $info[$i]['typeTip'] = '正常';
                $info[$i]['sourceTip'] = '电脑';
                switch( $info[$i]['type'] ){
                    case 'normal':
                            $info[$i]['typeTip'] = '正常';
                            break;
                    case 'india-show':
                        $info[$i]['typeTip'] = '印度会展';
                        break;
                }
                switch( $info[$i]['source'] ){
                    case 'wap':
                            $info[$i]['sourceTip'] = '移动端';
                            break;
                    case 'pc':
                            $info[$i]['sourceTip'] = '电脑';
                            break;
                }
            }
        }

        if(!empty($info)){
            $data['total']=$count;
            $data['rows']=$info;
        }else{
            $data['total']=0;
            $data['rows']=0;
        }
    //    print_r($data);exit;
        $this->ajaxReturn($data);

    }

    //批量禁用
    public function memberOperate(){
        if(!IS_POST) exit;
        $idStr = I('post.id');
        $status = I('post.status');
        if(empty($idStr))exit;
        if(!isset($status))exit;

        $reason = I('post.reason','','string');

        $member = D('Member');
        if($status == '2'){
            $re = $member ->RemoveDeletUser($idStr);
            if(!$re){
                $return['msg']='操作失败!';
                $return['code']=400;
            }
        }elseif ($status == '1') {
            $re = $member ->RevokeUser($idStr);
            if(!$re){
                $return['msg']='操作失败!';
                $return['code']=400;
            }
        }

        //启用操作
        $result = $member->memberOperateUpdate($idStr,$status,$reason);
        if(!$result){
            $return['msg']='操作失败!';
            $return['code']=400;
        }else{
            $return['msg']='操作成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    //批量删除
    public function memberOperateDel(){
        if(!IS_POST) exit;
        $idStr = I('post.id');
        $status = I('post.status');
        if(empty($idStr))exit;
        if(!isset($status))exit;

        $reason = I('post.reason','','string');

        $member = D('Member');

        //修改用户下的商品，求购，供应的状态
        $re = $member ->RemoveDeletUser($idStr);
        if(!$re){
            $return['msg']='操作失败!';
            $return['code']=400;
        }
        //启用操作
        $result = $member->memberOperateDel($idStr,$status,$reason);
        if(!$result){
            $return['msg']='操作失败!';
            $return['code']=400;
        }else{
            $return['msg']='操作成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    //用户组页面
    public function companyAuth(){
        $this->display();
    }

    //企业认证列表
    function companyAuthList(){
        //搜索条件
        $map=array();
        $companyName=I('post.companyName','','string');//公司名称
        $certType=I('post.certType','','int');//证照类型
        $state=I('post.state','','int');//企业认证

        $page = I('post.page',1,'int');
        $rows=I('post.rows',20,'int');
        $offset=($page-1)*$rows;

        $shell = D('Home/Shell');
        $redis = new Redis();

        $whereArr=array();
        !empty($companyName)?$whereArr[]=$shell->search('member:companyName',strtolower($companyName),'set'):'';
        !empty($certType)?$whereArr[]='set:member:company:certType:'.$certType:'';

        if($state!==''){
            $whereArr[]='set:company:state:'.$state;
        }else{
            $stateArr=array('set:company:state:0','set:company:state:1','set:company:state:2','set:company:state:3');
            $randomkey = rand(0,9999);
            $tmpStateList='tmp:zset:member:state:'.$randomkey;
            $redis->zUnion($tmpStateList,$stateArr);
            $whereArr[]=$tmpStateList;
        }
//        !empty($state)?$whereArr[]='set:company:state:'.$state:'';


        $statusWhereArr[]='set:member:status:1';
        $statusWhereArr[]='set:member:status:2';
        $randomkey = rand(0,9999);
        $tmpStatusZset='tmp:zset:member:status:'.$randomkey;
        $count = $redis->zUnion($tmpStatusZset,$statusWhereArr);
        if($count)$whereArr[]=$tmpStatusZset;

        $whereRandKey = rand(0,9999);

        $tmpSet='tmp:zset:company:search:'.$whereRandKey;
        $redis->zInter($tmpSet,$whereArr);//多个条件相交

        $info=array();

        $count=$redis->zCount($tmpSet,0,999999);
        $limit = array($offset, $rows);//分页数组
        $sort_option['id']=array(
            'by'=>'hash:member:info:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:info:*->id'),
        );
        $sort_option['companyName']=array(
            'by'=>'hash:member:info:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:info:*->companyName'),
        );
        $sort_option['cert']=array(
            'by'=>'hash:member:info:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:info:*->cert'),
        );
        $sort_option['state']=array(
            'by'=>'hash:member:info:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:info:*->state'),
        );
        $sort_option['addTime']=array(
            'by'=>'hash:member:info:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:*->addTime'),
        );

        //循环取数据
        $tmpKey=array();
        foreach($sort_option as $key=>$vo){
            $tmpKey[$key] = $redis->sort($tmpSet,$vo);
        }

        //删除临时集合
        $redis->del($tmpStateList,$tmpStatusZset,$tmpSet);

        $info=array();
        for($i=0;$i<count($tmpKey['id']);$i++){
            $info[$i]['id']=$tmpKey['id'][$i];
            $info[$i]['companyName']=$tmpKey['companyName'][$i];
            $tmpCertArr=unserialize($tmpKey['cert'][$i]);
            $info[$i]['businessCert']=isset($tmpCertArr['businessCert'])?$tmpCertArr['businessCert']:'';
            $info[$i]['accountCert']=isset($tmpCertArr['accountCert'])?$tmpCertArr['accountCert']:'';
            $info[$i]['codeCert']=isset($tmpCertArr['codeCert'])?$tmpCertArr['codeCert']:'';
            $info[$i]['taxCert']=isset($tmpCertArr['taxCert'])?$tmpCertArr['taxCert']:'';
            $info[$i]['type']=$tmpCertArr['type'];
            $info[$i]['addTime']=$tmpKey['addTime'][$i];
            $tmpStrArr = unserialize($tmpKey['cert'][$i]);
            $info[$i]['state']=$tmpKey['state'][$i];
            $historyId = $redis->hLen('hash:company:operation:history:'.$info[$i]['id']);
            $tmpHistory = $redis->hGet('hash:company:operation:history:'.$info[$i]['id'],$historyId);
            $tmpHistoryArr = unserialize($tmpHistory);
            $info[$i]['opera']=$tmpHistoryArr['opera'];
            $info[$i]['reason']=isset($tmpHistoryArr['reason'])?$tmpHistoryArr['reason']:'';
        }

        if(!empty($info)){
            $data['total']=$count;
            $data['rows']=$info;
        }else{
            $data['total']=0;
            $data['rows']=0;
        }
//        print_r($data);exit;
        $this->ajaxReturn($data);
    }

    public function companyVerify(){
        if(!IS_POST) exit;
        $id = I('post.id','','int');
        $prevState = I('post.prevState','','int');
        $state = I('post.state','','int');
        if(empty($id))exit;
        if(!isset($prevState))exit;
        if(!isset($state))exit;

        $redis = new redis();
        $companyName = $redis->hGet('hash:member:info:'.$id,'companyName');
        $companyType = $redis->get('string:company:'.$companyName);
//        echo $companyName;exit;
        /*   if($companyType && $state==1){
                $return['msg']=$companyName.'已认证过';
                $return['code']=400;
                $this->ajaxReturn($return);
            }*/

        $reason = I('post.reason','','string');

        $member = D('Member');
        //启用操作
        $result = $member->companyVerifyUpdate($id,$prevState,$state,$reason);
        if(!$result){
            $return['msg']='执行失败!';
            $return['code']=400;
        }else{
            $return['msg']='执行成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    public function memberSign(){
        $this->display();
    }

    public function signAdd(){
        if(!IS_POST) exit;
        $memberSign = D('MemberSign');
        $data = I('post.');
        $data['addTime']=time();


        if(empty($data['companyName'])){
            $return['msg']='公司名称不能为空';
            $return['code']=400;
            $this->ajaxReturn($return);
        }elseif($data['uid']=$memberSign->companyGetUid($data['companyName'])){
            unset($data['companyName']);
        }else{
            $return['msg']='公司名称错误';
            $return['code']=400;
            $this->ajaxReturn($return);
        }


        if (!$data=$memberSign->create($data)){
            $return['msg']=$memberSign->getError();
            $return['code']=400;
        }else{
            $data['contractTime']=strtotime($data['contractTime']);
            $data['expireTime']=strtotime($data['expireTime']);
            $addResult = $memberSign->memberSignAdd($data);
            if(!$addResult){
                $return['msg']='添加失败!';
                $return['code']=400;
            }else{
                $return['msg']='添加成功!';
                $return['code']=200;
            }
        }

        $this->ajaxReturn($return);
    }

    //修改签约
    public function signSave(){
        if(!IS_POST) exit;

        $memberSign = D('MemberSign');
        $data = I('post.');
        $data['addTime']=time();

        if(empty($data['companyName'])){
            $return['msg']='公司名称不能为空';
            $return['code']=400;
            $this->ajaxReturn($return);
        }elseif($data['uid']=$memberSign->companyGetUid($data['companyName'])){
            unset($data['companyName']);
        }else{
            $return['msg']='公司名称错误';
            $return['code']=400;
            $this->ajaxReturn($return);
        }

        if (!$data=$memberSign->create($data)){
            $return['msg']=$memberSign->getError();
            $return['code']=400;
        }else{
            $data['contractTime']=strtotime($data['contractTime']);
            $data['expireTime']=strtotime($data['expireTime']);
            $addResult = $memberSign->memberSignSave($data);
            if(!$addResult){
                $return['msg']='修改失败!';
                $return['code']=400;
            }else{
                $return['msg']='修改成功!';
                $return['code']=200;
            }
        }

        $this->ajaxReturn($return);
    }

    //签约认证列表
    function memberSignList(){
        //搜索条件
        $map=array();
        $code=I('post.code','','string');//合同编号
        $companyName=I('post.companyName','','string');//公司名称
//        $startCooperation=I('post.startCooperation','','string');
//        $endCooperation=I('post.endCooperation','','string');
//        $startCooperation=!empty($startCooperation)?strtotime($startCooperation):'';
//        $endCooperation=!empty($endCooperation)?strtotime($endCooperation):'';
//        $provinceId=I('post.provinceId','','int');//省份
//        $cityId=I('post.cityId','','int');//市份
//        $districtId=I('post.districtId','','int');//区份
//        $startContractTime =I('post.startContractTime','','string');
//        $endContractTime=I('post.endContractTime','','string');
//        $startContractTime=!empty($startContractTime)?strtotime($startContractTime):'';
//        $endContractTime=!empty($endContractTime)?strtotime($endContractTime):'';
//        $startExpireTime =I('post.startExpireTime','','string');
//        $endExpireTime=I('post.endExpireTime','','string');
//        $startExpireTime=!empty($startExpireTime)?strtotime($startExpireTime):'';
//        $endExpireTime=!empty($endExpireTime)?strtotime($endExpireTime):'';
        $state=I('post.state','','int');//签约认证

        $page = I('post.page',1,'int');
        $rows=I('post.rows',20,'int');
        $offset=($page-1)*$rows;

        $shell = D('Home/Shell');
        $redis = new Redis();

        $whereArr=array();

//        if(!empty($startCooperation) || !empty($endCooperation)){
//            $whereArr[]='zset:member:addTime';
//        }

        !empty($companyName)?$whereArr[]=$shell->search('member:companyName',$companyName,'set'):'';
        !empty($code)?$whereArr[]='set:member:sign:code:'.$code:'';
        if($state!==''){
            $whereArr[]='set:member:sign:state:'.$state;
        }else{
            $stateArr=array('set:member:sign:state:0','set:member:sign:state:1','set:member:sign:state:2','set:member:sign:state:3');
            $randomkey = rand(0,9999);
            $tmpStateList='tmp:zset:member:state:'.$randomkey;
            $redis->zUnion($tmpStateList,$stateArr);
            $whereArr[]=$tmpStateList;
        }


        $statusWhereArr[]='set:member:sign:status:1';
        $statusWhereArr[]='set:member:sign:status:2';
        $randomkey = rand(0,9999);
        $tmpStatusZset='tmp:zset:member:sign:status:'.$randomkey;
        $count = $redis->zUnion($tmpStatusZset,$statusWhereArr);
        if($count)$whereArr[]=$tmpStatusZset;

        $whereRandKey = rand(0,9999);

        $tmpSet='tmp:zset:memberSign:search:'.$whereRandKey;

        $redis->zInter($tmpSet,$whereArr);//多个条件相交
        $count=$redis->zCount($tmpSet,0,999999);
        $limit = array($offset, $rows);//分页数组
        $sort_option['id']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->id'),
        );
        $sort_option['code']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->code'),
        );
        $sort_option['companyName']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:info:*->companyName'),
        );
        $sort_option['other']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:info:*->other'),
        );
        $sort_option['cooperation']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->cooperation'),
        );
        $sort_option['contractTime']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->contractTime'),
        );
        $sort_option['content']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->content'),
        );
        $sort_option['expireTime']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->expireTime'),
        );
        $sort_option['signatory']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->signatory'),
        );
        $sort_option['state']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->state'),
        );
        $sort_option['attachment']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->attachment'),
        );
        $sort_option['state']=array(
            'by'=>'hash:member:sign:*->id',
            'limit' => $limit,
            'sort'=>'desc',
            'get'=>array('hash:member:sign:*->state'),
        );

        //循环取数据
        $tmpKey=array();
        foreach($sort_option as $key=>$vo){
            $tmpKey[$key] = $redis->sort($tmpSet,$vo);
        }
//        print_r($tmpKey);exit;
        $redis->del($tmpStateList,$tmpStatusZset,$tmpSet);//删除临时集合
//        print_r($tmpKey);exit;
        $info=array();
        for($i=0;$i<count($tmpKey['id']);$i++){
            $info[$i]['id']=$tmpKey['id'][$i];
            $info[$i]['code']=$tmpKey['code'][$i];
            $info[$i]['companyName']=$tmpKey['companyName'][$i];
            //获取省市区start
            $tmpMemberArr = unserialize($tmpKey['other'][$i]);
            $info[$i]['area']=$tmpMemberArr['area_s']?getAreaName($tmpMemberArr['area_s']):'';
            $info[$i]['area'].=$info[$i]['area']?'-'.getAreaName($tmpMemberArr['area_c']):'';
            $info[$i]['area'].=$info[$i]['area']?'-'.getAreaName($tmpMemberArr['area_x']):'';
            //end
            $info[$i]['cooperation']=$tmpKey['cooperation'][$i];
            $info[$i]['contractTime']=$tmpKey['contractTime'][$i];
            $info[$i]['expireTime']=$tmpKey['expireTime'][$i];
            $info[$i]['signatory']=$tmpKey['signatory'][$i];
            $info[$i]['addTime']=$tmpKey['addTime'][$i];
            $info[$i]['content']=$tmpKey['content'][$i];
//            if($info[$i]['expireTime']<time())$info[$i]['state']=5;
//            else $info[$i]['state']=$tmpKey['state'][$i];
            $info[$i]['state']=$tmpKey['state'][$i];

            if(!empty($tmpKey['attachment'][$i])){
                $tmpAttachmentArr=unserialize($tmpKey['attachment'][$i]);
                $attachment = implode(',',$tmpAttachmentArr);
            }else{
                $attachment='';
            }
            $info[$i]['attachment']=$attachment;
        }
     //或者最后一次的操作历史
        foreach($info as $k=>$v){
            $history=$redis->hgetAll("hash:member:sign:operation:history:{$v['id']}");
            $hit = array();
            foreach($history as $k1=>$v1){
                $hit[$k1]=unserialize($history[$k1]);
            }
            ksort($hit);
           $info[$k]['reason']=end($hit)['reason'];
        }

        if(!empty($info)){
            $data['total']=$count;
            $data['rows']=$info;
        }else{
            $data['total']=0;
            $data['rows']=0;
        }
//        print_r($data);exit;
        $this->ajaxReturn($data);
    }

    //签约操作
    public function signVerify(){
        if(!IS_POST) exit;
        $id = I('post.id','','int');
        $state = I('post.state','','int');
        $prevState = I('post.prevState','','int');
        if(empty($id))exit;
        if(!isset($state))exit;
        if(!isset($state))exit;

        $reason = I('post.reason','','string');
        $member = D('MemberSign');
        //启用操作
        $result = $member->signVerifyUpdate($id,$prevState,$state,$reason);
        if(!$result){
            $return['msg']='执行失败!';
            $return['code']=400;
        }else{
            $return['msg']='执行成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    public function getChildArea($id){
//        if(!IS_POST) exit;
        $id = I('get.id','','int');
        if(!isset($id))exit;
        $area=D('Area');
        $info = $area->getChildArea($id);
        $this->ajaxReturn($info);
    }

    //商家列表--数据导出
    public function expMember(){
        $companyState = I( 'get.companyState' );//来源
        $m_status = I( 'get.status' );//类型

        $redis = \Think\Cache::getInstance('Redis');
        /*set:member:status:1,2并集*/
        $status=array(
            '1'=>'正常',
            '2'=>'禁用'
        );
        $state=array(
            '0'=>'审核不通过',
            '1'=>'审核通过',
            '2'=>'待审核',
            '3'=>'已撤销',
        );
        $signs=array(
            '0'=>'审核不通过',
            '1'=>'审核通过',
            '2'=>'待审核',
            '3'=>'已撤销',
            '4'=>'已过期'
        );
        $tid=uniqid();
         $count=$redis->SUNIONSTORE("tmp:member:".$tid,'set:member:status:1','set:member:status:2');
         $whereArr[] = 'tmp:member:'.$tid;
        if( !empty( $companyState ) ){
            $whereArr[] = 'set:company:state:'.$companyState;
        }
        if( !empty( $m_status ) ){
            $whereArr[] = 'set:member:status:'.$m_status;
        }
        $redis->zInter("tmp:member:".$tid,$whereArr);//条件交集
        if($count && $redis->expire("tmp:member:".$tid,60)){
            $member_options=array(
                'get'=>array(
                    'hash:member:*->id','hash:member:*->username','hash:member:*->phone',
                    'hash:member:*->email','hash:member:*->addTime','hash:member:*->status'
                )
            );
            $memberArr=$redis->sort("tmp:member:".$tid,$member_options);
            if($memberArr){
                $num=0;
                foreach($memberArr as $k=>$v){
                     if($k%6==0){
                         $member[$num]['id']=$v;
                     }elseif($k%6==1){
                         $member[$num]['username']=$v;
                     }elseif($k%6==2){
                         $member[$num]['phone']=empty($v)?'未填写':' '.$v;
                     }elseif($k%6==3){
                         $member[$num]['email']=empty($v)?'未填写':' '.$v;
                     }elseif($k%6==4){
                         $member[$num]['addTime']=date('Y-m-d H:i:s',$v);
                     }elseif($k%6==5){
                         $member[$num]['status']=$status[$v];
                         $num++;
                     }
                }
            }

            if(!empty($_GET['id'])){
                $id=explode(',', I('id'));
            }
            $arr=array();
            if(!empty($id)){
                foreach ($member as $k){
                    if(in_array($k['id'], $id)){
                        $arr[]=$k;
                    }
                }
                $member=$arr;
            }

            //拼接企业信息
            foreach($member as $k1=>$v1){
                $infoArr=$redis->hmget("hash:member:info:{$v1['id']}",array('companyName','contact','other','state'));
                $member[$k1]['companyName']=empty($infoArr['companyName'])?'未填写':$infoArr['companyName'];
                $member[$k1]['contact']=empty($infoArr['contact'])?'未填写':$infoArr['contact'];
                $member[$k1]['state']=$state[$infoArr['state']];
                $other=unserialize($infoArr['other']);
                $area_s=$redis->hget("hash:area:{$other['area_s']}",'title');
                $area_c=$redis->hget("hash:area:{$other['area_c']}",'title');
                if(isset($other['area_x'])){
                    $area_x=$redis->hget("hash:area:{$other['area_x']}",'title');
                }else{
                    $area_x='';
                }
                $member[$k1]['area']=$area_s.'-'.$area_c.'-'.$area_x;
                $member[$k1]['area'] = trim($member[$k1]['area'],'-');
                $member[$k1]['area'] = empty($member[$k1]['area'])?'未填写':$member[$k1]['area'];
                //签约
                $sign=$redis->hget("hash:member:sign:{$v1['id']}",'state');
               if($sign){
                   $member[$k1]['sign']=$signs[$sign['state']];
               }else{
                   $member[$k1]['sign']='未添加';
               }

                if($redis->exists("hash:member:info:{$v1['id']}")){
                    $member[$k1]['complete']='是';
                }else{
                    $member[$k1]['complete']='否';
                }
            }


        }
        $xlsName  = "商家认证列表";
        $xlsCell  = array(
            array('username','用户名'),
            array('email','邮箱'),
            array('phone','手机'),
            array('companyName','公司名称'),
            array('contact','指定联系人'),
            array('area','所在地区'),
            array('complete','完善资料'),
            array('state','企业认证'),
            array('sign','签约为联营供应商'),
            array('addTime','注册时间'),
            array('status','状态')

        );
        $xlsData = $member;//读取列表
        exportExcel($xlsName,$xlsCell,$xlsData);
    }  


        //商家认证--数据导出
    public function expAuth()
    {
        /*先把所有的认证状态并集，然后与正常状态的用户交集*/
        $user = D('User');

        $_certType = C('CERT_TYPE');
        $AuthState = C('AUTH_STATE');
        //搜索条件
        $map = array();
        $companyName = I('get.companyName', '', 'string');//公司名称
        $certType = I('get.certType', '', 'int');//证照类型
        $state = I('get.state', '', 'int');//企业认证

        $shell = D('Home/Shell');
        $redis = new Redis();

        $whereArr = array();
        !empty($companyName) ? $whereArr[] = $shell->search('member:companyName', $companyName, 'set') : '';

        !empty($certType) ? $whereArr[] = 'set:member:company:certType:' . $certType : '';

        if ($state !== '') {
            $whereArr[] = 'set:company:state:' . $state;
        } else {
            $stateArr = array('set:company:state:0', 'set:company:state:1', 'set:company:state:2', 'set:company:state:3');
            $randomkey = rand(0, 9999);
            $tmpStateList = 'tmp:zset:member:state:' . $randomkey;
            $redis->zUnion($tmpStateList, $stateArr);
            $whereArr[] = $tmpStateList;
        }
//        !empty($state)?$whereArr[]='set:company:state:'.$state:'';

        $statusWhereArr[] = 'set:member:status:1';
        $statusWhereArr[] = 'set:member:status:2';
        $randomkey = rand(0, 9999);
        $tmpStatusZset = 'tmp:zset:member:status:' . $randomkey;
        $count = $redis->zUnion($tmpStatusZset, $statusWhereArr);
        if ($count) $whereArr[] = $tmpStatusZset;

        $whereRandKey = rand(0, 9999);

        $tmpSet = 'tmp:zset:company:search:' . $whereRandKey;

        $redis->zInter($tmpSet, $whereArr);//多个条件相交

        $count = $redis->zCount($tmpSet, 0, 999999);
        $sort_option['id'] = array(
            'by' => 'hash:member:info:*->id',
            'sort' => 'desc',
            'get' => array('hash:member:info:*->id'),
        );
        $sort_option['companyName'] = array(
            'by' => 'hash:member:info:*->id',
            'sort' => 'desc',
            'get' => array('hash:member:info:*->companyName'),
        );
        $sort_option['cert'] = array(
            'by' => 'hash:member:info:*->id',
            'sort' => 'desc',
            'get' => array('hash:member:info:*->cert'),
        );
        $sort_option['state'] = array(
            'by' => 'hash:member:info:*->id',
            'sort' => 'desc',
            'get' => array('hash:member:info:*->state'),
        );
        $sort_option['addTime'] = array(
            'by' => 'hash:member:info:*->id',
            'sort' => 'desc',
            'get' => array('hash:member:*->addTime'),
        );

        //循环取数据
        $tmpKey = array();
        foreach ($sort_option as $key => $vo) {
            $tmpKey[$key] = $redis->sort($tmpSet, $vo);
        }
        //删除临时集合
        $redis->del($tmpStateList, $tmpStatusZset, $tmpSet);

        $info = array();
        for($i=0;$i<count($tmpKey['id']);$i++){
            $info[$i]['id']=$tmpKey['id'][$i];
            $info[$i]['companyName']=$tmpKey['companyName'][$i];
            $tmpCertArr=unserialize($tmpKey['cert'][$i]);
            $info[$i]['businessCert']=$tmpCertArr['businessCert'];
            $info[$i]['accountCert']=$tmpCertArr['accountCert'];
            $info[$i]['codeCert']=$tmpCertArr['codeCert'];
            $info[$i]['taxCert']=$tmpCertArr['taxCert'];
            $info[$i]['type']=$tmpCertArr['type'];
            $info[$i]['addTime']=$tmpKey['addTime'][$i];
            $tmpStrArr = unserialize($tmpKey['cert'][$i]);
            $info[$i]['state']=$tmpKey['state'][$i];
            $historyId = $redis->hLen('hash:company:operation:history:'.$info[$i]['id']);
            $tmpHistory = $redis->hGet('hash:company:operation:history:'.$info[$i]['id'],$historyId);
            $tmpHistoryArr = unserialize($tmpHistory);
            $info[$i]['opera']=$tmpHistoryArr['opera'];
            $info[$i]['reason']=$tmpHistoryArr['reason'];
        }

        if(!empty($_GET['id'])){
            $id=explode(',', I('id'));
        }
        $arr=array();
        if(!empty($id)){
            foreach ($info as $k){
                if(in_array($k['id'], $id)){
                    $arr[]=$k;
                }
            }
            $info=$arr;
        }

        foreach ($info as $k1 => $v1) {
            $info[$k1]['type'] = $_certType[$info[$k1]['type']];
            $info[$k1]['state'] = $AuthState[$info[$k1]['state']];
            $info[$k1]['businessCert'] = empty($info[$k1]['businessCert']) ? '×' : '√';//营业执照
            $info[$k1]['codeCert'] = empty($info[$k1]['codeCert']) ? '×' : '√';//组织机构代码证
            $info[$k1]['taxCert'] = empty($info[$k1]['taxCert']) ? '×' : '√';//税务登记证
            $info[$k1]['accountCert'] = empty($info[$k1]['accountCert']) ? '×' : '√';//开户许可证
            //添加时间
            $addTime = $redis->hget("hash:member:{$v1['id']}", 'addTime');
            $info[$k1]['addTime'] = empty(date('Y-m-d H:i:s', $addTime))?'':date('Y-m-d H:i:s', $addTime);
            $info[$k1]['updateTime'] = empty(date('Y-m-d H:i:s', $addTime))?'':date('Y-m-d H:i:s', $addTime);
            $history = $redis->hgetAll("hash:company:operation:history:{$v1['id']}");
            ksort($history);
            foreach ($history as $k2 => $v2) {
                $hits[$k2] = unserialize($v2);
                foreach ($hits as $k3 => $v3) {
                    if ($v3['state'] == 2) {
                        unset($hits[$k3]);
                    }
                }
            }

            $first = array_slice($hits,0,1)[0];
            $last = end($hits);
            $info[$k1]['firstTime'] = empty($first['addTime']) ? '' : date('Y-m-d H:i:s', $first['addTime']);
            $firstName = $user->field('username,realname')->find($first['oid']);
            $info[$k1]['firstName'] = $firstName['realname'];
            $info[$k1]['lastTime'] = empty($last['addTime']) ? '' : date('Y-m-d H:i:s', $last['addTime']);
            $lastName = $user->field('username,realname')->find($last['oid']);
            $info[$k1]['lastName'] = $lastName['realname'];
             }

            $xlsName  = "企业认证列表";
            $xlsCell  = array(
                array('companyName','公司名称'),
                array('type','证件类型'),
                array('businessCert','营业执照'),
                array('codeCert','组织机构代码证'),
                array('taxCert','税务登记证'),
                array('accountCert','开户许可证'),
                array('state','状态'),
                array('addTime','创建时间'),
                array('updateTime','最新修改时间'),
                array('firstTime','初始审核时间'),
                array('firstName','初始审核人'),
                array('lastTime','最新审核时间'),
                array('lastName','最新审核人')
            );
            $xlsData = $info;//读取列表
            exportExcel($xlsName,$xlsCell,$xlsData);
    }


    //录入签约--数据导出
    public function expSign(){
        /*交集得到有效的用户id*/
        $redis = \Think\Cache::getInstance('Redis');
        $user=D('User');
        //签约状态
        $signState=C('SIGN_STATE');
        //合作年度
        $coopDate=C('COOPERATION_DATA');
        $tid=uniqid();
        $sunion=$redis->SUNIONSTORE("tmp:member:list:".$tid,'set:member:sign:state:0','set:member:sign:state:1','set:member:sign:state:2','set:member:sign:state:3');
        $count=$redis->SINTERSTORE("set:tmp:sign:{$tid}",'set:member:sign:status:1',"tmp:member:list:".$tid);
        if($count && $redis->expire("set:tmp:sign:{$tid}",60) && $sunion && $redis->expire("tmp:member:list:".$tid,60)){
            $member_sign=array(
                'get'=>array(
                    'hash:member:sign:*->id', 'hash:member:sign:*->code', 'hash:member:sign:*->cooperation',
                    'hash:member:sign:*->contractTime','hash:member:sign:*->expireTime', 'hash:member:sign:*->addTime',
                    'hash:member:sign:*->state'
                )
            );
            $signArr=$redis->sort("set:tmp:sign:{$tid}",$member_sign);
            $num=0;
            if($signArr){
                foreach($signArr as $k1=>$v1){
                    if($k1%7==0){
                        $sign[$num]['id']=$v1;
                    }elseif($k1%7==1){
                        $sign[$num]['code']=$v1;
                    }elseif($k1%7==2){
                        $sign[$num]['cooperation']=$coopDate[$v1]['title'];
                    }elseif($k1%7==3){
                        $sign[$num]['contractTime']=date('Y-m-d H:i:s',$v1);
                    }elseif($k1%7==4){
                        $sign[$num]['expireTime']=date('Y-m-d H:i:s',$v1);
                    }elseif($k1%7==5){
                        $sign[$num]['addTime']=date('Y-m-d H:i:s',$v1);
                    }elseif($k1%7==6){
                        $sign[$num]['state']=$signState[$v1];
                        $num++;
                    }
                }
            }
            foreach($sign as $k2=>$v2){
                //公司名
                $member=$redis->hmget("hash:member:info:{$v2['id']}",array('companyName','other'));
                $sign[$k2]['companyName']=$member['companyName'];
                $other=unserialize($member['other']);
                $area_s=$redis->hget("hash:area:{$other['area_s']}",'title');
                $area_c=$redis->hget("hash:area:{$other['area_c']}",'title');
                $area_x=$redis->hget("hash:area:{$other['area_x']}",'title');
                $sign[$k2]['area']=$area_s.'-'.$area_c.'-'.$area_x;
                //获取所有的操作记录,待审核的为初始添加
                $history=$redis->hgetAll("hash:member:sign:operation:history:{$v2['id']}");
                ksort($history);
                foreach($history as $k3=>$v3){
                    $InSignHits[$k3]=unserialize($v3);
                    //第一条为初始添加，最后一条为最新修改
                   foreach($InSignHits as $k4=>$v4){
                        if($v4['state']){
                            unset($InSignHits[$k4]);
                            ksort($InSignHits);
                        }
                    }
                    $SignHits[$k3]=unserialize($v3);
                    //第一条为初始添加，最后一条为最新修改
                    foreach($SignHits as $k5=>$v5){
                        if($v5['state']==null){
                            unset($SignHits[$k5]);
                            array_keys($SignHits);
                        }
                    }
                }
               /* echo '<pre>';
                var_dump($SignHits);*/

                //初始录入
                $start=current($InSignHits);
                //最新录入
                $end=end($InSignHits);
                $sign[$k2]['startTime']=empty($start['addTime'])?'':date('Y-m-d H:i:s',$start['addTime']);
                $startUserName=$user->field('username,realname')->find($start['oid']);
                $sign[$k2]['startName']=$startUserName['realname'];
                $sign[$k2]['endTime']=empty($end['addTime'])?'':date('Y-m-d H:i:s',$end['addTime']);
                $endUserName=$user->field('username,realname')->find($end['oid']);
                $sign[$k2]['endName']=$endUserName['realname'];

                //最初审核
                $first=reset($SignHits);
                $last=end($SignHits);
                $sign[$k2]['firstTime']=empty($first['addTime'])?'':date('Y-m-d H:i:s',$first['addTime']);
                $firstUserName=$user->field('username,realname')->find($first['oid']);
                $sign[$k2]['firstName']=$firstUserName['realname'];
                $sign[$k2]['lastTime']=empty($last['addTime'])?'':date('Y-m-d H:i:s',$last['addTime']);
                $lastUserName=$user->field('username,realname')->find($last['oid']);
                $sign[$k2]['lastName']=$lastUserName['realname'];
            }

            /*echo '<pre>';
            var_dump($sign);exit;*/
            $xlsName  = "录入签约";
            $xlsCell  = array(
                array('code','合同编号'),
                array('companyName','公司名称'),
                array('area','所在地区'),
                array('cooperation','合作年度'),
                array('contractTime','合同签约时间'),
                array('expireTime','合同到期时间'),
                array('state','状态'),
                array('startTime','初始录入时间'),
                array('startName','初始录入人'),
                array('endTime','最新修改时间'),
                array('endName','最新修改人'),
                array('firstTime','初始审核时间'),
                array('firstName','初始审核人'),
                array('lastTime','最新审核时间'),
                array('lastName','最新审核人')
            );

            $xlsData = $sign;//读取列表
            exportExcel($xlsName,$xlsCell,$xlsData);
        }

    }

    //录入审核--数据导出
    public function expSignAuth(){
        /*交集得到有效的用户id*/
        $redis = \Think\Cache::getInstance('Redis');
        $user=D('User');
        //签约状态
        $signState=C('SIGN_STATE');
        //合作年度
        $coopDate=C('COOPERATION_DATA');
        $tid=uniqid();
        $sunion=$redis->SUNIONSTORE("tmp:member:list:".$tid,'set:member:sign:state:0','set:member:sign:state:1','set:member:sign:state:2','set:member:sign:state:3');
        $count=$redis->SINTERSTORE("set:tmp:sign:{$tid}",'set:member:sign:status:1',"tmp:member:list:".$tid);
        if($count && $redis->expire("set:tmp:sign:{$tid}",60) && $sunion && $redis->expire("tmp:member:list:".$tid,60)){
            $member_sign=array(
                'get'=>array(
                    'hash:member:sign:*->id', 'hash:member:sign:*->code', 'hash:member:sign:*->cooperation',
                    'hash:member:sign:*->contractTime','hash:member:sign:*->expireTime', 'hash:member:sign:*->addTime',
                    'hash:member:sign:*->state'
                )
            );
            $signArr=$redis->sort("set:tmp:sign:{$tid}",$member_sign);
            $num=0;
            if($signArr){
                foreach($signArr as $k1=>$v1){
                    if($k1%7==0){
                        $sign[$num]['id']=$v1;
                    }elseif($k1%7==1){
                        $sign[$num]['code']=$v1;
                    }elseif($k1%7==2){
                        $sign[$num]['cooperation']=$coopDate[$v1]['title'];
                    }elseif($k1%7==3){
                        $sign[$num]['contractTime']=date('Y-m-d H:i:s',$v1);
                    }elseif($k1%7==4){
                        $sign[$num]['expireTime']=date('Y-m-d H:i:s',$v1);
                    }elseif($k1%7==5){
                        $sign[$num]['addTime']=date('Y-m-d H:i:s',$v1);
                    }elseif($k1%7==6){
                        $sign[$num]['state']=$signState[$v1];
                        $num++;
                    }
                }
            }
            foreach($sign as $k2=>$v2){
                //公司名
                $member=$redis->hmget("hash:member:info:{$v2['id']}",array('companyName','other'));
                $sign[$k2]['companyName']=$member['companyName'];
                $other=unserialize($member['other']);
                $area_s=$redis->hget("hash:area:{$other['area_s']}",'title');
                $area_c=$redis->hget("hash:area:{$other['area_c']}",'title');
                $area_x=$redis->hget("hash:area:{$other['area_x']}",'title');
                $sign[$k2]['area']=$area_s.'-'.$area_c.'-'.$area_x;
                //获取所有的操作记录,待审核的为初始添加
                $history=$redis->hgetAll("hash:member:sign:operation:history:{$v2['id']}");
                ksort($history);
                foreach($history as $k3=>$v3){
                    $InSignHits[$k3]=unserialize($v3);
                    //第一条为初始添加，最后一条为最新修改
                    foreach($InSignHits as $k4=>$v4){
                        if($v4['state']){
                            unset($InSignHits[$k4]);
                            ksort($InSignHits);
                        }
                    }
                    $SignHits[$k3]=unserialize($v3);
                    //第一条为初始添加，最后一条为最新修改
                    foreach($SignHits as $k5=>$v5){
                        if($v5['state']==null){
                            unset($SignHits[$k5]);
                            array_keys($SignHits);
                        }
                    }
                }
                /* echo '<pre>';
                 var_dump($SignHits);*/

                //初始录入
                $start=current($InSignHits);
                //最新录入
                $end=end($InSignHits);
                $sign[$k2]['startTime']=empty($start['addTime'])?'':date('Y-m-d H:i:s',$start['addTime']);
                $startUserName=$user->field('username,realname')->find($start['oid']);
                $sign[$k2]['startName']=$startUserName['realname'];
                $sign[$k2]['endTime']=empty($end['addTime'])?'':date('Y-m-d H:i:s',$end['addTime']);
                $endUserName=$user->field('username,realname')->find($end['oid']);
                $sign[$k2]['endName']=$endUserName['realname'];

                //最初审核
                $first=reset($SignHits);
                $last=end($SignHits);
                $sign[$k2]['firstTime']=empty($first['addTime'])?'':date('Y-m-d H:i:s',$first['addTime']);
                $firstUserName=$user->field('username,realname')->find($first['oid']);
                $sign[$k2]['firstName']=$firstUserName['realname'];
                $sign[$k2]['lastTime']=empty($last['addTime'])?'':date('Y-m-d H:i:s',$last['addTime']);
                $lastUserName=$user->field('username,realname')->find($last['oid']);
                $sign[$k2]['lastName']=$lastUserName['realname'];
            }

            /*echo '<pre>';
            var_dump($sign);exit;*/
            $xlsName  = "录入审核";
            $xlsCell  = array(
                array('code','合同编号'),
                array('companyName','公司名称'),
                array('area','所在地区'),
                array('cooperation','合作年度'),
                array('contractTime','合同签约时间'),
                array('expireTime','合同到期时间'),
                array('state','状态'),
                array('startTime','初始录入时间'),
                array('startName','初始录入人'),
                array('endTime','最新修改时间'),
                array('endName','最新修改人'),
                array('firstTime','初始审核时间'),
                array('firstName','初始审核人'),
                array('lastTime','最新审核时间'),
                array('lastName','最新审核人')
            );

            $xlsData = $sign;//读取列表
            exportExcel($xlsName,$xlsCell,$xlsData);
        }

    }
}