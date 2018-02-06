<?php
namespace User\Controller;
use       Think\Controller;

class AccountController extends CommonController{

    /*会员中心首页*/
    public function index(){
        $this->checkLogin();
        $this->checkBindEmail();
        /*获取用户信息*/
        $member = D('Member');
        //未读信息数
        $unreadMessages = getUnReadMessage();
        //求购数
        $buyOffers = D('Buyoffer')->getCount($this->uid);

        //CollectModel 里的 getCount 方法不是获取某个用户的收藏数量，我加了个 getUserCount 方法，502 出现的原因
        $collectBuyOffersNum = D('Collect')->getUserCount(['type' => 0, 'uid' => $this->uid]);
        $collectNum = D('Collect')->getUserCount(['type' => 1, 'uid' => $this->uid]);

        //收藏数
        $favoritesItems = $collectBuyOffersNum + $collectNum;
        //最近消息
        $recentMessage = D('Message')->getMessageList(['uid'=>$this->uid, 'pageSize' => 5]);
        if(!empty($recentMessage['list'])){
            $recentMessage = $recentMessage['list'];
        }else{
            $recentMessage = array();
        }
        //得到国家地址
        $country = getAllCountry();
        //获取有效中的商品数
        $productState = D( 'Home/Product' )->getProductState();
        $sellingProduct = D( 'Home/Product' )->lists( array( 'uid' => $this->uid, 'state' => $productState['ACTIVE']['value'] ) );
        $sellingProduct['count'] = isset($sellingProduct['count'])?$sellingProduct['count']:0;
        $this->assign( 'productCount', intval( $sellingProduct['count'] ) );

        $this->assign('country', $country);
        $this->assign('unreadMessages', $unreadMessages);
        $this->assign('buyOffers', $buyOffers);
        $this->assign('favoritesItems', $favoritesItems);
        $this->assign('recentMessage', $recentMessage);
        $this->assign('memberObj', $member->get($this->uid));
        $this->display();
    }

    /*
     * 公司名称唯一性验证
     * */
    public function CheckCompanyNameOnly(){
        $Account=D( 'User/Account' );
        $data['companyName']=trim(I('Post.companyName'));
        $OldData = $Account->SelectAccountInfo( $this->uid,array( 'companyName' ) );
        if( $OldData['companyName'] ==  $data['companyName']){
            $ret['msg']='The company name can be used';
            $ret['code']='200';
            $ret['data']['ok']='The company name can be used';
            $this->ajaxReturn($ret);
        }
        if($Account->Create($data)){
            $ret['msg']='The company name can be used';
            $ret['code']='200';
            $ret['data']['ok']='The company name can be used';
            $this->ajaxReturn($ret);
        }else{
            $ret['msg']=$Account->getError();
            $ret['code']='400';
            $ret['data']['error']=$Account->getError();
            $this->ajaxReturn($ret);
        }
    }

    public function info(){
        $this->checkLogin();
        $this->checkBindEmail();
        $Account=D( 'User/Account' );

        $trade=$Account->GetBaseData( 'trade' );//所在行业
        $employee=$Account->GetBaseData( 'employees' );//单位人数
        $turnover=$Account->GetBaseData( 'turnover' );//年营业额
        $model  = $Account->GetBaseData( 'model' );//经营模式

        $Fields=array( 'country','phone','email','bind' );
        $Base=$Account->GetNationality( $this->uid,$Fields );

        $pram=array( 'companyName','contact','model','employee','trade','turnover','businessScope','companyIntroduction','other','state' );
        $data=$Account->SelectAccountInfo( $this->uid,$pram );

        if( !empty( $data ) ){
            $area['country'] = empty($data['other']['country'])? '' : $Account->GetAreaTitle( $data['other']['country'],array( 'title' ) );//国家
            $area['area_s']  = empty($data['other']['area_s'])? '' : $Account->GetAreaTitle( $data['other']['area_s'],array( 'title' ) );//地区
            $area['area_c']  = empty($data['other']['area_c'])? '' : $Account->GetAreaTitle( $data['other']['area_c'],array( 'title' ) );//城市
        }

        $country=$Account->GetCountryName( );
        $IsCompleteInfo = $Account->checkInfoIsComplete( array( 'id'=>$this->uid) );
        $this->assign( 'IsCompleteInfo',intval($IsCompleteInfo) );
        $this->assign('countryName',empty($country[$Base['country']]) ? '' : $country[$Base['country']]);
        $this->assign('area',$area);
        $this->assign( 'data',$data );
        $this->assign('Base',$Base);
        $this->assign( 'trade',$trade );
        $this->assign( 'model',$model );
        $this->assign( 'employee',$employee );
        $this->assign( 'turnover',$turnover );
        $this->display('member-company-info');
    }

