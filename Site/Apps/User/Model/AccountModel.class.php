<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016-11-23
 * Time: 11:17
 */

namespace User\Model;
use      Think\Model;

class AccountModel extends Model {

    /*自动验证*/
    protected $_validate = array(
        /*企业信息*/
        array('companyName', 'require', 'The company name cannot be empty!'),
        array('companyName', 'CheckOnly', 'The company name has been used!',0,'callback' ),
        array('trade','require','Industry cannot be empty!'),
        array('turnover','require','Annual turnover cannot be empty!'),
        array('tel_a','require','Telephone area code cannot be empty!'),
        array('tel','require','Telephone number cannot be empty!'),
        array('tel_a','checkTel_a','The area code format is not correct!',0,'callback'),
        array('tel','checkNum','Must be numeric!',0,'callback'),
        array('fax_a','checkTel_a','Fax code format is not correct!',0,'callback'),
        array('fax','checkTel','Fax number format is not correct!',0,'callback'),
        array('tel_contryCode','checkNum','Must be numeric!',0,'callback'),
        array('phone_contryCode','checkNum','Must be numeric!',0,'callback'),
        array('fax_contryCode','checkNum','Must be numeric!',0,'callback'),
        array('contact', 'require', 'Contacts cannot be empty!'),
        array('country','require','Country can not be empty!'),
        array('address','require','Detailed address cannot be empty!'),
        array('zip','require',"Zip code can't be empty!"),

    );

    protected $redis;

    public function __construct(){
        $this->autoCheckFields = false;
        $this->redis = \Think\Cache::getInstance('Redis');
    }

    public function checkNum($str ){
        if(empty($str)){
            return true;
        }else{
            $ret = is_numeric($str);
            return (bool)$ret;
        }
    }
    //检查固定电话/传真区号格式
    public function checkTel_a($tel_a){
        if(empty($tel_a)){
            return true;
        }else{
            $ret=preg_match('/^\d{3,4}$/',$tel_a);
            return (bool)$ret;
        }
    }

     // 检查固定电话传真号格式
    public function checkTel($tel){

        if(empty($tel)){
            return true;
        }else{
            $ret=preg_match('/^\d{7,8}$/',$tel);
            return (bool)$ret;
        }
    }

    /*
     *验证公司名称的唯一性
     * */
    public function CheckOnly( $companyName ){
        if( !empty( $companyName ) ){
            $ret=$this->redis->exists( "string:company:{$companyName}" );
        }
        return !$ret;
    }

    /*
        *验证公司名称的唯一性
        * */
    public function SetCompanyName( $id,$companyName ){
        if( !empty( $companyName ) ){
            $ret=$this->redis->set( "string:company:{$companyName}",$id );
        }
        return !$ret;
    }

     /*
      * 设置地区集合
      * $id =>uid
      * $area => 地区id
      * $act 为空时，为删除方法
      * */
    public function SetMemberAreaKeys( $id,$area,$act='' ){

        if( !empty( $id )&& !empty( $area ) && !empty($act)){
            $art = $this->redis->SADD( "set:area:member:{$area}",$id );
        }else{
            $art = $this->redis->SREM( "set:area:member:{$area}",$id );
        }
        return $art;
    }

    /*
     * 第一次添加基础资料的状态
     * $id =>Uid
     * $state  状态值
     * */
    public function SetMemberState( $id,$state ){
        if(!empty($id) && !empty($state)){
            $rest = $this->redis->SADD("set:company:state:{$state}",$id);
            return $rest;
        }
    }

    /*
     * 移除状态
     *  $id =>Uid
     * $state  状态值
     * */
    public function DelMemberState( $id,$state ){
        if(!empty($id) && !empty($state)){
            $rest = $this->redis->SREM("set:company:state:{$state}",$id);
            return $rest;
        }
    }

    /*
     * 获取key
     * $CacheKeys
     * */
    public function GetMemberInfoCacheKeys( $id )
    {
        return "hash:member:info:{$id}";
    }
    /*
     * 获取用户Keys
     * $id =>Uid
     * */
    public function GetMemberCacheKeys($id){
        return "hash:member:{$id}";
    }
    /*
     * 获取基础数据key
     * */
    public function GetBaseCacheKeys($type){
        return "set:{$type}:status:1";
    }

    /*
     * 获取地区CackeKeys
     * */
    public function GetAreaCacheKeys( $id ){
        return "hash:area:{$id}";
    }

     /*
      * 获取Keys 是否存在
      * $id Uid
      * */
    public function MemberInfoKeysExists( $id ){
          if( !empty( $id ) ){
              $CacheKeys = $this->GetMemberInfoCacheKeys( $id );
              $ret = $this->redis->exists( $CacheKeys );
              return $ret;
          }
    }
    /*
     * 获取地区title
     *  $id
     *  $pram =>array()
     * */
    public function GetAreaTitle( $id,$pram ){
        $ret = false;
        if( empty( $id )|| empty( $pram ) ){
            return $ret;
        }
        $CacheKeys=$this->GetAreaCacheKeys( $id );
        $ret = $this->redis->hmget( $CacheKeys,$pram );
         if( empty($ret) ){
             return false;
         }
        return $ret;
    }

