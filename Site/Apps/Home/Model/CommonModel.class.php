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

class CommonModel extends Model{

    protected $redis;

	public function __construct(){
	    $this->autoCheckFields = false;
		$this->redis = \Think\Cache::getInstance('Redis');
	}

    /**
     * 从 REDIS SORT 出数据列表
     * @param string $cacheKey
     * @param array $param <pre> array(
    )
     * @return string
     */
    public function getListsByRedisSort( $cacheKey, $param ){
        $ret = array();
        $get = $param['get'];
        $newParam = $param;
        $data = array();
        foreach( $get as $k=>$v ){
            foreach( $v as $v1 ){
                $newGet = $k.'->'.$v1;
                $newParam['get'] = $newGet;
                $data[$v1] = $this->redis->sort( $cacheKey, $newParam );
            }
        }
        if( !empty( $data ) ){
            foreach( $data as $key => $value ){
                foreach( $value as $k=>$v ){
                    $ret[$k][$key] = $v;
                }
            }
        }
        return $ret;
    }
}