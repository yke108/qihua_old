<?php
// +----------------------------------------------------------------------
// | Keywa Inc.
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.keywa.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: vii
// +----------------------------------------------------------------------
namespace Home\Model;
use Think\Model;
use Think\Page;

class PurchaseModel extends Model{

	//开启自动验证
	protected $_validate = array(
        array( 'productDepotCode', 'require', '请选择商品仓库!' ),
		array( 'title', 'require', '请输入商城标题!' ),
        array( 'title', 'checkTitle', '商城标题最长50个字符!', 0, 'callback' ),
        array( 'price', 'require', '请输入参考价格!' ),
        array( 'price', 'checkPrice', '格式不正确，只允许输入数字!', 0, 'callback' ),
        array( 'weightUnit', 'require', '请选择重量单位!' ),
        array( 'currency', 'require', '请选择币种!' ),
        array( 'moq', 'require', '请输入最低起订量!' ),
        array( 'moq', '/^[0-9]*[1-9][0-9]*$/', '格式不正确，只允许输入数字且必须为整数!', 0, 'regex' ),
        array( 'inventory', 'require', '请输入库存!' ),
        array( 'inventory', '/^[0-9]*[1-9][0-9]*$/', '格式不正确，只允许输入数字且必须为整数!', 0, 'regex' ),
        array( 'paymentMethod', 'require', '请输入支付方式!' ),
        array( 'logisticsMethod', 'require', '请输入物流方式!' ),
        array( 'expire', array( 1, 3 , 5, 7, 10, 15, 20, 30 ), '请选择有效期!', 0, 'in' ),
	);

    protected $redis;

	public function __construct(){
	    $this->autoCheckFields = false;
		$this->redis = \Think\Cache::getInstance('Redis');
	}


    /**
     * 检查抢购标题
     * @param string $name
     * @return boolean
     */
	public function checkTitle( $name ){
	    $ret = TRUE;
	    $length = mb_strlen( $name, 'UTF-8' );
        if( $length > 50 ){
            $ret = FALSE;
        }
        return $ret;
    }

    /**
     * 检查抢购价格
     * @param string $price
     * @return boolean
     */
    public function checkPrice( $price ){
        $ret = FALSE;
        if( $price > 0 && preg_match( '/^\d+(?:\.\d{1,2})?$/i', $price ) ){
            $ret = TRUE;
        }
        return $ret;
    }

