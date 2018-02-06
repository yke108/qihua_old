<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016-11-30
 * Time: 13:56
 */

namespace Admin\Model;
use Think\Model;

class SupplyModel extends Model{
    protected $redis;

    public function __construct(){
        $this->autoCheckFields == false;
        $this->redis = \Think\Cache::getInstance('Redis');
    }

    public function GetSupplyCacheKeys( $id ){
        return "hash:supply:{$id}";
    }

    public function GetSupplyCacheTypeKeys( $type ){
        return "set:supply:type:{$type}";
    }

    public function GetSupplyStatusCacheKeys( $status ){
           return "set:supply:status:{$status}";
    }

    public function GetSupplyStateCacheKeys( $state ){
        return "set:supply:state:{$state}";
    }

    public function GetSupplyMemberCacheKeys( $uid ){
        return "set:supply:member:{$uid}";
    }

    public function GetSupplyOperaCacheKeys( $id ){
        return "hash:supply:operation:history:{$id}";
    }

    public function GetNumberCacheKeys( $number ){
        return "string:supply:{$number}";
    }
    public function GetMemberInfoCacheKeys( $uid ){
        return "hash:member:info:{$uid}";
    }
  public function GetAreaCacheKeys( $id ){
      return "hash:area:{$id}";
  }


    /*
     * 获取临时keys
     * */
    public function GetTmpSetCacheKeys( $name ){
        return "tmp:set:{$name}:lists:".uniqid();
    }

    //按信息编号查找
    public function GetNumber( $pram ){
        if( empty($pram) ){
            return false;
        }
        $CacheKeys= $this->GetNumberCacheKeys( $pram );
        $id = $this->redis->get( $CacheKeys );
        $tmpCacheKeys = $this->GetTmpSetCacheKeys( 'number' );
        $this->redis->SADD($tmpCacheKeys,$id);
        $this->redis->expire($tmpCacheKeys,60);
        return $tmpCacheKeys;
    }

    //按类型查找
    public function GetType( $pram ){
        if( empty($pram) ){
            return false;
        }
        $tmpCacheKeys = $this->GetTmpSetCacheKeys( 'type' );
         $TypeCacheKeys = $this->GetSupplyCacheTypeKeys( $pram );
         $arr = $this->redis->SMEMBERS( $TypeCacheKeys );
        if( $arr ){
            foreach( $arr as $v){
                $this->redis->SADD( $tmpCacheKeys,$v );
            }
            $this->redis->expire( $tmpCacheKeys,60 );
            return $tmpCacheKeys;
        }
    }

    //按用户名
    public function GetMember( $pram ){
        if( empty($pram) ){
            return false;
        }
        $tmpCacheKeys = $this->GetTmpSetCacheKeys( 'member' );
        $this->redis->ZUNIONSTORE( $tmpCacheKeys,$pram );
        $this->redis->expire( $tmpCacheKeys,60 );
        return $tmpCacheKeys;
    }

    //按状态
    public function GetState( $pram ){
        if( $pram === false ){
            return false;
        }
        $tmpCacheKeys = $this->GetTmpSetCacheKeys( 'state' );
        if( !is_array( $pram ) ){
            $StateCacheKeys = $this->GetSupplyStateCacheKeys( $pram );
            $arr = $this->redis->SMEMBERS( $StateCacheKeys );
            if( $arr ){
                foreach( $arr as $v){
                    $this->redis->SADD( $tmpCacheKeys,$v );
                }
            }
         }else{
            $this->redis->ZUNIONSTORE( $tmpCacheKeys,$pram );

        }

        $this->redis->expire( $tmpCacheKeys,60 );
        return $tmpCacheKeys;
    }

