<?php
namespace User\Model;
use Think\Model;


class CollectModel extends Model{

    protected $redis;

	public function __construct(){
		$this->redis = \Think\Cache::getInstance('Redis');
		$this->shell=D('Shell');
	}
	

	
	//新增收藏
	public function addcollect($param){
		$type=$this->getCollectType($param);
		$collect=$type['collect'];
		$times=$type['times'];
		
		//先判断有没有收藏过这个
		//var_dump($this->redis->zScore($collect,$param['id']));exit;
		if($this->redis->zScore($collect,$param['id'])){
			return array('code'=>400,'msg'=>'In your favorites','data'=>'');
		}
		$title=$this->redis->hget($type['hash'],'title');
		//事务处理
		for($i=0;$i<10;$i++){
			//加入收藏集合
			$this->redis->watch($collect,$times);
			$this->redis->multi();
			$this->redis->zadd($collect,time(),$param['id']);
			$this->redis->zIncrBy($times,1,$param['uid']);
			if($this->redis->exec()){
				$this->shell->index($type['title'],$title,$param['id']);
				return true;
			}
		}
		return false;
	}
	
	//删除收藏
	public  function delCollect($data){
		$collect=$this->getCollectType($data)['collect'];
		$times=$this->getCollectType($data)['times'];
		//先判断有没有收藏过这个
		$id=$data['id'];
		//var_dump($data);exit;
		for($i=0;$i<count($id);$i++){
			if(!$this->redis->zscore($collect,$id[$i])){
				return array('code'=>400,'msg'=>'Not in your favorites','data'=>'');
			}
		}

		for($i=0;$i<10;$i++){
			//加入收藏集合
			for($j=0;$j<count($id);$j++){
				$this->redis->watch($collect,$times);
				$this->redis->multi();
				$this->redis->zrem($collect,$id[$j]);
				$this->redis->zIncrBy($times,-1,$data['uid']);
			}
			if($this->redis->exec()){
				return true;
			}
		}
		return false;
	}
	
	//获取收藏列表，默认按照时间排序
	public function lists($data){
		$gettype=$this->getCollectType($data);
		$hash=$gettype['hash'];
		$search=$gettype['title'];
		$collect=$gettype['collect'];
		$page    = empty( $data['p'] ) ? 1 : intval( $data['p'] );
		$page_size = empty( $data['page_size'] ) ? 10 : intval( $data['page_size'] );
		$first=($page-1)*$page_size;
		$last=$page*$page_size;
		$start=isset($data['start'])?$data['start']:"-inf";
		$end=isset($data['end'])?$data['end']:"+inf";
		if(!empty($data['title']))$title=$data['title'];
		if(!empty($title)){
			$shell=D('Shell');
			$arr[]=$shell->search($search,strtolower($title),'set');
			$arr[]=$collect;
			$set=$this->redis->zInter('tmp:set:collect:list:'.$tmp,$arr);
			$res=$this->redis->zRangeByScore('tmp:set:collect:list:'.$tmp,$start,$end);
			$this->redis->expire( 'tmp:set:collect:list:'.$tmp, 3 );
		}else{
			$res=$this->redis->zRangeByScore($collect,$start,$end);
		}
		if(!$res)return array();
		$ret['count']=count($res);
		$a='';
		$a=http_build_query(array("type"=>$data['type'],'title'=>$data['title']));
		$show=$this->showpage($ret['count'], $page,$page_size,$a);
		if(empty($count)){
		    $count = 0;
		}
		$ret['pagecount']=ceil($count/$page_size)+1;
		
		if($page>$ret['pagecount']){
			$page=$ret['pagecount'];
			$offset=intval(($page-1)*$pageSize);
		}
		$ret['page']=$page;
		$arr=array();
		for($i=$first;$i<$last;$i++){
		    if(empty($res[$i])){
		        continue;
		    }
			$tmp['id']=$this->redis->hGet($hash.$res[$i],'id');
			if(!empty($tmp['id'])){
				$arr[$i] = $tmp;
				unset($tmp);
			}else{
			    continue;
			}
			$arr[$i]['title']=$this->redis->hGet($hash.$res[$i],'title');
			$arr[$i]['time']=$this->redis->zscore($collect,$res[$i]);
		}
		return array('ret'=>$ret,'list'=>$arr,'show'=>$show);
		
	}
	
	
	
	
	//获取收藏求购的key
	protected  function getBuyOfferTimes(){
		return 'zset:collect:buyoffer';
	}
	
	//获取求购收藏的key
	protected  function getBuyOfferCollect($uid){
		return 'zset:collect:buyoffer:'.$uid;
	}
	
	//获取收藏supply的key
	protected  function getSupplyTimes(){
		return 'zset:collect:supply';
	}
	
	//获取求购收藏的key
	protected  function getSupplyCollect($uid){
		return 'zset:collect:supply:'.$uid;
	}
	
	
	//获取商品收藏的key
	protected function getGoodsTimes(){
		return 'zset:collect:goods';
	}
	