    /**
     * 获取抢购活动列表
     * @param array $param <pre> array(
    'page' => '', //页面
    'page_size' => '', //页面个数
    )
     * @return array
     */
    public function lists( $param ){
        $ret 	= array();
        $param['page']      = empty( $param['p'] ) ? 1 : intval( $param['p'] );
        $param['page_size'] = empty( $param['page_size'] ) ? C( 'DEFAULT_PAGE_SIZE' ) : intval( $param['page_size'] );
        $param['state']     = ( $param['state'] );
        $param['uid']       = intval( $param['uid'] );
        $param['category']  = intval( $param['category'] );
        $offset = ( $param['page'] - 1 ) * $param['page_size'];
        $limit = $param['page_size'];

        $tempCacheKey = $this->getTempListsCacheKey();
        $unionCacheKeys = array(

        );
        $unionCacheKeys[] = $this->getStatusCacheKey( 1 );

        if( !is_array( $param['state'] ) ){
            $unionCacheKeys[] = $this->getStateCacheKey( $param['state'] );
        }else{
            $theOtherTempCacheKey = $this->getTempListsCacheKey();
            foreach( $param['state'] as $state ){
                $newUnionCacheKeys[] = $this->getStateCacheKey( $state );
            }
            $this->redis->ZUNIONSTORE( $theOtherTempCacheKey, $newUnionCacheKeys );
            $this->redis->expire( $theOtherTempCacheKey, 60 );
            $unionCacheKeys[] = $theOtherTempCacheKey;
        }

        if( !empty( $param['revoke'] ) ){
            $unionCacheKeys[] = $this->getStateCacheKey( $param['revoke'] );
        }
        if( !empty( $param['uid'] ) ){
            $unionCacheKeys[] = $this->getMemberCacheKey( $param['uid'] );
        }
        if( !empty( $param['showSignCompanyOnly'] ) ){
            $signMemberCacheKey = 'set:member:sign:state:1';
            $signMembers = $this->redis->SMEMBERS( $signMemberCacheKey );
            $newMemberCacheKey = array();
            foreach( $signMembers as $memberUid ){
                $newMemberCacheKey[] = $this->getMemberCacheKey( $memberUid );
            }
            $otherSignMemberTempCacheKey = $this->getTempListsCacheKey();
            $this->redis->zUnion( $otherSignMemberTempCacheKey, $newMemberCacheKey );
            $unionCacheKeys[] = $otherSignMemberTempCacheKey;
            $this->redis->expire( $otherSignMemberTempCacheKey, 60 );

        }
        if( !empty( $param['category'] ) ){
            $unionCacheKeys[] = $this->getCategoryCacheKey( $param['category'] );
        }
        if( !empty( $param['stock'] ) ){
            $unionCacheKeys[] = $this->getStockCacheKey( $param['stock'] );
        }
        if( !empty( $param['brandId'] ) ){
            $unionCacheKeys[] = $this->getBrandCacheKey( $param['brandId'] );
        }
        if( !empty( $param['seatId'] ) ){
            $unionCacheKeys[] = $this->getSeatCacheKey( $param['seatId'] );
        }
        if( !empty( $param['model'] ) ){
            $unionCacheKeys[] = $this->getModelCacheKey( $param['model'] );
        }
        if( !empty( $param['minPrice'] ) ){
            $cacheKey = $this->getPriceCacheKey();
            $otherTempCacheKey = $this->getTempListsCacheKey();
            $minPriceData = $this->redis->zrangebyscore( $cacheKey, $param['minPrice'], 1000000000000000000 );
            foreach( $minPriceData as $v ){
                $this->redis->sadd( $otherTempCacheKey, $v );
            }
            $unionCacheKeys[] = $otherTempCacheKey;
            $this->redis->expire( $otherTempCacheKey, 60 );
        }
        if( !empty( $param['maxPrice'] ) ){
            $cacheKey = $this->getPriceCacheKey();
            $otherTempCacheKey = $this->getTempListsCacheKey();
            $maxPriceData = $this->redis->zrangebyscore( $cacheKey, 0, $param['maxPrice'] );
            foreach( $maxPriceData as $v ){
                $this->redis->sadd( $otherTempCacheKey, $v );
            }
            $unionCacheKeys[] = $otherTempCacheKey;
            $this->redis->expire( $otherTempCacheKey, 60 );
        }


        $sort = empty( $param['sort'] ) ? 'desc' : $param['sort'];
        $by = empty( $param['by'] ) ? 'hash:purchase:*->id' : $param['by'];
        $keyword = trim( $param['keyword'] );
        if( !empty( $keyword ) ){
            $cacheKey = $this->getPurchaseCodeCacheKey( $keyword );
            $matchId = $this->redis->get( $cacheKey );
            $otherTempCacheKey = $this->getTempListsCacheKey();
            if( !empty( $matchId ) ){
                $this->redis->sadd( $otherTempCacheKey, $matchId );
            }else{
                $shellModel = D( 'Home/Shell' );
                $titleIndexCacheKey = $this->getTitleIndexCacheKey();
                $searchCacheKey = $shellModel->search( $titleIndexCacheKey, $keyword, 'set' );
                $searchData = $this->redis->zrange( $searchCacheKey, 0, -1 );
                foreach( $searchData as $v ){
                    $this->redis->sadd( $otherTempCacheKey, $v );
                }
            }
            $unionCacheKeys[] = $otherTempCacheKey;
            $this->redis->expire( $otherTempCacheKey, 60 );
        }

        $result = $this->redis->zInter( $tempCacheKey, $unionCacheKeys );
        if( $result && $this->redis->expire( $tempCacheKey, 60 ) ){
            $array = array(
                'get' => array(
                    'hash:purchase:*' => array(
                        'id', 'purchaseCode', 'productDepotCode', 'title', 'price', 'weightUnit', 'currency', 'moq', 'inventory', 'paymentMethod', 'logisticsMethod', 'addTime', 'updateTime', 'lastUpdateIp', 'sales', 'tradingCapacity', 'state', 'verifyTime', 'Uid', 'expire','submitOrderTime','orderNumber'
                    )
                ),
                'limit' => array( $offset, $limit ),
                'sort' => $sort,
                'by' => $by,
            );
            $data = D( 'Home/ProductDepot' )->getListsByRedisSort( $tempCacheKey, $array );
            if( !empty( $data ) ){

            }
            $count = ( $this->redis->zCard( $tempCacheKey ) );
            $page = new Page( $count, $param['page_size'] );
            $ret = array(
                'page' => $page->show(),
                'count' => $count,
                'lists' => $data,
            );
        }
        return $ret;
    }

