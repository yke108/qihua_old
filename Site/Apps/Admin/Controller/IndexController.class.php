<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class IndexController extends CommonController {
    public function index(){
//        $Redis = \Think\Cache::getInstance('Redis');
//        $result = $Redis->hsetnx('website','baidu11','www.baidu.com');
//        if($result)echo 1;
//        else echo 2;
//        print_r($result);exit;
//        print_r($Redis->hGet('website','google'));
//        exit;
        $this->display();
    }
}