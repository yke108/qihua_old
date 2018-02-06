<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016-11-30
 * Time: 13:56
 */

namespace Admin\Model;
use Think\Model;

class BuyOfferModel extends Model{
    protected $redis;

    public function __construct(){
        $this->autoCheckFields == false;
        $this->redis = \Think\Cache::getInstance('Redis');
    }

    public function GetBuyOfferCacheKeys( $id ){
        return "hash:buyoffer:{$id}";
    }

    public function GetBuyOfferCacheTypeKeys( $type ){
        return "set:buyoffer:type:{$type}";
    }

    public function GetBuyOfferStatusCacheKeys( $status ){
           return "set:buyoffer:status:{$status}";
    }

    public function GetBuyOfferStateCacheKeys( $state ){
        return "set:buyoffer:state:{$state}";
    }

    public function GetBuyOfferMemberCacheKeys( $uid ){
        return "set:buyoffer:member:{$uid}";
    }

    public function GetBuyOfferOperaCacheKeys( $id ){
        return "hash:buyoffer:operation:history:{$id}";
    }

    public function GetNumberCacheKeys( $number ){
        return "string:buyoffer:{$number}";
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
         $TypeCacheKeys = $this->GetBuyOfferCacheTypeKeys( $pram );
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
            $StateCacheKeys = $this->GetBuyOfferStateCacheKeys( $pram );
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

    public function GetAllBuyOffer( $pram,$offset,$rows ){
        if( empty($pram) ){
            return false;
        }
        $tmpCacheKeys = $this->GetTmpSetCacheKeys( 'BuyOffer' );
        $data = array();
        $data['total'] = $this->redis->ZINTER( $tmpCacheKeys,$pram );
        if( $data['total'] && $this->redis->expire( $tmpCacheKeys,60 ) ){
            $options = array(
                'get'=>array(
                    'hash:buyoffer:*->id','hash:buyoffer:*->number','hash:buyoffer:*->title','hash:buyoffer:*->type',
                    'hash:buyoffer:*->createTime','hash:buyoffer:*->updateTime','hash:buyoffer:*->expire','hash:buyoffer:*->content',
                    'hash:buyoffer:*->times','hash:buyoffer:*->state','hash:buyoffer:*->Uid'
                ),
                 'sort' =>'DESC',
                 'by' =>'hash:buyoffer:*->id'
            );
            if( !empty( $rows ) ){
                $options['limit'] = array($offset,$rows);
            }
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
            $type =$this->BuyOfferBaseData();
           foreach($listArr as $k1=>$v1){
               $listArr[$k1]['type'] =$type['FIND_GOODS_TYPE'][$listArr[$k1]['type']];
               $listArr[$k1]['companyName'] = $this->GetMemberInfo( $listArr[$k1]['Uid'],array('companyName'))['companyName'];
               if( $listArr[$k1]['state'] == 0 || $listArr[$k1]['state'] == 4 ){
                   $listArr[$k1]['reason'] = $this->getOneHistory($listArr[$k1]['id'])['reason'];
               }

           }

            if(empty($rows)){
                $data = $listArr;
            }else{
                $data['rows'] = $listArr;
            }
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
        $HistoryCacheKeys = $this->GetBuyOfferOperaCacheKeys( $id );
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
        $cacheKeys = $this->GetBuyOfferCacheKeys( $id );
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
    public function editBuyOffer( $id,$pram ){
        if(empty($id) || empty($pram)){
            return false;
        }
        $cacheKeys = $this->GetBuyOfferCacheKeys( $id );
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
        $stateKeys0 = $this->GetBuyOfferStateCacheKeys( 0 );
        $stateKeys1 = $this->GetBuyOfferStateCacheKeys( 1 );
        $stateKeys2 = $this->GetBuyOfferStateCacheKeys( 2 );
        $stateKeys3 = $this->GetBuyOfferStateCacheKeys( 3 );
        $stateKeys4 = $this->GetBuyOfferStateCacheKeys( 4 );

        $statesKeys = $this->GetBuyOfferStateCacheKeys( $pram['state'] );//新状态
        $cacheKeys = $this->GetBuyOfferCacheKeys( $id );//获取集合
       for($i=0;$i<5;$i++){
            $this->redis->watch($cacheKeys);
            $this->redis->multi();
            $this->redis->hmset($cacheKeys,$pram);
            $this->redis->srem($stateKeys0,$id);
           $this->redis->srem($stateKeys1,$id);
           $this->redis->srem($stateKeys2,$id);
           $this->redis->srem($stateKeys3,$id);
           $this->redis->srem($stateKeys4,$id);
            $this->redis->SADD($statesKeys,$id);
            if($this->redis->exec()){
                return true;
            }
        }
        return false;

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
        $num = $this->redis->incr("string:buyoffer:history");//自增id
        $pram = serialize($pram);
        $cacheKeys = $this->GetBuyOfferOperaCacheKeys( $id );
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
        $cacheKeys = $this->GetBuyOfferOperaCacheKeys( $id );
         $rest = $this->redis->hgetAll( $cacheKeys );
        krsort($rest);
        $newRest = array();
        $state =$this->BuyOfferBaseData();
        foreach( $rest as $k=>$v ){
            $newRest[$k] = unserialize( $v );
            if( $newRest[$k]['otype']=='Webmaster' ){
              $newRest[$k]['oid'] = D('Admin/User')->getUserName( $newRest[$k]['oid'] ); //后台
           }else{
               $newRest[$k]['oid'] = D('User/Account')->GetNationality( $newRest[$k]['oid'],array( 'username' ) )['username'];//前台
          }
            $newRest[$k]['states'] = $newRest[$k]['state'];
            $newRest[$k]['state'] = $state['FIND_GOODS_STATE'][$newRest[$k]['state']];
            $newRest[$k]['addTime'] = date( 'Y-m-d H:i:s',$newRest[$k]['addTime'] );
        }
       if(!empty($rows)){
           $data['total']  = Count($newRest);
           $data['rows']  = array_slice($newRest, $offset, $rows );
       }else{
           $data = $newRest;
       }

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

    public function BuyOfferBaseData(){
        $ret=array(
            'FIND_GOODS_STATE' => array(
                '1' => "Effective",
                '0' => "Audit Disapproved",
                '2' => "Audit Pending",
                '3' => "Expired",
                '4' => "Revoke"
            ),
        );
        $ret['FIND_GOODS_TYPE'] = C('FIND_GOODS_TYPE');;
        return $ret;
    }
} 