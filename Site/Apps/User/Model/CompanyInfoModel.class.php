<?php
namespace User\Model;
use      Think\Model;


class CompanyInfoModel extends Model{

    /*自动验证*/
    protected $_validate = array(
        /*企业信息*/
        array('companyName', 'require', '公司名称不能为空！'),
        array('trade','require','所在行业不能为空！'),
        //array('model','require','经营模式不能为空！'),
       // array('property','require','单位性质不能为空！'),
        //array('businessScope','require','经营范围不能为空！'),
        //array('employees','require','单位人数不能为空！'),
        array('turnover','require','年营业额不能为空！'),
        array('tel_a','require','区号不能为空！'),
        array('tel','require','电话号码不能为空！'),
        array('tel_a','checkTel_a','区号格式不正确',0,'callback'),
        array('tel','checkTel','电话号码格式不正确！',0,'callback'),
        array('fax_a','checkTel_a','区号格式不正确',0,'callback'),
        array('fax','checkTel','传真号码格式不正确！',0,'callback'),
        //array('companyIntroduction','require','公司简介不能为空！'),
        array('contact', 'require', '联系人不能为空！'),
        array('mobile','require','手机号码不能为空！'),
        array('mobile', '/^1[34578]\d{9}$/', '手机号码格式不正确', 0), // 正则表达式验证手机号码
        //array('email','require','个人邮箱不能为空！'),
        array('area_s','require','省份不能为空！'),
        array('area_c','require','城市不能为空！'),
        array('area_x','require','县/区不能为空！'),
        array('address','require','详细地址不能为空！'),

    );

      //检查固定电话区号
    public function checkTel_a($tel_a){
        if(empty($tel_a)){
            return true;
        }else{
            $ret=preg_match('/^\d{3,4}$/',$tel_a);
            return (bool)$ret;
        }
    }

    public function checkTel($tel){

        if(empty($tel)){
            return true;
        }else{
            $ret=preg_match('/^\d{7,8}$/',$tel);
            return (bool)$ret;
        }
    }

    Public function getList($type,$id='') {
        $redis = \Think\Cache::getInstance('Redis');
        $arr = array();
        if ($type == 'trade' && $id=='') {
            $sortParam = [
                'by' => 'hash:'.$type.':*->sorted',
                'get' => ['hash:'.$type.':*->id', 'hash:'.$type.':*->title'],
                'sort' => 'desc',
            ];
            $arr = $redis->sort("set:{$type}:status:1", $sortParam);
            $arr = array_chunk($arr, 2);
            foreach ($arr as $row) {
                $tmpArr[] = array_combine(['id', 'title'], $row);
            }
            $arr = $tmpArr;
        } else {
//            if($type == 'trade'){
                $sortParam = [
                    'by' => 'hash:'.$type.':*->sorted',
                    'get' => ['hash:'.$type.':*->id', 'hash:'.$type.':*->title','hash:'.$type.':*->sorted'],
                    'sort' => 'desc',
                ];
                $data = $redis->sort("set:{$type}:status:1", $sortParam);
//            }else{
//                $data = $redis->sMembers("set:{$type}:status:1");//查出所有的values
//            }
            if ($data) {
                $i = 1;
                $count = 3;
                foreach ($data as $k => $v) {
                    if( $i % $count == 1 ){
                        $arr[$v] = $redis->hGetAll("hash:{$type}:{$v}");//取出所有的hash values;
                        $arr[$v]['id'] = $v;
                    }
                    $i++;
                }
            }
        }
//        print_r($arr);
        return $arr;
    }

    //判断公司名称没有提交不能提交个人信息
    public function checkCompany($uid){
        $redis = \Think\Cache::getInstance('Redis');
        if($uid){
            $ret=$redis->hmget("hash:member:info:{$uid}",array('companyName','contact'));
        }
        return $ret;
    }