    /**
     * 获取临时列表缓存 Cachekey
     * @param array $param <pre> array(
    )
     * @return string
     */
    protected function getTempListsCacheKey(){
        return 'tmp:set:purchase:list:'.uniqid();
    }

    /**
     * 获取详情
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return array
     */
    public function detail( $param ){
        $ret 	= array();
        $id 	= intval( $param['id'] );
        if( empty( $id ) ){
            return $ret;
        }
        $cacheKey = $this->getDetailCacheKey( $id );
        $ret = $this->redis->hgetall( $cacheKey );
        return $ret;
    }

    /**
     * 获取详情缓存 Cachekey
     * @param int $id D
     * @return string
     */
    public function getDetailCacheKey( $id ){
        return 'hash:purchase:'.$id;
    }

    //修改审核时间字段 parm->array
    public function setFields($id,$pram){
        if(!empty($pram) && !empty($id)){
           $keys=$this->getDetailCacheKey($id);
            $rest=$this->redis->hmset($keys,$pram);
        }
        return $rest;
    }

    //添加状态
    public function setState($id,$state){
        if(!empty($id)){
          $keys=$this->getStateCacheKey($state);
            $rst=$this->redis->Sadd($keys,$id);
        }
        return $rst;
    }

    //删除状态
    public function delState($id,$state){
        if(!empty($id)){
            $keys=$this->getStateCacheKey($state);
            $rst=$this->redis->Srem($keys,$id);
        }
        return $rst;
    }
    /**
     * 通过code获取详情
     * @param array $param <pre> array(
    'code' => '', //CODE
    )
     * @return array
     */
    public function getDetailByCode( $param ){
        $ret 	= array();
        $code 	= $param['code'];
        if( empty( $code ) ){
            return $ret;
        }
        $id = $this->getIdByCode( $code );
        $ret = $this->detail( array( 'id' => $id ) );
        return $ret;
    }

    /**
     * 通过code获取详情
     * @param string $code
     * @return int
     */
    public function getIdByCode( $code ){
        $ret 	= 0;
        $cacheKey = $this->getPurchaseCodeCacheKey( $code );
        $ret = $this->redis->get( $cacheKey );
        return $ret;
    }


    /**
     * 获取自增id缓存 Cachekey
     * @return string
     */
    protected function getIncrementIdCacheKey(){
        return 'string:purchase';
    }

    /**
     * 获取抢购状态值集合缓存 Cachekey
     * @param string $state STATE
     * @return string
     */
    public function getStateCacheKey( $state ){
        return 'set:purchase:state:'.$state;
    }

    /**
     * 获取抢购状态值集合缓存 Cachekey
     * @param string $status STATUS
     * @return string
     */
    protected function getStatusCacheKey( $status ){
        return 'set:purchase:status:'.$status;
    }

    /**
     * 获取抢购分类集合缓存 Cachekey
     * @param string $category category
     * @return string
     */
    protected function getCategoryCacheKey( $category ){
        return 'set:purchase:category:'.$category;
    }

    /**
     * 获取抢购品牌集合缓存 Cachekey
     * @param string $brand brand
     * @return string
     */
    protected function getBrandCacheKey( $brand ){
        return 'set:purchase:brand:'.$brand;
    }

    /**
     * 获取抢购货物所在地集合缓存 Cachekey
     * @param string $seat seat
     * @return string
     */
    protected function getSeatCacheKey( $seat ){
        return 'set:purchase:seat:'.$seat;
    }

    /**
     * 获取会员和抢购关联缓存 Cachekey
     * @param string $uid uid
     * @return string
     */
    public function getMemberCacheKey( $uid ){
        return 'set:purchase:member:'.$uid;
    }

    /**
     * 获取商品仓库和抢购关联缓存 Cachekey
     * @param string $productDepotId id
     * @return string
     */
    public function getProductDepotCacheKey( $productDepotId ){
        return 'set:purchase:productDepot:'.$productDepotId;
    }

    /**
     * 获取抢购库存状态缓存 Cachekey
     * @param string $stockStatus stockStatus
     * @return string
     */
    protected function getStockCacheKey( $stockStatus ){
        return 'set:purchase:stock:'.$stockStatus;
    }

    /**
     * 获取经营模式和抢购绑定缓存 Cachekey
     * @param string $modelStatus modelStatus
     * @return string
     */
    protected function getModelCacheKey( $modelStatus ){
        return 'set:purchase:model:'.$modelStatus;
    }

    /**
     * 获取产品价格关联缓存 Cachekey
     * @return string
     */
    protected function getPriceCacheKey(){
        return 'zset:purchase:price';
    }

