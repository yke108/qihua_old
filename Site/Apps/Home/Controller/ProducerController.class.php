<?php
namespace Home\Controller;
use Think\Controller;


class ProducerController extends CommonController{

    /**
     * 生产商联动 API
     */
	public function producers(){
	    $ret = array();
        $model = D( 'Admin/Producer' );
        $data = $model->getProducer();

        $ret['code'] = '200';
        $ret['data'] = $data;
        $this->ajaxReturn( $ret );
	}

    /**
     * 添加生产商
     */
    public function add(){
        $ret = array(
            'code' => 200,
            'msg' => 'Success',
            'data' => NULL,
        );
        //循环拿到'其他id'
        $model = D( 'Admin/Producer' );
        $rid=$model->getProducer();
        foreach($rid as $k=>$v){
            if($rid[$k]['text']=='Else' || $rid[$k]['text']=='OTHER'){
                $letter = $rid[$k]['id'];
            }
        }
        $producer = trim( I( 'producer' ) );

        if( empty( $letter ) ){
            $ret['code'] = 400;
            $ret['msg'] = '生产商分类不能为空';
            $this->ajaxReturn( $ret );
        }
        if( empty( $producer ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Producer is required';
            $this->ajaxReturn( $ret );
        }
        //后期添加同名限制
        $isExist = $model->checkProducerIsExist( $producer );
        if( $isExist ){
            $ret['code'] = 400;
            $ret['msg'] = 'Producer is exist';
            $this->ajaxReturn( $ret );
        }

        $data = array(
            'id' => $letter,
            'text' => $producer,
        );
        $result = $model->create( $data );
        if( !$result ){
            $ret['code'] = 400;
            $ret['msg'] = $model->getError();
            $this->ajaxReturn( $ret );
        }
        $result = $model->addProducer( $data );
        if( !$result ){
            $ret['code'] = 400;
            $ret['msg'] = 'Unknown error';
            $this->ajaxReturn( $ret );
        }
        $ret['data'] = D( 'Home/Producer' )->detail( array( 'id' => $result ) );
        $this->ajaxReturn( $ret );
    }

    /**
     * 自动生成供应商
     */
    public function autoCreateProducer(){
        $model = D( 'Admin/Producer' );
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
            $id = $model->addProducer( array( 'text' => $v['text'] ) );
            if( !empty( $v['children'] ) ){
                foreach( $v['children'] as $v1 ){
                    $id1 = $model->addProducer( array( 'text' => $v1['text'], 'id' => $id ) );
                }
            }
        }

    }
}