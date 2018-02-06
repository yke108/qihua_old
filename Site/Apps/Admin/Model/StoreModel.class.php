<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/9/8
 * Time: 15:37
 */

namespace Admin\Model;
use       Think\Model;

class StoreModel extends Model{

    protected $redis;

    public function __construct(){
        $this->autoCheckFields = false;
        $this->redis = \Think\Cache::getInstance('Redis');
    }

  /*分类集合键*/
        public function getCategoryKeys($category){
            return "set:productDepot:category:{$category}";
        }

    /*获取有效列表的 集合键*/
    public function getListKeys($status){
      $this->redis->SINTERSTORE("tmp:set:productDepot:list","set:productDepot:state:{$status}",'set:productDepot:status:1');
        $this->redis->expire("tmp:set:productDepot:list",60);
        return  "tmp:set:productDepot:list";
    }

    /*创建临时键*/
    public function getTmpKeys(){
        return "tmp:set:List:".uniqid();
    }

    /*获取所有的cas号对应的商品id*/
    public function getCasKeys($cas){
        $Tmp=$this->getTmpKeys();
        if($cas){
            $res=$this->redis->get("string:productDepot:cas:{$cas}");
        }
        if(!empty($res)){
            $this->redis->Sadd($Tmp,$res);
            $this->redis->expire($Tmp,60);
        }
        return  $Tmp;
    }

    /*
     * 并集
     * */
    public function getAllData($keys){
        if(!empty($keys)){
            $TmpId=uniqid();
            $this->redis->ZUNIONSTORE('tmp:company:productDepot:list:'.$TmpId,$keys);
            $this->redis->expire('tmp:company:productDepot:list:'.$TmpId,60);
           return 'tmp:company:productDepot:list:'.$TmpId;
        }
    }

    /*获取总数*/
    public function getCount($keys){
        $TmpId=uniqid();
        $keys=$this->redis->ZINTER("tmp:hash:productDepot:list:{$TmpId}",$keys);
        $this->redis->expire("tmp:hash:productDepot:list:{$TmpId}",60);
            return $keys;
    }
    /*交集结果-分页*/
     public function getSinterstore($keys,$offset,$rows){
        $TmpId=uniqid();
         //var_dump($keys);
         $key=$this->redis->ZINTER("tmp:hash:productDepot:list:{$TmpId}",$keys);
          if($key && $this->redis->expire("tmp:hash:productDepot:list:{$TmpId}",60)){
              $arr_productDepot_option=array(
                  'get'=>array(
                            'hash:productDepot:*->id','hash:productDepot:*->productCode','hash:productDepot:*->cnName',
                            'hash:productDepot:*->cnAlias','hash:productDepot:*->enName','hash:productDepot:*->enAlias',
                            'hash:productDepot:*->categoryList','hash:productDepot:*->Uid','hash:productDepot:*->updateTime',
                            'hash:productDepot:*->matterType','hash:productDepot:*->cas','hash:productDepot:*->state'
                  ),
                    'limit'=>array($offset,$rows),
                    'sort'=>'desc',
                    'by'=>'hash:productDepot:*->updateTime',
              );
              $listTmpArr=$this->redis->sort("tmp:hash:productDepot:list:{$TmpId}",$arr_productDepot_option);
              $listArr=array();
              $num=0;
              /*数组整合*/
              if($listTmpArr){
                  foreach($listTmpArr as $k=>$v){
                      if($k%12==0){
                          $listArr[$num]['id']=$v;
                      }elseif($k%12==1){
                          $listArr[$num]['productCode']=$v;
                      }elseif($k%12==2){
                          $listArr[$num]['cnName']=$v;
                      }elseif($k%12==3){
                          $listArr[$num]['cnAlias']=$v;
                      }elseif($k%12==4){
                          $listArr[$num]['enName']=$v;
                      }elseif($k%12==5){
                          $listArr[$num]['enAlias']=$v;
                      }elseif($k%12==6){
                          $listArr[$num]['categoryList']=$v;
                      }elseif($k%12==7){
                          $listArr[$num]['Uid']=$v;
                      }elseif($k%12==8){
                          $listArr[$num]['updateTime']=date('Y-m-d H:i:s',$v);
                      }elseif($k%12==9){
                          $listArr[$num]['matterType']=$v;
                      }elseif($k%12==10){
                          $listArr[$num]['cas']=$v;
                      }elseif($k%12==11){
                          $listArr[$num]['state']=$v;
                          $num++;
                      }
                  }
              }

              $rest=array();
                foreach($listArr as $keys=>$values){
                    $listArr[$keys]['reason']=$this->getState($listArr[$keys]['id']);
                    $listArr[$keys]['Uid']=$this->getCompanyName($listArr[$keys]['Uid']);
                    $listArr[$keys]['categoryList']=explode(',',$listArr[$keys]['categoryList']);
               }
              foreach($listArr as $keys=>$v){
                  foreach($listArr[$keys]['categoryList'] as $k=>$V){
                      $rest[$keys]['categoryList'][$k]=$this->getCategoryName($listArr[$keys]['categoryList'][$k]);
                  }
                  $listArr[$keys]['categoryList']=implode(',',$rest[$keys]['categoryList']);
          }
              return $listArr;
          }
     }