    /**
     * 获取抢购编号关联抢购ID缓存 Cachekey
     * @param string $purchaseCode purchaseCode
     * @return string
     */
    public function getPurchaseCodeCacheKey( $purchaseCode ){
        return 'string:purchaseCode:'.$purchaseCode;
    }

    /**
     * 获取操作历史自增id缓存 Cachekey
     * @return string
     */
    protected function getHistoryIncrementIdCacheKey(){
        return 'string:purchase:history';
    }

    /**
     * 获取操作历史缓存 Cachekey
     * @param string $id id
     * @return string
     */
    protected function getHistoryCacheKey( $id ){
        return 'hash:purchase:operation:history:'.$id;
    }

    /**
     * 新增商城商品
     * @param  array $data 数据array
     * @return array       详细数据
     */
    public function insert( $data ){
        $ret = false;
        $valid = $this->create( $data );
        if( $valid ){
            if( $data['inventory'] < $data['moq'] ){
                $this->error = '库存数量不能少于最低起订量';
                return $ret;
            }
            $time = time();
            $cacheKey = $this->getIncrementIdCacheKey();
            $id = $this->redis->incr( $cacheKey );//获取自增长id
            $productCode = 'QG'.date( 'Ymd' ).$id;
            $ip = get_client_ip();
            $uid = D( 'Home/Member' )->getLoginUid();
            $states = C( 'PRODUCT.STATE' );

            $data['id'] = $id;
            $data['purchaseCode'] = $productCode;
            $data['addTime'] = $time;
            $data['updateTime'] = $time;
            $data['lastUpdateIp'] = $ip;
            $data['sales'] = 0;
            $data['tradingCapacity'] = 0;
            $data['verifyTime'] = 0;
            $data['state'] = $states['REVIEWING'];
            $data['submitOrderTime'] = 0;
            $data['orderNumber'] = 0;
            $data['Uid'] = $uid;

            $cacheKey = $this->getDetailCacheKey( $id );
            $this->redis->hmset( $cacheKey, $data );//商城商品

            $shellModel = D( 'Home/Shell' );
            $titleIndexCacheKey = $this->getTitleIndexCacheKey();
            $shellModel->index( $titleIndexCacheKey, $data['title'], $id );//添加抢购标题搜索索引

            $cacheKey = $this->getStateCacheKey( $states['REVIEWING'] );
            $this->redis->sadd( $cacheKey, $id );//增加到待审核集合
            $cacheKey = $this->getStatusCacheKey( 1 );
            $this->redis->sadd( $cacheKey, $id );//增加到正常集合

            $productDepotId = D( 'Home/ProductDepot' )->getIdByCode( $data['productDepotCode'] );
            $productDepot = D( 'Home/ProductDepot' )->getDetailByCode( array( 'code' => $data['productDepotCode'] ) );
            $categories = explode( ',', $productDepot['categoryList'] );
            foreach( $categories as $category ){
                $cacheKey = $this->getCategoryCacheKey( $category );
                $this->redis->sadd( $cacheKey, $id );//增加到分类集合
            }
            $cacheKey = $this->getBrandCacheKey( $productDepot['brandId'] );

            $this->redis->sadd( $cacheKey, $id );//保存该品牌的所有商品
            $seats = explode( ',', $productDepot['seatList'] );
            foreach( $seats as $seat ){
                $cacheKey = $this->getSeatCacheKey( $seat );
                $this->redis->sadd( $cacheKey, $id );//增加到货物所在地集合
            }
            $cacheKey = $this->getMemberCacheKey( $uid );
            $this->redis->sadd( $cacheKey, $id );//会员和商品关联
            $cacheKey = $this->getProductDepotCacheKey( $productDepotId );
            $this->redis->sadd( $cacheKey, $id );//商品仓库和商品关联

            $isInsertIntoStockSet = FALSE;
            $stockStatus = C( 'PRODUCT.STOCK' );
            if( $data['inventory'] <= 0 ){
                $isInsertIntoStockSet = TRUE;
                $stockCacheKey = $this->getStockCacheKey( $stockStatus['OUT_OF_STOCK'] );
            }elseif( $data['inventory'] < $data['moq'] ){
                $isInsertIntoStockSet = TRUE;
                $stockCacheKey = $this->getStockCacheKey( $stockStatus['LOW_STOCK'] );
            }
            if( $isInsertIntoStockSet ){
                $this->redis->sadd( $stockCacheKey, $id );//库存状态
            }

            $memberInfo = D( 'Home/Member' )->getMemberInfo( array( 'id' => $uid ) );
            $cacheKey = $this->getModelCacheKey( $memberInfo['model'] );
            $this->redis->sadd( $cacheKey, $id );//经营模式和商品绑定,需要先查出用户的经营模式

            $cacheKey = $this->getPriceCacheKey();
            $this->redis->zadd( $cacheKey, $data['price'], $id );//产品价格关联
            $cacheKey = $this->getPurchaseCodeCacheKey( $productCode );
            $this->redis->set( $cacheKey, $id );//商品编号关联商品ID

            $historyData = array(
                'id' => $id,
                'addTime' => $time,
                'opera' => '发布抢购活动',
                'oid' => $uid,
                'otype' => 'seller',
                'state' => $states['REVIEWING'],
            );
            $this->insertProductHistory( $historyData );

            $ret = $id;
        }
        return $ret;
    }

