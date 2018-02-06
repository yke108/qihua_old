<?php

namespace Mobile\Controller;

use Think\Controller;
use Think\Cache\Driver\Redis;

/**
 * 移动站活动会展
 * Class exhibitController
 * @package User\Controller
 */
class ExhibitController extends CommonController {
    //注册成功结果页
    public function result() {
        $this->display('register-result');
    }
    
    /**
     * 印度会展注册页面
     */
    public function in170425() {
        if (!empty($_POST)) {
            //检查token
            if ($_POST['uv_r'] != session('Send_Code')) {
                $res['msg'] = 'Sorry, you submitted before.';
                $res['code'] = '400';
                $res['data']['error'] = 'Sorry, you submitted before.';
                $res['data']['ur_v'] = mobileCache();
                $this->ajaxReturn($res);
            }
            $member = D('User/member');
            $redis = \Think\Cache::getInstance('Redis');
            $data = array();
            $dataInfo = array();
            $data['country'] = $_POST['country'];
            $data['username'] = strtolower(I('post.username'));
            $data['password'] = trim(I('post.password'));
            $data['repassword'] = trim(I('post.repassword'));
            $data['email'] = strtolower(I('post.email'));

            $data['companyName'] = trim(I('post.company'));
            $data['contact'] = trim(I('post.contact'));

            //检测邮箱存在
            if (isset($_POST['email'])) {
                $rest = $redis->exists("string:company:email:{$data['email']}");
                if ($rest) {
                    $res['msg'] = 'This Email Address has been registered already.';
                    $res['code'] = '400';
                    $res['data']['error'] = 'This Email Address has been registered already.';
                    $res['data']['ur_v'] = mobileCache();
                    $this->ajaxReturn($res);
                }
            }
            $rest = $member->checkUserNameIsRight($data['username']);
            if (!$rest) {
                $res['msg'] = 'Memeber ID already exist.';
                $res['code'] = '400';
                $res['data']['error'] = 'Username already exist.';
                $this->ajaxReturn($res);
            }

            $param['data'] = $data;
            $param['info'] = $dataInfo;

            $rules = array(
                array('country', 'require', 'country error!'),
                array('country', 'countryArea', 'country error!', 0, 'callback'),
                array('username', 'require', 'username error!'),
                array('username', 'CheckUser', 'username error!', 0, 'callback'),
                array('username', 'IsNumber', 'username error!', 0, 'callback'),
                array('username', '4,18', 'username error!', 0, 'length'),
                array('username', 'CheckUsers', 'username error!', 0, 'callback'),
                array('password', 'require', 'password error!'),
                // 正则验证密码 [需包含字母数字以及@*#中的一种,长度为6-18位]
                array('password', 'passwordRule', 'password error!', 0, 'callback'),
                array('password', '6,18', 'password error!', 0, 'length'),
                array('password', 'passwordComplexity', 'password error!', 0, 'callback'),
                array('repassword', 'password', 'repassword error!', 0, 'confirm'), // 验证确认密码是否和密码一致
                array('companyName', 'require', 'The company name error'),
                array('companyName', '2,100', 'The company name error', 0, 'length'),
                array('contact', 'require', 'The contact error'),
                array('contact', '2,50', 'The contact error', 0, 'length'),
                array('email', '3,50', 'email error', 0, 'length'),
                array('email', 'require', 'email error', 0),
                array('email', '/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/', 'email error', 0),
                array('email', '3,50', 'email error', 0, 'length'),
            );

            if ($member->validate($rules)->create()) {
                $res = $member->insertUserData($param);
                if ($res) {
                    send_mail($data['email'], "Dear {$data['username']}:", D('user/member')->certEmailContent($res, $data['username']));

                    session('Uid', $res);
                    session('memberName', $data['username']);
                    session('country', $_POST['country']);

                    $token = $res . $data['password'] . C('LOGIN_NUM')[0];
                    session('token', md5($token));

                    $authParam = array(
                        'id'   => $res,
                        'time' => time(),
                    );
                    $ret['msg'] = 'Registration success';
                    $ret['code'] = '200';
                    $ret['data']['url'] = U('Exhibit/intention');
                } else {
                    $ret['msg'] = 'Registration failure';
                    $ret['code'] = '400';
                    $ret['data']['ur_v'] = mobileCache();
                }
            } else {
                $ret['msg'] = $member->getError();
                $ret['code'] = '400';
                $ret['data']['ur_v'] = mobileCache();
            }
            $this->ajaxReturn($ret);
        } else {
            $this->assign('countryList', getAllCountry());
            $this->display('registerh5');
        }
    }

    /**
     * 添加意向
     */
    public function intention() {
        if (!empty($_POST)) {
            if ($_POST['uv_r'] != session('Send_Code')) {
                $res['msg'] = 'Sorry, you submitted before.';
                $res['code'] = '400';
                $res['data']['error'] = 'Sorry, you submitted before.';
                $res['data']['ur_v'] = mobileCache();
                $this->ajaxReturn($res);
            }
            if (empty($_POST['intention'])) {
                $res['code'] = '200';
                $res['data']['url'] = 'afterIntention';
                $this->ajaxReturn($res);
            }
            $data['intention'] = trim(I('post.intention'));
            $data['uid'] = session('Uid');
            D('User/Member')->intention($data);
            $res['code'] = '200';
            $res['data']['url'] = 'afterIntention';
            $this->ajaxReturn($res);
        } else {
            $email = D('User/Member')->CheckEmail(session('Uid'));
            if (!empty($email)) {
                $this->assign('email', $email);
            }
            $this->display('registerh5-success');
        }
    }

    /**
     * 填写完意向页面
     */
    public function afterIntention() {
        if (empty(session('Uid'))) {
            $this->redirect('mobile/exhibit/in170425');
        }
        $this->display('exit-h5');
    }

    /**
     * 法律声明
     */
    public function lawDetail() {
        $this->display('law-detail-h5');
    }

    /**
     * 注册协议
     */
    public function privacyPolicy() {
        $this->display('law-privacy-h5');
    }
}