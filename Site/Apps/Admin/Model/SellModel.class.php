<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/9/14
 * Time: 11:38
 */

namespace Admin\Model;
use       Think\Model;

class SellModel extends Model{
    protected $redis;

    public function __construct(){
        $this->autoCheckFields = false;
        $this->redis = \Think\Cache::getInstance('Redis');
    }

    /*分类集合键*/
    public function getCategoryKeys($category){
        return "set:product:category:{$category}";
    }

    /*获取有效列表的 集合键*/
    public function getListKeys($status){
        $this->redis->SINTERSTORE("tmp:set:product:list","set:product:state:{$status}",'set:product:status:1');
        $this->redis->expire("tmp:set:product:list",60);
        return  "tmp:set:product:list";
    }

    /*创建临时键*/
    public function getTmpKeys(){
        return "tmp:set:List:".uniqid();
    }

    /*获取所有的对应的商品id*/
    public function getCodeKeys($code){
        $Tmp=$this->getTmpKeys();
        if($code){
            $res=$this->redis->get("string:productCode:{$code}");
        }
        if(!empty($res)){
            $this->redis->Sadd($Tmp,$res);
            $this->redis->expire($Tmp,60);
        }

        return  $Tmp;
    }

     //商品中文名
    public function getCnName($productCode){
        $name = '';
        if($productCode){
            $id=$this->redis->get("string:productDepot:productCode:{$productCode}");
            if($id){
                $name= $this->redis->hget("hash:productDepot:{$id}",'cnName');
            }
        }
        return $name;
    }
    /*获取总数*/
    public function getCount($keys){
        $TmpId=uniqid();
        $keys=$this->redis->ZINTER("tmp:hash:product:list:{$TmpId}",$keys);

        return $keys;
    }

