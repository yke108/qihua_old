<?php 
namespace Home\Controller;
use Think\Controller;

/**
 * 供应商控制器
 */


class SupplierController extends CommonController{
	
	
	
	
	/*
	 * 供应商列表
	 */
	public function lists(){
		$model=D("User/Account");
		$param['p']=isset($_GET['p'])?I("p"):1;
		$param['country']=isset($_GET['countryIds'])?I('countryIds'):'';
		$param['title']=isset($_GET['keyword'])?I('keyword'):'';
        $param['title'] = trim($param['title']);
		$res=$model->getSupplierList($param);
		//获取国家数据
		$area=$model->getArrayActiveCountry();
		$this->assign('country',$area);
		$this->assign('show',$res['show']);
		$this->assign('list',$res['list']);
		$this->assign('pageinfo',$res['pageinfo']);
		$selectCountry=explode(",", $param['country']);
		$this->assign('select',$selectCountry);
		$this->assign( 'cate', getcategory() );
		$this->display('suppliers-list');
	}
	
	
	/*
	 * buyoffer List
	 */
	
	public function BuyofferList(){
		$param['Uid']=I('uid');
		if(empty($param['Uid'])){
			$this->redirect('Buyoffer/indexlist');
			return false;
		}
		//获取公司名称
		$companyName=D('User/Account')->SelectAccountInfo($param['Uid'],array('companyName'))['companyName'];
		if(empty($companyName)){
			$this->redirect('Buyoffer/indexlist');
			return false;
		}
		
		$param['state']=1;
		$param['p']=I('p');
		$model=D("User/Buyoffer");
		$param['all']=true;
		$res=$model->lists($param);
		$complete=intval(D('User/Account')->checkInfoIsComplete(array('id'=>$this->uid)));
		$this->assign('complete',$complete);
		$this->assign('companyName',$companyName);
		$this->assign('pageinfo',empty($res['pageinfo']) ? '' : $res['pageinfo'] );
		$this->assign( 'loginUid', $this->uid  );
		$this->assign('supplier',$param['Uid']);
		$this->assign('show', empty($res['show']) ? '' : $res['show']);
		$this->assign('list', empty($res['list']) ? '' : $res['list']);
		$this->display("suppliers-buyOffers");
	}
	
	
	/*
	 * supply list
	 */
	public function supplyList(){
		$param['Uid']=I('uid');
		if(empty($param['Uid'])){
			$this->redirect('Supply/lists');
			return false;
		}
		//获取公司名称
		$companyName=D('User/Account')->SelectAccountInfo($param['Uid'],array('companyName'))['companyName'];
		if(empty($companyName)){
			$this->redirect('Supply/lists');
			return false;
		}
		//$a=http_build_query($param);
		$param['state']=1;
		$param['p']=I('p');
		$model=D("User/Supply");
		$param['all']=true;
		$res=$model->lists($param);
		
		$complete=intval(D('User/Account')->checkInfoIsComplete(array('id'=>$this->uid)));
		$this->assign('complete',$complete);
		$this->assign('companyName',$companyName);
		$this->assign('pageinfo',empty($res['pageinfo']) ? '' : $res['pageinfo']);
		$this->assign( 'loginUid', $this->uid  );
		$this->assign('supplier',$param['Uid']);
		$this->assign('show',empty($res['show']) ? '' : $res['show']);
		$this->assign('list',empty($res['list']) ? '' : $res['list']);
		$this->display("suppliers-supplyOffers");
	}
	
	
	/*
	 * Product List
	 */
	