    /*
     * 获取基础数据
     * $type
     * */
    public function GetBaseData($type){
        $CacheKeys=$this->GetBaseCacheKeys($type);
        $sortParam = [
            'by' => 'hash:'.$type.':*->sorted',
            'get' => ['hash:'.$type.':*->id', 'hash:'.$type.':*->title','hash:'.$type.':*->sorted'],
            'sort' => 'asc',
        ];
        $data = $this->redis->sort($CacheKeys, $sortParam);
        if ($data) {
            foreach ($data as $k => $v) {
                $arr[$v] = $this->redis->hGetAll("hash:{$type}:{$v}");//取出所有的hash values;
                $arr[$v]['id'] = $v;
            }
        }
        return $arr;
    }

    /*
     * 新增
     * $id => Uid\
     * $pram =>array()
     * */
    public function InsertAccountInfo( $id,$pram )
    {

        $ret = false;
        if( empty( $id ) || empty( $pram ) ){
            return $ret;
        }

        $CacheKeys = $this->GetMemberInfoCacheKeys( $id );
        D( 'Home/Product' )->removeOldModelProduct( $id );
        $ret = $this->redis->hmset( $CacheKeys,$pram );
        $data=$this->SelectAccountInfo( $id,array('other') );
        
        if($ret){
        	$this->redis->sadd($this->getActiveCountryKey(),$data['other']['country']);
        	$this->redis->sadd($this->getActiveMemberKey($data['other']['country']),$id);
        	$this->redis->sadd($this->CompleteKey(1),$id);
        	$this->redis->srem($this->CompleteKey(0),$id);
        }
        D( 'Home/Product' )->insertOldModelProduct( $id );
        return $ret;
    }

    /*
     * 获取活跃国家的key
     */
    public function getActiveCountryKey(){
    	return "set:member:active:country";
    }
    /*
     * 获取活跃国家与用户关联的key
     * @param $country 国家ID
     */
    public function getActiveMemberKey($country){
    	if($country){
    		return "set:active:country:{$country}";
    	}
    	
    }
    
    /*
     * 查看个人资料
     * $id = > Uid
     * $pram =>array()
     * */
    public function SelectAccountInfo( $id,$pram )
    {
        $ret = false;
        if( empty( $id ) || empty( $pram ) ){
            return $ret;
        }
        $CacheKeys=$this->GetMemberInfoCacheKeys( $id );
        $ret = $this->redis->hmget( $CacheKeys,$pram );

            if( empty( $ret ) ){
               return false;
             }
             if( in_array( 'other',$pram ) ){
                 $ret['other']=unserialize( $ret['other'] );
             }
        return $ret;
    }

    /*获取用户信息
     * $id =>Uid
     * $pram =>array()
     * */
    public function GetNationality( $id,$pram ){
        if( !empty($id) ){
            $MemberCacheKeys=$this->GetMemberCacheKeys( $id );
            $rest=$this->redis->hmget( $MemberCacheKeys,$pram );
        }
        return $rest;
    }

    /*
     * 设置用户信息
     * $id =>Uid
     * $pram= >array()
     * */
    public function SetNationality( $id,$pram ){
        if( !empty($id) ){
            $MemberCacheKeys=$this->GetMemberCacheKeys( $id );
            $rest=$this->redis->hmset( $MemberCacheKeys,$pram );
        }
        return $rest;
    }

    /*
     * 删除邮箱集合
     * $email
     * */
    public function DelEmailCacheKeys( $email ){
        if( !empty( $email ) ){
           $arr=$this->redis->del("string:company:email:{$email}");
            return $arr;
        }
    }
    
    /*
     * 用户是否完善资料
     */
    public function CompleteKey($param){
    	if($param){
    		return "set:company:complete:{$param}";
    	}
    }

    /**
     * 获取用户其它信息
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return array
     */
    public function getMemberInfo( $param ){
        $ret 	= array();
        $id 	= intval( $param['id'] );
        if( empty( $id ) ){
            return $ret;
        }
        $redis = new \Think\Cache\Driver\Redis();
        $cacheKey = $this->GetMemberInfoCacheKeys( $id );
        $data = $redis->hgetall( $cacheKey );
        if( !empty( $data ) ){

        }
        $ret = $data;
        return $ret;
    }

