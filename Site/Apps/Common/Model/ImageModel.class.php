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
 * 图片模型
 */
class ImageModel extends Model{

    private $upload;

	public function __construct(){
		$this->autoCheckFields = false;
	}

    private $config = array(
        'maxSize'       =>  5242880,
        'exts'          =>  array('jpg', 'gif', 'png', 'jpeg', 'docx', 'doc', 'pdf' ),
        'savePath'      =>  'User/',
        'subName'       =>  array('date','Ymd'),
        'saveName'      =>  array('uniqid',''),
    );

    /**
     * 上传图片
     * @param string $files
     * @param array $config
     * @return array
     */
    public function upload( $files = '', $config = array() ){
		// 实例化上传类
		$ret = array();
        $config = array_merge( $this->config, $config );
        $this->upload = $upload = new \Think\Upload( $config );
		$info = $upload->upload( $files );

		if( !empty( $info ) ){
			foreach( $info as $r ){
				$r['savepath'] 		= preg_replace( '/^\.\//', '', $r['savepath'] );
				$saveData = array(
					'url' => '/Uploads/'.$r['savepath'].$r['savename'],
					'format' => empty( $r['ext'] ) ? 'jpg' : $r['ext'],
					'savepath' => $r['savepath'],
					'savename' => $r['savename'],
					'size' => empty( $r['size'] ) ? 0 : $r['size'],
					'width' => empty($r['fileInfo'][0])?0:$r['fileInfo'][0],
					'height' => empty($r['fileInfo'][1])?0:$r['fileInfo'][1],
				);
				$ret[] = $saveData;
			}
		}
        return $ret;
    }

    /**
     * 获取错误
     * @return string
     */
    public function getUploadError(){
        return $this->upload->getError();
    }
}
