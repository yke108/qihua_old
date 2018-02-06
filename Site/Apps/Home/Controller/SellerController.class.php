<?php
// +----------------------------------------------------------------------
// | Keywa Inc.
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.keywa.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: vii
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;
use Think\Page;

class SellerController extends CommonController{

    /**
     * 商品列表
     */
    public function products() {
        $model = D( 'Home/Product' );
        $param = I( 'get.' );
        $this->checkLogin();
        $param['uid'] = $this->uid;
        $param['page']      = empty( $param['p'] ) ? 1 : intval( $param['p'] );
        $param['page_size'] = empty( $param['page_size'] ) ? 10 : intval( $param['page_size'] );
        $productState = $model->getProductState();
        $param['state'] = !isset( $param['state'] ) ? $productState['ACTIVE']['value'] : intval( $param['state'] );

        //获取用户国藉
        $isUserAllowAccessProductList = $model->getIsUserAllowAccessProductList( array( 'uid' => $param['uid'] ) );
        if( !$isUserAllowAccessProductList ){
            $this->error( 'This feature allows only Chinese users', '/' );
        }
        $ret = $model->lists( $param );
        if( !empty( $ret['lists'] ) ){
            $products = array();
            foreach( $ret['lists'] as $v ){
                $v['images'] = unserialize( $v['images'] );
                $categoryIds = $v['categoryList'];
                $categoryParam = array( 'firstCategory', 'secondCategory', 'thirdCategory' );

                if( !empty( $categoryIds ) ){
                    $categoryIds = explode( ',', $categoryIds );
                    if( !empty( $categoryIds ) ){
                        $i = 0;
                        foreach( $categoryIds as $categoryId ){
                            $categoryData = D( 'Home/Category' )->detail( array( 'id' => $categoryId ) );
                            if( !empty( $categoryData['title'] ) ){
                                $v['selectedCategory'][] = $categoryData['title'];
                            }
                            $v['selectedCategoryParam'][$categoryParam[$i]] = intval( $categoryData['id'] );
                            $i++;
                        }
                    }
                }
                if( in_array( $v['state'], array( $productState['REFUSE']['value'], $productState['REVOKE']['value'], $productState['SELLER_REVOKE']['value'], $productState['ADMIN_REVOKE']['value'], $productState['SELLER_REVOKE']['value'], $productState['SYSTEM_REVOKE']['value'] ) ) ){
                    $v['lastHistory'] = $model->getLastProductHistory( array( 'id' => $v['id'] ) );
                }

                $products[] = $v;
            }
        }

        $currency = $model->getProductCurrency();
        $weightUnit = $model->getProductWeightUnit();
        $qualityGrade = $model->getProductQualityGrade();
        $states = $model->getProductState();

        if( in_array( $param['state'], array( $states['REFUSE']['value'] ) ) ){
            $model->flushUnreadProductCount( array( 'state' => $states['REFUSE']['value'], 'uid' => $this->uid ) );
        }
        $unreadRefuseCount = $model->getUnreadProductCount( array( 'state' => $states['REFUSE']['value'], 'uid' => $this->uid ) );
        if( in_array( $param['state'], array( $states['REVOKE']['value'] ) ) ){
            $model->flushUnreadProductCount( array( 'state' => $states['REVOKE']['value'], 'uid' => $this->uid ) );
        }
        $unreadRevokeCount = $model->getUnreadProductCount( array( 'state' => $states['REVOKE']['value'], 'uid' => $this->uid ) );


        foreach( $states as $state ){
            $newStates[$state['value']] = $state;
        }
        if(empty($ret['count'])){
            $ret['count'] = 0;
        }

        $pageHtml = new Page( $ret['count'], $param['page_size'] );
        $pageHtml->setConfig( 'prev', '<i class="icon-prev"></i>Previous Page' );
        $pageHtml->setConfig( 'next', 'Next Page<i class="icon-next"></i>' );

        //获取企业认证的状态
        $redis = \Think\Cache::getInstance('Redis');
        $cert_state=$redis->hGet("hash:member:info:".$param['uid'],"state");
        $cert_state = ($cert_state)?$cert_state:'0';
        if(empty($products)){
            $products = array();
        }
        //判断是否完成资料
        $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
        $this->assign( 'IsCompleteInfo', intval($IsCompleteInfo) );
        $this->assign( 'page_html', $pageHtml->show() );
        $this->assign( 'totalCount', $ret['count'] );
        $this->assign( 'lists', $products );
        $this->assign( 'page', $param['page'] );
        $this->assign( 'page_size', $param['page_size'] );
        $this->assign( 'currency', $currency );
        $this->assign( 'weightUnit', $weightUnit );
        $this->assign( 'qualityGrade', $qualityGrade );
        $this->assign( 'unreadRefuseCount', $unreadRefuseCount );
        $this->assign( 'unreadRevokeCount', $unreadRevokeCount );
        $this->assign( 'states', $newStates );
        $this->assign( 'cert_state', $cert_state );
        $this->display( 'member-store-valid' );
    }