    /**
     * 获取用户资料是否完善
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return bool
     */
    public function checkInfoIsComplete( $param ){
        $ret = false;
        if( empty( $param['id'] ) ){
            return $ret;
        }
        $data = $this->getMemberInfo( $param );
        if( !empty( $data ) ){
            $ret = true;
            $requiredKeys = array(
                'companyName', 'trade', 'turnover', 'contact'
            );
            foreach( $requiredKeys as $key ){
                if( empty( trim( $data[$key] ) ) ){
                    $ret = false;
                    break;
                }
            }

            $otherData = unserialize( $data['other'] );
            $requiredKeys = array(
                'country'
            );
            foreach( $requiredKeys as $key ){
                if( empty( $otherData[$key] ) ){
                    $ret = false;
                    break;
                }
            }
        }
        return $ret;
    }

   /*
    *根据国家的缩写取出长名
    * */
     public function GetCountryName( ){
         $rest=$this->redis->hgetAll("hash:country:name");
        return $rest;
     }
     
     /*
      * 获取supplier List
      * @param=array('country'=>,'p'=>);
      */
     public function getSupplierList($param=''){
     	$page=empty($param['p'])?1:intval($param['p']);
     	$pageSize=!empty($page['pageSize'])?$param['pageSize']:10;
     	$offset=($page-1)*$pageSize;
     	//$filter='';
     	if(!empty($param['country'])){
     		$filter['country']=$param['country'];
     		$countryarray=explode(',', $param['country']);
     		foreach ($countryarray as $v){
     			$tarr[]=$this->getActiveMemberKey($v);
     		}
     		$t=uniqid();
     		$this->redis->zUnion("tmp:set:country:Union:".$t,$tarr);
     		$this->redis->expire("tmp:set:country:Union:".$t,5);
     		$arr[]="tmp:set:country:Union:".$t;
     	}
     	if(!empty($param['title'])){
     		$filter['title']=$param['title'];
     		$arr[]=D("Shell")->search('member:companyName',strtolower($param['title']),'set');
     	}
     	
     	$arr[]=$this->CompleteKey(1);
     	$arr[]="set:member:status:1";
     	$a=md5(http_build_query(empty($filter)?array():$filter));
     	if($this->redis->exists('tmp:set:supplier:list:'.$a)){
     		$tmpset=1;
     	}else{
     		$tmpset=$this->redis->zInter('tmp:set:supplier:list:'.$a,$arr);
     	}
     	
     	if($tmpset &&$this->redis->expire('tmp:set:supplier:list:'.$a,3)){
     		//$res=$this->redis->ZCARD("tmp:set:supplier:list:{$a}");
     		$id=$this->redis->sort("tmp:set:supplier:list:{$a}",array('get'=>array('hash:member:*->id'),'by'=>'hash:member:*->addTime','sort'=>'asc','limit'=>array($offset,$pageSize)));
     		$country=$this->redis->sort("tmp:set:supplier:list:{$a}",array('get'=>array('hash:member:info:*->other'),'by'=>'hash:member:*->addTime','sort'=>'asc','limit'=>array($offset,$pageSize)));
     		$companyIntroduction=$this->redis->sort("tmp:set:supplier:list:{$a}",array('get'=>array('hash:member:info:*->companyIntroduction'),'by'=>'hash:member:*->addTime','sort'=>'asc','limit'=>array($offset,$pageSize)));
     		$companyName=$this->redis->sort("tmp:set:supplier:list:{$a}",array('get'=>array('hash:member:info:*->companyName'),'by'=>'hash:member:*->addTime','sort'=>'asc','limit'=>array($offset,$pageSize)));
     		//var_dump(unserialize($country[0]));exit;
     		$j=count($id);
     		$arr=array();
     		for($i=0;$i<$j;$i++){
     			$arr[$i]['id']=$id[$i];
     			$arr[$i]['country']=$this->GetAreaTitle(unserialize($country[$i])['country'],array( 'title' ) )['title'];
     			$arr[$i]["companyIntroduction"]=$companyIntroduction[$i];
     			$arr[$i]['companyName']=$companyName[$i];
     		}

     		$pageinfo['count']=$this->redis->ZCARD('tmp:set:supplier:list:'.$a);
     		$pageinfo['page']=$page;
     		$pageinfo['pagecount']=ceil($pageinfo['count']/$pageSize);
     		$a='';
     		if(!empty($filter)){$a=http_build_query($filter);};
     		$show=D("Buyoffer")->showpage($pageinfo['count'],$page,$pageSize,$a);
     		$return=array('show'=>$show,'list'=>$arr,'pageinfo'=>$pageinfo);
     	}else{
     		$return=null;
     	}
     		
     	return $return;
     }
     
     /**
      * 获取活跃国家
      * @return key=>id  value =>country
      */
     
     public function getArrayActiveCountry(){
     	$active=$this->getActiveCountryKey();
     	$arr=$this->redis->sdiff($active);
     	$ret=array();
     	foreach ($arr as $k=>$v){
     		$ret[$k]['id']=$v;
     		$ret[$k]['title']=$this->GetAreaTitle($v, array( 'title' ))['title'];
     	}
     	
     	return $ret;
     }
     
}