    /**
     * 编辑商城商品
     * @param int $id //ID
     * @param array $data <pre> array(

    )
     * @return boolean
     */
    public function edit( $id, $data ){
        $ret = false;
        $id = intval( $id );
        if( empty( $id ) ){
            return $ret;
        }
        unset( $data['id'] );
        $valid = $this->create( $data );
        if( $valid ){
            if( $data['inventory'] < $data['moq'] ){
                $this->error = '库存数量不能少于最低起订量';
                return $ret;
            }
            $oldData = $this->detail( array( 'id' => $id ) );
            $time = time();
            $ip = get_client_ip();
            $uid = D( 'Home/Member' )->getLoginUid();

            $states = C( 'PRODUCT.STATE' );
            $saveData = array(
                'updateTime'    => $time,
                'lastUpdateIp'  => $ip,
                'state'         => $states['REVIEWING'],
            );
            $saveData = array_merge( $data, $saveData );
            $cacheKey = $this->getDetailCacheKey( $id );
            $this->redis->hmset( $cacheKey, $saveData );//商城商品
            foreach( $states as $state ){
                $cacheKey = $this->getStateCacheKey( $state );
                $this->redis->srem( $cacheKey, $id );//删除待审核集合
            }
            $cacheKey = $this->getStateCacheKey( $states['REVIEWING'] );
            $this->redis->sadd( $cacheKey, $id );//增加到待审核集合

            if( $oldData['productDepotCode'] != $data['productDepotCode'] ){
                $oldProductDepotId = D( 'Home/ProductDepot' )->getIdByCode( $oldData['productDepotCode'] );
                $newProductDepotId = D( 'Home/ProductDepot' )->getIdByCode( $data['productDepotCode'] );
                $oldProductDepot = D( 'Home/ProductDepot' )->getDetailByCode( array( 'code' => $oldData['productDepotCode'] ) );
                $newProductDepot = D( 'Home/ProductDepot' )->getDetailByCode( array( 'code' => $data['productDepotCode'] ) );

                $categories = explode( ',', $oldProductDepot['categoryList'] );
                foreach( $categories as $category ){
                    $cacheKey = $this->getCategoryCacheKey( $category );
                    $this->redis->srem( $cacheKey, $id );//删除到分类集合
                }
                $categories = explode( ',', $newProductDepot['categoryList'] );
                foreach( $categories as $category ){
                    $cacheKey = $this->getCategoryCacheKey( $category );
                    $this->redis->sadd( $cacheKey, $id );//增加到分类集合
                }
                $cacheKey = $this->getBrandCacheKey( $oldProductDepot['brandId'] );
                $this->redis->srem( $cacheKey, $id );//删除该品牌的所有商品
                $cacheKey = $this->getBrandCacheKey( $newProductDepot['brandId'] );
                $this->redis->sadd( $cacheKey, $id );//保存该品牌的所有商品

                $seats = explode( ',', $oldProductDepot['seatList'] );
                foreach( $seats as $seat ){
                    $cacheKey = $this->getSeatCacheKey( $seat );
                    $this->redis->srem( $cacheKey, $id );//删除到货物所在地集合
                }
                $seats = explode( ',', $newProductDepot['seatList'] );
                foreach( $seats as $seat ){
                    $cacheKey = $this->getSeatCacheKey( $seat );
                    $this->redis->sadd( $cacheKey, $id );//增加到货物所在地集合
                }
                $cacheKey = $this->getProductDepotCacheKey( $oldProductDepotId );
                $this->redis->srem( $cacheKey, $id );//删除商品仓库和商品关联
                $cacheKey = $this->getProductDepotCacheKey( $newProductDepotId );
                $this->redis->sadd( $cacheKey, $id );//商品仓库和商品关联
            }

            $stockStatus = C( 'PRODUCT.STOCK' );
            foreach( $stockStatus as $v ){
                $stockCacheKey = $this->getStockCacheKey( $v );
                $this->redis->srem( $stockCacheKey, $id );//库存状态
            }
            if( $data['inventory'] <= 0 ){
                $stockCacheKey = $this->getStockCacheKey( $stockStatus['OUT_OF_STOCK'] );
                $this->redis->sadd( $stockCacheKey, $id );//库存状态
            }elseif( $data['inventory'] < $data['moq'] ){
                $stockCacheKey = $this->getStockCacheKey( $stockStatus['LOW_STOCK'] );
                $this->redis->sadd( $stockCacheKey, $id );//库存状态
            }
            if( $data['price'] != $oldData['price'] ){
                $cacheKey = $this->getPriceCacheKey();
                $this->redis->zadd( $cacheKey, $data['price'], $id );//产品价格关联
            }

            $memberInfo = D( 'Home/Member' )->getMemberInfo( array( 'id' => $uid ) );
            $cacheKey = $this->getModelCacheKey( $memberInfo['model'] );
            $this->redis->sadd( $cacheKey, $id );//经营模式和商品绑定,需要先查出用户的经营模式

            $historyData = array(
                'id' => $id,
                'addTime' => $time,
                'opera' => '修改抢购活动提交审核',
                'oid' => $uid,
                'otype' => 'seller',
                'state' => $states['REVIEWING'],
            );
            $this->insertProductHistory( $historyData );

            $ret = true;
        }
        return $ret;
    }


