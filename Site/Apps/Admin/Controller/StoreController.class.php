<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/9/8
 * Time: 15:07
 * 商品仓库
 */
namespace Admin\Controller;
use      Think\Controller;

class StoreController extends  CommonController{

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
    public function quash(){
        $this->display();
    }

    //获取详情页

    public function goodsDetails(){
        $store=D('Store');
        $id=I('get.id');
        $arr=$store->detail($id);
        $this->assign('arr',$arr);
        $this->display();
    }


    /*列表*/
    public function productDepotList(){
        $shell=D('Home/Shell');
        $store=D('Store');
        $param=I('post.');

        /*接收分页条件*/
        $page = I('post.page',1,'int');
        $rows=I('post.rows',20,'int');
        $offset=($page-1)*$rows;

        $status=isset($_POST['status'])?$_POST['status']:'1';
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
            $companyKey = $shell->search('member:companyName',$param['companyName'],'array');
            if( !empty( $companyKey ) ){
                $companyKey = explode( ',', $companyKey );
                $newCompanyProductDepotKey = array();
                foreach( $companyKey as $companyId ){
                    $newCompanyProductDepotKey[] = 'set:productDepot:member:'.$companyId;
                }
            }
            $keys[] =$keys[]=$store->getAllData($newCompanyProductDepotKey);
        }

        /*获取公司的集合*/
        if( !empty( $param['masterType'] ) ){
            $keys[] = D( 'Home/ProductDepot' )->getMasterTypeCacheKey( intval( $param['masterType'] ) );
        }

