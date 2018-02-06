<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends CommonController {
    public function index() {

        $model = D( 'Home/Product' );
        $state = $model->getProductState();
        $currency = $model->getProductCurrency();
        $weightUnit = $model->getProductWeightUnit();
        $param = array(
            'state' => $state['ACTIVE']['value'],
            'page_size' => 4,
        );
        $hotProducts = $model->lists( $param );
        $hotProducts = empty($hotProducts['lists'])?array():$hotProducts['lists'];
        if( !empty( $hotProducts ) ){
            foreach( $hotProducts as &$v9 ){
                $v9['priceTip'] = (empty($currency[$v9['currency']]['character']) ? '' : $currency[$v9['currency']]['character']).sprintf( '%.2f', $v9['price'] );
                $v9['weightUnitTip'] = $weightUnit[$v9['weightUnit']]['enTitle'];
                $v9['images'] = unserialize( $v9['images'] );
                $v9['attribute'] = unserialize( $v9['attribute'] );
            }
        }
        $this->assign( 'products', $hotProducts );

        $categories = D( 'Home/Category' )->getCategory();
        $allowCategory = array( 'Daily Chemicals', 'Agrochemicals', 'Petrochemicals' );
        $newCategory = array();
        $param = array(
            'state' => $state['ACTIVE']['value'],
            'page_size' => 4,
        );

        foreach( $categories as $v ){
            if( !in_array( $v['text'], $allowCategory ) ){
                continue;
            }
            foreach( $v['children'] as $v1 ){
                if( !isset( $newCategory[$v['id']] ) ){
                    $newCategory[$v['id']] = $v;
                }else{
                    $param['category'] = $v['id'];
                    $products = $model->lists( $param );
                    if( !empty( $products['lists'] ) ){
                        foreach( $products['lists'] as &$v8 ){
                            $v8['priceTip'] = (empty($currency[$v8['currency']]['character']) ? '' : $currency[$v8['currency']]['character']).sprintf( '%.2f', $v8['price'] );
                            $v8['weightUnitTip'] = $weightUnit[$v8['weightUnit']]['enTitle'];
                            $v8['images'] = unserialize( $v8['images'] );
                            $v8['attribute'] = unserialize( $v8['attribute'] );
                        }
                    }
                    $v1['product'] = empty( $products['lists'] ) ? array() : $products['lists'];
                    $newCategory[$v['id']]['childrenCategory'][] = $v1;
                }
            }
        }

        $buyoffer=D('Buyoffer')->detailList();
        if(empty($buyoffer['list'])){
            $buyoffer['list']=array();
        }
        $this->assign('buyoffer',$buyoffer['list']);
        
        //合作伙伴
        $partners = D( 'Home/Partner' )->lists( array() );
        $this->assign( 'partners', empty($partners['lists'])?array():$partners['lists'] );

        $this->assign( 'newCategory', $newCategory );
        $this->assign( 'cate', getcategory() );
        $this->display();
    }

}