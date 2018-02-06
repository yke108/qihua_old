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

class ProductModel extends CommonModel{

	//开启自动验证
	protected $_validate = array(
        array( 'enName', 'require', 'Product Name is required' ),
        array( 'categoryList', 'require', 'category is required' ),
        //array( 'producerId', 'require', 'Manufacturer is required' ),
        //array( 'brandId', 'require', 'Brand is required' ),
        //array( 'placeList', 'require', 'Origin is required' ),
        //array( 'seatList', 'require', 'Product Location is required' ),
        //array( 'format', 'require', 'Purity is required' ),
        //array( 'character', 'require', 'Characters is required' ),
        //array( 'qualityGradeID', 'require', 'Quality Grade is required' ),
        //array( 'model', 'require', 'Item No/Model is required' ),
        //array( 'pack', 'require', 'Specification/Package is required' ),
        //array( 'cas', 'require', 'CAS NO is required' ),
        //array( 'summary', 'require', 'Introduction is required' ),
        //array( 'features', 'require', 'Property is required' ),
        //array( 'purpose', 'require', 'Application is required' ),
        //array( 'condition', 'require', 'Storage Condition is required' ),
        //array( 'emergency', 'require', 'Emergency Measures is required' ),
        array( 'images', 'checkImageCount', 'There should be at least 1 pictures', 0, 'callback' ),
		array( 'title', 'require', 'Sales Title is required' ),
        array( 'price', 'require', 'Reference Price is required' ),
        array( 'price', 'checkPrice', 'Reference Price format is not correct, only the input number!', 0, 'callback' ),
        array( 'weightUnit', 'require', 'Please select a weight unit!' ),
        //array( 'currency', 'require', 'Please choose a currency!' ),
        //array( 'moq', 'require', 'MOQ is required!' ),
        //array( 'moq', '/^[0-9]*[1-9][0-9]*$/', 'The order quantity format is not correct, allowing only input numbers and must be integers.!', 0, 'regex' ),
        //array( 'inventory', 'require', 'In Stock Quantity is required!' ),
        //array( 'inventory', '/^[0-9]*[1-9][0-9]*$/', 'In Stock quantity format is not correct, allowing only input numbers and must be integers!', 0, 'regex' ),
	);

	public function __construct(){
	    $this->autoCheckFields = false;
        $this->redis = \Think\Cache::getInstance('Redis');
	}

    /**
     * 检查商品图片张数
     * @param array $images
     * @return boolean
     */
    public function checkImageCount( $images ){
        $ret = FALSE;
        if( is_string( $images ) ){
            $images = explode( ',', $images );
        }
        $count = count( $images );
        if( $count >= 1 ){
            $ret = TRUE;
        }
        return $ret;
    }

