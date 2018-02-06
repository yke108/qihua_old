<?php
/*
 *RedisFSC,Redis fulltext search for chinese
 */
namespace Org\Net;
use      Think\Cache\Driver\Redis;
class RedisFSC{

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
        $this->nowtime = time();
        $this->keyprefix = "RFSC";
        $this->key_contentid = "{$this->keyprefix}:content:id";
        $this->key_index_prefix = "{$this->keyprefix}:index";
        $this->resultreturntype = "string";
        if(isset($redislink)){
            $this->r = $redislink;
        }else{
            $this->r = new Redis();
//            $this->r->connect('192.168.2.22');
//            $this->r->auth('keywa08181328');
//            $this->r->select(1);
        }
        //init cws here
        $this->cws = new \scws_new();
        $this->cws->set_charset('utf8');
        $multi = 8;
        $this->cws->set_duality(false);
        $this->cws->set_ignore(true);  //ignore punctuations
        $this->cws->set_multi($multi);
    }
//    function RedisFSC(){
//        $this->nowtime = time();
//        $this->keyprefix = "RFSC";
//        $this->key_contentid = "{$this->keyprefix}:content:id";
//        $this->key_index_prefix = "{$this->keyprefix}:index";
//        $this->resultreturntype = "string";
//        if(isset($redislink)){
//            $this->r = $redislink;
//        }else{
//            $this->r = new Redis();
//            $this->r->connect('192.168.2.22');
//            $this->r->auth('keywa08181328');
//            $this->r->select(1);
//        }
//        //init cws here
//        $this->cws = scws_new();
//        $this->cws->set_charset('utf8');
//        $multi = 8;
//        $this->cws->set_duality(false);
//        $this->cws->set_ignore(true);  //ignore punctuations
//        $this->cws->set_multi($multi);
//        //end
//
//    }
    
    /*
     *index
     *param $content,content to index
     *param $postid,primary key of message in another database
     */
    
    function index($content,$postid){
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
                    $this->r->zAdd("{$this->key_index_prefix}:postid:{$tmp['word']}",$postid,$postid);
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
     
    function search($key,$resultorder="desc"){
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
                    $keyarray[] = "{$this->key_index_prefix}:postid:{$tmp['word']}";
                }
            }
        }
        $randomkey = rand(0,9999);
        $this->cws->close();
        $tmpkeyname = "{$this->keyprefix}:tmp:{$this->nowtime}{$randomkey}";
	$this->r->zInter($tmpkeyname ,$keyarray);
        $data = $this->r->zRange($tmpkeyname,0,-1);
	//print_r($data);exit;
        return $this->resultreturntype=="string"?join(",",$data):$data;
    }
}
?>