    /*获取companyName*/
    public function getCompanyName($uid){
        if($uid){
            $name=$this->redis->hget("hash:member:info:{$uid}",'companyName');
        }
        return $name;
    }

    /*获取分类地址*/
    public function getCategoryName($id){
        if($id){
            $title=$this->redis->hget("hash:category:{$id}",'title');
        }
        return $title;
    }

    /*获取品牌名*/
    public function getBrandName($id){
        if($id){
            $title=$this->redis->hget("hash:brand:{$id}",'title');
        }
        return $title;
    }

    /*获取生产商*/
    public function getproducerName($id){
        if($id){
            $title=$this->redis->hget("hash:producer:{$id}",'title');
        }
        return $title;
    }

  /*获取产地名称*/
    public function getplaceListName($id){
        if($id){
            $title=$this->redis->hget("hash:area:{$id}",'title');
        }
        return $title;
    }
    /*获取产品详细图片*/
    public function getImg($id){
        if($id){
            $imgArr=array();
            $img=$this->redis->get("string:productDepot:img:{$id}");
            $imgArr=unserialize($img);
        }
            return $imgArr;
    }

    /*获取详细描述*/
    public function getInfo($id){
        if($id){
            $infoKeys=$this->redis->hkeys("hash:productDepot:info:{$id}");
            $infoVals=$this->redis->hvals("hash:productDepot:info:{$id}");
            $info=array_combine($infoKeys,$infoVals);
        }
        return $info;
    }

    /*获取商品状态*/
    public function getState($id){
   /*     $o=array(
            '0'=>'待审核',
            '1'=>'有效',
            '2'=>'审核不通过',
             '3'=>'审核已撤消'
        );*/
        if($id){
            $opera=$this->redis->hgetAll("hash:productDepot:operation:history:{$id}");
            ksort($opera);;
            $operas=end($opera);
            $operation= unserialize($operas);
            $Arr['addTime']=date('Y-m-d H:i:s',$operation['addTime']);
            $Arr['state']=$operation['state'];
            $Arr['reason']=$operation['reason'];
        }

        return $Arr;
    }

    //商品或抢购是否在售


    /*获取详情页--详细信息*/
    public function detail($id){
        if($id){
            $matterType=array(
                 '0' =>'商城和抢购都未在售',
                 '1'=>'商城在售',
                 '2'=>'抢购在售',
                 '3'=>'商城、抢购都在售'
            );
            /*获取详情页*/
            $keys=$this->redis->hkeys("hash:productDepot:{$id}");
            $vals=$this->redis->hvals("hash:productDepot:{$id}");
            $arr=array_combine($keys,$vals);

            $arr['companyName']=$this->getCompanyName($arr['Uid']);//公司名称
            $arr1=explode(',',$arr['categoryList']);
            foreach($arr1 as $k=>$v){
                $rest[$k]=$this->getCategoryName($arr1[$k]);
            }
            $rest=implode('>',$rest);
            $arr['matterType']=$matterType[$arr['matterType']];
            $arr['categoryList']=$rest;//分类列表
            $arr['brandId']=$this->getBrandName($arr['brandId']);//品牌名称
            $arr['producerId']=$this->getproducerName($arr['producerId']);//生产商名称

            $arr2=explode(',',$arr['placeList']);
            foreach($arr2 as $k=>$v){
                $rest2[$k]=$this->getplaceListName($arr2[$k]);
            }
            if(!empty($rest2)){
                $rest2=implode('>',$rest2);
            }
            //$rest2=implode(' ',$rest2);
            $arr['placeList']=$rest2;//产地

            $arr3=explode(',',$arr['seatList']);
            foreach($arr3 as $k=>$v){
                $rest3[$k]=$this->getplaceListName($arr3[$k]);
            }
            if(!empty($rest3)){
                $rest3=implode('>',$rest3);
            }
            $arr['seatList']=$rest3;//货物路径*/
            $arr['attribute']=unserialize($arr['attribute']);

            $arr['img']=$this->getImg($id);//产品图片
            $arr['info']=$this->getInfo($id);//详细描述
            $arr['state']=$this->getState($id);
            $arr['history']=$this->getHistory($id);
        }
        return $arr;
    }

