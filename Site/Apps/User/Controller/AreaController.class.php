<?php
namespace User\Controller;
use Think\Controller;


class AreaController extends CommonController{
    /**
     * 地区联动 API
     */
	public function areas(){
	    $ret = array();
        $model = D( 'Admin/Area' );
        $id = I( 'id' );
        if( !empty( $id ) ){
            $data = $model->getChildArea( $id );
        }else{
            $data = $model->getArea();
        }

        $ret['code'] = '200';
        $ret['data'] = $data;
        $this->ajaxReturn( $ret );
	}
}