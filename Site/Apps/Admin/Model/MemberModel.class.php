<?php
namespace Admin\Model;
use Think\Model;
use Think\Cache\Driver\Redis;

class MemberModel extends Model {
	//重置密码
    public function resetPassword($id){
        $redis=new redis();
        $salt=rand(1000,9999);
        $pass=passencrypt(C('REST_PASS'),$salt);
        $result = $redis->hmset('hash:member:'.$id,array('password'=>$pass,'salt'=>$salt));
        return $result;
    }
    //获取企业其他信息

	//type:member->商家历史记录;2company->企业历史记录；3sign->供应商签约记录
	//读取历史操作记录
    public function getHistoryList($type,$id){
        $redis = new redis();
        //读取历史操作
        $historyInfo = $redis->hGetAll('hash:'.$type.':operation:history:'.$id);
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
    public function getCompanyDetail($id){
        $redis = new redis();
        $companyInfo = $redis->hMGet('hash:member:info:'.$id,array(
            'id',
            'state',
            'cert',
            'contact',
        ));

        $info['id'] = $companyInfo['id'];
        if($companyInfo['state']==1)$info['state']='有效';
        elseif($companyInfo['state']==0)$info['state']='审核不通过';
        elseif($companyInfo['state']==2)$info['state']='待审核';
        elseif($companyInfo['state']==3)$info['state']='已撤消';
//		$info['company']['state'] = $companyInfo['state'];
        $info['contact'] = $companyInfo['contact'];

        if($companyInfo['cert']){
            $companyInfo['cert'] = unserialize($companyInfo['cert']);
//			if($companyInfo['cert']['type']==1)$info['type'] = '普通营业执照';
//			elseif($companyInfo['cert']['type']==2)$info['type'] = '三证合一';
//			$info['type'] = $companyInfo['cert']['type'];
            $info['businessCert'] = isset($companyInfo['cert']['businessCert'])?$companyInfo['cert']['businessCert']:'';
            $info['codeCert'] = isset($companyInfo['cert']['codeCert'])?$companyInfo['cert']['codeCert']:'';
            $info['taxCert'] = isset($companyInfo['cert']['taxCert'])?$companyInfo['cert']['taxCert']:'';
            $info['accountCert'] = isset($companyInfo['cert']['accountCert'])?$companyInfo['cert']['accountCert']:'';
            $info['authCert'] = isset($companyInfo['cert']['authCert'])?$companyInfo['cert']['authCert']:'';
            $info['type'] = isset($companyInfo['cert']['type'])?$companyInfo['cert']['type']:'';
        }

//		$user=D('User');

//		//去除历史操作
//		$companyHistoryInfo = $redis->hGetAll('hash:company:operation:history:'.$id);
//		if($companyHistoryInfo){
//			foreach($companyHistoryInfo as $key=>$vo){
//				$info['history'][$key]=unserialize($vo);
//				$info['history'][$key]['operaName']=$user->getUserName($info['history'][$key]['oid']);
//			}
//		}
//		print_r($info);exit;
        return $info;
    }

	//读取会员详情页
    public function getMemberDetail($id){
        $redis = new redis();
        $memberInfo = $redis->hMGet('hash:member:'.$id,array(
            'id',
            'username',
            'phone',
            'bind',
            'email',
            'img',
            'country',
        ));
        $memberCompanyInfo = $redis->hMGet('hash:member:info:'.$id,array(
            'companyName',
            'trade',
            'model',
            'property',
            'establishmentDate',
            'businessTerm',
            'employee',
            'turnover',
            'businessScope',
            'companyIntroduction',
            'contact',
            'other',
            'state',
            'intention',
        ));

        $companyData = D('Companydata');
        $memberCompanyInfo['trade']=$companyData->getCompanyDataName($memberCompanyInfo['trade'],'trade');
        $memberCompanyInfo['model']=$companyData->getCompanyDataName($memberCompanyInfo['model'],'model');
        $memberCompanyInfo['property']=$companyData->getCompanyDataName($memberCompanyInfo['property'],'property');
        $memberCompanyInfo['employee']=$companyData->getCompanyDataName($memberCompanyInfo['employee'],'employees');
        $memberCompanyInfo['turnover']=$companyData->getCompanyDataName($memberCompanyInfo['turnover'],'turnover');
        //$memberCompanyInfo['employees']=$companyData->getCompanyDataName($id,'employees');
        $tmpArr = unserialize($memberCompanyInfo['other']);
        $memberCompanyInfo['posstion']=$tmpArr['position'];

        //电话号码
        if(!empty($tmpArr['tel_a']) && !empty($tmpArr['tel'])){
            $memberCompanyInfo['tel']=$tmpArr['tel_a'].'-'.$tmpArr['tel'];
        }else{
            $memberCompanyInfo['tel']=$tmpArr['tel'];
        }

        //fax
        if(!empty($tmpArr['fax_a']) && !empty($tmpArr['fax'])){
            $memberCompanyInfo['fax']=$tmpArr['fax_a'].'-'.$tmpArr['fax'];
        }else{
            $memberCompanyInfo['fax']=$tmpArr['fax'];
        }
        //地址
        $area_c = getAreaName($tmpArr['area_c']);
        $area_s = getAreaName($tmpArr['area_s']);
        $country = getAreaName($tmpArr['country']);
        $memberCompanyInfo['address'] = empty($tmpArr['address'])?'':$tmpArr['address'].',';
        $memberCompanyInfo['address'] .= empty($area_c)?'':$area_c.',';
        $memberCompanyInfo['address'] .= empty($area_s)?'':$area_s.',';
        $memberCompanyInfo['address'] .= empty($country)?'':$country.',';
        $memberCompanyInfo['address'] = trim($memberCompanyInfo['address'],',');


        unset($memberCompanyInfo['other']);

        $info['personal']=$memberInfo;
        $info['company']=$memberCompanyInfo;
        $tmpArr=explode('to',$info['company']['businessTerm'])   ;//营业期限
        // $info['company']['businessTerm']=date("Y-m-d",$tmpArr['0']).'to'.date("Y-m-d",$tmpArr['1']);//营业期限时间戳

        $memberHistoryInfo = $redis->hGetAll('hash:member:operation:history:'.$id);

//		$user=D('User');
//
//		if($memberHistoryInfo){
//			foreach($memberHistoryInfo as $key=>$vo){
//				$info['history'][$key]=unserialize($vo);
//				$info['history'][$key]['operaName']=$user->getUserName($info['history'][$key]['oid']);
//			}
//		}

        return $info;
    }

	//用户禁用状态修改
    //用户禁用状态修改
    public function memberOperateUpdate($idStr,$status,$reason){
        $redis = new redis();

        $idArr = explode(',',$idStr);
        if($idArr){
            foreach($idArr as $key=>$vo){
                $prevStatus = $redis->hGet('hash:member:'.$vo,'status');
                $redis->sRem('set:member:status:'.$prevStatus, $vo);
                $redis->sAdd('set:member:status:'.$status, $vo);
                $redis->hSet('hash:member:'.$vo,'status',$status);
                $insertId = $redis->incr('string:member:history');
                $arr['id']=$insertId;
                $arr['addTime']=time();
                if($prevStatus==1)$arr['opera']='禁用商家账号';
                elseif($prevStatus==2)$arr['opera']='取消禁用商家账号';
                $arr['oid']=session('userid');
                $arr['status']=1;
                if(!empty($reason))$arr['reason']=$reason;
                $redis->hSet('hash:member:operation:history:'.$vo,$insertId,serialize($arr));
            }
        }

        return true;
    }


     //用户删除状态修改
     /**
      * *
      * @param  string $idStr   用户id，多用户时用逗号隔开。
      * @param  string $status  修改后的用户状态
      * @param  string $reason  用户原因。
      * @return false/true
      */
    public function memberOperateDel($idStr,$status,$reason){
        $redis = new redis();

        $idArr = explode(',',$idStr);
        if($idArr){
            foreach($idArr as $key=>$vo){
                $prevStatus = $redis->hGet('hash:member:'.$vo,'status');
                $redis->sRem('set:member:status:'.$prevStatus, $vo);
                $redis->sAdd('set:member:status:'.$status, $vo);
                $redis->hSet('hash:member:'.$vo,'status',$status);
                $insertId = $redis->incr('string:member:history');
                $arr['id']=$insertId;
                $arr['addTime']=time();
                if($prevStatus==1)$arr['opera']='删除商家账号';
                elseif($prevStatus==0)$arr['opera']='取消删除商家账号';
                $arr['oid']=session('userid');
                $arr['status']=1;
                if(!empty($reason))$arr['reason']=$reason;
                $redis->hSet('hash:member:operation:history:'.$vo,$insertId,serialize($arr));
            }
        }
        return true;
    }
    

    public function companyVerifyUpdate($id,$prevState,$state,$reason='',$otype='1'){
        $redis = new redis();
        //$member=D('Admin/Store');
//			$redis->pipeline();
        $redis->hSet('hash:member:info:'.$id,'state',$state);
        $redis->sRem('set:company:state:'.$prevState,$id);
        // $redis->sRem('set:company:state:4',$id);
        $redis->sAdd('set:company:state:'.$state,$id);

        $companyName = $redis->hGet('hash:member:info:'.$id,'companyName');
        $insertId = $redis->hLen('hash:company:operation:history:'.$id)+1;
        $arr['id']=$insertId;
        $arr['addTime']=time();

        //发送邮件通知用户
        if($state!=2){
            $this ->set_mess($id,$prevState,$state);
        }

        if($prevState==1 && $state != 2){
            $arr['opera']='Revoke the Authentication';
            $redis->del('string:company:'.$companyName);//删除认证公司名和用户关联
            $arr['reason']=$reason;
            $matchState = 1;
        }elseif($prevState==2 && $state==1){
            $arr['opera']='Application Passed';
            $redis->set('string:company:'.$companyName,$id);//添加认证公司名和用户关联
            $matchState = 6;
        }elseif($prevState==2 && $state==0){
            $arr['opera']='Application Failed';
            $redis->del('string:company:'.$companyName);//删除认证公司名和用户关联
            $arr['reason']=$reason;
            $matchState = 1;
        }elseif($prevState==3 && $state==1){
            $arr['opera']='Revert the  Authentication';
            $redis->set('string:company:'.$companyName,$id);//添加认证公司名和用户关联
            $matchState = 6;
        }elseif($prevState==0 && $state==1){
            $arr['opera']='Application Passed';
            $redis->set('string:company:'.$companyName,$id);//添加认证公司名和用户关联
            $matchState = 6;
        }elseif($prevState===false && $state==2){           
            $arr['opera']='Submit';
            $redis->set('string:company:'.$companyName,$id);//添加认证公司名和用户关联
            $matchState = 1;
        }elseif($state==2){
            $arr['opera']='Modify';
            $redis->set('string:company:'.$companyName,$id);//添加认证公司名和用户关联
            $matchState = 1;
        }
        $arr['oid']=session('userid');
        $arr['state']=3;
        $arr['otype']=$otype;//系統管理員
        if(!empty($reason))$arr['reason']=$reason;
        $redis->hSet('hash:company:operation:history:'.$id,$insertId,serialize($arr));
//		$companyName=$redis->hGet('hash:member:info:'.$id,'companyName');
//		$redis->del('string:company:'.$companyName);
//			$result = $redis->exec();

        $tempCacheKey = 'tmp:set:company:verify:list:'.uniqid();
        $unionCacheKeys = array(
            D( 'Home/Product' )->getStateCacheKey( $matchState ),
            D( 'Home/Product' )->getMemberCacheKey( $id ),
        );
        $redis->zInter( $tempCacheKey, $unionCacheKeys );
        $redis->expire( $tempCacheKey, 60 );
        $data = $redis->zRange( $tempCacheKey, 0, -1 );

        if( $matchState == 1 ){
            $saveData = array(
                'opera' => 'System Unshelve',
                'reason' => 'Sales Suspension, cause your Enterprise Authentication has been revoked.',
                'oid' => 1,
                'otype' => 'system',
                'state' => 6,
            );
        }elseif( $matchState == 6 ){
            $saveData = array(
                'opera' => 'System Shelve',
                'reason' => 'Sales, cause your Enterprise Authentication Successfully.',
                'oid' => 1,
                'otype' => 'system',
                'state' => 1,
            );
        }
        if( !empty( $data ) ){
            foreach( $data as $v ){
                $saveData['id'] = $v;
                D( 'Home/Product' )->editState( $v, $saveData );
            }
        }
    
        return true;
    }
    /*
        * 获取企业认证操作历史
        * */
    public function getCompanyHistory($id){
        $redis = new redis();
        $list=$redis->hGetAll("hash:company:operation:history:{$id}");
        ksort($list);
        foreach($list as $k=>$v){
            $arr=unserialize($list[$k]);
            $info[$k]['id']=$arr['id'];
            $info[$k]['addTime']=date('Y/m/d H:i:s',$arr['addTime']);
            $info[$k]['opera'] =$arr['opera'];
            $info[$k]['reason']=isset($arr['reason'])?$arr['reason']:'';
            if($arr['otype']==2){
                $info[$k]['oid']   =$redis->hGet("hash:member:{$id}",'username');
            }elseif($arr['otype']==1){
                $info[$k]['oid']='系统管理员';
            }

        }
        return $info;
    }
    
    
    /*
     * 禁用或者删除用户时将用户名下的商品，求购，供求加入xxx:user:delete集合中
     * @param userid  uid
     * @return boolean 成功返回array，失败返回false
     */
    public function RemoveDeletUser($uid){
    	$redis = new redis();
    	$product  = D("Home/Product")->getStatusCacheKey(1);
    	$supply   = D("User/Supply")->GetKeyStatus(1);
    	$buyoffer = D("User/Buyoffer")->GetKeyStatus(1);
    	
    	//取出supply集合
    	$sup = D("User/Supply")->GetKeyMember($uid);
    	$pro = D("Home/Product")->getMemberCacheKey($uid);
    	$buy = D("User/Buyoffer")->GetKeyMember($uid);
    	for($i=0;$i<5;$i++){
	    	$redis->watch($product,$supply,$buyoffer);
	    	$redis->multi();
	    	$redis->sDiffStore($supply,$supply,$sup);
	    	$redis->sDiffStore($product,$product,$pro);
	    	$redis->sDiffStore($buyoffer,$buyoffer,$buy);
	    	$res=$redis->exec();
	    	if($res){
	    		break;
	    	}
    	}
    	return $res;
    }
    
    
    /*
     * 撤销禁用账号，商品求购等集合原路返回返回
     */
    
    public function RevokeUser($uid){
    	$redis = new redis();
    	$product0  = D("Home/Product")->getStatusCacheKey(0);
    	$supply0   = D("User/Supply")->GetKeyStatus(0);
    	$buyoffer0 = D("User/Buyoffer")->GetKeyStatus(0);
    	
    	$product1  = D("Home/Product")->getStatusCacheKey(1);
    	$supply1   = D("User/Supply")->GetKeyStatus(1);
    	$buyoffer1 = D("User/Buyoffer")->GetKeyStatus(1);
    	 
    	//取出supply集合
    	$sup = D("User/Supply")->GetKeyMember($uid);
    	$pro = D("Home/Product")->getMemberCacheKey($uid);
    	$buy = D("User/Buyoffer")->GetKeyMember($uid);
    	
    	$s=uniqid();
    	
    	$redis->sInterStore("tmp:supply:revoke:".$s,$supply0,$sup);
    	$redis->sInterStore("tmp:product:revoke:".$s,$product0,$pro);
    	$redis->sInterStore("tmp:buyoffer:revoke:".$s,$buyoffer0,$buy);
    	
    	$redis->expire("tmp:product:revoke:".$s,1);
    	$redis->expire("tmp:buyoffer:revoke:".$s,1);
    	$redis->expire("tmp:supply:revoke:".$s,1);
    	
    	$redis->sDiffStore("tmp:supply:revoke:".$s,$sup,"tmp:supply:revoke:".$s);
    	$redis->sDiffStore("tmp:product:revoke:".$s,$pro,"tmp:product:revoke:".$s);
    	$redis->sDiffStore("tmp:buyoffer:revoke:".$s,$buy,"tmp:buyoffer:revoke:".$s);
    	
    	$redis->sUnionStore($product1,$product1,"tmp:product:revoke:".$s);
    	$redis->sUnionStore($buyoffer1,$buyoffer1,"tmp:buyoffer:revoke:".$s);
    	$redis->sUnionStore($supply1,$supply1,"tmp:supply:revoke:".$s);
    	
    	
    }


     /*
     *获取通知内容
     * $uid       用户id             
     * $state     修改后状态
     * $reason    原因
     * */
    function set_mess($uid,$prevState,$state){
        $param['uid'] = $uid;
        $param['content'] = $this -> get_content($prevState,$state);
        $param['sender']  = 'WebMaster';
        $system = D('User/Message');
        $system -> createSystem($param);
  
    }

     /*
     *获取通知内容
     * $state    修改后状态
     * $reason    原因
     * */
    public function get_content($prevState,$state){
        if(empty($prevState)){
            return false;
        }
        if($state === ''){
            return false;
        }

        if($state == '1' && ($prevState == '0' || $prevState == '3')){
            $content = "Dear Keywa user, your Enterprise Authentication has reverted, please log on our website for more details.";
        }elseif($state == '1'){
            $content = "Dear Keywa user, your Enterprise Authentication has been approved and you can check it on our website now.";
        }elseif ($state == '3') {
            $content = "Dear Keywa user, Sorry! your Enterprise Authentication has been revoked, please log on our website for more details.";
        }elseif($state == '0'){
            $content = "Dear Keywa user, Sorry! your Enterprise Authentication has failed, please log on our website for more details.";
        }
        return $content;
    }

}