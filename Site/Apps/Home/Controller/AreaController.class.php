<?php
namespace Home\Controller;
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

    /**
     * 自动生成地区
     */
    public function autoCreateArea(){
        $model = D( 'Admin/Area' );
        $array = require_once(APP_PATH.'/Common/Conf/country.php');
        $array = $array['country'];
        foreach( $array as $k => $v ){
            $id = $model->areaAdd( array( 'text' => $v, 'short' => $k ) );
        }
        /*
        foreach( $array as $v ){
            $id = $model->areaAdd( array( 'text' => $v['text'] ) );
            if( !empty( $v['children'] ) ){
                foreach( $v['children'] as $v1 ){
                    $id1 = $model->areaAdd( array( 'text' => $v1['text'], 'id' => $id ) );
                    if( !empty( $v1['children'] ) ){
                        foreach( $v1['children'] as $v2 ){
                            $id2 = $model->areaAdd( array( 'text' => $v2['text'], 'id' => $id1 ) );
                        }
                    }
                }
            }
        }
        */

    }
}