	public function productList(){
		$param['uid']=I('uid');
		if(empty($param['uid'])){
			$this->redirect('Supply/lists');
			return false;
		}
		
		//获取公司名称
		$companyName=D('User/Account')->SelectAccountInfo($param['uid'],array('companyName'))['companyName'];
		if(empty($companyName)){
			$this->redirect('Supply/lists');
			return false;
		}
		$param['p']=empty(I('p'))?1:I('p');
		$param['page_size']=empty($_GET['page_size'])?10:I('page_size');

		
		$param['state'] = 1;
		$res=D("Product")->lists($param);
		if(!empty($res['lists'])){
			$lists=array();
			$weigth=D("Product")->getProductWeightUnit();
			$currency=D("Product")->getProductCurrency();
			foreach ($res['lists'] as $key=>$v){
				$attribute					= empty($v['attribute']) ? array() : unserialize($v['attribute']);
				$categoryList				= explode(",", $v['categoryList']);
				$area						= explode(',', $v['seatList']);
				$lists[$key]['id']			= empty($v['id']) ? '' : $v['id'];
				$lists[$key]['cas']			= empty($v['cas']) ? '' : $v['cas'];
				$lists[$key]['title']		= empty($v['title']) ? '' : $v['title'];
				$lists[$key]['price']		= empty($v['price']) ? '' : $v['price'];
				$lists[$key]['moq']			= empty($v['moq']) ? '' : $v['moq'];
				$lists[$key]['inventory']	= empty($v['inventory']) ? 2 : $v['inventory'];
				$lists[$key]['format']		= empty($attribute['format']) ? '' : $attribute['format'];
				$lists[$key]['msds']		= empty($attribute['msds']) ? '' : $attribute['msds'];
				$lists[$key]['tds']			= empty($attribute['tds']) ? '' : $attribute['tds'];
				$lists[$key]['coa']			= empty($attribute['coa']) ? '' : $attribute['coa'];
				$lists[$key]['w']			= empty($weigth[$v['weightUnit']]['enTitle']) ? '' : $weigth[$v['weightUnit']]['enTitle'];
				$lists[$key]['c']			= !empty($currency[$v['currency']]['character']) ? $currency[$v['currency']]['character'] : '';
				$lists[$key]['seat']		='';
				for($i=0;$i<count($area);$i++){
					$tmp01 = D("Area")->detail(array('id'=>$area[$i]));
					$lists[$key]['seat'].=isset($tmp01['title'])?$tmp01['title']:'';
					if($i<count($area)-1&&!empty(D("Area")->detail(array('id'=>$area[$i]))['title'])){
						$lists[$key]['seat'].=">";
					}
				}
				$lists[$key]['categoryList']='';
				for($i=0;$i<count($area);$i++){
					$lists[$key]['categoryList'].=D("Category")->detail(array('id'=>$categoryList[$i]))['title'];
					if($i<(count($area)-1)&&!empty(D("Category")->detail(array('id'=>$categoryList[$i]))['title'])){
						$lists[$key]['categoryList'].=">";
					}
				}
				
				//$lists[$key]['seat']		=D("Area")->detail(array('id'=>$area[0]))['title'].">".D("Area")->detail(array('id'=>$area[1]))['title'].">".D("Area")->detail(array('id'=>$area[2]))['title'];
				//$lists[$key]['categoryList']=D("Category")->detail(array('id'=>$categoryList[0]))['title'].">".D("Category")->detail(array('id'=>$categoryList[1]))['title'].">".D("Category")->detail(array('id'=>$categoryList[2]))['title'];
				$lists[$key]['img']			=unserialize($v['images'])[0];
				$tmp02 = D('Brand')->detail(array('id'=>$v['brandId']));
				$lists[$key]['brand']		=isset($tmp02['title'])?$tmp02['title']:'';
				$tmp03 = D('Producer')->detail(array('id'=>$v['producerId']));
				$lists[$key]['producer']	=isset($tmp03['title'])?$tmp03['title']:'';
				$lists[$key]['pack']		=$attribute['pack'];
			}
			$pageinfo['count']=$res['count'];
			$pageinfo['page']=$param['p'];
			$pageinfo['pagecount']=ceil($res['count']/$param['page_size']);
			$this->assign('pageinfo',$pageinfo);
			$this->assign('lists',$lists);
			$p=$param['p'];
			$size=$param['page_size'];
			unset($param['p']);
			unset($param['page_size']);
			unset($param['state']);
			$a=http_build_query($param);
			$show=D("Buyoffer")->showpage($res['count'],$p,$size,$a);
			$this->assign('show',$show);
		}
		$complete=intval(D('User/Account')->checkInfoIsComplete(array('id'=>$this->uid)));
		$this->assign('complete',$complete);
		$this->assign('companyName',$companyName);
		$this->assign( 'loginUid', $this->uid  );
		$this->assign('supplier',$param['uid']);
		$this->display('suppliers-products');
		
	}
	
	
	/*
	 * 商家详情
	 */
	
	
	public function profile(){
		$param['uid']=I('uid');
		if(empty($param['uid'])){
			$this->redirect('Supply/lists');
			return false;
		}
		$Account=D( 'User/Account' );
		//获取公司名称
		$IsCompleteInfo = $Account->checkInfoIsComplete( array( 'id'=>$param['uid']) );
		if(!$IsCompleteInfo){
			$this->redirect('Supply/lists');
			return false;
		}

		//获取公司的详细资料

		$trade=$Account->GetBaseData( 'trade' );//所在行业
		$employee=$Account->GetBaseData( 'employees' );//单位人数
		$turnover=$Account->GetBaseData( 'turnover' );//年营业额
		$model  = $Account->GetBaseData( 'model' );//经营模式
		$pram=array( 'companyName','contact','model','employee','trade','turnover','businessScope','companyIntroduction','other','state' );
		$data=$Account->SelectAccountInfo( $param['uid'],$pram );
		
		if( !empty( $data ) ){
			$data['model']= empty($model[$data['model']]['title']) ? '' : $model[$data['model']]['title'];
			$data['employee']= empty($employee[$data['employee']]['title'])? '' : $employee[$data['employee']]['title'];
			$data['trade']= empty($trade[$data['trade']]['title']) ? '' : $trade[$data['trade']]['title'];
			$data['turnover']= empty($turnover[$data['turnover']]['title']) ? '' : $turnover[$data['turnover']]['title'];
			$area['country'] = empty($Account->GetAreaTitle( $data['other']['country'],array( 'title' ) )['title']) ? '' : $Account->GetAreaTitle( $data['other']['country'],array( 'title' ) )['title'];//国家
			$area['area_s']  = empty($Account->GetAreaTitle( $data['other']['area_s'],array( 'title' ) )['title']) ? '' : $Account->GetAreaTitle( $data['other']['area_s'],array( 'title' ) )['title'];//地区
			$area['area_c']  = empty($Account->GetAreaTitle( $data['other']['area_c'],array( 'title' ) )['title']) ? '' : $Account->GetAreaTitle( $data['other']['area_c'],array( 'title' ) )['title'];//城市
		}
		$complete=intval($Account->checkInfoIsComplete(array('id'=>$this->uid)));
		$this->assign('complete',$complete);
		$this->assign('area',$area);
		$this->assign('data',$data);
		$this->assign( 'loginUid', $this->uid  );
		$this->assign('supplier',$param['uid']);
		$this->display('suppliers-profile');
	
	}
	
	protected function getKeyComplete($state){
		return "set:company:complete:".$state;
	}
	
	protected  function getCompanyHash($id){
		return "hash:member:info:".$id;
	}
	
}





?>