    /*
     * 新增企业信息
     *
     * */
    public function InsertInfo(){
        //检查TOKEN
        $this->checkActionToken();

        $modify_cert = '';    //确认用户是否修改公司信息/联系信息。有修改就把认证状态改为待审核
        $Account=D( 'User/Account' );
        $OldData = $Account->SelectAccountInfo( $this->uid,array( 'companyName','contact','other'));
        $Base    = $Account->GetNationality( $this->uid,array('country','email') );
        $data = I( 'post.' );
        $param = array( 'id','companyName','model', 'trade', 'employee', 'turnover','businessScope',
                        'companyIntroduction','contact','position','email','address',
                        'area_c','area_s','country','zip','tel_a','tel','fax_a','fax','tel_contryCode','phone_contryCode','fax_contryCode' );
        $other = array('position','address','area_c','area_s','country','zip','tel_a','tel','fax_a','fax','tel_contryCode','phone_contryCode','fax_contryCode');
        $newData=array();
        $newOther=array();
        foreach( $param as $v ){
            $newData[$v] = trim( idx($data, $v) );
            if(in_array($v,$other)){
                $newOther[$v] = trim( idx($data, $v) );
                unset( $newData[$v] );
            }
        }

        $newData['other'] = serialize( $newOther );
        $newData['id'] = session('Uid');

        $MemberInfo['email'] = $newData['email'];
//        $MemberInfo['phone']  = $data['mobile'];
        $Account->SetNationality( $this->uid,$MemberInfo );
        if( empty( $newData['email'] ) ){
            $Account->DelEmailCacheKeys($Base['email']);//删除绑定集合
            $Account->SetNationality($this->uid,array('bind'=>'0'));//已绑定邮箱
        }
          unset($newData['email']);
       if( $OldData['companyName'] == $newData['companyName'] ){
            unset( $newData['companyName'] );
        }else{
            $modify_cert = '1';
        }
        if( $OldData['contact'] != $newData['contact'] ) $modify_cert = '1';

        if($Account->create( $newData )){
            $res = $Account->InsertAccountInfo( $this->uid,$newData );

            if( $res ){
                if( $OldData ){
                    //删除旧集合
                    $Account->SetMemberAreaKeys( $this->uid,$data['country'] );//国家
                    if(isset($data['area_s'])){$Account->SetMemberAreaKeys( $this->uid,$data['area_s'] );}//地区
                    if(isset($data['area_c'])){$Account->SetMemberAreaKeys( $this->uid,$data['area_c'] );}//城市
                }
                $shell = D('User/Shell');
                $shell->index( 'member:companyName', strtolower($data['companyName']), $this->uid );
                //添加公司名集合 string:companyName:
                $Account->SetCompanyName( $this->uid,$data['companyName'] );
                    //添加地区集合
                $Account->SetMemberAreaKeys( $this->uid,$data['country'],'1' );//国家
                $Account->SetMemberAreaKeys( $this->uid,$data['area_s'],'1' );//地区
                if(isset($data['area_c'])){$Account->SetMemberAreaKeys( $this->uid,$data['area_c'],'1' );}//城市
                //检测用户修改的是不是公司名称/联系人。

                //修改企业的认证状态
                $user_member = D("User/Member");
                $prevState = $user_member->CheckCompanyAuth($this->uid,"state");  
                if(($prevState !== false) && $modify_cert == '1'){       //$prevState为空时，企业认证为还没填写，所以修改公司信息不需要修改企业认证的信息。
                  $member = D('Admin/Member');
                  $state = '2';
                  $result = $member->companyVerifyUpdate($this->uid,$prevState,$state,'','2');
                }
                //返回当前状态
                $ret['msg'] = 'AddSuccess';
                $ret['code'] = '200';
                $this->ajaxReturn($ret);
            }
        }else{
            $ret['msg'] = $Account->getError();
            $ret['code'] = '400';
            $ret['data']['error'] = $Account->getError();
            $this->ajaxReturn($ret);
        }
    }

//    /*提交申请*/
   Public function submit_auth(){
       $this->checkLogin();
       $this->checkBindEmail();
       $this->checkcountry('info');     //检测企业不在中国就跳转到info页面。
       $CompanyInfo=D('User/CompanyInfo');

       //判断是否完成主要基础资料主要字段填写
       if( D( 'User/Member' )->checkInfoIsComplete( array( 'id'=>$this->uid ) ) == false ){
           redirect('info');
       }

       $id=session('Uid');
       // $redis=new Redis();
       $redis = \Think\Cache::getInstance('Redis');
       $Account=D('User/CompanyInfo');

       if(IS_POST){
          //检查TOKEN
          $this->checkActionToken();
           /*循环处理图片*/
           $data=I('post.');
           if(empty($data['businessCert'])){
               $res['msg']='Business License is null';
               $res['code']=400;
               $this->ajaxReturn($res);
               exit;
           }

           if($data['type']=='1'){
               if(empty($data['codeCert'])){
                   $res['msg']='Organization Code Certificate is null';
                   $res['code']=400;
                   $this->ajaxReturn($res);
                   exit;
               }

               if(empty($data['taxCert'])){
                   $res['msg']='Tax Registration Certificate is null';
                   $res['code']=400;
                   $this->ajaxReturn($res);
                   exit;
               }
           }else{
               unset($data['codeCert']);
               unset($data['taxCert']);
           }

           if(empty($data['authCert'])){
               $res['msg']='Enterprise Authentication Authorization is null';
               $res['code']=400;
               $this->ajaxReturn($res);
           }

           if(empty($data['accountCert'])){
               unset($data['accountCert']);
           }
           $cert=serialize($data);
           /*更新到hashmember:info:uid*/
           if(!empty($cert)){
            //检测用户的认证数据来确认用户的操作为增加还是修改。
                $re = $company=$redis->hGet("hash:member:info:{$id}",'cert');
                if($re){
                    $opera = 'Modify';
                }else{
                    $opera = 'Submit';
                }
               $rest=$redis->hmset("hash:member:info:{$id}",array('cert'=>$cert));  //上传认证信息。
               if($rest){
                    //修改企业的认证状态
                    $user_member = D("User/Member");
                    $prevState = $user_member->CheckCompanyAuth($id,"state");
                    $member = D('Admin/Member');
                    $state = '2';
                    $member->companyVerifyUpdate($id,$prevState,$state,'','2');     //修改认证状态为待审核，写入日志，修改商品状态。

                    $CompanyInfo=D('User/CompanyInfo');
                    $CompanyInfo->insertContactInfo($id,array('opera'=>$opera));
                    $res['msg']='Add Success';
                    $res['code']=200;
                    $res['data']['url']='/User/Account/exam_auth';
                    $this->ajaxReturn($res);
                    exit;
                   
               }else{
                   $res['mag']='Add Failed';
                   $res['code']=400;
                   $this->ajaxReturn($res);
               }
           }
       }

        /*显示操作记录*/
       $list=$redis->hGetAll("hash:company:operation:history:{$id}");
       ksort($list);
      foreach($list as $k=>$v){
          $arr=unserialize($list[$k]);
          $infos[$k]['id']=$arr['id'];
          $infos[$k]['addTime']=date('Y/m/d  H:i:s',$arr['addTime']);
          $infos[$k]['opera'] =isset($arr['opera'])?$arr['opera']:'';
          $infos[$k]['reason']=isset($arr['reason'])?$arr['reason']:'';
          if($arr['otype']==2){
              $infos[$k]['oid']   =$redis->hGet("hash:member:{$id}",'username');
          }elseif($arr['otype']==1){
              $infos[$k]['oid']   ='webmaster';
          }

      }

      /*显示公司名称,联系人,所有信息*/
       $company=$redis->hGetAll("hash:member:info:{$id}");
       $rest=unserialize($company['cert']);
       $rest['businessCert'] = isset($rest['businessCert'])?$rest['businessCert']:'';
       $rest['codeCert'] = isset($rest['codeCert'])?$rest['codeCert']:'';
       $rest['taxCert'] = isset($rest['taxCert'])?$rest['taxCert']:'';
       $rest['accountCert'] = isset($rest['accountCert'])?$rest['accountCert']:'';

       $companyName=$company['companyName'];
       $contact=$company['contact'];
       $type=$rest['type'];
       /*插叙企业认证状态*/
       $CompanyInfo=D('User/CompanyInfo');
       $state=$CompanyInfo->getState($id);
// var_dump($rest);
       $this->assign('state',$state);
       $this->assign('type',$type);
       $this->assign('rest',$rest);
       $this->assign('companyName',$companyName);
       $this->assign('contact',$contact);
       $this->assign('info',$infos);
       $this->display('member-company-auth');
   }