    //获取操作历史
    public function getHistory($id){
        if($id){
           $arr= $this->redis->hgetAll("hash:productDepot:operation:history:{$id}");
        }
        krsort($arr);
        $arr1=array_merge($arr);
        foreach($arr1 as $k=>$v){
            $arr1[$k]=unserialize($arr1[$k]);
        }
        return $arr1;
    }
    /*获取商品分类*/
    public function getCategory(){
        $tmpId=uniqid();
        $count=$this->redis->SINTERSTORE("tmp:hash:category:list:{$tmpId}",'set:category:status:1','set:categoryChild:0');
        if($count && $this->redis->expire("tmp:hash:category:list:{$tmpId}",60)){
            $category_sort_option=array(
                'get'=>array('hash:category:*->id','hash:category:*->title','hash:category:*->parentId','hash:category:*->depth'),
            );
            $categoryTmpArr=$this->redis->sort("tmp:hash:category:list:{$tmpId}",$category_sort_option);
            $categoryArr=array();
            $num=0;

            //数组整合
            if($categoryTmpArr){
                foreach($categoryTmpArr as $k=>$v){
                    if($k%4==0){
                        $categoryArr[$num]['id']=$v;
                    }elseif($k%4==1){
                         $categoryArr[$num]['text']=$v;
                    }elseif($k%4==2){
                        $categoryArr[$num]['attributes']['parentId']=$v;
                    }elseif($k%4==3){
                        $categoryArr[$num]['attributes']['depth']=$v;
                        $num++;
                    }
                }
                return $categoryArr;
            }
        }

    }

    /*获取子分类*/
    public function getChildCategory($id){
        if($id){
            $tmpId=uniqid();
            $count=$this->redis->SINTERSTORE("tmp:hash:category:list:{$tmpId}",'set:area:status:1',"set:categoryChild:{$id}");
            if($count && $this->redis->expire("tmp:hash:category:list:{$tmpId}",60)){
                $category_sort_option=array(
                    'get'=>array('hash:category:*->id','hash:category:*->title','hash:category:*->parentId','hash:category:*->depth'),
                );
                $categoryTmpArr=$this->redis->sort("tmp:hash:category:list:{$tmpId}",$category_sort_option);
                $categoryArr=array();
                $num=0;

                //数组整合
                if($categoryTmpArr){
                    foreach($categoryTmpArr as $k=>$v){
                        if($k%4==0){
                            $categoryArr[$num]['id']=$v;
                        }elseif($k%4==1){
                            $categoryArr[$num]['text']=$v;
                        }elseif($k%4==2){
                            $categoryArr[$num]['attributes']['parentId']=$v;
                        }elseif($k%4==3){
                            $categoryArr[$num]['attributes']['depth']=$v;
                            $num++;
                        }
                    }
                    return $categoryArr;
                }
            }
        }else{
            return false;
        }
    }

    /*获取分类id对应的商品仓库id*/
    public function getProductDepot($id){
        if($id){
            $categoryArr=$this->redis->sMembers("set:productDepot:category:{$id}");
        }
        return $categoryArr;
    }

    /*
     * $cas CAS号
     * 判断是否为CAS号*/
    public function is_cas($cas){
        if($this->redis->exists("string:productDepot:cas:{$cas}")){
            return true;
        }else{
            return false;
        }

    }


