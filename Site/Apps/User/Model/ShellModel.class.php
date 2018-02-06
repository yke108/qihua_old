<?php
namespace User\Model;
use Think\Model;
use Think\Cache\Driver\Redis;

class ShellModel extends Model{

	var $notime; //currenttime
	var $cws;   //chinese word spliter
	var $key_prefix;  //key prefix for the whole search engine
	var $key_contentid;   //key for {contentid} set
	var $key_index_prefix;  //key prefix for the index
	var $r; //redis link
	var $resultreturntype;

	/*
     *RedisFSC,constructor
     */
	public function __construct() {
	    $this->autoCheckFields = false;
		$this->nowtime = time();
		$this->keyprefix = "RFSC";
		$this->key_contentid = "{$this->keyprefix}:content:id";
		$this->key_index_prefix = "{$this->keyprefix}:index";
		$this->resultreturntype = "string";
		if(isset($redislink)){
			$this->r = $redislink;
		}else{
			$this->r = new Redis();
		}
		//init cws here
		import('Vendor.scws.PSCWS4');
		$this->cws = new \PSCWS4();

		$this->cws->set_dict(VENDOR_PATH.'scws/lib/dict.utf8.xdb');
		$this->cws->set_rule(VENDOR_PATH.'scws/lib/rules.utf8.ini');

		$multi = 8;
		$this->cws->set_duality(false);
		$this->cws->set_ignore(true);  //ignore punctuations
		$this->cws->set_multi($multi);
	}

	/*
     *index
     *param $content,content to index
     *param $postid,primary key of message in another database
     */

	function index($type,$content,$postid){
		if(empty($content)){
			return true;
		}
		$this->cws->send_text($content);
		while ($res = $this->cws->get_result()){
			foreach ($res as $tmp)
			{
				if ($tmp['len'] == 1 && $tmp['word'] == "\r"){
					continue;
				}elseif ($tmp['len'] == 1 && $tmp['word'] == "\n"){
					continue;
				}else{
					$this->r->zAdd("{$this->key_index_prefix}:{$type}:postid:{$tmp['word']}",$postid,$postid);
				}
			}
		}
		return true;
	}

	/*
     *search
     *param $key,keyword to search
     *param $resultorder,order of search result 'desc' or 'asc'
     */

	function search($type,$key,$result="array",$resultorder="desc"){
		if(empty($key)){
			return true;
		}
		$this->cws->send_text($key);
		$keyarray = array();
		while ($res = $this->cws->get_result()){
			foreach ($res as $tmp)
			{
				if ($tmp['len'] == 1 && $tmp['word'] == "\r"){
					continue;
				}elseif ($tmp['len'] == 1 && $tmp['word'] == "\n"){
					continue;
				}else{
					$keyarray[] = "{$this->key_index_prefix}:{$type}:postid:{$tmp['word']}";
				}
			}
		}
		$randomkey = rand(0,9999);
		$this->cws->close();
		$tmpkeyname = "{$this->keyprefix}:tmp:{$type}:{$this->nowtime}{$randomkey}";
		$this->r->zInter($tmpkeyname ,$keyarray);
		if($result=="set")return $tmpkeyname;
		else $data = $this->r->zRange($tmpkeyname,0,-1);
		//print_r($data);exit;
		return $this->resultreturntype=="string"?join(",",$data):$data;
	}
}