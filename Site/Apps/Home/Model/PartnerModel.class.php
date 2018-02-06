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

class PartnerModel extends CommonModel{

	//开启自动验证
	protected $_validate = array(
        array( 'text', 'require', '标题不能为空' ),
        array( 'img', 'require', '图标不能为空' ),

	);

	public function __construct(){
	    $this->autoCheckFields = false;
        $this->redis = \Think\Cache::getInstance('Redis');
	}

    /**
     * 获取合作伙伴列表
     * @param array $param <pre> array(
    'page' => '', //页面
    'page_size' => '', //页面个数
    )
     * @return array
     */
    public function lists( $param ){
        $ret 	= array();

        $tempCacheKey = $this->getTempListsCacheKey();
        $unionCacheKeys = array(

        );
        $unionCacheKeys[] = $this->getStatusCacheKey( 1 );
        $sort = empty( $param['sort'] ) ? 'desc' : $param['sort'];
        $by = empty( $param['by'] ) ? 'hash:partner:*->id' : $param['by'];
        $result = $this->redis->zInter( $tempCacheKey, $unionCacheKeys );

        if( $result && $this->redis->expire( $tempCacheKey, 60 ) ){
            $array = array(
                'get' => array(
                    'hash:partner:*' => array(
                        'id', 'text', 'img', 'addTime'
                    )
                ),
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
        return 'tmp:set:partner:list:'.uniqid();
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
        return 'hash:partner:'.$id;
    }

    /**
     * 获取自增id缓存 Cachekey
     * @return string
     */
    protected function getIncrementIdCacheKey(){
        return 'string:partner';
    }

    /**
     * 获取合作伙伴状态值集合缓存 Cachekey
     * @param string $status STATUS
     * @return string
     */
    protected function getStatusCacheKey( $status ){
        return 'set:partner:status:'.$status;
    }

    /**
     * 新增合作伙伴
     * @param  array $data 数据array
     * @return array       详细数据
     */
    public function insert( $data ){
        $ret = false;

        $valid = $this->create( $data );
        if( $valid ){
            $time = time();
            $cacheKey = $this->getIncrementIdCacheKey();
            $id = $this->redis->incr( $cacheKey );//获取自增长id

            $status = 1;
            $saveData = array(
                'id' => $id,
                'text' => trim( $data['text'] ),
                'img' => $data['img'],
                'addTime' => $time,
                'status' => $status,
            );

            $cacheKey = $this->getDetailCacheKey( $id );
            $this->redis->hmset( $cacheKey, $saveData );

            $cacheKey = $this->getStatusCacheKey( $status );
            $this->redis->sadd( $cacheKey, $id );//增加到正常集合

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
            $saveData = array(

            );
            if( isset( $data['text'] ) ){
                $saveData['text'] = trim( $data['text'] );
            }
            if( isset( $data['img'] ) ){
                $saveData['img'] = trim( $data['img'] );
            }
            $cacheKey = $this->getDetailCacheKey( $id );
            $this->redis->hmset( $cacheKey, $saveData );

            $ret = $id;
        }
        return $ret;
    }

    /**
     * 删除商城商品
     * @param int $id //ID
     * @param array $data <pre> array(

    )
     * @return boolean
     */
    public function remove( $id ){
        $ret = false;
        $id = intval( $id );
        if( empty( $id ) ){
            return $ret;
        }

        $status = 0;
        $saveData = array(
            'status' => $status,
        );
        $cacheKey = $this->getDetailCacheKey( $id );
        $this->redis->hmset( $cacheKey, $saveData );

        $cacheKey = $this->getStatusCacheKey( $status );
        $this->redis->sadd( $cacheKey, $id );//增加到正常集合
        $cacheKey = $this->getStatusCacheKey( 1 );
        $ret = $this->redis->srem( $cacheKey, $id );
        return $ret;
    }
}