<?php
// +----------------------------------------------------------------------
// | Keywa Inc.
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.keywa.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: vii
// +----------------------------------------------------------------------

namespace Common\Model;
use Think\Model;

/**
 * 短信类
 */
class SmsModel extends Model{
	
	public function __construct(){
        $this->autoCheckFields = false;
	}

    private $config = array(
        'url' => 'http://hprpt2.eucp.b2m.cn:8080/sdkproxy/sendsms.action',
        'key' => '8SDK-EMY-6699-RISOO',
        'password' => '105126',
        'sign' => '【奇化网】',
    );

	/**
	 * 发送短信
	 * @param array $param <pre> array(
	'phone' => '', //手机号码
	'content' => '', //短信内容
	)
	 * @return boolean
	 */
	public function send( $param ){
		$ret = false;
        if( empty( $param['phone'] ) ){
            $this->error = '手机号不能为空';
            return $ret;
        }
        if( is_array( $param['phone'] ) ){
            $param['phone'] = implode( ',', $param['phone'] );
        }
        if( empty( $param['content'] ) ){
            $this->error = '短信内容不能为空';
            return $ret;
        }

        $data = array(
            'cdkey' => $this->config['key'],
            'password' => $this->config['password'],
            'phone' => $param['phone'],
            'addserial' => '',
            'message' => $this->config['sign'].$param['content'],
        );
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $this->config['url'] );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
        $res = curl_exec( $ch );
        curl_close( $ch );

		if( $res != 0 ){
			$this->error = '发送失败';
		}else{
            $ret = true;
        }
		return $ret;
	}

}