    //仓库下架，商品为系统下架,并记录history
    public function setHistory($id,$data){
        $num=$this->redis->incr("string:product:history");
        $ret=serialize($data);
        $res=$this->redis->hmset("hash:product:operation:history:".$id,array($num=>$ret));
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function productSystemOff($id,$arr){
        $num=uniqid();
        $count=$this->redis->SINTERSTORE("tmp:product:lists:{$num}","set:product:productDepot:{$id}",'set:product:status:1');
        if($count && $this->redis->expire("tmp:product:lists:{$num}",60)){
            $ids=$this->redis->smembers("tmp:product:lists:{$num}");//该仓库下的所有商品id集合
            foreach($ids as $k=>$v){
                      $this->redis->hset("hash:product:{$v}",'state','6');//6为系统下架
               $state=$this->redis->SADD("set:product:state:6",$v);//添加一个为系统下架集合，存对应的id进去
                      $this->redis->SADD("set:product:state:3",$v);//加入下架集合，存对应的id进去
                      $this->redis->srem("set:product:state:1",$v);//删除正常状态的id
                $data=array(
                    'id'=>$id,
                    'oid'=>$_SESSION['uid'],
                    'opera'=>'商品已被系统下架',
                    'addTime' => time(),
                    'state'=>6,
                    'reason'=>$arr['reason'],
                    'otype'=>'system',
                );
                $this->setHistory($v,$data);//记录商品历史
            }
            return $state;
        }
    }

    //仓库下架，抢购为系统下架,并记录history
    public function setHistoryHot($id,$data){
        $num=$this->redis->incr("string:purchase:history");
        $ret=serialize($data);
        $res=$this->redis->hmset("hash:purchase:operation:history:".$id,array($num=>$ret));
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function purchaseSystemOff($id,$arr){
        $num=uniqid();
        $count=$this->redis->SINTERSTORE("tmp:purchase:lists:{$num}","set:purchase:productDepot:{$id}",'set:purchase:status:1');
        if($count && $this->redis->expire("tmp:purchase:lists:{$num}",60)){
            $ids=$this->redis->smembers("tmp:purchase:lists:{$num}");//该仓库下的所有商品id集合
            foreach($ids as $k=>$v){
                $this->redis->hset("hash:purchase:{$v}",'state','6');//6为系统下架
                        $this->redis->SADD("set:purchase:state:6",$v);//添加一个为系统下架集合，存对应的id进去
                $state=$this->redis->SADD("set:purchase:state:3",$v);
                     $this->redis->srem("set:purchase:state:1",$v);
                $data=array(
                    'id'=>$v,
                    'oid'=>$_SESSION['uid'],
                    'opera'=>'抢购活动已被系统下架',
                    'addTime' => time(),
                    'state'=>6,
                    'reason'=>$arr['reason'],
                    'otype'=>'system',
                );
               $this->setHistoryHot($v,$data);//记录商品历史
            }

            return $state;
        }
    }
    /*
     * $id ID
     * $data  array
     * 修改单个用户status 撤销通过*/
    public function changeStatus($id,$data){
        $ret=false;
        if(empty($data['oid'])){
            return $ret;
        }
        if(empty($id)){
            return $ret;
        }

        /*
         * 撤销通过时，该仓库的商品以及抢购同时为6 系统下架
         * 1，先获取到该商品仓库中的商品id和抢购id
         * 2,修改商品和抢购状态为删除5  单独存在一个集合
         * 修改--删除--新增--新增历史操作*/
        $arr['reason']=$data['reason'];
        $this->productSystemOff($id,$arr);//修改商品状态
        $this->purchaseSystemOff($id,$arr);//修改抢购状态
        $this->redis->hmset("hash:productDepot:{$id}",array('state'=>3));//撤销通过
        $this->redis->srem("set:productDepot:state:1",$id);//删除状态
        $this->redis->SAdd("set:productDepot:state:3",$id);//新增状态
        //历史记录
        $num=$this->redis->incr('string:productDepot:history');
          $ret=serialize($data);
        $res=$this->redis->hmset("hash:productDepot:operation:history:".$id,array($num=>$ret));
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /*
    * $id ID
    * $data  array
    * 修改单个用户status 审核通过*/
    public function examStatus($id,$uid){
        $ret=false;
        if(empty($id)){
            return $ret;
        }
        $ids=explode(',',$id['id']);
        /*修改--删除--新增--新增历史操作*/
        foreach($ids as $k=>$v){
            $this->setProductOn($v);
            $this->setPurchaseOn($v);
            $this->redis->hmset("hash:productDepot:{$v}",array('state'=>1));//审核通过
            $this->redis->srem("set:productDepot:state:0",$v);//删除待审核状态
            $this->redis->SAdd("set:productDepot:state:1",$v);//新增状态
            //历史记录
                $data=array(
                    'id'=>$v,
                    'oid'=>$uid,
                    'opera'=>'审核通过',
                    'addTime' => time(),
                    'state'=>1,
                    'otype'=>'admin',
                );
            $ret=serialize($data);
            $num=$this->redis->incr('string:productDepot:history');
            $res=$this->redis->hmset("hash:productDepot:operation:history:".$id['id'],array($num=>$ret));
        }
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /*审核不通过*/
    public function failStatus($id,$data){
        $ret=false;
        if(empty($data['oid'])){
            return $ret;
        }
        if(empty($id)){
            return $ret;
        }

        /*修改--删除--新增--新增历史操作*/
        $this->redis->hmset("hash:productDepot:{$id}",array('state'=>2));//审核不通过
        $this->redis->SAdd("set:productDepot:state:2",$id);//新增状态
        $this->redis->srem("set:productDepot:state:0",$id);//删除状态
        //历史记录
        $num=$this->redis->incr('string:productDepot:history');
        $ret=serialize($data);
        $res=$this->redis->hmset("hash:productDepot:operation:history:".$id,array($num=>$ret));

        if($res){
            return true;
        }else{
            return false;
        }
    }
    //修改商品仓库在售状态
    public function setProductDepotMatter($id,$state){
        if($id){
            $res=$this->redis->hset("hash:productDepot:{$id}",'matterType',$state);
        }
        return $res;
    }
    //加入集合
    public function setMasterType($state,$id){
        if($id){
            $ret= $this->redis->SADD("set:productDepot:masterType:{$state}",$id);
        }
        return $ret;
    }
    //移除集合
    public function MoveMasterType($state,$id){
        if($id){
            $ret= $this->redis->SREM("set:productDepot:masterType:{$state}",$id);
        }
        return $ret;
    }
    //1，找到hash state=6的商品或者抢购，修改为1， set：xx:state：6中的id移除,并记录操作历史
    public function setProductOn($id){
        $num=uniqid();
        $count=$this->redis->SINTERSTORE("tmp:product:lists:{$num}","set:product:productDepot:{$id}",'set:product:status:1','set:product:state:6');
        if($count && $this->redis->expire("tmp:product:lists:{$num}",60)){
            $ids=$this->redis->smembers("tmp:product:lists:{$num}");//该仓库下的所有商品id集合
            foreach($ids as $k=>$v){
                $this->redis->hset("hash:product:{$v}",'state','1');//6为系统下架
                   $this->redis->srem("set:product:state:6",$v);//添加一个为系统下架集合，存对应的id进去
                       $this->redis->srem("set:product:state:3",$v);
                $state=$this->redis->SADD("set:product:state:1",$v);
                $data=array(
                    'id'=>$id,
                    'oid'=>$_SESSION['uid'],
                    'opera'=>'系统上架',
                    'addTime' => time(),
                    'state'=>1,
                    'otype'=>'system',
                );
                $this->setHistory($v,$data);//记录商品历史
            }
            if($state){
                $rest=$this->redis->hget("hash:productDepot:{$id}",'matterType');
                if($rest==0){
                    $this->setProductDepotMatter($id,'1');
                    $this->MoveMasterType('3',$id);
                    $this->MoveMasterType('2',$id);
                    $this->MoveMasterType('0',$id);
                }elseif($rest==2){
                    $this->setProductDepotMatter($id,'3');
                    $this->MoveMasterType('0',$id);
                }
            }
            return $state;
        }
    }

    public function setPurchaseOn($id){
        $num=uniqid();
        $count=$this->redis->SINTERSTORE("tmp:purchase:lists:{$num}","set:purchase:productDepot:{$id}",'set:purchase:status:1','set:purchase:state:6');
        if($count && $this->redis->expire("tmp:purchase:lists:{$num}",60)){
            $ids=$this->redis->smembers("tmp:purchase:lists:{$num}");//该仓库下的所有商品id集合
            foreach($ids as $k=>$v){
                $expire=$this->redis->hmget("hash:purchase:{$v}",array('verifyTime','expire'));
                if($expire['verifyTime'] + $expire['expire']*24*3600 <= time()) continue;
                $this->redis->hset("hash:purchase:{$v}",'state','1');//6为系统下架
                $state=$this->redis->srem("set:purchase:state:6",$v);
                       $this->redis->srem("set:purchase:state:3",$v);
                       $this->redis->SADD("set:purchase:state:1",$v);
                $data=array(
                    'id'=>$id,
                    'oid'=>$_SESSION['uid'],
                    'opera'=>'系统上架',
                    'addTime' => time(),
                    'state'=>1,
                    'otype'=>'system',
                );
                $this->setHistoryHot($v,$data);//记录商品历史
            }
            if($state){
                $rest=$this->redis->hget("hash:productDepot:{$id}",'matterType');
                if($rest==0){
                    $this->setProductDepotMatter($id,'2');
                    $this->MoveMasterType('3',$id);
                    $this->MoveMasterType('1',$id);
                    $this->MoveMasterType('0',$id);
                }elseif($rest==1){
                    $this->setProductDepotMatter($id,'3');
                    $this->MoveMasterType('0',$id);
                }
            }
            return $state;
        }
    }

    /*恢复通过*/
    public function renewStatus($id,$data){
        $ret=false;
        if(empty($data['oid'])){
            return $ret;
        }
        if(empty($id)){
            return $ret;
        }

        /*
         *
         * 修改--删除--新增--新增历史操作*/
        $this->setProductOn($id);
        $this->setPurchaseOn($id);
        $this->redis->hmset("hash:productDepot:{$id}",array('state'=>1));//审核通过
        $this->redis->srem("set:productDepot:state:3",$id);//删除状态
        $this->redis->SAdd("set:productDepot:state:1",$id);//新增状态
        //历史记录
        $num=$this->redis->incr('string:productDepot:history');

        $ret=serialize($data);
        $res=$this->redis->hmset("hash:productDepot:operation:history:".$id['id'],array($num=>$ret));

        if($res){
            return true;
        }else{
            return false;
        }
    }

    //重审通过
    public function rStatus($id,$data){
        $ret=false;
        if(empty($data['oid'])){
            return $ret;
        }
        if(empty($id)){
            return $ret;
        }

        /*修改--删除--新增--新增历史操作*/
        $this->setProductOn($id);
        $this->setPurchaseOn($id);
        $this->redis->hmset("hash:productDepot:{$id}",array('state'=>1));//审核通过
       $arr= $this->redis->srem("set:productDepot:state:2",$id);//删除状态
        $this->redis->SAdd("set:productDepot:state:1",$id);//新增状态
        //历史记录
        $num=$this->redis->incr('string:productDepot:history');

        $ret=serialize($data);
        $res=$this->redis->hmset("hash:productDepot:operation:history:".$id,array($num=>$ret));
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 仓库下的商品批量删除
     * */
    public function delAll($id){
        if($id) {
            $ids = $this->redis->smembers("set:product:productDepot:{$id}");
            foreach ($ids as $k => $v) {
                $this->redis->srem('set:product:state:1', $v);
                $res = $this->redis->sadd('set:product:state:2', $v);
            }
        }
        if($res){
            return true;
        }else{
            return false;
        }
    }
    /*
     * 商品、抢购为删除状态
     * 批量删除*/
    public function del($id,$uid){
        $ret=false;
        if(empty($id)){
            return $ret;
        }
        $ids=explode(',',$id['id']);
        /*删除*/
        foreach($ids as $k=>$v){
            $this->redis->srem("set:productDepot:status:1",$v);//删除状态
            $res= $this->redis->SAdd("set:productDepot:status:2",$v);//新增状态
            $this->delAll($v);
        }
        if($res){
            return true;
        }else{
            return false;
        }
    }

} 