   /*资质审核*/
   Public function auth_history(){
       $this->checkLogin();
       $this->checkcountry('info');     //检测企业不在中国就跳转到info页面。

       /*查出所有的数据*/
       /*1，普通营业执照，2，企业三证合一*/
       $id=session('Uid');
       $redis = \Think\Cache::getInstance('Redis');

       /*显示操作记录*/
       $list=$redis->hGetAll("hash:company:operation:history:{$id}");
       ksort($list);
       foreach($list as $k=>$v){
           $arr=unserialize($list[$k]);
           $info[$k]['addTime']=date('H:i:s M d,Y ',$arr['addTime']);
           $info[$k]['opera'] =isset($arr['opera'])?$arr['opera']:'';
           $info[$k]['reason']=isset($arr['reason'])?$arr['reason']:'';
           if($arr['otype']==2){
               $info[$k]['oid']   =$redis->hGet("hash:member:{$id}",'username');
           }elseif($arr['otype']==1){
               $info[$k]['oid']='webmaster';
           }

       }

       /*查看所有的信息*/
       $company=$redis->hGetAll("hash:member:info:{$id}");
       $rest=unserialize($company['cert']);
       $companyName=$company['companyName'];
       $contact=$company['contact'];
       $type=$rest['type'];
       /*判断企业认证状态显示不同的提示信息*/
// var_dump($info);
       $this->assign('type',$type);
       $this->assign('rest',$rest);
       $this->assign('companyName',$companyName);
       $this->assign('contact',$contact);
       $this->assign('info',$info);
       $this->display('member-auth-history');
   }