	protected function getGoodsCollect($uid){
		return 'zset:collect:goods:'.$uid;
	}
	
	//获取收藏类型
	protected function getCollectType($param){
	    if(!empty($param['uid'])){
    
    		switch ($param['type']){
    			case 0:
    				$hash='hash:buyoffer:';
    				$title='buyoffer:title';
    				$times=$this->getBuyOfferTimes();
    				$collect=$this->getBuyOfferCollect($param['uid']);
    				break;
    			case 1:
    				$hash='hash:product:';
    				$title="product:title";
    				$times=$this->getGoodsTimes();
    				$collect=$this->getGoodsCollect($param['uid']);
    				break;
    			case 2:
    				$hash='hash:supply:';
    				$title='supply:title';
    				$times=$this->getSupplyTimes();
    				$collect=$this->getSupplyCollect($param['uid']);
    				break;
    		}
    		return array('times'=>$times,'collect'=>$collect,'title'=>$title,'hash'=>$hash);
	    }
	}
	
	
	protected function showpage($count,$page,$pageSize=6,$filter='',$showPage=5){
		//计算总页数
		$total=ceil($count/$pageSize);
		if($total<=1)return ;
		$offset=($showPage-1)/2;
		//起始页和结束页
		$start=1;
		$end=$total;
		//分页代码
		$show='';
		if($page>=1){
			if($page==1){
				//$show.="<a href='javascript:void(0);' class='prev'><i class='icon-prev'></i>Previous Page</a>";
			}else {
				$show.="<a href='".U(ACTION_NAME)."?p=".($page-1).'&'.$filter."'class='prev'><i class='icon-prev'></i>Previous Page</a>";
			}
				
		}
	
		if($total>$showPage){
			if($page>$offset+1){
				$show.="<a href='javascript:void(0);' class='num'>……</a>";
				//$show.="……";
			}
				
			if($page>$offset){
				$start=$page-$offset;
				$end=$total>$page+$offset?$page+$offset:$total;
			}else{
				$start=1;
				$end=$total>$showPage?$showPage:$total;
			}
				
			if($page+$offset>$total){
				$start=$start-($page+$offset-$end);
			}
				
		}
		for ($i=$start;$i<=$end;$i++){
			if($i==$page){
						$show.="<a href='".U(ACTION_NAME)."?p=".$i.'&'.$filter."' class='current'>{$i}</a>";
					}else{
						$show.="<a href='".U(ACTION_NAME)."?p=".$i.'&'.$filter."' class='num'>{$i}</a>";
					}
		}
		if($total>$showPage&&$total>$page+$offset){
			$show.="<a href='javascript:void(0);' class='num'>……</a>";
			//$show.="……";
		}
	
		if($page<=$total){
			if($page+$offset<$total){
				if($end!=$total){
					$show.="<a href='".U(ACTION_NAME)."?p=".$total.'&'.$filter."' class='num'>{$total}</a>";
				}
	
			}
			if($page==$total){
				//$show.="<a href='javascript:void(0);' class='next'>Next Page<i class='icon-next'></i></a>";
			}else{
				$show.="<a href='".U(ACTION_NAME)."?p=".($page+1).'&'.$filter."' class='next'>Next Page<i class='icon-next'></i></a>";
			}
		}
		return $show;
	
	}
	
	

    /**
     * 获得某个 商品/求购 收藏数量
     * @param array $param <pre> array(
    'type' => '',
    'id' => '',
    )
     * @return boolean
     */
    public function getCount( $param ){
        $ret = 0;
        if( empty( $param['id'] ) ){
            return $ret;
        }
        $type = $this->getCollectType($param);
        $times = $type['times'];
        return $this->redis->zScore( $times, $param['id'] );
    }

    /**
     * 获取某用户是否已收藏商品/求购
     * @param array $param <pre> array(
    'uid' => '',
    'type' => '',
    'id' => '',
    )
     * @return boolean
     */
    public function getIsCollect( $param ){
        $ret = false;
        $uid = intval( $param['uid'] );
        $type = intval( $param['type'] );
        $id = intval( $param['id'] );
        if( empty( $uid ) ){
            return $ret;
        }
        if( empty( $id ) ){
            return $ret;
        }
        $type = $this->getCollectType($param);
        $collectCacheKey = $type['collect'];
        return $this->redis->zScore( $collectCacheKey, $id );
    }

    /**
     * 获得某个用户收藏 商品/求购 数量
     * @param array $param <pre> array(
    'type' => '',
    'uid' => '',
    )
     * @return boolean
     */
    public function getUserCount( $param ){
        $ret = 0;
        $uid = intval( $param['uid'] );
        $type = intval( $param['type'] );
        if( empty( $uid ) ){
            return $ret;
        }

        $type = $this->getCollectType($param);
        $times = $type['collect'];
        return intval( $this->redis->zCard( $times ) );
    }
}