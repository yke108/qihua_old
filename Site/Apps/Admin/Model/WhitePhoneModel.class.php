<?php
/**
 * Created by PhpStorm.
 * User: 手机白名单
 * Date: 2016-11-29
 * Time: 9:35
 */

namespace Admin\Model;
use       Think\Model;

class WhitePhoneModel extends Model {
    //开启自动验证
    protected $_validate = array(
        array( 'phone', 'require', '手机号码不能为空!',1  ),
    );

    protected $redis;

    public function __construct(){
        $this->autoCheckFields = false;
        $this->redis = \Think\Cache::getInstance('Redis');
    }


  public function lists( $param ){
      $ret 	= array();
      $param['page']      = empty( $param['p'] ) ? 1 : intval( $param['p'] );
      $param['page_size'] = empty( $param['page_size'] ) ? C( 'DEFAULT_PAGE_SIZE' ) : intval( $param['page_size'] );
      $offset = ( $param['page'] - 1 ) * $param['page_size'];
      $limit = $param['page_size'];
      $count=$this->redis->SCARD("set:white:phone");

      $option = array(
          'limit' => array( $offset, $limit ),
      );
      $ret = $this->redis->sort( "set:white:phone",$option );
      $rest = array(
          'total' => $count ,
          'rows' => array(),
      );

      foreach( $ret as $k=>$v ){
          $rest['rows'][] = array( 'id' =>$k+1, 'phone' => $v );
      }
      return $rest;
  }

    public function addWhite( $pram ){
        $ret = false;
        if( empty( $pram ) ){
            return $ret;
        }
       $ret = $this->redis->SADD( "set:white:phone",$pram['phone'] );
        return $ret;
    }

    public function DelWhite( $pram ){
        $ret = false;
        if( empty( $pram ) ){
            return $ret;
        }
        $ret = $this->redis->SREM( "set:white:phone",$pram['phone'] );
        return $ret;
    }
} 