   /*资质审核*/
   Public function exam_auth(){
       $this->checkLogin();
       $this->checkBindEmail();
       $this->checkcountry('info');     //检测企业不在中国就跳转到info页面。
       /*查出所有的数据*/
       /*1，普通营业执照，2，企业三证合一*/
       $id=session('Uid');
       $redis = \Think\Cache::getInstance('Redis');

       /*显示操作记录*/
       $list=$redis->hGetAll("hash:company:operation:history:{$id}");
       ksort($list);
       $info = array();
       foreach($list as $k=>$v){
           $arr=unserialize($list[$k]);
           $info[$k]['addTime']=date('Y/m/d  H:i:s',$arr['addTime']);
           $info[$k]['opera'] = empty($arr['opera']) ? '' : $arr['opera'];
           if(!empty($arr['reason'])){
              $info[$k]['reason']=$arr['reason'];
           }else{
               $info[$k]['reason'] = '';
           }
          
           if($arr['otype']==2){
               $info[$k]['oid']   =$redis->hGet("hash:member:{$id}",'username');
           }elseif($arr['otype']==1){
               $info[$k]['oid']='administrator';
           }

       }
      

       /*查看所有的信息*/
       $company=$redis->hGetAll("hash:member:info:{$id}");
       if (isset($company['cert'])) {
         $rest=unserialize($company['cert']);
         $companyName=$company['companyName'];
         $contact=$company['contact'];
         $type=$rest['type'];
         $state=$company['state'];
         /*判断企业认证状态显示不同的提示信息*/
         $reason = end($info);
       }else{
          $companyName = '';
          $contact = '';
          $type = '';
          $state = 4;
          $reason = '';
          $rest = [];
       }
       

       $this->assign('reason',$reason);
       $this->assign('state',$state);
       $this->assign('type',$type);
       $this->assign('rest',$rest);
       $this->assign('companyName',$companyName);
       $this->assign('contact',$contact);
       $this->assign('info',$info);
       $this->display('member-auth');
   }

//     /*审核成功*/
//    Public function success_auth(){
//        $this->checkLogin();
//        $id=session('Uid');
//        $redis=new Redis();
//
//        /*显示操作记录*/
//        $list=$redis->hGetAll("hash:company:operation:history:{$id}");
//        ksort($list);
//        foreach($list as $k=>$v){
//            $arr=unserialize($list[$k]);
//            $info[$k]['id']=$arr['id'];
//            $info[$k]['addTime']=date('Y/m/d H:i:s',$arr['addTime']);
//            $info[$k]['opera'] =$arr['opera'];
//            $info[$k]['reason']=$arr['reason'];
//            if($arr['otype']==2){
//                $info[$k]['oid']   =$redis->hGet("hash:member:{$id}",'username');
//            }elseif($arr['otype']==1){
//                $info[$k]['oid']='系统管理员';
//            }
//
//        }
//        $auth=$redis->hget("hash:member:info:{$id}",'state');
//        if($auth!=1){
//            redirect('submit_auth');
//            exit;
//        }
//        $data=array(
//            '1'=>'普通营业执照',
//            '2'=>'企业三证合一',
//        );
//        /*查看所有的信息*/
//        $company=$redis->hGetAll("hash:member:info:{$id}");
//        $rest=unserialize($company['cert']);
//        $companyName=$company['companyName'];
//        $contact=$company['contact'];
//        $type=$data[$rest['type']];
//        /*判断企业认证状态显示不同的提示信息*/
//
//        $this->assign('type',$rest['type']);
//        $this->assign('typeTip',$type);
//        $this->assign('rest',$rest);
//        $this->assign('companyName',$companyName);
//        $this->assign('contact',$contact);
//        $this->assign('info',$info);
//
//        $this->display();
//    }
//
//    /**
//     * 修改用户名
//     */
//    public function editusername(){
//        $model  = D( 'Home/Member' );
//        $uid    = $this->uid;
//        $username     = trim( I( 'username' ) );
//
//        if( empty( $uid ) ){
//            $ret = array(
//                'code' => 400,
//                'msg' => '请先登录',
//                'data' => NULL,
//            );
//        }elseif( empty( $username ) ){
//            $ret = array(
//                'code' => 400,
//                'msg' => '请先登录',
//                'data' => NULL,
//            );
//        }else{
//            $ret = $model->editUserName( $uid, array( 'username' => $username ) );
//            if( !$ret ){
//                $ret = array(
//                    'code' => 400,
//                    'msg' => $model->getError(),
//                    'data' => NULL,
//                );
//            }else{
//                $data = D( 'Home/Member' )->detail( array( 'id' => $uid ) );
//                $ret = array(
//                    'code' => 200,
//                    'msg' => '修改成功',
//                    'data' => array(
//                        'id' => $uid,
//                        'username' => $data['username'],
//                    ),
//                );
//            }
//        }
//        $this->ajaxReturn( $ret );
//    }
//    