    /*交集结果-分页*/
    public function getSinterstore($keys,$offset,$rows){
        $TmpId=uniqid();
        $keys=$this->redis->ZINTER("tmp:hash:product:list:{$TmpId}",$keys);
        if($keys && $this->redis->expire("tmp:hash:product:list:{$TmpId}",60)){
            $arr_product_option=array(
                'get'=>array(
                    'hash:product:*->id','hash:product:*->productCode','hash:product:*->productDepotCode',
                    'hash:product:*->title','hash:product:*->price','hash:product:*->Uid','hash:product:*->moq',
                    'hash:product:*->inventory','hash:product:*->addTime','hash:product:*->categoryList',
                    'hash:product:*->state','hash:product:*->inventoryType','hash:product:*->inventoryNum',
                    'hash:product:*->weightUnit'
                ),
                'limit'=>array($offset,$rows),
                'sort'=>'desc',
                'by'=>'hash:product:*->id',
            );
            $listTmpArr=$this->redis->sort("tmp:hash:product:list:{$TmpId}",$arr_product_option);
            $listArr=array();
            $num=0;
            $weightUnits = C( 'WEIGHTUNIT' );
            //$weightUnits[$arr['weightUnit']];
            /*数组整合*/
            if($listTmpArr){
                foreach($listTmpArr as $k=>$v){
                    if($k%14==0){
                        $listArr[$num]['id']=$v;
                    }elseif($k%14==1){
                        $listArr[$num]['productCode']=$v;
                    }elseif($k%14==2){
                        $listArr[$num]['productDepotCode']=$v;
                    }elseif($k%14==3){
                        $listArr[$num]['title']=$v;
                    }elseif($k%14==4){
                        $listArr[$num]['price']=$v;
                    }elseif($k%14==5){
                        $listArr[$num]['Uid']=$v;
                    }elseif($k%14==6){
                        $listArr[$num]['moq']=$v;
                    }elseif($k%14==7){
                        $listArr[$num]['inventory']=$v;
                    }elseif($k%14==8){
                        $listArr[$num]['addTime']=date('Y-m-d H:i:s',$v);
                    }elseif($k%14==9){
                        $listArr[$num]['categoryList']=$v;
                    }elseif($k%14==10){
                        $listArr[$num]['state']=$v;
                    }elseif($k%14==11){
                        $listArr[$num]['inventoryType']=$v;
                    }elseif($k%14==12){
                        $listArr[$num]['inventoryNum']=$v;   
                    }elseif($k%14==13){
                        $listArr[$num]['weightUnit']=$v;
                        $num++;
                    }
                }
            }

            $rest=array();
            foreach($listArr as $keys=>$values){
                $listArr[$keys]['Uid']=$this->getCompanyName($listArr[$keys]['Uid']);
                $listArr[$keys]['categoryList']=explode(',',$listArr[$keys]['categoryList']);
                $listArr[$keys]['cnName']=$this->getCnName($listArr[$keys]['productDepotCode']);
                $listArr[$keys]['enName']=$this->redis->hget("hash:product:".$values['id'],"enName");
                $listArr[$keys]['reason']=$this->getState($listArr[$keys]['id']);
                if($values['inventoryType'] == 1 && $values['inventoryNum'] == 0){
                    $listArr[$keys]['inventory'] = '有货';
                }elseif($values['inventoryType'] == 1 && $values['inventoryNum'] > 0 && $values['inventory'] == 1){
                    $listArr[$keys]['inventory'] = $values['inventoryNum'].$weightUnits[$values['weightUnit']];//.$arr['weightUnitsTip'];
                }elseif($values['inventoryType'] == 1 && $values['inventoryNum'] > 0 && $values['inventory'] == 0){
                    $listArr[$keys]['inventory'] = '有货';
                }elseif($values['inventoryType'] == 2){
                    $listArr[$keys]['inventory'] = '缺货';
                }
                
                unset($listArr[$keys]['inventoryType']);
                unset($listArr[$keys]['inventoryNum']);
                unset($listArr[$keys]['weightUnit']);
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

    /*获取商品状态*/
    public function getState($id){
        $o=array(
            '0'=>'审核不通过',
            '1'=>'有效',
            '2'=>'待审核',
            '3'=>'审核已撤消',
            '4'=>'商户下架',
            '5'=>'工作人员下架',
            '6'=>'系统下架'

        );
        if($id){
            $data=$this->redis->hgetAll("hash:product:{$id}");
            $opera=$this->redis->hgetAll("hash:product:operation:history:{$id}");
            ksort($opera);;
            $operas=end($opera);
            $operation= unserialize($operas);
            $Arr['addTime']=date('Y-m-d H:i:s',$operation['addTime']);
            $Arr['state']=$o[$data['state']];
            $Arr['reason']=isset($operation['reason'])?$operation['reason']:'';
        }
        return $Arr;
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
            return $title;
        }
    }

    /*获取品牌名*/
    public function getBrandName($id){
        $title = '';
        if($id){
            $title=$this->redis->hget("hash:brand:{$id}",'title');
        }
        return $title;
    }

    /*获取生产商*/
    public function getproducerName($id){
        $title = '';
        if($id){
            $title=$this->redis->hget("hash:producer:{$id}",'title');
        }
        return $title;
    }

    /*获取产地名称*/
    public function getplaceListName($id){
        $title = '';
        if($id){
            $title=$this->redis->hget("hash:area:{$id}",'title');
        }
        return $title;
    }
    /*获取产品详细图片*/
    public function getImg($id){
        $imgArr=array();
        if($id){
            $img=$this->redis->get("string:product:img:{$id}");
            $imgArr=unserialize($img);
        }
        return $imgArr;
    }

    /*获取详细描述*/
    public function getInfo($id){
        $info = '';
        if($id){
            $infoKeys=$this->redis->hkeys("hash:product:info:{$id}");
            $infoVals=$this->redis->hvals("hash:product:info:{$id}");
            $info=array_combine($infoKeys,$infoVals);
        }
        return $info;
    }

    /*获取详情页--详细信息*/
    public function detail($id){
        if($id){
            /*获取详情页*/
            $keys=$this->redis->hkeys("hash:product:{$id}");
            $vals=$this->redis->hvals("hash:product:{$id}");
            $arr=array_combine($keys,$vals);
            $arr['companyName']=$this->getCompanyName($arr['Uid']);//公司名称
            $arr['states']=$arr['state'];//状态
            $arr1=explode(',',$arr['categoryList']);
            foreach($arr1 as $k=>$v){
                $rest[$k]=$this->getCategoryName($arr1[$k]);
            }
            $rest=implode(',',$rest);
            $arr['categoryList']=$rest;//分类列表
            $arr['brandId']=$this->getBrandName($arr['brandId']);//品牌名称
            $arr['producerId']=$this->getproducerName($arr['producerId']);//生产商名称

            $arr2=explode(',',$arr['placeList']);
            foreach($arr2 as $k=>$v){
                $rest2[$k]=$this->getplaceListName($arr2[$k]);
            }
            $rest2=implode(',',$rest2);
            $arr['placeList']=$rest2;//产地

            $arr3=explode(',',$arr['seatList']);
            foreach($arr3 as $k=>$v){
                $rest3[$k]=$this->getplaceListName($arr3[$k]);
            }
            $rest3=implode(',',$rest3);
            $arr['seatList']=$rest3;//货物路径*/
            $arr['attribute']=unserialize($arr['attribute']);
            $arr['origin_state']=$arr['state'];

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
            $arr= $this->redis->hgetAll("hash:product:operation:history:{$id}");
        }
        ksort($arr);
        $arr1=array_merge($arr);
        return $arr1;
    }
    /*获取商品分类*/
    public function getCategory(){
        $tmpId=uniqid();
        $count=$this->redis->ZINTER("tmp:hash:category:list:{$tmpId}",'set:category:status:1','set:categoryChild:0');
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
                        $categoryArr[$num]['title']=$v;
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
            $count=$this->redis->ZINTER("tmp:hash:category:list:{$tmpId}",'set:area:status:1',"set:categoryChild:{$id}");
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
                            $categoryArr[$num]['title']=$v;
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
            $categoryArr=$this->redis->sMembers("set:product:category:{$id}");
        }
        return $categoryArr;
    }

    /*
     *
     * 判断是否为商品编号*/
    public function is_code($code){
        if($this->redis->exists("string:productCode:{$code}")){
            return true;
        }else{
            return false;
        }

    }

    //加入集合
    public function setMasterType($state,$id){
        $ret = '';
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

    /*
     * $id ID
     * $data  array
     * 修改单个用户status 下架*/
    public function changeOff($id,$data){
        $ret=false;
        if(empty($data['oid'])){
            return $ret;
        }
        if(empty($id)){
            return $ret;
        }
        /*修改--删除--新增--新增历史操作*/
        $this->redis->hmset("hash:product:{$id}",array('state'=>5));
        $this->redis->srem("set:product:state:1",$id);//删除状态
        $this->redis->SAdd("set:product:state:3",$id);//新增状态
        $this->redis->SADD("set:product:state:5",$id);

        //历史记录
        $num=$this->redis->incr('string:product:history');

        $ret=serialize($data);
        $res=$this->redis->hmset("hash:product:operation:history:".$id,array($num=>$ret));
        $oldData = D( 'Home/Product' )->detail( array( 'id' => $id ) );
        $this->redis->sadd( 'set:product:unread:'.intval( $oldData['Uid'] ).':3', $id );

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
        $re = $this -> checkCert($id['id']);
        if(!$re){
            return '2';
        }

        $ids=explode(',',$id['id']);
        /*修改--删除--新增--新增历史操作*/
        foreach($ids as $k=>$v){
            $this->redis->hmset("hash:product:{$v}",array('state'=>1));//审核通过
            $this->redis->srem("set:product:state:2",$v);//删除待审核状态
            $this->redis->SAdd("set:product:state:1",$v);//新增状态
            //历史记录
            $data=array(
                'id'=>$v,
                'oid'=>$uid,
                'opera'=>'approved',
                'addTime' => time(),
                'state'=>1,
                'otype'=>'admin',
            );
            $ret=serialize($data);
                    /*
              * 1,获取仓库id
              * 2商品上架,修改商品仓库的matterType 为商城在售，如已是抢购在售，改成商城/抢购在售。
              */
            $num=$this->redis->incr('string:product:history');
            $res=$this->redis->hmset("hash:product:operation:history:".$v,array($num=>$ret));
            $oldData = D( 'Home/Product' )->detail( array( 'id' => $id ) );
            $this->redis->srem( 'set:product:unread:'.intval( $oldData['Uid'] ).':3', $id );
            $this->redis->srem( 'set:product:unread:'.intval( $oldData['Uid'] ).':0', $id );
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
        $this->redis->hmset("hash:product:{$id}",array('state'=>0));//审核不通过
        $this->redis->srem("set:product:state:2",$id);//删除状态
        $this->redis->SAdd("set:product:state:0",$id);//新增状态
        //历史记录
        $num=$this->redis->incr('string:product:history');
        $rest=serialize($data);
        $res=$this->redis->hmset("hash:product:operation:history:".$id,array($num=>$rest));
        $oldData = D( 'Home/Product' )->detail( array( 'id' => $id ) );
        $this->redis->sadd( 'set:product:unread:'.intval( $oldData['Uid'] ).':0', $id );
        if($res){
            return true;
        }else{
            return false;
        }
    }

    //获取仓库id
    public function getProductCode($id){
        if($id){
           $productDepotCode= $this->redis->hget("hash:product:{$id}",'productDepotCode');
            $DepotId=$this->getDepotId($productDepotCode);
        }
        return $DepotId;
    }

    //获取仓库id
    public function getDepotId($productDepotCode){
        $DepotId = '';
        if($productDepotCode){
            $DepotId=$this->redis->get("string:productDepot:productCode:{$productDepotCode}");
        }
        return $DepotId;
    }

    //获取仓库状态
    public function getDepotState($id){
       if($id){
           $pid=$this->getProductCode($id);
           $state=$this->redis->hget("hash:productDepot:{$pid}",'state');
       }
        return $state;
    }

    //修改商品仓库在售状态
    public function setProductDepotMatter($id,$state){
        $res = '';
        if($id){
            $res=$this->redis->hset("hash:productDepot:{$id}",'matterType',$state);
        }
        return $res;
    }

    /*上架*/
    public function renewStatus($id,$data){
        $ret=false;
        if(empty($data['oid'])){
            return $ret;
        }
        if(empty($id)){
            return $ret;
        }
        $re = $this -> checkCert($id);
        if(!$re){
            return '2';
        }

        /*修改--删除--新增--新增历史操作*/
        $this->redis->hmset("hash:product:{$id}",array('state'=>1));//审核通过
        $this->redis->srem("set:product:state:5",$id);//删除状态
        $this->redis->srem("set:product:state:3",$id);//删除状态
        $this->redis->SAdd("set:product:state:1",$id);//新增状态
        /*
       * 1,获取仓库id
       * 2商品上架,修改商品仓库的matterType 为商城在售，如已是抢购在售，改成商城/抢购在售。
       */
        $matter=$this->getProductCode($id);
         $rest=$this->redis->hget("hash:productDepot:{$matter}",'matterType');
        if($rest==0){
            $this->setProductDepotMatter($matter,'1');
            $this->setMasterType('1',$matter);
        }elseif($rest==2){
            $this->setProductDepotMatter($matter,'3');
            $this->setMasterType('1',$matter);
            $this->setMasterType('3',$matter);
        }
        //历史记录
        $num=$this->redis->incr('string:product:history');

        $ret=serialize($data);
        $res=$this->redis->hmset("hash:product:operation:history:".$id,array($num=>$ret));
        $oldData = D( 'Home/Product' )->detail( array( 'id' => $id ) );
        $this->redis->srem( 'set:product:unread:'.intval( $oldData['Uid'] ).':0', $id );
        $this->redis->srem( 'set:product:unread:'.intval( $oldData['Uid'] ).':3', $id );

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
        $re = $this -> checkCert($id);
        if(!$re){
            return '2';
        }

        /*修改--删除--新增--新增历史操作*/
        $this->redis->hmset("hash:product:{$id}",array('state'=>1));//审核通过
        $this->redis->srem("set:product:state:0",$id);//删除状态
        $this->redis->SAdd("set:product:state:1",$id);//新增状态
        //历史记录
        $num=$this->redis->incr('string:product:history');

        $ret=serialize($data);
        /*
       * 1,获取仓库id
       * 2商品上架,修改商品仓库的matterType 为商城在售，如已是抢购在售，改成商城/抢购在售。
       */
        $matter=$this->getProductCode($id);
        $rest=$this->redis->hget("hash:productDepot:{$matter}",'matterType');
        if($rest==0){
            $this->setProductDepotMatter($matter,'1');
            $this->setMasterType('1',$matter);
        }elseif($rest==2){
            $this->setProductDepotMatter($matter,'3');
            $this->setMasterType('3',$matter);
        }
        $res=$this->redis->hmset("hash:product:operation:history:".$id,array($num=>$ret));
        $oldData = D( 'Home/Product' )->detail( array( 'id' => $id ) );
        $this->redis->srem( 'set:product:unread:'.intval( $oldData['Uid'] ).':0', $id );
        $this->redis->srem( 'set:product:unread:'.intval( $oldData['Uid'] ).':3', $id );
        if($res){
            return true;
        }else{
            return false;
        }
    }

    /*批量删除*/
    public function del($id,$uid){
        $ret=false;
        if(empty($id)){
            return $ret;
        }
        $ids=explode(',',$id['id']);
        /*删除--新增历史操作*/
        foreach($ids as $k=>$v){
            $this->redis->srem("set:product:status:1",$v);//删除状态
            $res= $this->redis->sadd("set:product:status:2",$v);//新增状态
        }
        if($res){
            return true;
        }else{
            return false;
        }
    }

    //检测用户的企业认证状态
    //$id   为商品id。
    public function checkCert($id){

        $ids=explode(',',$id);
        foreach ($ids as $key => $value) {
            $uid    = $this->redis->hGet("hash:product:{$value}",'Uid');           //获取商品对应的用户uid，用于检测用户的企业认证
            $state  = $this->redis->hGet("hash:member:info:{$uid}","state");      
            if($state !='1'){       //审核不通过，撤销的企业跳转
                return false;
            }
        }
        return true;
        
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
} 