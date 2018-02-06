<?php
namespace User\Controller;
use Think\Controller;
/**
 * 商家工作台控制器
 */

class MemberController extends CommonController{
	
	public function index(){
		$this->display('member-index');
	}
	
	//我的收藏
	public  function favorites(){
		$param['p']=I('p');
		$param['uid']=$this->uid;
		$param['type']=empty($_GET['type'])?0:intval(I('type'));
		$param['title']=I('title');
		$model=D('Collect');
		$res=$model->lists($param);
		$companyName=D('Account')->SelectAccountInfo($this->uid,array('companyName'))['companyName'];
		$this->assign('companyName',$companyName);
		$this->assign('ret',empty($res['ret'])? array() : $res['ret']);
		$this->assign('list',empty($res['list']) ? array() : $res['list']);
		$this->assign('show',empty($res['show']) ? '' : $res['show']);
		$this->display('member-collect');
	}
	
	public function sendMessage(){
		$model=D('Buyoffer');
		$model->getCount(12);
		//var_dump($res);
	}
	
	public function getSystem(){
		//$param['from']=$this->uid;
		$param['type']=1;
		$param['id']=12;
		$model=D('Collect');
		var_dump($model->getCount($param));
	}

    /**
     * 修改头像
     */
    public function avatar() {
        header("Access-Control-Allow-Origin: *");
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit;
        }
        $uid = $this->uid;
        if (empty($uid)) {
            $ret['code'] = '400';
            $ret['msg'] = '请先登录';
            $ret['data'] = NULL;
            $this->ajaxReturn($ret);
        }
        if (IS_POST) {
            $data = D('Common/Image')->upload();
            if (empty($data)) {
                $ret['code'] = '400';
                $ret['msg'] = D('Common/Image')->getUploadError();
                $ret['data'] = NULL;
            } else {
                if (!empty($data[0]['url'])) {
                    D('Home/Member')->Modify($uid, 'img', $data[0]['url']);
                    session('userHeadImg', $data[0]['url']);
                }
                $ret['code'] = '200';
                $ret['msg'] = '';
                $ret['data'] = $data;
            }
        } else {
            $ret['code'] = '400';
            $ret['msg'] = '参数异常';
            $ret['data'] = NULL;
        }
        $this->ajaxReturn($ret);
    }


    public function certifiedMail(){
        $username = I('get.username');
        $time = I('get.time');
        $sign = I('get.sign');
        if((time()-$time) > (3600 * 48) || $username == '' || $sign == ''){
            $this->redirect('/Account/bindTips');
            exit();
        }
        $redis = \Think\Cache::getInstance('Redis');
        $userId = $redis->get('member:' . $username);
        $nowSign = hash_hmac('sha1', $username . $time . $userId, strrev($username)).substr(sha1($username), 0, 24);
        if($nowSign != $sign){
            $this->redirect('/Account/bindTips');
            exit();
        }
        //通过上面的认证后，确认用户的邮箱绑定正常。
        $redis->hset('hash:member:' . $userId, 'bind', '1');
        $this->redirect('/Account/index');
    }



	
}