    public function GetAllSupply( $pram,$offset,$rows ){
        if( empty($pram) ){
            return false;
        }
        $tmpCacheKeys = $this->GetTmpSetCacheKeys( 'Supply' );
        $data = array();

        $data['total'] = $this->redis->ZINTER( $tmpCacheKeys,$pram );
        if( $data['total'] && $this->redis->expire( $tmpCacheKeys,60 ) ){
            $options = array(
                'get'=>array(
                    'hash:supply:*->id','hash:supply:*->number','hash:supply:*->title','hash:supply:*->type',
                    'hash:supply:*->createTime','hash:supply:*->updateTime','hash:supply:*->expire','hash:supply:*->content',
                    'hash:supply:*->Times','hash:supply:*->state','hash:supply:*->Uid'
                ),
                'limit'=>array($offset,$rows),
                 'sort' =>'DESC',
                 'by' =>'hash:supply:*->id'
            );
            $TmpArr = $this->redis->sort( $tmpCacheKeys,$options );
            $listArr = array();
            $num = 0;
            if( $TmpArr ){
                foreach( $TmpArr as $k=>$v ){
                    if($k%11==0){
                        $listArr[$num]['id']=$v;
                    }elseif($k%11==1){
                        $listArr[$num]['number']=$v;
                    }elseif($k%11==2){
                        $listArr[$num]['title']=$v;
                    }elseif($k%11==3){
                        $listArr[$num]['type']=$v;
                    }elseif($k%11==4){
                        $listArr[$num]['createTime']=empty($v)?'':date('Y-m-d H:i:s',$v);
                    }elseif($k%11==5){
                        $listArr[$num]['updateTime']=empty($v)?'':date('Y-m-d H:i:s',$v);
                    }elseif($k%11==6){
                        $listArr[$num]['expire']=$v;
                    }elseif($k%11==7){
                        $listArr[$num]['content']=$v;
                    }elseif($k%11==8){
                        $listArr[$num]['Times']=empty($v)?'':date('Y-m-d H:i:s',$v);
                    }elseif($k%11==9){
                        $listArr[$num]['state']=$v;
                    }elseif($k%11==10){
                        $listArr[$num]['Uid']=$v;
                        $num++;
                    }
                }
            }
            $type =$this->SupplyBaseData();
           foreach($listArr as $k1=>$v1){
               $listArr[$k1]['type'] =$type['FIND_GOODS_TYPE'][$listArr[$k1]['type']];
               $listArr[$k1]['companyName'] = $this->GetMemberInfo( $listArr[$k1]['Uid'],array('companyName'))['companyName'];
               if( $listArr[$k1]['state'] == 0 || $listArr[$k1]['state'] == 4 ){
                   $listArr[$k1]['reason'] = $this->getOneHistory($listArr[$k1]['id'])['reason'];
               }
           }
            $data['rows'] = $listArr;
           return $data;
        }
    }

    /*
   * 获取状态为待审核不通过或撤销通过的操作历史
   */
    public function getOneHistory( $id ){
        if( empty($id) ){
            return false;
        }
        $HistoryCacheKeys = $this->GetSupplyOperaCacheKeys( $id );
        $arr = $this->redis->hGetAll($HistoryCacheKeys);
        ksort($arr);
        $arr = unserialize(end($arr));
        return $arr;
    }

    /*
     * 获取详情
     * $id=>id
     * $pram =>array()
     * */
    public function details( $id,$pram='' ){
        if(empty( $id ) ){
            return false;
        }
        $cacheKeys = $this->GetSupplyCacheKeys( $id );
        if( !empty( $pram ) ){
            $ret = $this->redis->hmget( $cacheKeys,$pram );
        }else{
            $ret = $this->redis->hgetAll( $cacheKeys );
        }

       return $ret;
    }

    /*
     * 修改字段
     * $id =>id
     * $pram =>array()
     * */
    public function editSupply( $id,$pram ){
        if(empty($id) || empty($pram)){
            return false;
        }
        $cacheKeys = $this->GetSupplyCacheKeys( $id );
        $rest = $this->redis->hmset( $cacheKeys,$pram );
        return $rest;
    }

    /*
     * 修改状态
     * $id => id
     * $pram =>array();
     * */
    public function editState( $id,$pram ){
        if(empty($id) || empty($pram)){
            return false;
        }
        //获取当前的状态值
        $state = $this->details( $id,array( 'state' ) );
        $stateKeys = $this->GetSupplyStateCacheKeys( $state['state'] );

        $statesKeys = $this->GetSupplyStateCacheKeys( $pram['state'] );
        $cacheKeys = $this->GetSupplyCacheKeys( $id );
        $this->redis->hmset($cacheKeys,$pram);
            $this->redis->srem($stateKeys,$id);
        $ret = $this->redis->SADD($statesKeys,$id);
        return $ret;
    }