  private function checkcountry($url){
    $country = session('country');
    if($country != 'CN'){
      redirect('info');
    }
  }    

  //检测用户是否有修改公司名称或联系人。
  /**
   * *
   * @param  array $param   
   * @return true/false
   */
  // private function check_company_info($param){

  // }

  /**
   * 用户没有绑定邮箱的提示页面。
   */
  public function bindTips(){
     $this->checkLogin();
     $redis = \Think\Cache::getInstance('Redis');
     $data = $redis->hmget('hash:member:' . $this->uid, array('bind', 'type', 'email'));
     $this->assign('email', $data['email']);
     $bindEmailSate = $data['bind'] & C('STATUS_BIND')['BIND_EMAIL'];
     if ($data['type'] == 'india-show' && !$bindEmailSate){
        $member = D('User/Member');
        $num = $member->getEmailRate($data['email']);
        $res = $this->_checkSendTime();
        $content['0'] = "A confirmation mail had been sent to your";
        $moreNum = ($num >= 3)?1:0;
        $moreRate = $res?0:1;

        $this->assign('content', $content);
        $this->assign('moreNum', $moreNum);
        $this->assign('moreRate', $moreRate);
        $this->assign('email', $data['email']);
        $this->display();
     }else{
      $this->redirect('/user/Account/index');
     }    
  }