    /**
     * 选择分类
     */
    public function selectCategory() {
        $this->checkCert();

        $model = D( 'Home/Product' );

        $uid = $this->uid;
        //判断是否登录
        $this->checkLogin();
        //获取用户国藉
        $isUserAllowAccessProductList = $model->getIsUserAllowAccessProductList( array( 'uid' => $uid ) );
        if( !$isUserAllowAccessProductList ){
            $this->error( 'This feature allows only Chinese users', '/' );
        }

        $categories = D( 'Home/Category' )->getCategory();
        $this->assign( 'categories', $categories );
        $this->display( 'member-addGood-brand' );
    }

    /**
     * 添加商品
     */
    public function addProduct() {
        $this->checkCert();

        $model = D( 'Home/Product' );
        //判断是否完成资料
        $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
        if(!$IsCompleteInfo){
            //选择分类
            $this->redirect( 'Seller/products' );
        }
        $uid = $this->uid;
        //判断是否登录
        $this->checkLogin();
        //获取用户国藉
        $isUserAllowAccessProductList = $model->getIsUserAllowAccessProductList( array( 'uid' => $uid ) );
        if( !$isUserAllowAccessProductList ){
            $this->error( 'This feature allows only Chinese users', '/' );
        }

        $firstCategory = intval( I( 'firstCategory' ) );
        $secondCategory = intval( I( 'secondCategory' ) );
        $thirdCategory = intval( I( 'thirdCategory' ) );
        if( empty( $firstCategory ) && empty( $secondCategory ) && empty( $thirdCategory ) ){
            //选择分类
            $this->redirect( 'Seller/selectCategory' );
        }
        $categoryModel = D( 'Home/Category' );
        $firstCategoryData = $categoryModel->detail( array( 'id' => $firstCategory ) );
        $secondCategoryData = $categoryModel->detail( array( 'id' => $secondCategory ) );
        $thirdCategoryData = $categoryModel->detail( array( 'id' => $thirdCategory ) );
        $selectedCategory = $selectedCategoryId = array();
        if( !empty( $firstCategoryData['title'] ) ){
            $selectedCategoryId[] = $firstCategoryData['id'];
            $selectedCategory[] = $firstCategoryData['title'];
        }
        if( !empty( $secondCategoryData['title'] ) ){
            $selectedCategoryId[] = $secondCategoryData['id'];
            $selectedCategory[] = $secondCategoryData['title'];
        }
        if( !empty( $thirdCategoryData['title'] ) ){
            $selectedCategoryId[] = $thirdCategoryData['id'];
            $selectedCategory[] = $thirdCategoryData['title'];
        }

        $countries = D( 'Admin/Area' )->getArea();

        $currency = $model->getProductCurrency();
        $weightUnit = $model->getProductWeightUnit();
        $qualityGrade = $model->getProductQualityGrade();

        $this->assign( 'firstCategory', $firstCategory );
        $this->assign( 'secondCategory', $secondCategory );
        $this->assign( 'thirdCategory', $thirdCategory );
        $this->assign( 'selectedCategory', $selectedCategory );
        $this->assign( 'selectedCategoryId', $selectedCategoryId );
        $this->assign( 'currency', $currency );
        $this->assign( 'weightUnit', $weightUnit );
        $this->assign( 'qualityGrade', $qualityGrade );
        $this->assign( 'countries', $countries );
        $this->display( 'member-addGoods-info' );
    }

    public function indicator() {
        if (IS_POST && IS_AJAX) {
            $params = array(
                'by'    => 'hash:forum:*->id',
//                'limit' => array(($page - 1) * $rows, $rows),
                'sort'  => 'desc',
                'get'   => array('hash:product:keyIndex:*->cid', 'hash:product:keyIndex:*->name'),
            );

            $lists = D('Home/Indicator')->search($params);
            $data['code'] = !empty($lists['rows']) ? 200 : 400;
            $data['data'] = !empty($lists['rows']) ? $lists['rows'] : array();
            $this->ajaxReturn($data);
        }
        exit();
    }