    /**
     * 检查商城价格
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
     * 获取商品列表
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
        $param['state']     = intval( $param['state'] );
        if(!empty($param['uid']) ){
            $param['uid']       = intval( $param['uid'] );
        }
        $offset = ( $param['page'] - 1 ) * $param['page_size'];
        $limit = $param['page_size'];

        $tempCacheKey = $this->getTempListsCacheKey();
        $unionCacheKeys = array(

        );
        $unionCacheKeys[] = $this->getStatusCacheKey( 1 );
        $unionCacheKeys[] = $this->getStateCacheKey( $param['state'] );
        if( !empty( $param['revoke'] ) ){
            $unionCacheKeys[] = $this->getStateCacheKey( $param['revoke'] );
        }
        if( !empty( $param['uid'] ) ){
            $unionCacheKeys[] = $this->getMemberCacheKey( $param['uid'] );
        }
        if( !empty( $param['uids'] ) ){
            $newMemberCacheKey = array();
            foreach( $param['uids'] as $memberUid ){
                $newMemberCacheKey[] = $this->getMemberCacheKey( $memberUid );
            }
            $otherMemberTempCacheKey = $this->getTempListsCacheKey();
            $this->redis->zUnion( $otherMemberTempCacheKey, $newMemberCacheKey );
            $unionCacheKeys[] = $otherMemberTempCacheKey;
            $this->redis->expire( $otherMemberTempCacheKey, 60 );
        }
        if( !empty( $param['category'] ) ){
            $unionCacheKeys[] = $this->getCategoryCacheKey( $param['category'] );
        }
        if( !empty( $param['stock'] ) ){
            $unionCacheKeys[] = $this->getStockCacheKey( $param['stock'] );
        }

        if( !empty( $param['brandIds'] ) ){
            if( is_string( $param['brandIds'] ) ){
                $param['brandIds'] = explode( ',', $param['brandIds'] );
            }
            if( !empty( $param['brandIds'] ) ){
                $otherBrandTempCacheKey = $this->getTempListsCacheKey();
                foreach( $param['brandIds'] as $brandId ){
                    $unionBrand[] = $this->getBrandCacheKey( $brandId );
                }
                $this->redis->zunion( $otherBrandTempCacheKey, $unionBrand );
                $this->redis->expire( $otherBrandTempCacheKey, 60 );
                $unionCacheKeys[] = $otherBrandTempCacheKey;
            }
        }
        if( !empty( $param['countryIds'] ) ){
            if( is_string( $param['countryIds'] ) ){
                $param['countryIds'] = explode( ',', $param['countryIds'] );
            }
            if( !empty( $param['countryIds'] ) ){
                $otherCountryTempCacheKey = $this->getTempListsCacheKey();
                foreach( $param['countryIds'] as $seatId ){
                    $unionCountry[] = $this->getSeatCacheKey( $seatId );
                }
                $this->redis->zunion( $otherCountryTempCacheKey, $unionCountry );
                $this->redis->expire( $otherCountryTempCacheKey, 60 );
                $unionCacheKeys[] = $otherCountryTempCacheKey;
            }
        }
        if( !empty( $param['models'] ) ){
            if( is_string( $param['models'] ) ){
                $param['models'] = explode( ',', $param['models'] );
            }
            if( !empty( $param['models'] ) ){
                $otherModelTempCacheKey = $this->getTempListsCacheKey();
                foreach( $param['models'] as $modelId ){
                    $unionModel[] = $this->getModelCacheKey( $modelId );
                }
                $this->redis->zunion( $otherModelTempCacheKey, $unionModel );
                $this->redis->expire( $otherModelTempCacheKey, 60 );
                $unionCacheKeys[] = $otherModelTempCacheKey;
            }
        }
        if( !empty( $param['categoryIds'] ) ){
            if( is_string( $param['categoryIds'] ) ){
                $param['categoryIds'] = explode( ',', $param['categoryIds'] );
            }
            if( !empty( $param['categoryIds'] ) ){
                $otherCategoryTempCacheKey = $this->getTempListsCacheKey();
                foreach( $param['categoryIds'] as $newCategoryId ){
                    $unionCategory[] = $this->getCategoryCacheKey( $newCategoryId );
                }
                $this->redis->zunion( $otherCategoryTempCacheKey, $unionCategory );
                $this->redis->expire( $otherCategoryTempCacheKey, 60 );
                $unionCacheKeys[] = $otherCategoryTempCacheKey;
            }
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
        $by = empty( $param['by'] ) ? 'hash:product:*->id' : $param['by'];
        $keyword = isset($param['keyword'])?trim( $param['keyword'] ):'';
        if( !empty( $keyword ) ){
            $cacheKey = $this->getProductCodeCacheKey( $keyword );
            $matchId = $this->redis->get( $cacheKey );
            $cacheKey = $this->getProductCasCacheKey( $keyword );
            $casMatchId = $this->redis->smembers( $cacheKey );

            $otherTempCacheKey = $this->getTempListsCacheKey();
            if( !empty( $casMatchId ) ){
                foreach( $casMatchId as $theCasMatchId ){
                    $this->redis->sadd( $otherTempCacheKey, $theCasMatchId );
                }
            }
            if( !empty( $matchId ) ){
                $this->redis->sadd( $otherTempCacheKey, $matchId );
            }
            $shellModel = D( 'Home/Shell' );
            $titleIndexCacheKey = $this->getTitleIndexCacheKey();
            $searchCacheKey = $shellModel->search( $titleIndexCacheKey, strtolower( $keyword ), 'set' );
            $searchData = $this->redis->zrange( $searchCacheKey, 0, -1 );
            foreach( $searchData as $v ){
                $this->redis->sadd( $otherTempCacheKey, $v );
            }
            $unionCacheKeys[] = $otherTempCacheKey;
            $this->redis->expire( $otherTempCacheKey, 60 );
        }

        $result = $this->redis->zInter( $tempCacheKey, $unionCacheKeys );


        if( $result && $this->redis->expire( $tempCacheKey, 60 ) ){
            $array = array(
                'get' => array(
                    'hash:product:*' => array(
                        'id', 'productCode', 'title', 'price', 'weightUnit', 'currency', 'moq', 'inventory','inventoryType','inventoryNum', 'paymentMethod', 'logisticsMethod', 'addTime', 'updateTime', 'lastUpdateIp', 'sales', 'tradingCapacity', 'state', 'images', 'cas', 'categoryList', 'seatList','brandId', 'producerId','attribute'
                    )
                ),
                'limit' => array( $offset, $limit ),
                'sort' => $sort,
                'by' => $by,
            );
            $data = $this->getListsByRedisSort( $tempCacheKey, $array );
            if( !empty( $data ) ){

            }
            $count = $this->redis->zCard( $tempCacheKey );
            $ret = array(
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
        return 'tmp:set:product:list:'.uniqid();
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
        return 'hash:product:'.$id;
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
        $cacheKey = $this->getProductCodeCacheKey( $code );
        $ret = $this->redis->get( $cacheKey );
        return $ret;
    }

    /**
     * 获取自增id缓存 Cachekey
     * @return string
     */
    protected function getIncrementIdCacheKey(){
        return 'string:product';
    }