    /*获取字段*/
    public function getFiled($id,$filed){
        $redis = \Think\Cache::getInstance('Redis');
        $res=$redis->hget("hash:member:{$id}",$filed);
        return $res;
    }

    //邮箱是否已被绑定
    public function ISBindEmail($email){
        if($email){
            $redis = \Think\Cache::getInstance('Redis');
            $ret=$redis->exists("string:company:email:{$email}");
            return $ret;
        }

    }

    /*
     * $id Uid
     * $filed  string
     * 判断是否有该字段*/
    public function CheckField($id,$filed){
        $redis = \Think\Cache::getInstance('Redis');
       $res= $redis->hexists("hash:member:info:{$id}",$filed);
        return $res;
    }

    /*
     * 集合是否存在
     * */
    public function ExistsKeys($id){
        $redis = \Think\Cache::getInstance('Redis');
        if(!empty($id)){
            $ret=$redis->exists("hash:member:info:{$id}");
            return $ret;
        }
    }

    /*
     * $id Uid
     * $key  字段 string
     * 获取hash:member:info字段信息*/
    Public function getTitle($id,$key){
        $redis = \Think\Cache::getInstance('Redis');
        $ret= $redis->hGet("hash:member:info:{$id}","{$key}");
        if($ret){
            return true;
        }else{
            return false;
        }
    }

    Public function getState($id){
        $redis = \Think\Cache::getInstance('Redis');
        $ret= $redis->hGet("hash:member:info:{$id}",'state');
        return intval($ret);
       /* if($ret){
            return $ret;
        }else{
            return false;
        }*/
    }

    Public function getCert($id){
        $redis = \Think\Cache::getInstance('Redis');
        $ret= $redis->hGet("hash:member:info:{$id}",'cert');
        if($ret){
            return $ret;
        }else{
            return false;
        }
    }


    /*
     * $id Uid
     * $key  字段string
     * 获取hash:member:info字段信息*/
    Public function setEmail($id,$key){
        $redis = \Think\Cache::getInstance('Redis');
        $ret= $redis->hset("hash:member:info:{$id}",'email',$key);
       return $ret;
    }

    /*
  * $id Uid
  * $key  字段 string,获取单个字段
     * $key  array  获取多个字段
  * 获取hash:member:info字段信息*/
    Public function getTitles($id,$key){
        $redis = \Think\Cache::getInstance('Redis');
        if(is_array($key)){
            $ret= $redis->hmget("hash:member:{$id}",$key);
        }else{
            $ret= $redis->hGet("hash:member:{$id}","{$key}");
        }
       return $ret;
    }

    /*
     * $area_id  地区id
     * $str   字段  string
     * 取地区*/
    public function getArea($area_id,$str){
        $redis = \Think\Cache::getInstance('Redis');
        if($area_id){
            $area[$area_id]=$redis->hget("hash:area:{$area_id}",$str);
        }
        return $area;
    }

    /*
     * $id  用户id string；
     * 获取company的所有信息
     * */
    public function getCompanyInfo($id){
        $redis = \Think\Cache::getInstance('Redis');
        if($id){
            $keys=$redis->hkeys("hash:member:info:{$id}");
            $values=$redis->hvals("hash:member:info:{$id}");
            $info=array_combine($keys,$values);
        }
        return $info;
    }

       /*
        * 新增一个string集合
        * */
    public function createMemberCompany($id,$name){
        $redis = \Think\Cache::getInstance('Redis');
        if($id){
            $redis->set("string:member:{$name}",$id);
        }
    }

    public function createCert($id,$name){
        $redis = \Think\Cache::getInstance('Redis');
        if($id){
            $redis->srem("set:member:company:certType:1",$id);
            $redis->srem("set:member:company:certType:2",$id);
            $redis->Sadd("set:member:company:certType:{$name}",$id);
        }
    }