    /**
     * 编辑商城商品状态
     * @param int $id //ID
     * @param array $data <pre> array(

    )
     * @return boolean
     */
    public function editState( $id, $data ){
        $ret = false;
        $id = intval( $id );
        if( empty( $id ) ){
            return $ret;
        }
        $oldData = $this->detail( array( 'id' => $id ) );
        if( empty( $oldData ) ){
            return $ret;
        }
        $oldDataParam = $data;

        $productDepot = D( 'Home/ProductDepot' )->getDetailByCode( array( 'code' => $oldData['productDepotCode'] ) );
        $time = time();
        $states = C( 'PRODUCT.STATE' );
        foreach( $states as $state ){
            $cacheKey = $this->getStateCacheKey( $state );
            $this->redis->srem( $cacheKey, $id );//删除待审核集合
        }
        $state = intval( $data['state'] );
        $detailCacheKey = $this->getDetailCacheKey( $id );
        $oldData = $this->redis->hgetall( $detailCacheKey );
        $this->redis->hmset( $detailCacheKey, array( 'state' => $state ) );//抢购
        if( $oldData['state'] == $states['REVIEWING'] && $state == $states['ACTIVE'] ){
            $this->redis->hmset( $detailCacheKey, array( 'verifyTime' => $time ) );//审核
        }
        $cacheKey = $this->getStateCacheKey( $state );
        $this->redis->sadd( $cacheKey, $id );//添加到待审核集合
        if( $state != $states['REVOKE'] ){
            if( in_array( $state, array( $states['SELLER_REVOKE'], $states['ADMIN_REVOKE'], $states['SYSTEM_REVOKE'] ) ) ){
                $cacheKey = $this->getStateCacheKey( $states['REVOKE'] );
                $this->redis->sadd( $cacheKey, $id );
            }
        }

        $productDepotMasterState = C( 'PRODUCT_DEPOT.MASTER_TYPE' );
        foreach( $productDepotMasterState as $v ){
            $cacheKey = D( 'Home/ProductDepot' )->getMasterTypeCacheKey( $v );
            $this->redis->srem( $cacheKey, $productDepot['id'] );
        }
        $cacheKey = D( 'Home/Product' )->getProductDepotCacheKey( $productDepot['id'] );
        $array = array(
            'get' => array(
                'hash:product:*' => array(
                    'id', 'state'
                )
            )
        );
        $data = D( 'Home/ProductDepot' )->getListsByRedisSort( $cacheKey, $array );
        $isProductSell = FALSE;
        foreach( $data as $v ){
            if( $v['state'] == $states['ACTIVE'] ){
                $isProductSell = TRUE;
                break;
            }
        }
        $cacheKey = $this->getProductDepotCacheKey( $productDepot['id'] );
        $array = array(
            'get' => array(
                'hash:purchase:*' => array(
                    'id', 'state'
                )
            )
        );
        $data = D( 'Home/ProductDepot' )->getListsByRedisSort( $cacheKey, $array );
        $isPurchaseSell = FALSE;
        foreach( $data as $v ){
            if( $v['state'] == $states['ACTIVE'] ){
                $isPurchaseSell = TRUE;
                break;
            }
        }
        $masterType = $productDepotMasterState['NONE'];
        if( $isProductSell && $isPurchaseSell ){
            $masterType = $productDepotMasterState['BATH_IN_PRODUCT_AND_PURCHASE'];
            $cacheKey = D( 'Home/ProductDepot' )->getMasterTypeCacheKey( $productDepotMasterState['ONLY_IN_PRODUCT'] );
            $this->redis->sadd( $cacheKey, $productDepot['id'] );
            $cacheKey = D( 'Home/ProductDepot' )->getMasterTypeCacheKey( $productDepotMasterState['ONLY_IN_PURCHASE'] );
            $this->redis->sadd( $cacheKey, $productDepot['id'] );
        }else{
            if( $isProductSell ){
                $masterType = $productDepotMasterState['ONLY_IN_PRODUCT'];
            }
            if( $isPurchaseSell ){
                $masterType = $productDepotMasterState['ONLY_IN_PURCHASE'];
            }
        }
        $cacheKey = D( 'Home/ProductDepot' )->getMasterTypeCacheKey( $masterType );
        $this->redis->sadd( $cacheKey, $productDepot['id'] );
        $detailCacheKey = D( 'Home/ProductDepot' )->getDetailCacheKey( $productDepot['id'] );
        $this->redis->hmset( $detailCacheKey, array( 'matterType' => $masterType ) );

        $historyData = array(
            'id' => $id,
            'addTime' => $time,
            'opera' => $oldDataParam['opera'],
            'oid' => $oldDataParam['oid'],
            'otype' => $oldDataParam['otype'],
            'reason' => $oldDataParam['reason'],
            'state' => $state,
        );
        $this->insertProductHistory( $historyData );

        $ret = TRUE;
        return $ret;
    }

