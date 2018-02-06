<?php
namespace Home\Controller;
use Think\Controller;


class ImageController extends CommonController{

    /**
     * webUploader提交图片
     */
    public function webUploader() {
        header("Access-Control-Allow-Origin: *");
        if( $_SERVER['REQUEST_METHOD'] == 'OPTIONS' ) {
            exit;
        }
        $data = D( 'Common/Image' )->upload();
        if( empty( $data ) ){
            $ret['code'] = '400';
            $ret['msg'] = D( 'Common/Image' )->getUploadError();
            $ret['data'] = NULL;
        }else{
            $ret['code'] = '200';
            $ret['msg'] = '';
            $ret['data'] = $data;
        }

        $this->ajaxReturn( $ret );
    }
}