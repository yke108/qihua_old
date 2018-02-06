<?php
namespace Home\Controller;

use Think\Controller;

class ContentController extends CommonController {

    //关于我们-联系我们
    public function contact() {
        $contentsObj = D('Contents');
        //佛山总公司
        $this->assign('fs', $contentsObj->getContact(1));
        //广州分公司
        $this->assign('gz', $contentsObj->getContact(2));
        //供应商合作
        $this->assign('p', $contentsObj->getCooperation(1));
        //采购合作
        $this->assign('b', $contentsObj->getCooperation(2));
        //品牌推广
        $this->assign('e', $contentsObj->getCooperation(3));
        //投资洽谈
        $this->assign('i', $contentsObj->getCooperation(4));
        //客户服务
        $this->assign('k', $contentsObj->getCooperation(5));
        $this->assign( 'cate', getcategory() );
        $this->display('about-contact');
    }

    //关于我们--网站公告
    public function notice() {
        //取出数据
        $con = D('Contents');
        $res = $con->notice();
        $this->assign('show', $res[0]);
        $this->assign('list', $res[1]);
        $this->assign('cate', getcategory());
        $this->display('about-notice');
    }

    //网站公告详情页
    public function noticedetails() {
        if (!$_GET) exit;
        $id = I('get.id');
        $con = D('Contents');
        $res = $con->noticedetails($id);
        $this->assign('details', $res[0]);
        $this->assign('pre', $res[1]);
        $this->assign('next', $res[2]);
        $this->assign('cate', getcategory());
        $this->display('about-notice-details');
    }

    //法律声明
    public function legal() {
        $contentsObj = D('Contents');
        $this->assign('legal', $contentsObj->getLegalStatement());
        $this->assign( 'cate', getcategory() );
        $this->display('about-legal');
    }

    public function protocol() {
        $contentsObj = D('Contents');
        $this->assign('protocol', $contentsObj->getProtocol());
        $this->assign( 'cate', getcategory() );
        $this->display('about-protocol');
    }

    //新闻报道
    public function news() {
        $contentsObj = D('Contents');
        $sortParams = array(
//            'limit' => [($page - 1) * $pagesize, $pagesize],
            'get' => array('hash:aboutUs:mediaReport:*->id', 'hash:aboutUs:mediaReport:*->title',
                'hash:aboutUs:mediaReport:*->img', 'hash:aboutUs:mediaReport:*->reportDate',
                'hash:aboutUs:mediaReport:*->referer', 'hash:aboutUs:mediaReport:*->content',
            ),
            'sort' => 'desc',
//            'alpha' => true,
            'by' => 'hash:aboutUs:mediaReport:*->id',
        );
        $rs = $contentsObj->getMediaReportList($sortParams);
        $this->assign('newsList', empty($rs['rows']) ? [] : $rs['rows']);
        $this->assign('cate', getcategory());
        $this->display('about-news');
    }

    //新闻报道详情
    public function newsdetails() {
        if (!$_GET) exit;
        $id = I('get.id') + 0;
        $contentsObj = D('Contents');
        $this->assign('news', $r = $contentsObj->getMediaReport($id));
//        $this->assign('pre', $res[1]);
//        $this->assign('next', $res[2]);
        $this->assign('cate', getcategory());
        $this->display('about-news-details');
    }

    //平台简介
    public function description() {
        $contentsObj = D('Contents');
        $this->assign('desc', $contentsObj->getDescription());
        $this->assign( 'cate', getcategory() );
        $this->display('about-description');
    }

}