    /**
     * 获取商城销售操作历史列表
     * @param array $param <pre> array(
    'id' => '', //页面
    )
     * @return array
     */
    public function getProductHistoryLists( $param ){
        $ret 	= array();
        $cacheKey = $this->getHistoryCacheKey( $param['id'] );
        $data = $this->redis->hgetall( $cacheKey );
        if( !empty( $data ) ){
            foreach( $data as &$value ){
                $value = unserialize( $value );
                $value['operator'] = array();
                switch( $value['otype'] ){
                    case 'system':
                        $value['operator']['user_name'] = '系统';
                        break;
                    case 'seller':
                        //$memberData = D( 'Home/Member' )->detail( array( 'id' => $value['uid'] ) );
                        $value['operator']['user_name'] = '商家';
                        break;
                    default:
                        $value['operator']['user_name'] = '网站管理员';
                        break;
                }
            }
            ksort( $data );
            $ret = array(
                'lists' => $data,
            );
        }
        return $ret;
    }

    /**
     * 获取商城销售操作历史列表
     * @param array $data <pre> array(
    )
     * @return boolean
     */
    public function insertProductHistory( $data ){
        $ret = false;
        $cacheKey = $this->getHistoryIncrementIdCacheKey();
        $historyId = $this->redis->incr( $cacheKey );//获取操作历史自增id
        if( empty( $data['time'] ) ){
            $data['time'] = time();
        }
        if( empty( $data['reason'] ) ){
            $data['reason'] = '';
        }
        $cacheKey = $this->getHistoryCacheKey( $data['id'] );
        $this->redis->hmset( $cacheKey, array( $historyId => serialize( $data ) ) );
        return $historyId;
    }

    /**
     * 获取商城销售最后一个操作历史
     * @param array $param <pre> array(
    'id' => '', //页面
    )
     * @return array
     */
    public function getLastProductHistory( $param ){
        $ret = array();
        $history = $this->getProductHistoryLists( array( 'id' => $param['id'] ) );
        if( !empty( $history['lists'] ) ){
            $ret = end( $history['lists'] );
        }
        return $ret;
    }