    //修改状态
    public function ModifyState($id,$state){
        $redis=new \Think\Cache\Driver\Redis();
        if($id){
            $arr= $redis->hset("hash:member:info:{$id}",'state',$state);
            $this->setCompanyState( $id, $state );
        }
        return $arr;
    }

    public function setCompanyState($id,$state){
        $redis=new \Think\Cache\Driver\Redis();
        if($id){
            $redis->srem("set:company:state:1",$id);
            $redis->srem("set:company:state:2",$id);
            $redis->srem("set:company:state:3",$id);
            $redis->srem("set:company:state:4",$id);
            $redis->srem("set:company:state:0",$id);
            $redis->SADD("set:company:state:{$state}",$id);
        }
    }

    /*
     * 删除一个状态，新增新状态
     * */
    public function ModifyStatus($id,$AddState,$RemState){
        $redis=new \Think\Cache\Driver\Redis();
        if($id){
            $redis->srem("set:company:state:1",$id);
            $redis->srem("set:company:state:2",$id);
            $redis->srem("set:company:state:3",$id);
            $redis->srem("set:company:state:0",$id);
            $redis->srem("set:company:state:4",$id);
            $res= $redis->SADD("set:company:state:{$AddState}",$id);
        }
        return $res;
    }

    /*判断是否存在*/
    public function is_setCompanyName($name){
        $redis = \Think\Cache::getInstance('Redis');
        $name=trim($name);
        $res=$redis->exists("string:company:".$name);
        return $res;
    }

    /*
     * $id Uid
     * $pram 要插入的字段 array
     * 公司信息插入*/
    public function insertCompanyInfo($id,$pram){
        $redis = \Think\Cache::getInstance('Redis');
        if($id){
            $result=$redis->hmset("hash:member:info:{$id}",$pram);
            if($result){
               return $result;
            }else{
                return false;
            }
        }
    }

    /*获取联系信息*/
    public function getContactInfo($id){
        $redis = \Think\Cache::getInstance('Redis');
        $arr=$this->getCompanyInfo($id);
        $mail=$redis->hGet('hash:member:'.$id,'email');
        if($arr){
            $other=unserialize($arr['other']);
            $other['contact']=$arr['contact'];
            /*获取手机号码*/
            $other['phone']=$this->getTitles($id,'phone');
            $other['contact']=$arr['contact'];
            $other['mail']=$mail;
        }
          return $other;
    }

    /*
     * $id //Uid
     * $pram 插入的字段  array
     * 插入联系信息*/
    public function insertContactInfo($id,$pram){
        $redis = \Think\Cache::getInstance('Redis');
        if($id){
            $result=$redis->hmset("hash:member:info:{$id}",$pram);
        }
        return $result;
    }

    /*
     * $id   Uid
     * $pram 字段 array
     * 字段修改*/
    public function insertMember($id,$pram){
        $redis = \Think\Cache::getInstance('Redis');
        if($id){
           $res=$redis->hmset("hash:member:{$id}",$pram);
        }
        return $res;

    }

    /*
     * $id   Uid
     * $type  地区id；
     * 创建地区.用户集合*/
    public function CreateMember($id,$type){
        $redis = \Think\Cache::getInstance('Redis');
        if($id){
            $area=$redis->sadd("set:area:member:{$type}",$id);
        }
        return $area;
    }

    /*
     * $id   Uid
     * $type  地区id；
     * 创建地区.用户集合*/
    public function removeMemberArea($id,$type){
        $redis = \Think\Cache::getInstance('Redis');
        if($id){
            $area=$redis->srem("set:area:member:{$type}",$id);
        }
        return $area;
    }

