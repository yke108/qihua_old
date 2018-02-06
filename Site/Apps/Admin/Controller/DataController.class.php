<?php
namespace Admin\Controller;
use Think\Controller;



class DataController extends ContentController{

	//地区管理
	public function area(){
		$this->display();
	}
	//地区列表
	public function areaList(){
		$area=D('Area');
		$res=$area->getArea();
		echo json_encode($res);
	}
	//新增地区
	public function  addArea(){
		if(!IS_POST) exit;
		$data=I('post.');

		$area=D('Area');

		if (!$data=$area->create($data)){
			$return['msg']=$area->getError();
			$return['code']=400;
		}else{
			$addResult = $area->areaAdd($data);
			if(!$addResult){
				$return['msg']='添加失败!';
				$return['code']=400;
			}else{
				$return['msg']='添加成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}
	
	//更新地区
	public function updateArea(){
		if(!IS_POST) exit;
		$data=I('post.');
		$area=D('Area');
		if (!$data=$area->create($data)){
			$return['msg']=$area->getError();
			$return['code']=400;
		}else{
			$addResult = $area->updateArea($data);
			if(!$addResult){
				$return['msg']='修改失败!';
				$return['code']=400;
			}else{
				$return['msg']='修改成功!';
				$return['code']=200;
			}
		}
		$this->ajaxReturn($return);
	}
	
	//删除地区
	public function delArea(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$area = D('Area');

		//删除操作
		$result = $area->areaDelete($id);
		if(!$result){
			$return['msg']='删除失败!';
			$return['code']=400;
		}else{
			$return['msg']='删除成功!';
			$return['code']=200;
		}
		$this->ajaxReturn($return);
	}
	
	
	//生产商
	public function producer(){
		$this->display();
	}

	//品牌管理
	public function brand(){
		$this->display();
	}
	
	//品牌列表
	public function brandList(){
		$brand=D('Brand');
		echo json_encode($brand->getBrand());
	}
	
	//新增品牌
	public function addBrand(){
		if(!IS_POST) exit;
		$data=I('post.');

		$brand=D('Brand');

		if (!$data=$brand->create($data)){
			$return['msg']=$brand->getError();
			$return['code']=400;
		}else{
			$addResult = $brand->addBrand($data);
			if(!$addResult){
				$return['msg']='添加失败!';
				$return['code']=400;
			}else{
				$return['msg']='添加成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}
	
	//修改品牌
	public function modify(){
		if(!IS_POST) exit;
		$data=I('post.');
		$brand=D('Brand');
		if (!$data=$brand->create($data)){
			$return['msg']=$brand->getError();
			$return['code']=400;
		}else{
			$addResult = $brand->modify($data);
			if(!$addResult){
				$return['msg']='修改失败!';
				$return['code']=400;
			}else{
				$return['msg']='修改成功!';
				$return['code']=200;
			}
		}
		$this->ajaxReturn($return);
	}
	
	//删除品牌
	public function delBrand(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$brand = D('Brand');

		//删除操作
		$result = $brand->delBrand($id);
		if(!$result){
			$return['msg']='删除失败!';
			$return['code']=400;
		}else{
			$return['msg']='删除成功!';
			$return['code']=200;
		}
		$this->ajaxReturn($return);
	}
	
	/**
	 * 商品类别管理
	 */
	public function Category(){
		$this->display();
	}
	
	//商品类别列表
	public function getCategory(){
		$cat=D('Category');
		echo json_encode($cat->getCategory());
	}
	
	//新增商品类别
	public function addCategory(){
		if(!IS_POST) exit;
		$data=I('post.');

		$category=D('Category');

		if (!$data=$category->create($data)){
			$return['msg']=$category->getError();
			$return['code']=400;
		}else{
			$addResult = $category->addCategory($data);
			if(!$addResult){
				$return['msg']='添加失败!';
				$return['code']=400;
			}else{
				$return['msg']='添加成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}
	
	//修改商品类别
	public function updateCate(){
		if(!IS_POST) exit;
		$data=I('post.');
		$category=D('Category');
		if (!$data=$category->create($data)){
			$return['msg']=$category->getError();
			$return['code']=400;
		}else{
			$updateResult = $category->updateCategory($data);
			if(!$updateResult){
				$return['msg']='修改失败!';
				$return['code']=400;
			}else{
				$return['msg']='修改成功!';
				$return['code']=200;
			}
		}
		$this->ajaxReturn($return);
	}
	//删除商品类别
	public function delCate(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$category = D('Category');

		//删除操作
		$result = $category->delCategory($id);
		if(!$result){
			$return['msg']='删除失败!';
			$return['code']=400;
		}else{
			$return['msg']='删除成功!';
			$return['code']=200;
		}
		$this->ajaxReturn($return);
	}
	
	
	/**
	 * 生产商管理
	 */
	public function getProducer(){
		$p=D('Producer');
		echo json_encode($p->getProducer());
	}
	
	//新增生产商
	public function addProducer(){
		if(!IS_POST) exit;
		$data=I('post.');
		$producer=D('Producer');

		if (!$data=$producer->create($data)){
			$return['msg']=$producer->getError();
			$return['code']=400;
		}else{

			$addResult = $producer->addProducer($data);
			if(!$addResult){
				$return['msg']='添加失败!';
				$return['code']=400;
			}else{
				$return['msg']='添加成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}
	
	//更新生产商
	public function updateProducer(){
		if(!IS_POST) exit;
		$data=I('post.');
		$producer=D('Producer');
		if (!$data=$producer->create($data)){
			$return['msg']=$producer->getError();
			$return['code']=400;
		}else{
			$updateResult = $producer->updateProducer($data);
			if(!$updateResult){
				$return['msg']='修改失败!';
				$return['code']=400;
			}else{
				$return['msg']='修改成功!';
				$return['code']=200;
			}
		}
		$this->ajaxReturn($return);
	}
	
	//删除生产商
	public function delProducer(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$producer = D('Producer');

		//删除操作
		$result = $producer->delProducer($id);
		if(!$result){
			$return['msg']='删除失败!';
			$return['code']=400;
		}else{
			$return['msg']='删除成功!';
			$return['code']=200;
		}
		$this->ajaxReturn($return);
	}
	
	/**
	 * 所在行业
	 */

	public function trade(){
		$this->display();
	}

	//行业列表
	public function tradeList(){
		$companyData=D('Companydata');
		echo json_encode($companyData->getList('trade'));
	}
	//行业添加
	public function tradeAdd(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='trade';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$addResult = $company->companyDataInsert($data);
			if(!$addResult){
				$return['msg']='添加失败!';
				$return['code']=400;
			}else{
				$return['msg']='添加成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//行业修改
	public function tradeEdit(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='trade';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$editResult = $company->companyDataUpdate($data);
			if(!$editResult){
				$return['msg']='修改失败!';
				$return['code']=400;
			}else{
				$return['msg']='修改成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//删除行业
	public function tradeDel(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$type='trade';//匹配公司数据类型
		$company = D('Companydata');

		//删除操作
		$result = $company->companyDataDelete($id,$type);
		if(!$result){
			$return['msg']='删除失败!';
			$return['code']=400;
		}else{
			$return['msg']='删除成功!';
			$return['code']=200;
		}
		$this->ajaxReturn($return);
	}


	/**
	 * 单位性质
	 */

	public function property(){
		$this->display();
	}

	//单位性质列表
	public function propertyList(){
		$companyData=D('Companydata');
		echo json_encode($companyData->getList('property'));
	}
	//单位性质添加
	public function propertyAdd(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='property';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$addResult = $company->companyDataInsert($data);
			if(!$addResult){
				$return['msg']='添加失败!';
				$return['code']=400;
			}else{
				$return['msg']='添加成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//单位性质修改
	public function propertyEdit(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='property';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$editResult = $company->companyDataUpdate($data);
			if(!$editResult){
				$return['msg']='修改失败!';
				$return['code']=400;
			}else{
				$return['msg']='修改成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//单位性质行业
	public function propertyDel(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$type='property';//匹配公司数据类型
		$company = D('Companydata');

		//删除操作
		$result = $company->companyDataDelete($id,$type);
		if(!$result){
			$return['msg']='删除失败!';
			$return['code']=400;
		}else{
			$return['msg']='删除成功!';
			$return['code']=200;
		}
		$this->ajaxReturn($return);
	}


	/**
	 * 经营模式
	 */

	public function model(){
		$this->display();
	}

	//经营模式列表
	public function modelList(){
		$companyData=D('Companydata');
		echo json_encode($companyData->getList('model'));
	}
	//经营模式添加
	public function modelAdd(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='model';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$addResult = $company->companyDataInsert($data);
			if(!$addResult){
				$return['msg']='添加失败!';
				$return['code']=400;
			}else{
				$return['msg']='添加成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//经营模式修改
	public function modelEdit(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='model';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$editResult = $company->companyDataUpdate($data);
			if(!$editResult){
				$return['msg']='修改失败!';
				$return['code']=400;
			}else{
				$return['msg']='修改成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//经营模式行业
	public function modelDel(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$type='model';//匹配公司数据类型
		$company = D('Companydata');

		//删除操作
		$result = $company->companyDataDelete($id,$type);
		if(!$result){
			$return['msg']='删除失败!';
			$return['code']=400;
		}else{
			$return['msg']='删除成功!';
			$return['code']=200;
		}
		$this->ajaxReturn($return);
	}


	/**
	 * 年营业额
	 */

	public function turnover(){
		$this->display();
	}

	//年营业额列表
	public function turnoverList(){
		$companyData=D('Companydata');
		echo json_encode($companyData->getList('turnover'));
	}
	//年营业额添加
	public function turnoverAdd(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='turnover';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$addResult = $company->companyDataInsert($data);
			if(!$addResult){
				$return['msg']='添加失败!';
				$return['code']=400;
			}else{
				$return['msg']='添加成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//年营业额修改
	public function turnoverEdit(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='turnover';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$editResult = $company->companyDataUpdate($data);
			if(!$editResult){
				$return['msg']='修改失败!';
				$return['code']=400;
			}else{
				$return['msg']='修改成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//年营业额行业
	public function turnoverDel(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$type='turnover';//匹配公司数据类型
		$company = D('Companydata');

		//删除操作
		$result = $company->companyDataDelete($id,$type);
		if(!$result){
			$return['msg']='删除失败!';
			$return['code']=400;
		}else{
			$return['msg']='删除成功!';
			$return['code']=200;
		}
		$this->ajaxReturn($return);
	}

	/**
	 * 单位人数
	 */

	public function employees(){
		$this->display();
	}

	//单位人数列表
	public function employeesList(){
		$companyData=D('Companydata');
		echo json_encode($companyData->getList('employees'));
	}
	//单位人数添加
	public function employeesAdd(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='employees';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$addResult = $company->companyDataInsert($data);
			if(!$addResult){
				$return['msg']='添加失败!';
				$return['code']=400;
			}else{
				$return['msg']='添加成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//单位人数修改
	public function employeesEdit(){
		if(!IS_POST) exit;
		$data=I('post.');
		$data['type']='employees';//匹配公司数据类型
		$company=D('Companydata');

		if (!$data=$company->create($data)){
			$return['msg']=$company->getError();
			$return['code']=400;
		}else{
			$editResult = $company->companyDataUpdate($data);
			if(!$editResult){
				$return['msg']='修改失败!';
				$return['code']=400;
			}else{
				$return['msg']='修改成功!';
				$return['code']=200;
			}
		}

		$this->ajaxReturn($return);
	}

	//单位人数行业
	public function employeesDel(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$type='employees';//匹配公司数据类型
		$company = D('Companydata');

		//删除操作
		$result = $company->companyDataDelete($id,$type);
		if(!$result){
			$return['msg']='删除失败!';
			$return['code']=400;
		}else{
			$return['msg']='删除成功!';
			$return['code']=200;
		}
		$this->ajaxReturn($return);
	}

	public function getChildArea(){
		if(!IS_POST)exit;
		$id=I('post.id');
		$area=D('Area');
		$info = $area->getChildArea($id);
//		if(!$info){
//			$return['msg']='读取失败!';
//			$return['code']=400;
//		}else{
//			$return['msg']='读取成功!';
//			$return['data']=$info;
//			$return['code']=200;
//		}

		$this->ajaxReturn($info);
	}

    /**
     * 获取APP_SECRET列表
     */
    public function getAppSecretLists(){
        $ret = array(
            'total' => 0,
            'rows' => array(),
        );
        $page = intval( I( 'page', 1 ) );
        $rows = intval( I( 'rows', 20 ) );
        $model = D( 'Home/AppSecret' );
        $result = $model->lists( array( 'p' => $page, 'page_size' => $rows, 'status' => 1 ) );
        if( !empty( $result['lists'] ) ){
            foreach( $result['lists'] as &$v ){
                $v['addTimeTip'] = date( 'Y-m-d H:i:s', $v['addTime'] );
            }
            $ret['total'] = $result['count'];
            $ret['rows'] = $result['lists'];
        }
        $this->ajaxReturn( $ret );
    }

    /**
     * 增加APP_SECRET
     */
    public function addAppSecret(){
        $ret = array(
            'code' => 200,
            'msg' => '操作成功',
            'data' => NULL,
        );
        $appId = trim( I( 'appId' ) );
        $appSecret = trim( I( 'appSecret' ) );
        if( empty( $appId ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'appId不能为空';
            $this->ajaxReturn( $ret );
        }
        if( empty( $appSecret ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'appSecret不能为空';
            $this->ajaxReturn( $ret );
        }
        $data = array(
            'appId' => $appId,
            'appSecret' => $appSecret,
        );
        $model = D( 'Home/AppSecret' );
        $result = $model->insert( $data );
        if( !$result ){
            $ret['code'] = 400;
            $ret['msg'] = $model->getError();
            $this->ajaxReturn( $ret );
        }
        $this->ajaxReturn( $ret );
    }

    /**
     * 修改APP_SECRET
     */
    public function editAppSecret(){
        $ret = array(
            'code' => 200,
            'msg' => '操作成功',
            'data' => NULL,
        );
        $id = trim( I( 'id' ) );
        $appId = trim( I( 'appId' ) );
        $appSecret = trim( I( 'appSecret' ) );
        if( empty( $id ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Id不能为空';
            $this->ajaxReturn( $ret );
        }
        if( empty( $appId ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'appId不能为空';
            $this->ajaxReturn( $ret );
        }
        if( empty( $appSecret ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'appSecret不能为空';
            $this->ajaxReturn( $ret );
        }
        $data = array(
            'appId' => $appId,
            'appSecret' => $appSecret,
        );
        $model = D( 'Home/AppSecret' );
        $result = $model->edit( $id, $data );
        if( !$result ){
            $ret['code'] = 400;
            $ret['msg'] = $model->getError();
            $this->ajaxReturn( $ret );
        }
        $this->ajaxReturn( $ret );
    }

    /**
     * 删除APP_SECRET
     */
    public function removeAppSecret(){
        $ret = array(
            'code' => 200,
            'msg' => '操作成功',
            'data' => NULL,
        );
        $id = trim( I( 'id' ) );
        if( empty( $id ) ){
            $ret['code'] = 400;
            $ret['msg'] = 'Id不能为空';
            $this->ajaxReturn( $ret );
        }
        $data = array(
            'id' => $id,
        );
        $model = D( 'Home/AppSecret' );
        $result = $model->remove( $data );
        if( !$result ){
            $ret['code'] = 400;
            $ret['msg'] = $model->getError();
            $this->ajaxReturn( $ret );
        }
        $this->ajaxReturn( $ret );
    }

    //APP_SECRET
    public function wxappsecret(){
        $this->display();
    }

    /*
   * 手机白名单
   * */
    public function Phone(){
        $this->display();
    }
    /*
      * 手机白名单列表
      * */
    public function PhoneLists(){
        $ret = array(
            'total' => 0,
            'rows' => array(),
        );
        $page = intval( I( 'page', 1 ) );
        $rows = intval( I( 'rows', 20 ) );
        $model = D( 'Admin/WhitePhone' );
        $result = $model->lists( array( 'p' => $page, 'page_size' => $rows ) );
        if( !empty( $result ) ){
            $ret['total'] = $result['count'];
            $ret['rows'] = $result['lists'];
        }
        $this->ajaxReturn( $result );
    }

    /*
     * 新增手机白名单
     * */
    public function AddPhone(){
        $data['phone'] = I('post.phone');
        $model = D( 'Admin/WhitePhone' );
        if( $model->create( $data ) ){
            $ret = $model -> addWhite( $data );
            if( $ret ){
                $res['msg'] = '添加成功';
                $res['code'] = '200';
                $res['data']['ok'] = '添加成功';
                $this->ajaxReturn( $res );
            }else{
                $res['msg'] = '添加失败';
                $res['code'] = '400';
                $res['data']['error'] = '添加失败';
                $this->ajaxReturn( $res );
            }
        }else{
            $res['msg'] = $model->getError();
            $res['code'] = '400';
            $res['data']['error'] = $model->getError();
            $this->ajaxReturn( $res );
        }
    }

    /*
     * 删除手机白名单
     * */
    public function DelPhone(){
        $data['phone'] = I('post.phone');
        $model = D( 'Admin/WhitePhone' );
        if( $model->create( $data ) ){
            $ret = $model -> DelWhite( $data );
            if( $ret ){
                $res['msg'] = '删除成功';
                $res['code'] = '200';
                $res['data']['ok'] = '删除成功';
                $this->ajaxReturn( $res );
            }else{
                $res['msg'] = '删除失败';
                $res['code'] = '400';
                $res['data']['error'] = '删除失败';
                $this->ajaxReturn( $res );
            }
        }else{
            $res['msg'] = $model->getError();
            $res['code'] = '400';
            $res['data']['error'] = $model->getError();
            $this->ajaxReturn( $res );
        }
    }

    /**
     * 关键指标
     */
    public function indicator() {
        $this->display();
    }

    /**
     * 关键指标列表
     */
    public function indicatorList() {
        $page = (int)I('page', 1);
        $rows = (int)I('rows', 20);
        $keyword = trim(I('keyword'));
        if (IS_AJAX && $keyword) {
            $indicatorModel = D('Admin/Indicator');
            $nameKey = $indicatorModel->getNameStringKey($keyword);
            $indicatorId = $indicatorModel->get($nameKey);
            $ds['total'] = 0;
            $ds['rows'] = array();
            if ($indicatorId && preg_match('/(\d+)/', $indicatorId, $matches)) {
                $ds['total'] = 1;
                $ds['rows'] = $indicatorModel->get($indicatorModel->getHashkey($matches[1]), null, null, 'processData');
                $this->ajaxReturn($ds);
            }
            $this->ajaxReturn($ds);
        }
        
        $params = array(
            'by'    => 'hash:product:keyIndex:*->id',
            'limit' => array(($page - 1) * $rows, $rows),
            'sort'  => 'desc',
            'get'   => array('hash:product:keyIndex:*->id', 'hash:product:keyIndex:*->cid', 'hash:product:keyIndex:*->name',
                'hash:product:keyIndex:*->addTime', 'hash:product:keyIndex:*->editTime'),
        );

        $lists = D('Admin/Indicator')->search($params);
        $this->ajaxReturn($lists);
    }

    /**
     * 添加关键指标
     */
    public function indicatorAdd() {
        $names = I('post.names');
        $names = explode("\n", $names);
        if ($names) {
            $rs = D('Admin/Indicator')->insert($names);
        }
        $return['msg'] = $rs ? '添加成功!' : '添加失败';
        $return['code'] = $rs ? 200 : 400;
        $this->ajaxReturn($return);
    }

    /**
     * 修改关键指标
     */
    public function indicatorEdit() {
        $name = trim(I('post.name'));
        $id = I('post.id') + 0;
        if (!$name || !$id) {
            $return['msg'] = '参数异常!';
            $return['code'] = 400;
            $this->ajaxReturn($return);
        }
        $IndicatorModel = D('Admin/Indicator');
        if ($IndicatorModel->checkIndicatorIsExist($name)) {
            $this->ajaxReturn(array('msg' => '关键指标已经存在!', 'code' => 400, 'data' => array('error' => '关键指标已经存在!')));
        }
        $rs = $IndicatorModel->update(I('post.'));
        $return['msg'] = $rs ? '修改成功!' : '修改失败';
        $return['code'] = $rs ? 200 : 400;
        $this->ajaxReturn($return);
    }
}