    /**
     * 编辑抢购活动某个字段
     * @param int $id //ID
     * @param array $data <pre> array(

    )
     * @return boolean
     */
    public function editField( $id, $data ){
        $ret = false;
        $id = intval( $id );
        if( empty( $id ) ){
            return $ret;
        }
        if( isset( $data['price'] ) ){
            $data['price'] = floatval( $data['price'] );
            if( $data['price'] > 0 && preg_match( '/^\d+(?:\.\d{1,2})?$/i', $data['price'] ) ){
                $valid = TRUE;
            }else{
                return $ret;
            }
        }
        if( isset( $data['moq'] ) ){
            $data['moq'] = intval( $data['moq'] );
            if( $data['moq'] > 0 ){
                $valid = TRUE;
            }else{
                return $ret;
            }
        }
        if( isset( $data['inventory'] ) ){
            $data['inventory'] = intval( $data['inventory'] );
            if( $data['inventory'] >= 0 ){
                $valid = TRUE;
            }else{
                return $ret;
            }
        }
        if( isset( $data['weightUnit'] ) ){
            $data['weightUnit'] = intval( $data['weightUnit'] );
            $weightUnits = array_keys( C( 'weightUnit' ) );
            if( in_array( $data['weightUnit'], $weightUnits ) ){
                $valid = TRUE;
            }else{
                return $ret;
            }
        }
        if( isset( $data['expire'] ) ){
            $data['expire'] = intval( $data['expire'] );
            $expires = array_keys( C( 'PRODUCT.EXPIRE' ) );
            if( in_array( $data['expire'], $expires ) ){
                $valid = TRUE;
            }else{
                return $ret;
            }
        }
        if( $valid ){
            $time = time();
            $ip = get_client_ip();
            $cacheKey = $this->getDetailCacheKey( $id );
            $data['updateTime'] = $time;
            $data['lastUpdateIp'] = $ip;
            if( isset( $data['expire'] ) ){
                $data['verifyTime'] = $time;
            }
            $ret = $this->redis->hmset( $cacheKey, $data );
            if( $ret ){
                if( isset( $data['price'] ) ){
                    $cacheKey = $this->getPriceCacheKey();
                    $this->redis->zadd( $cacheKey, $data['price'], $id );//产品价格关联
                }
                if( isset( $data['moq'] ) || isset( $data['inventory'] ) ){
                    $stockStatus = C( 'PRODUCT.STOCK' );
                    foreach( $stockStatus as $v ){
                        $stockCacheKey = $this->getStockCacheKey( $v );
                        $this->redis->srem( $stockCacheKey, $id );//库存状态
                    }
                    $newData = $this->detail( array( 'id' => $id ) );
                    if( $newData['inventory'] <= 0 ){
                        $stockCacheKey = $this->getStockCacheKey( $stockStatus['OUT_OF_STOCK'] );
                        $this->redis->sadd( $stockCacheKey, $id );//库存状态
                    }elseif( $newData['inventory'] < $newData['moq'] ){
                        $stockCacheKey = $this->getStockCacheKey( $stockStatus['LOW_STOCK'] );
                        $this->redis->sadd( $stockCacheKey, $id );//库存状态
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * 通过抢购标题搜索索引缓存 Cachekey
     * @return string
     */
    public function getTitleIndexCacheKey(){
        return 'purchase:title';
    }

    /**
     * 自动下架已过期抢购活动
     * @return string
     */
    public function autoCheckPurchaseExpireTime(){
        $ret = false;
        $states = C( 'PRODUCT.STATE' );
        $matchState = $states['ACTIVE'];
        $tempCacheKey = $this->getTempListsCacheKey();
        $unionCacheKeys = array(

        );
        $unionCacheKeys[] = $this->getStatusCacheKey( 1 );
        $unionCacheKeys[] = $this->getStateCacheKey( $matchState );
        $result = $this->redis->zInter( $tempCacheKey, $unionCacheKeys );
        if( $result && $this->redis->expire( $tempCacheKey, 60 ) ){
            $array = array(
                'get' => array(
                    'hash:purchase:*' => array(
                        'id', 'state', 'verifyTime', 'expire'
                    )
                ),
            );
            $time = time();
            $timeStamp = 24 * 3600;
            $data = D( 'Home/ProductDepot' )->getListsByRedisSort( $tempCacheKey, $array );
            if( !empty( $data ) ){
                $saveData = array(
                    'opera' => '抢购活动已被系统下架',
                    'reason' => '抢购活动已过期',
                    'oid' => 0,
                    'otype' => 'system',
                    'state' => $states['SYSTEM_REVOKE'],
                );
                foreach( $data as $v ){
                    if( $v['state'] == $matchState && ( $v['verifyTime'] + $v['expire'] * $timeStamp ) < $time ){
                        $saveData['id'] = $v['id'];
                        $this->editState( $v['id'], $saveData );
                    }
                }
            }
        }
        return $ret;
    }
}