    /**
     * 添加商品
     */
    public function insertProduct() {
        $this->checkCert();

        $ret = array(
            'code' => 200,
            'msg' => 'Success',
            'data' => NULL,
        );
        $uid = $this->uid;
        if( empty( $uid ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Please Login';
            $this->ajaxReturn( $ret );
        }
        $model = D( 'Home/Product' );
        $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
        if(!$IsCompleteInfo){
            $ret['code'] = 400;
            $ret['msg'] = 'Basic information is not yet perfect';
            $this->ajaxReturn( $ret );
        }
        //获取用户国藉
        $isUserAllowAccessProductList = $model->getIsUserAllowAccessProductList( array( 'uid' => $uid ) );
        if( !$isUserAllowAccessProductList ){
            $ret['code'] = 400;
            $ret['msg'] = 'This feature allows only Chinese users';
            $this->ajaxReturn( $ret );
        }

        $data = I( 'post.' );
        $data['uid'] = $uid;

        //得到商品关键指标, 并检测传入的参数是否合理
        $indicatorModel = D('Home/Indicator');
        $keyIndexs = $indicatorModel->search(array('get' => array('hash:product:keyIndex:*->cid')));
        if (!empty($keyIndexs['rows'])) {
            $keyIndexKeys = array_column($keyIndexs['rows'], 'cid');
            foreach ($data as $dk => $dv) {
                if (in_array($dk, $keyIndexKeys, true)) {
                    $kis[$dk] = $dv;
                }
            }
            $data['keyIndex'] = !empty($kis) ? json_encode($kis) : '';
        } else {
            $data['keyIndex'] = trim( idx($data, 'keyIndex', '') );
        }

        $result = $model->insert( $data );
        if( !$result ){
            $ret['code'] = 400;
            $ret['msg'] = $model->getError();
        }else{
            $data = $model->detail( array( 'id' => $result ) );
            $data['url'] = U( 'Seller/insertProductSuccess' );
            $ret['data'] = $data;
        }
        $this->ajaxReturn( $ret );
    }

    /**
     * 添加商品成功
     */
    public function insertProductSuccess() {
        $this->display( 'member-addGood-succ' );
    }

    /**
     * 修改商品
     */
    public function editProduct() {
        $this -> checkCert();
        error_reporting(0);
        ini_set('display_errors', 0);

        $model = D( 'Home/Product' );
        $uid = $this->uid;
        $firstCategory = intval( I( 'firstCategory' ) );
        $secondCategory = intval( I( 'secondCategory' ) );
        $thirdCategory = intval( I( 'thirdCategory' ) );

        //判断是否登录
        $this->checkLogin();
        $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
        if(!$IsCompleteInfo){
            $this->redirect( 'Seller/products' );
        }
        //获取用户国藉
        $isUserAllowAccessProductList = $model->getIsUserAllowAccessProductList( array( 'uid' => $uid ) );
        if( !$isUserAllowAccessProductList ){
            $this->error( 'This feature allows only Chinese users', '/' );
        }

        $id = I( 'id' );
        $data = $model->detail( array( 'id' => $id ) );

        if( empty( $data ) ){
            $this->redirect( 'Seller/products' );
        }
        //非本用户发布的商品不允许修改
        if( $data['Uid'] != $uid ){
            $this->redirect( 'Seller/products' );
        }

        $newCategoryList = array();
        if( !empty( $firstCategory ) ){
            $newCategoryList[] = $firstCategory;
            if( !empty( $secondCategory ) ){
                $newCategoryList[] = $secondCategory;
                if( !empty( $thirdCategory ) ){
                    $newCategoryList[] = $thirdCategory;
                }
            }
        }
        $newCategoryList = implode( ',', $newCategoryList );
        $data['categoryList'] = empty( $newCategoryList ) ? $data['categoryList']: $newCategoryList;

        $categoryIds = $data['categoryList'];
        $data['selectedCategoryParam']['id'] = $id;
        if( !empty( $categoryIds ) ){
            $categoryIds = explode( ',', $categoryIds );
            if( !empty( $categoryIds ) ){
                $i = 0;
                $categoryParam = array( 'firstCategory', 'secondCategory', 'thirdCategory' );
                foreach( $categoryIds as $categoryId ){
                    $categoryData = D( 'Home/Category' )->detail( array( 'id' => $categoryId ) );
                    if( !empty( $categoryData['title'] ) ){
                        $data['selectedCategory'][] = $categoryData['title'];
                    }
                    $data['selectedCategoryParam'][$categoryParam[$i]] = intval( $categoryData['id'] );
                    $i++;
                }
            }
        }
        $producer = D( 'Home/Producer' )->detail( array( 'id' => $data['producerId'] ) );
        $data['producer'] = empty( $producer ) ? array() : $producer;
        $brand = D( 'Home/Brand' )->detail( array( 'id' => $data['brandId'] ) );
        $data['brand'] = empty( $brand ) ? array() : $brand;

        $countries = D( 'Admin/Area' )->getArea();

        $placeIds = $data['placeList'];
        if( !empty( $placeIds ) ){
            $placeIds = explode( ',', $placeIds );
            $data['placeListIds'] = $placeIds;
            if( !empty( $placeIds ) ){
                $i = 0;
                foreach( $placeIds as $placeId ){
                    $name  = 'placeList_'.$i;
                    $placeData = D( 'Admin/Area' )->getChildArea( $placeId );
                    $this->assign( $name, $placeData );
                    $i++;
                }
            }
        }
        $placeIds = $data['seatList'];
        if( !empty( $placeIds ) ){
            $placeIds = explode( ',', $placeIds );
            $data['seatListIds'] = $placeIds;
            if( !empty( $placeIds ) ){
                $i = 0;
                foreach( $placeIds as $placeId ){
                    $name  = 'seatList_'.$i;
                    $placeData = D( 'Admin/Area' )->getChildArea( $placeId );
                    $this->assign( $name, $placeData );
                    $i++;
                }
            }
        }
        $data['attribute'] = unserialize( $data['attribute'] );
        $data['images'] = unserialize( $data['images'] );
        $data['lastHistory'] = $model->getLastProductHistory( array( 'id' => $data['id'] ) );
        //得到商品关键指标, 并检测传入的参数是否合理
        $indicatorModel = D('Home/Indicator');
        $keyIndexs = $indicatorModel->search(array('get' => array('hash:product:keyIndex:*->cid', 'hash:product:keyIndex:*->name')));
        if (!empty($keyIndexs['rows'])) {
            foreach ($keyIndexs['rows'] as $row) {
                $keyIndexList[$row['cid']] = $row['name'];
            }
        }

        $currency = $model->getProductCurrency();
        $weightUnit = $model->getProductWeightUnit();
        $qualityGrade = $model->getProductQualityGrade();

        $this->assign('data', $data);
        $this->assign('keyIndexList', !empty($keyIndexList) ? $keyIndexList : array());
        $this->assign('currency', $currency);
        $this->assign('weightUnit', $weightUnit);
        $this->assign('qualityGrade', $qualityGrade);
        $this->assign('countries', $countries);
        $this->display('member-addGoods-infoModify');
    }

    /**
     * 修改商品
     */
    public function updateProduct() {
        $this->checkCert();
        
        $ret = array(
            'code' => 200,
            'msg' => 'Success',
            'data' => NULL,
        );
        $uid = $this->uid;
        if( empty( $uid ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Please Login';
            $this->ajaxReturn( $ret );
        }
        $model = D( 'Home/Product' );
        $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
        if(!$IsCompleteInfo){
            $ret['code'] = 400;
            $ret['msg'] = 'Basic information is not yet perfect';
            $this->ajaxReturn( $ret );
        }
        //获取用户国藉
        $isUserAllowAccessProductList = $model->getIsUserAllowAccessProductList( array( 'uid' => $uid ) );
        if( !$isUserAllowAccessProductList ){
            $ret['code'] = 400;
            $ret['msg'] = 'This feature allows only Chinese users';
            $this->ajaxReturn( $ret );
        }

        $data = I( 'post.' );
        $id = $data['id'];
        $oldData = $model->detail( array( 'id' => $id ) );
        if( empty( $oldData ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Access Permission';
            $this->ajaxReturn( $ret );
        }
        //非本用户发布的商品不允许修改
        if( $oldData['Uid'] != $uid ){
            $ret['code'] = 400;
            $ret['msg'] = 'Access Permission';
            $this->ajaxReturn( $ret );
        }
        $data['uid'] = $uid;

        //得到商品关键指标, 并检测传入的参数是否合理
        $indicatorModel = D('Home/Indicator');
        $keyIndexs = $indicatorModel->search(array('get' => array('hash:product:keyIndex:*->cid')));
        if (!empty($keyIndexs['rows'])) {
            $keyIndexKeys = array_column($keyIndexs['rows'], 'cid');
            foreach ($data as $dk => $dv) {
                if (in_array($dk, $keyIndexKeys, true)) {
                    $kis[$dk] = $dv;
                }
            }
            $data['keyIndex'] = !empty($kis) ? json_encode($kis) : '';
        } else {
            $data['keyIndex'] = trim( idx($data, 'keyIndex', '') );
        }

        $result = $model->edit( $id, $data );
        if( !$result ){
            $ret['code'] = 400;
            $ret['msg'] = $model->getError();
        }else{
            $data = $model->detail( array( 'id' => $result ) );
            $data['url'] = U( 'Seller/updateProductSuccess' );
            $ret['data'] = $data;
        }
        $this->ajaxReturn( $ret );
    }

    /**
     * 修改商品成功
     */
    public function updateProductSuccess() {
        $this->display( 'member-addGood-succModify' );
    }

    /**
     * 上下架商品
     */
    public function editProductShelf() {
        $ret = array(
            'code' => 200,
            'msg' => 'Successful operation',
            'data' => NULL,
        );
        $ids = I( 'id' );
        $state = I( 'state' );
        $uid = $this->uid;
        $IsCompleteInfo = D('User/Account') ->checkInfoIsComplete( array( 'id'=>$this->uid ) );
        if(!$IsCompleteInfo){
            $ret['code'] = 400;
            $ret['msg'] = 'Basic information is not yet perfect';
            $this->ajaxReturn( $ret );
        }
        if( empty( $ids ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Id is required';
            $this->ajaxReturn( $ret );
        }
        if( is_string( $ids ) ){
            $ids = explode( ',', $ids );
        }
        if( empty( $uid ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Please Login';
            $this->ajaxReturn( $ret );
        }
        $model = D( 'Home/Product' );
        $failId = $successId= array();
        $states = $model->getProductState();
        foreach( $ids as $id ){
            $data = $model->detail( array( 'id' => $id ) );
            if( $data['Uid'] != $uid ){
                $failId[] = $id;
                break;
            }
            if( $state == 1 ){
                if( $data['state'] != $states['SELLER_REVOKE']['value'] ){
                    $failId[] = $id;
                    break;
                }
                $saveData = array(
                    'opera' => 'Shelve Product',
                    'oid' => $uid,
                    'otype' => 'seller',
                    'state' => $states['ACTIVE']['value'],
                );
                $result = $model->editState( $id, $saveData );
                if( !$result ){
                    $failId[] = $id;
                }else{
                    $successId[] = $id;
                }
            }else{
                if( $data['state'] != $states['ACTIVE']['value'] ){
                    $failId[] = $id;
                    break;
                }
                $saveData = array(
                    'opera' => 'Unshelve Product',
                    'oid' => $uid,
                    'otype' => 'seller',
                    'state' => $states['SELLER_REVOKE']['value'],
                );
                $result = $model->editState( $id, $saveData );
                if( !$result ){
                    $failId[] = $id;
                }else{
                    $successId[] = $id;
                }
            }
        }
        if( !empty( $failId ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Some product occurred error';
        }
        $ret['data'] = array(
            'fail_id' => $failId,
            'success_id' => $successId,
        );
        $this->ajaxReturn( $ret );
    }

    /**
     * 商品历史
     */
    public function productHistories() {
        $model = D( 'Home/Product' );
        $uid = $this->uid;
        //判断是否登录
        $this->checkLogin();
        //获取用户国藉
        $isUserAllowAccessProductList = $model->getIsUserAllowAccessProductList( array( 'uid' => $uid ) );
        if( !$isUserAllowAccessProductList ){
            $this->error( 'This feature allows only Chinese users', '/' );
        }

        $param = I( 'get.' );
        $id = $param['id'];
        $data = $model->detail( array( 'id' => $id ) );
        $param['page']      = empty( $param['p'] ) ? 1 : intval( $param['p'] );
        $param['page_size'] = empty( $param['page_size'] ) ? 10 : intval( $param['page_size'] );
        $param['is_not_ksort'] = true;

        if( empty( $data ) ){
            $this->redirect( 'Seller/products' );
        }
        //非本用户发布的商品不允许查看
        if( $data['Uid'] != $uid ){
            $this->redirect( 'Seller/products' );
        }
        $data = $model->getProductHistoryLists( $param );

        $this->assign( 'histories', $data['lists'] );
        $pageHtml = new Page( $data['count'], $param['page_size'] );
        $pageHtml->setConfig( 'prev', '<i class="icon-prev"></i>Previous Page' );
        $pageHtml->setConfig( 'next', 'Next Page<i class="icon-next"></i>' );
        $this->assign( 'page_html', $pageHtml->show() );
        $this->display( 'member-addGoods-operHistory' );
    }

    //检测企业认证是否通过。
    public function checkCert(){
        $uid = $this->uid;
        //获取企业认证状态
        $redis = \Think\Cache::getInstance('Redis');
        $state=$redis->hGet("hash:member:info:{$uid}","state");
        if($state=='0' || $state == '3'){       //审核不通过，撤销的企业跳转
            redirect('/Seller/products');
        }
    }
}