    /**
     * 获取公司使用过的分类
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return array
     */
    public function getUsedCategory( $param ){
        $ret 	= array();
        $uid 	= intval( $param['uid'] );
        if( empty( $uid ) ){
            return $ret;
        }
        $state = C( 'PRODUCT.STATE' );
        $categories = array();
        $otherParam = array(
            'page_size' => 1000,
            'state' => $state['ACTIVE'],
            'uid' => $uid,
        );
        $otherProducts = D( 'Home/Product' )->lists( $otherParam );
        if( !empty( $otherProducts['lists'] ) ){
            foreach( $otherProducts['lists'] as $otherProduct ){
                $depotId = D( 'Home/ProductDepot' )->getIdByCode( $otherProduct['productDepotCode'] );
                $depotData = D( 'Home/ProductDepot' )->detail( array( 'id' => $depotId ) );
                $categoryList = explode( ',', $depotData['categoryList'] );
                if( !empty( $categoryList ) ){
                    $categoryId0 = $categoryId1 = $categoryId2 = 0;
                    $i = 0;
                    foreach( $categoryList as $categoryId ){
                        if( $i == 0 ){
                            if( !isset( $categories[$categoryId] ) ){
                                $categories[$categoryId] = array(

                                );
                            }
                            $categoryId0 = $categoryId;
                        }elseif( $i == 1 ){
                            if( !isset( $categories[$categoryId0] ) ){
                                $categories[$categoryId0] = array(
                                    $categoryId => array(),
                                );
                            }else{
                                $categories[$categoryId0][$categoryId] = array();
                            }
                            $categoryId1 = $categoryId;
                        }elseif( $i == 2 ){
                            if( !isset( $categories[$categoryId0][$categoryId1] ) ){
                                $categories[$categoryId0][$categoryId1] = array(
                                    $categoryId => array(),
                                );
                            }else{
                                $categories[$categoryId0][$categoryId1][$categoryId] = array();
                            }
                        }
                        $i++;
                    }
                }
            }
        }
        $newCategories = array();
        if( !empty( $categories ) ){
            foreach( $categories as $categoryId1 => $value1 ){
                $category = D( 'Home/Category' )->detail( array( 'id' => $categoryId1 ) );
                $newCategory1 = array(
                    'id' => $category['id'],
                    'title' => $category['title'],
                );
                if( !empty( $value1 ) ){
                    $newCategory2 = array();
                    foreach( $value1 as $categoryId2 => $value2 ){
                        $category = D( 'Home/Category' )->detail( array( 'id' => $categoryId2 ) );
                        $newTempCategory2 = array(
                            'id' => $category['id'],
                            'title' => $category['title'],
                        );
                        if( !empty( $value2 ) ){
                            $newCategory3 = array();
                            foreach( $value2 as $categoryId3 => $value3 ){
                                $category = D( 'Home/Category' )->detail( array( 'id' => $categoryId3 ) );
                                $newCategory3[] = array(
                                    'id' => $category['id'],
                                    'title' => $category['title'],
                                );
                            }
                            $newTempCategory2['children'] = $newCategory3;
                        }
                        $newCategory2[] = $newTempCategory2;
                    }
                    $newCategory1['children'] = $newCategory2;
                }
                $newCategories[] = $newCategory1;
            }
        }
        return $newCategories;
    }

    /*
     * 获取字段信息  pram =array
     * */
   public function getCompany($uid,$pram){
        if(!empty($uid) && !empty($pram)){
            $redis = \Think\Cache::getInstance('Redis');
            $data=$redis->hmget("hash:member:info:{$uid}",$pram);
            return $data;
        }
   }

    /*
     * 删除key
     * */
    public function delCompany($name){
        if(!empty($name)){
            $name=trim($name);
            $redis = \Think\Cache::getInstance('Redis');
            if($redis->exists("string:company:{$name}")){
                $res=$redis->del("string:company:{$name}");
                return $res;
            }
            return true;
        }
    }

    /*
     * 获取UId下的所有的商品仓库id
     * $id=>Uid
     * */
    public function getProductDepotId( $id ){
        $arr=false;
           if(!empty($id)){
               $redis = \Think\Cache::getInstance( 'Redis' );
               $arr=$redis->sMembers( "set:productDepot:member:".$id );
           }
        return $arr;
    }
} 