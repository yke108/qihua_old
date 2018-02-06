<?php
namespace Home\Controller;
use Think\Controller;


class BrandController extends CommonController{

    /**
     * 品牌联动 API
     */
	public function brands(){
	    $ret = array();
        $model = D( 'Admin/Brand' );
        $data = $model->getBrand();
        $ret['code'] = '200';
        $ret['data'] = $data;
        $this->ajaxReturn( $ret );
	}

    /**
     * 添加品牌
     */
    public function add(){
        $ret = array(
            'code' => 200,
            'msg' => 'Success',
            'data' => NULL,
        );
        $model = D( 'Admin/Brand' );
        $rid=$model->getBrand();
        foreach($rid as $k=>$v){
            if($rid[$k]['text']=='Else' || $rid[$k]['text']=='OTHER'){
                $letter = $rid[$k]['id'];
            }
        }
        $brand = trim( I( 'brand' ) );

        if( empty( $letter ) ){
            $ret['code'] = 400;
            $ret['msg'] = '品牌分类不能为空';
            $this->ajaxReturn( $ret );
        }
        if( empty( $brand ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Brand is required';
            $this->ajaxReturn( $ret );
        }
        //后期添加同名限制
        $isExist = $model->checkBrandIsExist( $brand );
        if( $isExist ){
            $ret['code'] = 400;
            $ret['msg'] = 'Brand is exist';
            $this->ajaxReturn( $ret );
        }
        $data = array(
            'id' => $letter,
            'text' => $brand,
        );

        $result = $model->create( $data );
        if( !$result ){
            $ret['code'] = 400;
            $ret['msg'] = $model->getError();
            $this->ajaxReturn( $ret );
        }
        $result = $model->addBrand( $data );
        if( !$result ){
            $ret['code'] = 400;
            $ret['msg'] = 'Unknown error';
            $this->ajaxReturn( $ret );
        }
        $ret['data'] = D( 'Home/Brand' )->detail( array( 'id' => $result ) );
        $this->ajaxReturn( $ret );
    }

    /**
     * 自动生成品牌
     */
    public function autoCreateBrand(){
        $model = D( 'Admin/Brand' );
        $array = array(
            array(
                'text' => 'A',
            ),
            array(
                'text' => 'B',
            ),
            array(
                'text' => 'C',
            ),
            array(
                'text' => 'D',
            ),
            array(
                'text' => 'E',
            ),
            array(
                'text' => 'F',
            ),
            array(
                'text' => 'G',
            ),
            array(
                'text' => 'H',
            ),
            array(
                'text' => 'I',
            ),
            array(
                'text' => 'J',
            ),
            array(
                'text' => 'K',
            ),
            array(
                'text' => 'L',
            ),
            array(
                'text' => 'M',
            ),
            array(
                'text' => 'N',
            ),
            array(
                'text' => 'O',
            ),
            array(
                'text' => 'P',
            ),
            array(
                'text' => 'Q',
            ),
            array(
                'text' => 'R',
            ),
            array(
                'text' => 'S',
            ),
            array(
                'text' => 'T',
            ),
            array(
                'text' => 'U',
            ),
            array(
                'text' => 'V',
            ),
            array(
                'text' => 'W',
            ),
            array(
                'text' => 'X',
            ),
            array(
                'text' => 'Y',
            ),
            array(
                'text' => 'Z',
            ),
            array(
                'text' => 'Else',
            ),
        );
        foreach( $array as $v ){
            $id = $model->addBrand( array( 'text' => $v['text'] ) );
            if( !empty( $v['children'] ) ){
                foreach( $v['children'] as $v1 ){
                    $id1 = $model->addBrand( array( 'text' => $v1['text'], 'id' => $id ) );
                }
            }
        }

    }

    public function shortUrl() {
        echo 'shortURL';exit();
    }
}