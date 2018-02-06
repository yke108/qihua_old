<?php
namespace Home\Controller;
use Think\Controller;


class CategoryController extends CommonController{

    /**
     * 分类联动 API
     */
	public function categories(){
	    $ret = array();
        $model = D( 'Admin/Category' );
        $data = $model->getCategory();
        $ret['code'] = '200';
        $ret['data'] = $data;
        $this->ajaxReturn( $ret );
	}

    /**
     * 自动生成分类
     */
    public function autoCreateCategory(){
        $model = D( 'Admin/Category' );
        $array = array(
            array(
                'text' => 'Category One',
                'children' => array(
                    array(
                        'text' => 'Category One-One',
                        'children' => array(
                            array(
                                'text' => 'Category One-One-One',
                            ),
                            array(
                                'text' => 'Category One-One-Two',
                            )
                        ),
                    ),
                    array(
                        'text' => 'Category One-Two',
                        'children' => array(
                            array(
                                'text' => 'Category One-Two-One',
                            ),
                            array(
                                'text' => 'Category One-Two-Two',
                            )
                        ),
                    ),
                ),
            ),
            array(
                'text' => 'Category Two',
                'children' => array(
                    array(
                        'text' => 'Category Two-One',
                        'children' => array(
                            array(
                                'text' => 'Category Two-One-One',
                            ),
                            array(
                                'text' => 'Category Two-One-Two',
                            )
                        ),
                    ),
                    array(
                        'text' => 'Category Two-Two',
                        'children' => array(
                            array(
                                'text' => 'Category Two-Two-One',
                            ),
                            array(
                                'text' => 'Category Two-Two-Two',
                            )
                        ),
                    ),
                ),
            ),
        );
        foreach( $array as $v ){
            $id = $model->addCategory( array( 'text' => $v['text'] ) );
            if( !empty( $v['children'] ) ){
                foreach( $v['children'] as $v1 ){
                    $id1 = $model->addCategory( array( 'text' => $v1['text'], 'id' => $id ) );
                    if( !empty( $v1['children'] ) ){
                        foreach( $v1['children'] as $v2 ){
                            $id2 = $model->addCategory( array( 'text' => $v2['text'], 'id' => $id1 ) );
                        }
                    }
                }
            }
        }

    }
}