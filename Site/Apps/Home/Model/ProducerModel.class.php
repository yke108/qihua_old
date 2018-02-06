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

class ProducerModel extends CommonModel{

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
    protected function getDetailCacheKey( $id ){
        return 'hash:producer:'.$id;
    }
}