    /**
     * 获取商城商品状态值集合缓存 Cachekey
     * @param string $state STATE
     * @return string
     */
    public function getStateCacheKey( $state ){
        return 'set:product:state:'.$state;
    }

    /**
     * 获取商城商品状态值集合缓存 Cachekey
     * @param string $status STATUS
     * @return string
     */
    public function getStatusCacheKey( $status ){
        return 'set:product:status:'.$status;
    }

    /**
     * 获取商城商品分类集合缓存 Cachekey
     * @param string $category category
     * @return string
     */
    protected function getCategoryCacheKey( $category ){
        return 'set:product:category:'.$category;
    }

    /**
     * 获取商城商品品牌集合缓存 Cachekey
     * @param string $brand brand
     * @return string
     */
    protected function getBrandCacheKey( $brand ){
        return 'set:product:brand:'.$brand;
    }

    /**
     * 获取商城商品货物所在地集合缓存 Cachekey
     * @param string $seat seat
     * @return string
     */
    protected function getSeatCacheKey( $seat ){
        return 'set:product:seat:'.$seat;
    }

    /**
     * 获取会员和商品关联缓存 Cachekey
     * @param string $uid uid
     * @return string
     */
    public function getMemberCacheKey( $uid ){
        return 'set:product:member:'.$uid;
    }

    /**
     * 获取商品仓库和商品关联缓存 Cachekey
     * @param string $productDepotId id
     * @return string
     */
    public function getProductDepotCacheKey( $productDepotId ){
        return 'set:product:productDepot:'.$productDepotId;
    }

    /**
     * 获取商品库存状态缓存 Cachekey
     * @param string $stockStatus stockStatus
     * @return string
     */
    protected function getStockCacheKey( $stockStatus ){
        return 'set:product:stock:'.$stockStatus;
    }

    /**
     * 获取经营模式和商品绑定缓存 Cachekey
     * @param string $modelStatus modelStatus
     * @return string
     */
    protected function getModelCacheKey( $modelStatus ){
        return 'set:product:model:'.$modelStatus;
    }

    /**
     * 获取产品价格关联缓存 Cachekey
     * @return string
     */
    protected function getPriceCacheKey(){
        return 'zset:product:price';
    }

    /**
     * 获取商品编号关联商品ID缓存 Cachekey
     * @param string $productCode productCode
     * @return string
     */
    public function getProductCodeCacheKey( $productCode ){
        return 'string:productCode:'.$productCode;
    }

    /**
     * 获取商品编号关联商品ID缓存 Cachekey
     * @param string $cas cas
     * @return string
     */
    public function getProductCasCacheKey( $cas ){
        return 'set:product:cas:'.$cas;
    }

    /**
     * 获取操作历史自增id缓存 Cachekey
     * @return string
     */
    protected function getHistoryIncrementIdCacheKey(){
        return 'string:product:history';
    }

