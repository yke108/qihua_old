<?php
// +----------------------------------------------------------------------
// | Keywa Inc.
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.keywa.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: vii
// +----------------------------------------------------------------------
namespace Home\Model;
use Think\Cache\Driver\Redis;
use Think\Model;
use Think\Page;

class BrandModel extends CommonModel{

    protected $redis;

	public function __construct(){
	    $this->autoCheckFields = false;
		$this->redis = \Think\Cache::getInstance('Redis');
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
        return 'hash:brand:'.$id;
    }

    /**
     * 获取详情
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return array
     */
    public function getModelDetail( $param ){
        $ret 	= array();
        $id 	= intval( $param['id'] );
        if( empty( $id ) ){
            return $ret;
        }
        $cacheKey = $this->getModelDetailCacheKey( $id );
        $ret = $this->redis->hgetall( $cacheKey );
        return $ret;
    }

    /**
     * 获取详情缓存 Cachekey
     * @param int $id D
     * @return string
     */
    public function getModelDetailCacheKey( $id ){
        return 'hash:model:'.$id;
    }

    /**
     * 获取热门品牌列表
     * @param array $param <pre> array(
    'page' => '', //页面
    'page_size' => '', //页面个数
    )
     * @return array
     */
    public function getHotLists( $param ){
        $ret 	= array();
        $param['page']      = empty( $param['p'] ) ? 1 : intval( $param['p'] );
        $param['page_size'] = empty( $param['page_size'] ) ? C( 'DEFAULT_PAGE_SIZE' ) : intval( $param['page_size'] );
        $offset = ( $param['page'] - 1 ) * $param['page_size'];
        $limit = $param['page_size'];

        $tempCacheKey = $this->getTempListsCacheKey();
        $result = $this->redis->sDiffStore( $tempCacheKey, 'set:brand:status:1', 'set:brandChild:0' );
        if( $result && $this->redis->expire( $tempCacheKey, 60 ) ){
            $array = array(
                'get' => array(
                    'hash:brand:*' => array(
                        'id', 'title', 'productSales', 'parentId'
                    )
                ),
                'limit' => array( $offset, $limit ),
                'sort' => 'desc',
                'by' => 'hash:brand:*->productSales',
            );
            $ret = $this->getListsByRedisSort( $tempCacheKey, $array );
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
        return 'tmp:set:brand:list:'.uniqid();
    }
}