    /*
     * 新增操作历史
     * $id => id
     * $pram =>array()
     * */
    public function insertHistory( $id,$pram ){
        if(empty($id) || empty($pram)){
            return false;
        }
        $num = $this->redis->incr("string:supply:history");//自增id
        $pram = serialize($pram);
        $cacheKeys = $this->GetSupplyOperaCacheKeys( $id );
        $rest = $this->redis->hset( $cacheKeys,$num,$pram );
         return $rest;
    }

    /*
     * 获取操作历史
     * $id =>id
     * */
    public function GetHistory( $id,$offset,$rows ){
        if( empty( $id ) ){
            return false;
        }
        $cacheKeys = $this->GetSupplyOperaCacheKeys( $id );
         $rest = $this->redis->hgetAll( $cacheKeys );
        krsort($rest);
        $newRest = array();
        $state =$this->SupplyBaseData();
        foreach( $rest as $k=>$v ){
            $newRest[$k] = unserialize( $v );
            if( $newRest[$k]['otype']=='Admin' ){
              $newRest[$k]['oid'] = D('Admin/User')->getUserName( $newRest[$k]['oid'] ); //后台
           }else{
               $newRest[$k]['oid'] = D('User/Account')->GetNationality( $newRest[$k]['oid'],array( 'username' ) )['username'];//前台
          }
            $newRest[$k]['state'] = $state['FIND_GOODS_STATE'][$newRest[$k]['state']];
            $newRest[$k]['addTime'] = date( 'Y-m-d H:i:s',$newRest[$k]['addTime'] );
        }
        $data['total']  = Count($newRest);
        $data['rows']  = array_slice($newRest, $offset, $rows );
        return  $data;
    }

    /*
     * 获取用户信息
     * $id =>Uid
     * $pram =>array()
     * */
    public function GetMemberInfo( $id,$pram='' ){
         if( empty( $id ) ){
             return false;
         }
        $cacheKeys=$this->GetMemberInfoCacheKeys( $id );
        if( !empty( $pram ) ){
            $ret = $this->redis->hmget( $cacheKeys,$pram );
        }else{
            $ret = $this->redis->hgetAll( $cacheKeys );
        }
        return $ret;
    }

    /*
     *获取地区title
     * $id =>id
     * $pram =>array()
     * */
    public function GetAreaTitle( $id,$pram='' ){
        if( empty( $id ) ){
            return false;
        }
        $cacheKeys=$this->GetAreaCacheKeys( $id );
        if( !empty( $pram ) ){
            $ret = $this->redis->hmget( $cacheKeys,$pram );
        }else{
            $ret = $this->redis->hgetAll( $cacheKeys );
        }
        return $ret;
    }

    public function SupplyBaseData(){
        $ret['FIND_GOODS_TYPE']     = D('User/Supply')-> getSupplyType();
        $ret['FIND_GOODS_STATE']    = C('FIND_GOODS_STATUS');
        return $ret;
    }


    /*
     *获取通知内容
     * $id              
     * $state     修改后状态
     * $reason    原因
     * */
    function set_mess($id,$state,$reason=''){
        if($state == '0' || $state == '1' || $state == '3' || $state == '4'){

            //获取供应的编号和用户id
            $model=D('Admin/Supply');
            $pram = array('Uid','number',);
            $data=$model->details( $id,$pram ); 
            $param['uid'] = $data['Uid'];
            $param['content'] = $this -> get_content($state,$reason,$data['number']);
            $param['sender']  = 'WebMaster';
            $system = D('User/Message');
            $system -> createSystem($param);
            return true;
        }else{
            return false;
        }
    }

     /*
     *获取通知内容
     * $state    修改后状态
     * $reason    原因
     * */
    public function get_content($state,$reason='',$number){
        $content = '';
        if(!$number){
            return false;
        }
        if ($state == '2') {
            $content = "";
        }elseif($state == '1'){
            $content = "Your supply information [".$number."] authentication approved!";
        }elseif ($state == '3') {
            $content = "Your supply information [".$number."] has expired!";
        }elseif($state == '4'){
            $content = "Your supply information [".$number."] has been revoked! [why: ".$reason."]";
        }elseif($state == '0'){
            $content = "Your supply information [".$number."] authentication unapproved! [why: ".$reason."]";
        }
        return $content;
    }
} 