    /**
     * 获取操作历史缓存 Cachekey
     * @param string $id id
     * @return string
     */
    protected function getHistoryCacheKey( $id ){
        return 'hash:product:operation:history:'.$id;
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
            /*if( $data['inventory'] < $data['moq'] ){
                $this->error = '库存数量不能少于最低起订量';
                return $ret;
            }*/
            $time = time();
            $cacheKey = $this->getIncrementIdCacheKey();
            $id = $this->redis->incr( $cacheKey );//获取自增长id
            $productCode = 'SC'.date( 'Ymd' ).$id;
            $ip = get_client_ip();
            $states = $this->getProductState();
            $uid = $data['uid'];

            $images = $data['images'];
            $data['images'] = array();
            if( !empty( $images ) ){
                $images = explode( ',', $images );
                if( !empty( $images ) ){
                    foreach( $images as $v ){
                        $data['images'][] = $v;
                    }
                }
            }
            $attributeKey = array(
                'format', 'character', 'qualityGradeID', 'model', 'pack', 'smell', 'melting', 'boiling', 'flash', 'ph', 'density', 'solubility', 'susceptibility', 'psa', 'formula', 'exterior', 'msds', 'tds', 'coa', 'qualityGrade', 'summary', 'features', 'purpose', 'condition', 'emergency'
            );
            foreach( $attributeKey as $key ){
                $attribute[$key] = idx($data, $key, '');
            }

            $saveData = array(
                'id' => $id,
                'productCode' => $productCode,
                'title' => $data['title'],
                'price' => $data['price'],
                'weightUnit' => $data['weightUnit'],
                'currency' => $data['currency'],
                'moq' => $data['moq'],
                'inventory' => $data['inventory'],
                'paymentMethod' => 1,
                'logisticsMethod' => 1,
                'addTime' => $time,
                'updateTime' => $time,
                'lastUpdateIp' => $ip,
                'sales' => 0,
                'tradingCapacity' => 0,
                'state' => $states['REVIEWING']['value'],
                'Uid' => $data['uid'],
                'enName' => $data['enName'],
                'enAlias' => $data['enAlias'],
                'categoryList' => $data['categoryList'],
                'producerId' => $data['producerId'],
                'brandId' => $data['brandId'],
                'placeList' => $data['placeList'],
                'seatList' => $data['seatList'],
                'cas' => $data['cas'],
                'einecsNO' => $data['einecs_no'],
                'attribute' => serialize( $attribute ),
                'images' => serialize( $data['images'] ),
                'keyIndex' => idx($data, 'keyIndex', '') ,
                'inventoryType' => idx($data, 'inventoryType', 0),
                'inventory' => idx($data, 'inventory', 0),
                'inventoryNum' => idx($data, 'inventoryNum', 0),
            );

            $cacheKey = $this->getDetailCacheKey( $id );
            $this->redis->hmset( $cacheKey, $saveData );//商城商品

            $shellModel = D( 'Home/Shell' );
            $titleIndexCacheKey = $this->getTitleIndexCacheKey();
            $shellModel->index( $titleIndexCacheKey, strtolower( $data['title'] ), $id );//添加商品标题搜索索引

            $cacheKey = $this->getStateCacheKey( $states['REVIEWING']['value'] );
            $this->redis->sadd( $cacheKey, $id );//增加到待审核集合
            $cacheKey = $this->getStatusCacheKey( 1 );
            $this->redis->sadd( $cacheKey, $id );//增加到正常集合

            $categories = explode( ',', $data['categoryList'] );
            foreach( $categories as $category ){
                $cacheKey = $this->getCategoryCacheKey( $category );
                $this->redis->sadd( $cacheKey, $id );//增加到分类集合
            }
            if (!empty($data['brandId'])) {
                $cacheKey = $this->getBrandCacheKey( $data['brandId'] );
                $this->redis->sadd( $cacheKey, $id );//保存该品牌的所有商品
            }

            if (!empty($data['seatList'])) {
                $seats = explode( ',', $data['seatList'] );
                foreach( $seats as $seat ){
                    $cacheKey = $this->getSeatCacheKey( $seat );
                    $this->redis->sadd( $cacheKey, $id );//增加到货物所在地集合
                }
            }
            $cacheKey = $this->getMemberCacheKey( $uid );
            $this->redis->sadd( $cacheKey, $id );//会员和商品关联


            $isInsertIntoStockSet = FALSE;
            $stockStatus = $this->getProductStock();
            if( $data['inventory'] <= 0 ){
                $isInsertIntoStockSet = TRUE;
                $stockCacheKey = $this->getStockCacheKey( $stockStatus['OUT_OF_STOCK']['value'] );
            }elseif( $data['inventory'] < $data['moq'] ){
                $isInsertIntoStockSet = TRUE;
                $stockCacheKey = $this->getStockCacheKey( $stockStatus['LOW_STOCK']['value'] );
            }
            if( $isInsertIntoStockSet ){
                $this->redis->sadd( $stockCacheKey, $id );//库存状态
            }

            $memberInfo = D( 'Home/Member' )->getMemberInfo( array( 'id' => $uid ) );
            $cacheKey = $this->getModelCacheKey( $memberInfo['model'] );
            $this->redis->sadd( $cacheKey, $id );//经营模式和商品绑定,需要先查出用户的经营模式

            $cacheKey = $this->getPriceCacheKey();
            $this->redis->zadd( $cacheKey, $data['price'], $id );//产品价格关联
            $cacheKey = $this->getProductCodeCacheKey( $productCode );
            $this->redis->set( $cacheKey, $id );//商品编号关联商品ID

            //cas关联商品ID
            if (!empty($data['cas'])) {
                $cacheKey = $this->getProductCasCacheKey( $data['cas'] );
                $this->redis->sadd( $cacheKey, $id );//商品编号关联商品ID
            }

            $historyData = array(
                'id' => $id,
                'addTime' => $time,
                'opera' => 'Add Product',
                'oid' => $uid,
                'otype' => 'seller',
                'state' => $states['REVIEWING']['value'],
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
        if( empty( $data ) ){
            return $ret;
        }
        unset( $data['id'] );
        $valid = $this->create( $data );
        if( $valid ){
            if( isset($data['inventoryNum']) && $data['inventoryNum'] < $data['moq'] ){
                $this->error = 'The inventory quantity should not be less than MOQ.';
                return $ret;
            }
            $oldData = $this->detail( array( 'id' => $id ) );
            $time = time();
            $ip = get_client_ip();
            $uid = $data['uid'];

            $states = $this->getProductState();

            $images = $data['images'];
            $data['images'] = array();
            if( !empty( $images ) ){
                $images = explode( ',', $images );
                if( !empty( $images ) ){
                    foreach( $images as $v ){
                        $data['images'][] = $v;
                    }
                }
            }
            $attributeKey = array(
                'format', 'character', 'qualityGradeID', 'model', 'pack', 'smell', 'melting', 'boiling', 'flash', 'ph', 'density', 'solubility', 'susceptibility', 'psa', 'formula', 'exterior', 'msds', 'tds', 'coa', 'qualityGrade', 'summary', 'features', 'purpose', 'condition', 'emergency'
            );
            foreach( $attributeKey as $key ){
                $attribute[$key] = idx($data, $key);
            }
            $saveData = array(
                'title' => $data['title'],
                'price' => $data['price'],
                'weightUnit' => $data['weightUnit'],
                'currency' => $data['currency'],
                'moq' => $data['moq'],
                'inventory' => $data['inventory'],
                'updateTime' => $time,
                'lastUpdateIp' => $ip,
                'state' => $states['REVIEWING']['value'],
                'enName' => $data['enName'],
                'enAlias' => $data['enAlias'],
                'categoryList' => $data['categoryList'],
                'producerId' => $data['producerId'],
                'brandId' => $data['brandId'],
                'placeList' => $data['placeList'],
                'seatList' => $data['seatList'],
                'cas' => $data['cas'],
                'einecsNO' => $data['einecs_no'],
                'attribute' => serialize( $attribute ),
                'images' => serialize( $data['images'] ),
                'keyIndex' => idx($data, 'keyIndex', ''),
                'inventoryType' => idx($data, 'inventoryType', 0),
                'inventory' => idx($data, 'inventory', 0),
                'inventoryNum' => idx($data, 'inventoryNum', 0),
            );

            $cacheKey = $this->getDetailCacheKey( $id );
            $this->redis->hmset( $cacheKey, $saveData );//商城商品
            foreach( $states as $state ){
                $cacheKey = $this->getStateCacheKey( $state['value'] );
                $this->redis->srem( $cacheKey, $id );//删除待审核集合
            }
            $cacheKey = $this->getStateCacheKey( $states['REVIEWING']['value'] );
            $this->redis->sadd( $cacheKey, $id );//增加到待审核集合

            if( $oldData['categoryList'] != $data['categoryList'] ){
                $categories = explode( ',', $oldData['categoryList'] );
                foreach( $categories as $category ){
                    $cacheKey = $this->getCategoryCacheKey( $category );
                    $this->redis->srem( $cacheKey, $id );//删除到分类集合
                }
                $categories = explode( ',', $data['categoryList'] );
                foreach( $categories as $category ){
                    $cacheKey = $this->getCategoryCacheKey( $category );
                    $this->redis->sadd( $cacheKey, $id );//增加到分类集合
                }
            }
            if( $oldData['brandId'] != $data['brandId'] ){
                $cacheKey = $this->getBrandCacheKey( $oldData['brandId'] );
                $this->redis->srem( $cacheKey, $id );//删除该品牌的所有商品
                $cacheKey = $this->getBrandCacheKey( $data['brandId'] );
                $this->redis->sadd( $cacheKey, $id );//保存该品牌的所有商品
            }
            if( $oldData['seatList'] != $data['seatList'] ){
                $seats = explode( ',', $oldData['seatList'] );
                foreach( $seats as $seat ){
                    $cacheKey = $this->getSeatCacheKey( $seat );
                    $this->redis->srem( $cacheKey, $id );//删除到货物所在地集合
                }
                $seats = explode( ',', $data['seatList'] );
                foreach( $seats as $seat ){
                    $cacheKey = $this->getSeatCacheKey( $seat );
                    $this->redis->sadd( $cacheKey, $id );//增加到货物所在地集合
                }
            }

            //cas关联商品ID
            if( $oldData['cas'] != $data['cas'] ){
                $cacheKey = $this->getProductCasCacheKey( $oldData['cas'] );
                $this->redis->srem( $cacheKey, $id );//关联商品ID
                $cacheKey = $this->getProductCasCacheKey( $data['cas'] );
                $this->redis->sadd( $cacheKey, $id );//关联商品ID
            }


            $stockStatus = $this->getProductStock();
            foreach( $stockStatus as $v ){
                $stockCacheKey = $this->getStockCacheKey( $v['value'] );
                $this->redis->srem( $stockCacheKey, $id );//库存状态
            }
            if( $data['inventory'] <= 0 ){
                $stockCacheKey = $this->getStockCacheKey( $stockStatus['OUT_OF_STOCK']['value'] );
                $this->redis->sadd( $stockCacheKey, $id );//库存状态
            }elseif( $data['inventory'] < $data['moq'] ){
                $stockCacheKey = $this->getStockCacheKey( $stockStatus['LOW_STOCK']['value'] );
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
                'opera' => 'Modify Product',
                'oid' => $uid,
                'otype' => 'seller',
                'state' => $states['REVIEWING']['value'],
            );
            $this->insertProductHistory( $historyData );
            $ret = $id;
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

        $time = time();
        $states = $this->getProductState();
        foreach( $states as $state ){
            $cacheKey = $this->getStateCacheKey( $state['value'] );
            $this->redis->srem( $cacheKey, $id );//删除待审核集合
        }
        $state = intval( $data['state'] );
        $detailCacheKey = $this->getDetailCacheKey( $id );
        $this->redis->hmset( $detailCacheKey, array( 'state' => $state ) );//商城销售
        $cacheKey = $this->getStateCacheKey( $state );
        $this->redis->sadd( $cacheKey, $id );//添加到待审核集合

        $isAddUnreadCount = false;
        if( $state != $states['REVOKE']['value'] ){
            if( in_array( $state, array( $states['SELLER_REVOKE']['value'], $states['ADMIN_REVOKE']['value'], $states['SYSTEM_REVOKE']['value'] ) ) ){
                $cacheKey = $this->getStateCacheKey( $states['REVOKE']['value'] );
                $this->redis->sadd( $cacheKey, $id );
                $isAddUnreadCount = TRUE;
                $unreadState = $states['REVOKE']['value'];
            }
        }
        if( $state == $states['REFUSE']['value'] ){
            $isAddUnreadCount = TRUE;
            $unreadState = $states['REFUSE']['value'];
        }
        //增加未读数
        if( $isAddUnreadCount ){
            $cacheKey = $this->getUnreadProductCountCacheKey( array( 'uid' => $oldData['Uid'], 'state' => $unreadState ) );
            $this->redis->sadd( $cacheKey, $id );
        }else{
            $cacheKey = $this->getUnreadProductCountCacheKey( array( 'uid' => $oldData['Uid'], 'state' => $states['REVOKE']['value'] ) );
            $this->redis->srem( $cacheKey, $id );
            $cacheKey = $this->getUnreadProductCountCacheKey( array( 'uid' => $oldData['Uid'], 'state' => $states['REFUSE']['value'] ) );
            $this->redis->srem( $cacheKey, $id );
        }

        $historyData = array(
            'id' => $id,
            'addTime' => $time,
            'opera' => $oldDataParam['opera'],
            'oid' => $oldDataParam['oid'],
            'otype' => $oldDataParam['otype'],
            'reason' => isset($oldDataParam['reason'])?$oldDataParam['reason']:'',
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
        $historyOperate = $this->getHistoryOperate();
        if( !empty( $data ) ){
            foreach( $data as &$value ){
                $value = unserialize( $value );
                $value['operator'] = array();
                switch( $value['otype'] ){
                    case 'system':
                        $value['operator']['user_name'] = 'Webmaster';
                        break;
                    case 'seller':
                        $value['operator']['user_name'] = 'Merchant';
                        break;
                    default:
                        $value['operator']['user_name'] = 'Webmaster';
                        break;
                }
                $value['opera'] = isset($historyOperate[$value['opera']]) ? $historyOperate[$value['opera']] : $value['opera'];
            }
            ksort( $data );
            if( isset($param['is_not_ksort']) ){
                $data = array_reverse( $data );
            }
            $count = count( $data );
            if( isset( $param['page'] ) && isset( $param['page_size'] ) ){
                $offset = ( $param['page'] - 1 ) * $param['page_size'];
                $offset = $offset < 0 ? 0 : $offset;
                $data = array_slice( $data, $offset, $param['page_size'] );
            }
            $ret = array(
                'lists' => $data,
                'count' => $count,
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
     * 获取操作历史中英对应
     * @param array $param <pre> array(
    'id' => '', //页面
    )
     * @return array
     */
    public function getHistoryOperate(){
        $ret = array(
            '工作人员下架' => 'Staff down frame',
            '审核不通过' => 'Audit does not pass',
            '工作人员上架' => 'Staff on the shelves',
            '重审通过' => 'Through the review',
            '审核通过'=> 'Audit pass',
        );

        return $ret;
    }

    /**
     * 编辑商城销售某个字段
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
            if( $data['inventory'] >= 0){
                $valid = TRUE;
            }else{
                return $ret;
            }
        }
        if( isset( $data['weightUnit'] ) ){
            $data['weightUnit'] = intval( $data['weightUnit'] );
            $weightUnits = array_keys( C( 'WEIGHTUNIT' ) );
            if( in_array( $data['weightUnit'], $weightUnits ) ){
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
            $ret = $this->redis->hmset( $cacheKey, $data );
            if( $ret ){
                if( isset( $data['price'] ) ){
                    $cacheKey = $this->getPriceCacheKey();
                    $this->redis->zadd( $cacheKey, $data['price'], $id );//产品价格关联
                }
                if( isset( $data['moq'] ) || isset( $data['inventory'] ) ){
                    $stockStatus = $this->getProductStock();
                    foreach( $stockStatus as $v ){
                        $stockCacheKey = $this->getStockCacheKey( $v['value'] );
                        $this->redis->srem( $stockCacheKey, $id );//库存状态
                    }
                    $newData = $this->detail( array( 'id' => $id ) );
                    if( $newData['inventory'] <= 0 ){
                        $stockCacheKey = $this->getStockCacheKey( $stockStatus['OUT_OF_STOCK']['value'] );
                        $this->redis->sadd( $stockCacheKey, $id );//库存状态
                    }elseif( $newData['inventory'] < $newData['moq'] ){
                        $stockCacheKey = $this->getStockCacheKey( $stockStatus['LOW_STOCK']['value'] );
                        $this->redis->sadd( $stockCacheKey, $id );//库存状态
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * 通过商品标题搜索索引缓存 Cachekey
     * @return string
     */
    public function getTitleIndexCacheKey(){
        return 'product:title';
    }

    /**
     * 获取未读审核不通过或下架数 Cachekey
     * @param int $param //state
     * @return string
     */
    public function getUnreadProductCountCacheKey( $param ){
        return 'set:product:unread:'.intval( $param['uid'] ).':'.intval( $param['state'] );
    }

    /**
     * 获取未读审核不通过或下架数
     * @param array $param //state
     * @return int
     */
    public function getUnreadProductCount( $param ){
        $ret = 0;
        $cacheKey = $this->getUnreadProductCountCacheKey( $param );
        $ret = $this->redis->sCard( $cacheKey );
        return intval( $ret );
    }

    /**
     * 清除未读审核不通过或下架数
     * @param array $param //state
     * @return int
     */
    public function flushUnreadProductCount( $param ){
        $ret = false;
        $cacheKey = $this->getUnreadProductCountCacheKey( $param );
        $ret = $this->redis->del( $cacheKey );
        return $ret;
    }

    /**
     * 获取用户是否有权限进入商品列表页
     * @param array $param //uid
     * @return string
     */
    public function getIsUserAllowAccessProductList( $param ){
        $ret = false;
        $uid = intval( $param['uid'] );
        if( empty( $uid ) ){
            return $ret;
        }
        $data = D( 'Home/Member' )->detail( array( 'id' => $uid ) );
        $match = 'CN';
        if(!empty($data['country'])){
            if( strtoupper( $data['country'] ) == $match ){
                $ret = true;
            }
        }
        
        return $ret;
    }

    /**
     * 获取商品状态
     * @return string
     */
    public function getProductState(){
        $ret = array(
            'REFUSE' => array(
                'value' => 0,
                'enTitle' => 'Audit Disapproval',
                'cnTitle' => '审核不通过',
            ),
            'ACTIVE' => array(
                'value' => 1,
                'enTitle' => 'Valid',
                'cnTitle' => '有效',
            ),
            'REVIEWING' => array(
                'value' => 2,
                'enTitle' => 'Audit Pending',
                'cnTitle' => '待审核',
            ),
            'REVOKE' => array(
                'value' => 3,
                'enTitle' => 'Unshelve',
                'cnTitle' => '下架',
            ),
            'SELLER_REVOKE' => array(
                'value' => 4,
                'enTitle' => 'Supplier Unshelve',
                'cnTitle' => '商家下架',
            ),
            'ADMIN_REVOKE' => array(
                'value' => 5,
                'enTitle' => 'Staff Unshelve',
                'cnTitle' => '工作人员下架',
            ),
            'SYSTEM_REVOKE' => array(
                'value' => 6,
                'enTitle' => 'System Unshelve',
                'cnTitle' => '系统下架',
            ),
        );
        return $ret;
    }

    /**
     * 获取商品库存
     * @return string
     */
    public function getProductStock(){
        $ret = array(
            'OUT_OF_STOCK' => array(
                'value' => 1,
                'enTitle' => 'Out of stock',
                'cnTitle' => '库存不足',
            ),
            'LOW_STOCK' => array(
                'value' => 2,
                'enTitle' => 'Understock',
                'cnTitle' => '缺货',
            ),
        );
        return $ret;
    }

    /**
     * 获取商品价格单位
     * @return string
     */
    public function getProductCurrency(){
        $ret = array(
            '2' => array(
                'value' => 2,
                'character' => '$',
                'enTitle' => 'USD',
                'cnTitle' => '美元',
            ),
            /*  产品要求屏蔽 yilin:2017/01/09
                '1' => array(
                'value' => 1,
                'character' => '￥',
                'enTitle' => 'RMB',
                'cnTitle' => '人民币',
            ),*/
        );
        return $ret;
    }

    /**
     * 获取商品价格单位
     * @return string
     */
    public function getProductWeightUnit(){
        $ret = array(
            '1' => array(
                'value' => 1,
                'enTitle' => 'ton',
                'cnTitle' => '吨',
            ),
            '2' => array(
                'value' => 2,
                'enTitle' => 'Kg',
                'cnTitle' => 'Kg',
            ),
            '3' => array(
                'value' => 3,
                'enTitle' => 'ml',
                'cnTitle' => 'ml',
            ),
            '4' => array(
                'value' => 4,
                'enTitle' => 'L',
                'cnTitle' => 'L',
            ),
            '5' => array(
                'value' => 5,
                'enTitle' => 'm³',
                'cnTitle' => 'm³',
            ),
            '6' => array(
                'value' => 6,
                'enTitle' => 'g',
                'cnTitle' => 'g',
            ),
            '7' => array(
                'value' => 7,
                'enTitle' => 'mg',
                'cnTitle' => 'mg',
            ),
        );
        return $ret;
    }

    /**
     * 获取商品质量等级
     * @return string
     */
    public function getProductQualityGrade(){
        $ret = array(
            '1' => array(
                'value' => 1,
                'enTitle' => 'Industrial Grade',
                'cnTitle' => '工业级',
            ),
            '2' => array(
                'value' => 2,
                'enTitle' => 'Food Grade',
                'cnTitle' => '食品级',
            ),
            '3' => array(
                'value' => 3,
                'enTitle' => 'Medical Grade',
                'cnTitle' => '医药级',
            ),
            '4' => array(
                'value' => 4,
                'enTitle' => 'Other',
                'cnTitle' => '其它',
            ),
        );
        return $ret;
    }

    /**
     * 清除用户旧model的商品
     * @param int $uid 用户ID
     * @return string
     */
    public function removeOldModelProduct( $uid ){
        $ret = false;
        $uid = intval( $uid );
        if( empty( $uid ) ){
            return $ret;
        }
        $data = D( 'Home/Member' )->getMemberInfo( array( 'id' => $uid ) );
        if( empty( $data ) ){
            return $ret;
        }
        $cacheKey = $this->getModelCacheKey( $data['model'] );
        $productCacheKey = $this->getStatusCacheKey( 1 );
        $products = $this->redis->smembers( $productCacheKey );
        if( !empty( $products ) ){
            foreach( $products as $productId ){
                $this->redis->srem( $cacheKey, $productId );
            }
            $ret = true;
        }
        return $ret;
    }

    /**
     * 增加用户新model的商品
     * @param int $uid 用户ID
     * @return string
     */
    public function insertOldModelProduct( $uid ){
        $ret = false;
        $uid = intval( $uid );
        if( empty( $uid ) ){
            return $ret;
        }
        $data = D( 'Home/Member' )->getMemberInfo( array( 'id' => $uid ) );
        if( empty( $data ) ){
            return $ret;
        }
        $cacheKey = $this->getModelCacheKey( $data['model'] );
        $productCacheKey = $this->getStatusCacheKey( 1 );
        $products = $this->redis->smembers( $productCacheKey );
        if( !empty( $products ) ){
            foreach( $products as $productId ){
                $this->redis->sadd( $cacheKey, $productId );
            }
            $ret = true;
        }
        return $ret;
    }
}