        /*判断是否为空*/
        //$param['keyword']='123456799';
        if(!empty($param['keyword'])){
            /*判断是否为CAS*/
            $ret=$store->is_cas($param['keyword']);
            if($ret){
                /*是cas号*/
                $keys[]=$store->getCasKeys($param['keyword']);
            }else{
                /*是商品名称*/
                $keys[]=$shell->search('productDepot:cnName',$param['keyword'],'set');
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
        $store=D('Store');
        if(!empty($id)){
           	$data=array_merge(array(array('id'=>'','text'=>'全部','attributes'=>array())),$store->getChildCategory($id));
        }else{
            $data=array_merge(array(array('id'=>'','text'=>'全部','attributes'=>array())),$store->getCategory());
        }
        $ret['code'] = '200';
        $ret['data'] = $data;
        $this->ajaxReturn( $ret );
    }

    /*
     * 修改用户状态--撤消通过*/
    public function changeStatus(){
        $data=I('post.');
        $store=D('Store');
        $data=array(
            'id'=>$data['id'],
            'oid'=>$_SESSION['userid'],
            'opera'=>'撤消通过',
            'addTime' => time(),
             'state'=>3,
              'otype'=>'admin',
               'reason'=>$data['reason']
        );

       $ret= $store->changeStatus($data['id'],$data);
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
        $store=D('Store');
        $uid=$_SESSION['userid'];
        $ret= $store->examStatus($id,$uid);
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
        $store=D('Store');
        $data=array(
            'id'=>$data['id'],
            'oid'=>$_SESSION['userid'],
            'opera'=>'审核不通过',
            'addTime' => time(),
            'state'=>2,
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

    /*恢复通过*/
    public function renewStatus(){
        $data=I('post.');
        $store=D('Store');
        $uid=$_SESSION['userid'];
        $data=array(
            'id'=>$data['id'],
            'oid'=>$uid,
            'opera'=>'恢复通过',
            'addTime' => time(),
            'state'=>1,
            'otype'=>'admin',
            'reason'=>$data['reason']
        );
        $ret= $store->renewStatus($data['id'],$data);
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

    /*重审通过*/
    public function rStatus(){
        $data=I('post.');
        $store=D('Store');
        $uid=$_SESSION['userid'];
        $data=array(
            'id'=>$data['id'],
            'oid'=>$uid,
            'opera'=>'重审通过',
            'addTime' => time(),
            'state'=>1,
            'otype'=>'admin',
            'reason'=>$data['reason']
        );
        $ret= $store->rStatus($data['id'],$data);
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
        $store=D('Store');
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

    //获取在售情况
    public function getMasterType(){
        $ret = array(
            'code' => 200,
            'msg' => '操作成功',
            'data' => '',
        );
        $masterTypes = C( 'PRODUCT_DEPOT.MASTER_TYPE' );
        $masterType = array(
            array( 'text' => '全部', 'id' => 0 ),
            array( 'text' => '商城', 'id' => $masterTypes['ONLY_IN_PRODUCT'] ),
            array( 'text' => '抢购', 'id' => $masterTypes['ONLY_IN_PURCHASE'] ),
        );
        $ret['data'] = $masterType;
        $this->ajaxReturn( $ret );
    }

    //商品仓库数据导出
    public function expStore(){
        $status=I('id');
        //$status=1;
        $state=array(
            '0'=>'待审核',
            '1'=>'有效',
            '2'=>'审核不通过',
            '3'=>'已撤销'
        );
        $quality=C('PRODUCT_DEPOT.QUALITY_GRADE');
        /*set:productDepot:status:1,set:productDepot:state:$status交集*/
        $user=D('User');
        $redis = \Think\Cache::getInstance('Redis');
        $tid=uniqid();
        $count=$redis->SINTERSTORE("set:tmp:store:{$tid}","set:productDepot:status:1","set:productDepot:state:{$status}");
        if($count && $redis->expire("set:tmp:store:{$tid}",60)){
            $stores_option=array(
                'get'=>array(
                    'hash:productDepot:*->id',
                    'hash:productDepot:*->productCode','hash:productDepot:*->categoryList',
                    'hash:productDepot:*->cnName', 'hash:productDepot:*->cnAlias','hash:productDepot:*->enName',
                    'hash:productDepot:*->enAlias','hash:productDepot:*->cas','hash:productDepot:*->brandId',
                    'hash:productDepot:*->placeList','hash:productDepot:*->seatList','hash:productDepot:*->producerId',
                    'hash:productDepot:*->state','hash:productDepot:*->addTime', 'hash:productDepot:*->updateTime','hash:productDepot:*->attribute',
                    'hash:productDepot:*->Uid'
                )
            );
            $storeArr=$redis->sort("set:tmp:store:{$tid}",$stores_option);
            if($storeArr){
                $num=0;
                $store=array();
                foreach($storeArr as $k=>$v){
                    if($k%17==0){
                        $store[$num]['id']=$v;
                    }elseif ($k%17==1){
                        $store[$num]['productCode']=$v;
                    }elseif($k%17==2){
                        $store[$num]['categoryList']=$v;
                    }elseif($k%17==3){
                        $store[$num]['cnName']=$v;
                    }elseif($k%17==4){
                        $store[$num]['cnAlias']=$v;
                    }elseif($k%17==5){
                        $store[$num]['enName']=$v;
                    }elseif($k%17==6){
                        $store[$num]['enAlias']=$v;
                    }elseif($k%17==7){
                        $store[$num]['cas']=$v;
                    }elseif($k%17==8){
                        $store[$num]['brandId']=$v;
                    }elseif($k%17==9){
                        $store[$num]['placeList']=$v;
                    }elseif($k%17==10){
                        $store[$num]['seatList']=$v;
                    }elseif($k%17==11){
                        $store[$num]['producerId']=$v;
                    }elseif($k%17==12){
                        $store[$num]['state']=$state[$v];
                    }elseif($k%17==13){
                        $store[$num]['addTime']=date('Y-m-d H:i:s',$v);
                    }elseif($k%17==14){
                        $store[$num]['updateTime']=date('Y-m-d H:i:s',$v);
                    }elseif($k%17==15){
                        $store[$num]['attribute']=$v;
                    }elseif($k%17==16){
                        $store[$num]['Uid']=$v;
                        $num++;
                    }
                }
                
                //根据条件导出
                if(!empty($_GET['filter'])){
                	$id=explode(',', I('filter'));
                }
                $arr=array();
                if(!empty($id)){
                	foreach ($store as $k){
                		if(in_array($k['id'], $id)){
                			$arr[]=$k;
                		}
                	}
                	$store=$arr;
                }
                
               // var_dump($id);exit;
                
                foreach($store as $k1=>$v1){
                     $category=explode(',',$v1['categoryList']);
                    foreach($category as $k2=>$v2){
                        $cate[$k2]=$redis->hget("hash:category:{$v2}",'title');
                        $store[$k1]['categoryList']=implode('-',$cate);//商品分类名
                    }

                    $store[$k1]['brandId']=$redis->hget("hash:brand:{$v1['brandId']}",'title');//品牌名
                    $store[$k1]['producerId']=$redis->hget("hash:producer:{$v1['producerId']}",'title');//生产商
                    $placeList=explode(',',$v1['placeList']);//产地
                    $seatList=explode(',',$v1['seatList']);//所在地
                    foreach($placeList as $k3=>$v3){
                        $place[$k3]=$redis->hget("hash:area:{$v3}",'title');
                        $store[$k1]['placeList']=implode('-',$place);
                    }

                    foreach($seatList as $k4=>$v4){
                         $seat[$k4]=$redis->hget("hash:area:{$v4}",'title');
                         $store[$k1]['seatList']=implode('-',$seat);
                    }
                    //其他属性
                    $attr=unserialize($v1['attribute']);
                    $store[$k1]['format']=$attr['format'];//纯度
                    $store[$k1]['qualityGradeID']=$quality[$attr['qualityGradeID']];//质量等级
                    $store[$k1]['model']=$attr['model'];//货号
                    $store[$k1]['msds']=empty($attr['msds'])?'无':'有';
                    $store[$k1]['tds']=empty($attr['tds'])?'无':'有';
                    $store[$k1]['coa']=empty($attr['coa'])?'无':'有';
                    $store[$k1]['companyName']=$redis->hget("hash:member:info:{$v1['Uid']}",'companyName');
                     if($status==1){
                         //审核人
                         $history=$redis->hgetAll("hash:productDepot:operation:history:{$v1['id']}");
                         ksort($history);
                         $hit=unserialize(end($history));
                         //审核人
                        $realname=$user->field('username,realname')->find($hit['oid']);
                         $store[$k1]['realname']=$realname['realname'];
                     }
                    }
                }
            }
       /* echo '<pre>';
        var_dump($store);*/

             $xlsName  = "商品仓库列表_{$state[$status]}";
             $xlsCell  = array(
                array('productCode','商品编号'),
                array('categoryList','商品分类'),
                array('cnName','商品中文名'),
                array('cnAlias','中文别名'),
                array('enName','商品英文名'),
                array('enAlias','英文别名'),
                array('cas','CAS号'),
                array('brandId','品牌名'),
                array('producerId','生产商'),
                array('placeList','产地'),
                 array('seatList','货物所在地'),
                array('format','纯度/规格'),
                 array('qualityGradeID','质量等级'),
                 array('model','货号/型号'),
                 array('msds','MSDS'),
                 array('tds','TDS'),
                 array('coa','CoA'),
                 array('companyName','公司名称'),
                 array('state','状态'),
                 array('addTime','创建时间'),
                 array('updateTime','最新修改时间'),
                 array('realname','审核人')

            );

            if($status==1){
                $nums=0;
                unset($xlsCell['19']);
                unset($xlsCell['20']);
                foreach($xlsCell as $k=>$v){
                    $xlsCell[$nums]=$v;
                    $nums++;
                }
            }else{
                unset($xlsCell['21']);
            }
            $xlsData = $store;//读取列表
            exportExcel($xlsName,$xlsCell,$xlsData);


    }
} 