  /**
     * 发送邮件验证码
     */
    public function sendEmail() {
        if (IS_AJAX && IS_POST) {
            $to = I('post.email');
            $ip = get_client_ip();
            //检测发邮件频率
            $res = $this->_checkSendTime();
            if(!$res){
              $code['code'] = 200;
              $code['data']['error'] = 'Mail has been sent out, 60 seconds later can send again.';
              $this->ajaxReturn($code);
            }

            $member = D('User/Member');
            $random = uniqid();
            $num = $member->getEmailRate($to);
            $username = $member->getOneFiled($this->uid,'username'); 
            $email = $member->getOneFiled($this->uid,'email'); 
            if($to !== $email){
              $code['code'] == 200;
              $code['data']['error'] == 'Abnormal email address!';
              $this->ajaxReturn($code);
            }

            if ($num < 3) {
                $res = send_mail($to, 'Dear ' . $username, $member->certEmailContent($this->uid, $username));
                //算出先到到23:59:59剩余的秒数为保存时
                $time = strtotime(date('Ymd',time()))+3600*24-1;
                $expireTime = $time - time();
                $member->checkSendEmailRate($to, $random, $expireTime);
                if ($res) {
                    $member->unsetSendEmailLock($to, $random);
                    $this->_checkSendTime('send');
                    $code['code'] = 200;
                    $code['data']['num'] = $num;
                    $this->ajaxReturn($code);
                } else {
                    $code['code'] = 200;
                    $code['data']['num'] = $num;
                    $code['data']['error'] = $code['msg'];
                    $this->ajaxReturn($code);
                }
            } else {
                $member->unsetSendEmailLock($to, $random);
                $code['msg'] = 'Can only send 3 emails a day.';
                $code['code'] = '200';
                $code['data']['error'] = 'Can only send 3 emails a day.';
                $code['data']['num'] = $num;
                $this->ajaxReturn($code);
            }
        }
    }

    /**
     * 检测上一次邮件发送的间隔时间
     * @param  string $type 为空时只返回true/false,为send时就保存当前时间为发送时间
     * @return true/false
     */
    protected function _checkSendTime($type=''){
      //取出session的数据
        $sedata=session('sendEmail');
        $time=time()-$sedata['addTime'];
       if ($time >= 60 && $type == 'send'){
            $data['addTime'] = time();
            session('sendEmail',$data);
            return true;
        }else{
            return true;
        }
    }

}