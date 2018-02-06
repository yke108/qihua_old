<?php
namespace User\Controller;

use Think\Controller;
use Think\Cache\Driver\Redis;

class IndexController extends CommonController {

    /*检测手机号码是否已经被注册*/
    public function CheckPhone() {
        /*检测手机号*/
        $data['phone'] = $_POST['phone'];
        $data['act'] = $_POST['act'];
        if ($data['act'] == 'reg') {
            //手机白名单
            if (D('User/Member')->IsWhitePhone($data)) {
                $res['msg'] = '手机号码可以正常使用';
                $res['code'] = '200';
                $res['data']['ok'] = '手机号码可以正常使用';
                $this->ajaxReturn($res);
            }
            $rest = D('User/Member')->checkPhoneIsRight($data['phone']);
            if ($rest == 2) {
                $res['ok'] = '手机号码可以正常使用';
                $this->ajaxReturn($res);
            } else {
                $res['error'] = 'This phone number has been registered already.';
                $this->ajaxReturn($res);
            }
        } else if ($data['act'] == 'forget') {
            $rest = D('User/Member')->checkPhoneIsRight($data['phone']);
            if ($rest == 1) {
                $res['code'] = '200';
                $res['data']['ok'] = '手机号码可以正常使用';
            } else {
                $res['code'] = '400';
                $res['data']['error'] = 'Please use the registered phone number.';
            }

            $this->ajaxReturn($res);
        }
    }

    /**
     * 图片验证码检测
     */
    public function CheckVerify() {
        $verify = new \Think\Verify(array('reset' => false));
        $captcha = $_POST['captcha'];
        if ($verify->check ( $captcha ) == false) {
            $res ['msg'] = 'Sorry! Incorrect Captcha Code Format';
            $res ['code'] = '400';
            $res ['data'] ['error'] = 'Sorry! Incorrect Captcha Code Format';
            $this->ajaxReturn ( $res );
        } else {
            $res ['msg'] = '验证码正确';
            $res ['code'] = '200';
            $res ['data'] ['ok'] = '验证码正确';
            $this->ajaxReturn ( $res );
        }
    }
    
    /**
     * 发送邮件验证码
     */
    public function sendEmail() {
        if (IS_AJAX && IS_POST) {
            $to = I('post.email');
            $ip = get_client_ip();
            $member = D('User/Member');
            $random = uniqid();
            $IpNum = $member->checkIp($ip);
            $num = $member->checkSendEmailRate($to, $random);

            if ($num >0 && $num < 4 && $IpNum < 100) {
                $code = sendEmail($to);
                $member->unsetSendEmailLock($to, $random);
                if ($code['code'] == 200) {
                    $this->ajaxReturn($code);
                } else {
                    $code['data']['error'] = $code['msg'];
                    $this->ajaxReturn($code);
                }
            } else {
                $member->unsetSendEmailLock($to, $random);
                $code['msg'] = 'Three Email Verification Codes in 30 minutes only!';
                $code['code'] = '400';
                $code['data']['error'] = 'Three Email Verification Codes in 30 minutes only!';
                $this->ajaxReturn($code);
            }
        }
    }

    /**
     * 检测邮箱存在性
     */
    public function CheckEmail() {
        $data['email'] = I('post.email');
        $rest = D('User/Member')->checkEmailIsRight($data['email']);
        $data['act'] = $_POST['act'];
        if ($data['act'] == 'reg') {
            if ($rest == 2) {
                $res['ok'] = '';
                $this->ajaxReturn($res);
            } else {
                $res['error'] = D('User/Member')->getError();
                $this->ajaxReturn($res);
            }
        } else if ($data['act'] == 'forget') {
            if ($rest == 1) {
                $res['ok'] = '';
                $this->ajaxReturn($res);
            } else {
                $res['error'] = 'Please use the registered Email Address.';;
                $this->ajaxReturn($res);
            }
        }
    }

    /*检测用户名是否已经被注册*/
    public function CheckUserName() {
        /*检测用户名*/
        $data['username'] = I('post.username');
        $rest = D('User/Member')->checkUserNameIsRight($data['username']);
        if ($rest) {
//            $res['msg'] = '用户名可以注册';
//            $res['code'] = '200';
            $res['ok'] = '用户名可以注册';
            $this->ajaxReturn($res);
        } else {
//            $res['code'] = '400';
            $res['error'] = 'This member id has been registered already.';
            $this->ajaxReturn($res);
        }
    }

    /*
     * 判断失败次数
     * */
    public function getCount() {
        $name = I('post.username');
        if (!empty($name)) {
            $redis = \Think\Cache::getInstance('Redis');
            $art = $redis->get("string:fail:{$name}");
            $res['msg'] = '';
            $res['code'] = '200';
            $res['data']['count'] = $art;
            $this->ajaxReturn($res);
        }
    }


    /*登录*/
    Public function login() {
        if (empty($_SESSION['Uid'])) {
            if (IS_AJAX && IS_POST) {
                /*登录验证*/
                $data['username'] = strtolower(I('post.username'));
                $data['password'] = trim(I('post.password'));
                $data['captcha'] = I('post.captcha');

                $member = D('member');
                $redis = \Think\Cache::getInstance('Redis');

                $rule = array(
                    array('username', 'require', 'username required！'),
                    array('password', 'require', 'password required！'),
                );
                if ($member->validate($rule)->create($data)) {
                    $failCount = $redis->get("string:fail:".$data['username']) + 0;
                    if ($failCount > 3) {
                        $verify = new \Think\Verify();
                        if (empty($data['captcha']) || $verify->check($data['captcha']) == false) {
                            $res['msg'] = 'Sorry! Incorrect Captcha Code Format';
                            $res['code'] = '400';
                            $res['data']['error'] = 'Sorry! Incorrect Captcha Code Format';
                            $this->ajaxReturn($res);
                        }
                    }
                    /*redis取出username*/
                    $phoneCacheKey = 'string:phone:' . $data['username'];
                    $emailCacheKey = "string:company:email:{$data['username']}";
                    /*拿到对应的用户Uid*/
                    if ($redis->exists("member:{$data['username']}")) {
                        $keys = $redis->get("member:{$data['username']}");
                    } else if ($redis->exists($phoneCacheKey)) {
                        $keys = $redis->get($phoneCacheKey);
                    } else if ($redis->exists($emailCacheKey)) {
                        $keys = $redis->get($emailCacheKey);
                    } else {
                        $keys = '';
                    }
                    if ($keys) {
                        /*取出用户名*/
                        if ($redis->hget("hash:member:{$keys}", 'username') === $data['username'] || $redis->hget("hash:member:{$keys}", 'phone') === $data['username'] ||
                            $redis->hget("hash:member:{$keys}", 'email') === $data['username']) {
                            //判断用户状态
                            if ($redis->hget("hash:member:{$keys}", 'status') != 1) {
                                // $res['msg'] = 'Your account has been disabled, please contact the administrator. ';
                                $res['msg'] = 'Sorry! Username does not exist.';
                                $res['code'] = '400';
                                $res['data']['error'] = 'Sorry! Username does not exist.';
                                // $res['data']['error'] = 'Your account has been disabled, please contact the administrator. ';
                                $this->ajaxReturn($res);
                            }
                            //取出对应密码
                            $passData = $redis->hmget("hash:member:{$keys}", array('password', 'salt'));
                            $salt = $passData['salt'];
                            $pass = $passData['password'];
                            $password = passencrypt($data['password'], $salt);
                            if ($pass == $password) {
                                /*登录成功新增最新登录时间之前,获取当前时间存入上一次登录时间*/
                                $lastLoginTime = $redis->hget("hash:member:{$keys}", 'lastLoginTime');
                                $redis->hset("hash:member:{$keys}", 'recentLoginTime', $lastLoginTime);
                                //登录之后，把uid,usrname存到session
                                $m = $redis->hMGet("hash:member:{$keys}", ['username', 'country', 'img']);
                                session('Uid', $keys);
                                session('memberName', $m['username']);
                                session('country', $m['country']);
                                if ($m['img']) {
                                    session('userHeadImg', $m['img']);
                                }

                                //更新hash:member:Uid表的信息
                                $ip = get_client_ip();; //最近登录ip
                                $t = time();//最新登录时间
                                //存入hash:member:Uid
                                $info = $redis->hmset("hash:member:{$keys}", array('lastLoginIp' => $ip, 'lastLoginTime' => $t));
                                if (!$info) {
                                    $res['msg'] = 'login error';
                                    $res['code'] = '400';
                                    $this->ajaxReturn($res);
                                }
                                //登录成功
                                $token = $keys . $password . C('LOGIN_NUM')[0];
                                session('token', md5($token));

                                $authParam = array(
                                    'id'   => $keys,
                                    'time' => time(),
                                );
                                D('User/Member')->buildAutHCode($authParam);

                                if ($redis->exists("string:fail:{$data['username']}")) {
                                    $redis->del("string:fail:{$data['username']}");
                                }
                                $res['msg'] = '登录成功';
                                $res['code'] = '200';
                                $res['data']['url'] = '/User/Account/index';//登录成功，跳转页面
                                $this->ajaxReturn($res);
                            } else {
                                //加一个错误次数记录
                                if (!$redis->exists("string:fail:{$data['username']}")) {
                                    $redis->set("string:fail:{$data['username']}", 1);
                                    $redis->expire("string:fail:{$data['username']}", 60 * 60);
                                } else {
                                    $str = $redis->get("string:fail:{$data['username']}");
                                    $redis->set("string:fail:{$data['username']}", $str + 1);
                                    $redis->expire("string:fail:{$data['username']}", 60 * 60);
                                }

                                $res['msg'] = 'Sorry! Incorrect Password.';
                                $res['code'] = '400';
                                $art = $redis->get("string:fail:{$data['username']}");
                                $res['data']['count'] = $art + 0;
                                $res['data']['url'] = '/User/index/login';//登录成功，跳转页面
                                $this->ajaxReturn($res);
                            }
                        } else {
                            $res['msg'] = 'Sorry! Username does not exist.';
                            $res['code'] = '400';
                            $res['data']['error'] = 'Sorry! Username does not exist.';
                            $this->ajaxReturn($res);
                        }
                    } else {
                        //登录失败
                        if (!$redis->exists("string:fail:{$data['username']}")) {
                            $redis->set("string:fail:{$data['username']}", 1);
                            $redis->expire("string:fail:{$data['username']}", 60 * 60);
                        } else {
                            $str = $redis->get("string:fail:{$data['username']}");
                            $redis->set("string:fail:{$data['username']}", $str + 1);
                            $redis->expire("string:fail:{$data['username']}", 60 * 60);
                        }
                        $res['msg'] = 'Sorry! Username or Password incorrect.';
                        $res['code'] = '400';
                        $res['data']['error'] = 'Sorry! Username or Password incorrect.';
                        $this->ajaxReturn($res);
                    }
                } else {
                    $res['msg'] = $member->getError();
                    $res['code'] = '400';
                    $res['data']['error'] = $member->getError();
                    $this->ajaxReturn($res);
                }
            }
        } else {
            /*session直接登录到首页*/
            $this->redirect('Account/index');
        }

        $this->display('login');
    }

    /*注册*/
    Public function register() {
        if (IS_AJAX && IS_POST) { 
            $verify = new \Think\Verify(array('reset' => true));
            $captcha = $_POST['imgcode'];
            if ($verify->check($captcha) == false) {
                $res['msg'] = 'Sorry! Incorrect Captcha Code Format';
                $res['code'] = '400';
                $res['data']['error'] = 'Sorry! Incorrect Captcha Code Format';
                $this->ajaxReturn($res);
            }
            $member = D('member');
            $redis = \Think\Cache::getInstance('Redis');
            /*接收数据*/
            $data = array();
            $data['country'] = $_POST['country'];     //I('post.country'); I方法, 值为AF,直接过滤??
            $data['username'] = strtolower(I('post.username'));
            $data['password'] = trim(I('post.password'));
            $data['repassword'] = trim(I('post.repassword'));
            
            if (isset($_POST['phone'])) {
                $data['phone'] = I('post.phone');
            }
            if (isset($_POST['email'])) {
                $data['email'] = strtolower(I('post.email'));
            }
            if (isset($_POST['captcha'])) {
                $data['captcha'] = I('post.captcha');
            }
            $data['msgCode'] = I('post.msgCode');

            //检测手机存在
            if (isset($_POST['phone'])) {
                if (!D('User/Member')->IsWhitePhone($data)) {   //手机白名单
                    $rest = $redis->exists("string:phone:{$data['phone']}");
                    if ($rest) {
                        $res['msg'] = 'This Phone has been registered already.';
                        $res['code'] = '400';
                        $res['data']['error'] = 'This Phone has been registered already.';
                        $this->ajaxReturn($res);
                    }
                }
            }
            //检测邮箱存在
            if (isset($_POST['email'])) {
                $rest = $redis->exists("string:company:email:{$data['email']}");
                if ($rest) {
                    $res['msg'] = 'This Email Address has been registered already.';
                    $res['code'] = '400';
                    $res['data']['error'] = 'This Email Address has been registered already.';
                    $this->ajaxReturn($res);
                }
            }
            //手机或邮箱验证
            if (!empty($_POST['country']) && $_POST['country'] == 'CN') {
                /*手机验证码*/
                $code = checkMessage($data['msgCode']);
            } else {
                //邮箱验证码
                $code = checkEmail($data['msgCode']);
            }
            if ($code['code'] == 400) {
                $this->ajaxReturn($code);
            }

            /*自动验证创建数据集*/
            if ($data = $member->create($data)) {
                $num = $redis->incr('string:member');
                $t = time();
                $ip = get_client_ip();;
                $salt = rand(1000, 9999);
                $pass = passencrypt($data['password'], $salt);
                $rest = D('User/Member')->checkUserNameIsRight($data['username']);
                if (!$rest) {
                    $res['msg'] = 'Username already exist.';
                    $res['code'] = '400';
                    $res['data']['error'] = 'Username already exist.';
                    $this->ajaxReturn($res);
                }
                $redis->multi(\Redis::PIPELINE);
                $source = 'pc';
                $type = 'normal';
                $redis->hmset("hash:member:{$num}", $a = array('id'              => $num,
                                                               'username'        => $data['username'],
                                                               'password'        => $pass,
                                                               'country'         => $_POST['country'],
                                                               'salt'            => $salt,
                                                               'phone'           => isset($data['phone']) ? $data['phone'] : '',
                                                               'img'             => '',
                                                               'email'           => isset($data['email']) ? $data['email'] : '',
                                                               'bind'            => isset($data['phone']) ? C('STATUS_BIND')['BIND_PHONE'] : (isset($data['email']) ? C('STATUS_BIND')['BIND_EMAIL'] : 0),
                                                               'addTime'         => $t,
                                                               'lastLoginIp'     => $ip,
                                                               'lastLoginTime'   => $t,
                                                               'recentLoginTime' => '',
                                                               'status'          => '1', 'is_new' => 1, 'close' => 1, 'isFirstLogin' => '1', 'source' => $source, 'type' => $type));
                /*注册成功，把username id存到一个单独的集合*/
                $redis->set("member:{$data['username']}", $num);
                /*注册成功,把phone 和Uid存到string:phone集合*/
                //当用户选择国家为中国时,才有手机号码
                if (isset($data['phone'])) {
                    $redis->set("string:phone:{$data['phone']}", $num);
                }
                //当用户选择除中国之外的国家时,才填写邮箱
                if (isset($data['email'])) {
                    $redis->set("string:company:email:{$data['email']}", $num);
                }


                /*Uid状态集合(初始值为正常1)0为删除,2禁用*/
                $redis->SAdd("set:member:status:1", $num);
                /*存user的status(初始值为正常)*/
                $redis->SAdd("set:member:sign:status:1", $num);
                /*用户签约情况记录(初始值未待审核)*/
                // $sign=$redis->sadd("set:member:sign:state:2",$num);
                /*加会员中心*/
                $redis->ZAdd("zset:member:addTime", $t, $num);
                $event = $redis->exec();
                /*注册成功增加搜索索引*/
                $shell = D('shell');
                $shell->index("member:username", $data['username'], $num);
                /*注册成功，把Uid,username存到session*/
                if (!$event) {
                    $res['msg'] = 'Registration failure';
                    $res['code'] = '400';
                    $res['data']['url'] = '/User/index/register';
                    $this->ajaxReturn($res);
                }

                /*注册成功之后注销验证码 session*/
                session('message', null);
                session('sendEmail', null);
                unset($_SESSION['timestamp']);

                session('Uid', $num);
                session('memberName', $data['username']);
                session('country', $_POST['country']);

                $token = $num . $pass . C('LOGIN_NUM')[0];
                session('token', md5($token));

                $authParam = array(
                    'id'   => $num,
                    'time' => time(),
                );
                D('User/Member')->buildAutHCode($authParam);

                $cacheKey = D( 'Home/Member' )->getRegisterSourceCacheKey( $source );
                $redis->SAdd( $cacheKey, $num );
                $cacheKey = D( 'Home/Member' )->getRegisterTypeCacheKey( $type );
                $redis->SAdd( $cacheKey, $num );

                $res['msg'] = 'registration succeeds';
                $res['code'] = '200';
                $res['data']['url'] = '/User/index/result';
                $this->ajaxReturn($res);
            } else {
                $res['msg'] = $member->getError();
                $res['code'] = '400';
                $this->ajaxReturn($res);
            }
        }
        $this->assign('countryList', getAllCountry());
        $this->display('register');
    }

    /**
     * 检测短信|邮箱验证码
     */
    public function CheckMsg() {
        $data['msgCode'] = $_POST['msgCode'];
        if (!empty($_POST['country']) && $_POST['country'] == 'CN') {
            /*手机验证码*/
            $code = checkMessage($data['msgCode']);
        } else {
            //邮箱验证码
            $code = checkEmail($data['msgCode']);
        }
        $this->ajaxReturn($code);
    }

    /**
     * 忘记密码第一步
     */
    Public function forgetPasswordStep() {
        if (IS_AJAX && IS_POST) {
            $sessForget = [];       //存SESSION,当第二步的凭证
            if (!empty($_POST['phone'])) {
                $data['phone'] = I('post.phone');
                $sessForget = [1, $data['phone']];
            }
            if (!empty($_POST['email'])) {
                $data['email'] = I('post.email');
                $sessForget = [2, $data['email']];
            }
            $data['captcha'] = I('post.captcha');
            $data['msgCode'] = I('post.msgCode');
            //手机和EMAIL不能同时为空
            if (empty($data['phone']) && empty($data['email'])) {
                $res['msg'] = 'parameter error';
                $res['code'] = '400';
                $this->ajaxReturn($res);
            }
            //图形验证码
            $verify = new \Think\Verify();
            if (!$verify->check($data['captcha'])) {
                $res['msg'] = 'Incorrect Captcha Code.';
                $res['code'] = '400';
                $this->ajaxReturn($res);
            }
            if (!empty($_POST['phone'])) {
                /*手机验证码*/
                $code = checkMessage($data['msgCode']);
            } else {
                //邮箱验证码
                $code = checkEmail($data['msgCode']);
            }
            if ($code['code'] != 200) {
                $this->ajaxReturn($code);
            }
            $member = D('member');
            if ($member->create($data)) {
                /*手机验证码*/
                session('forget-validation', $sessForget);
                $res['msg'] = 'success';
                $res['code'] = '200';
                $res['data']['url'] = '/User/index/forgetPasswordStep2';
                $this->ajaxReturn($res);
            } else {
                $res['msg'] = $member->getError();
                $res['code'] = '400';
                $this->ajaxReturn($res);
            }
        }
        $this->display('forget-password');
    }

    /**
     * 忘记密码第二步
     */
    Public function forgetPasswordStep2() {
        $sess = session('forget-validation');
        if (empty($sess)) {
            $this->redirect('User/index/forgetPasswordStep');
        }
        $redis = \Think\Cache::getInstance('Redis');
        if (IS_AJAX && IS_POST) {
            $member = D('member');
            $data['password'] = I('post.newpass');
            $data['repassword'] = I('post.repass');

            if ($member->create($data)) {
                if ($sess[0] == 1) {
                    $uid = $redis->get("string:phone:{$sess[1]}");
                } else {
                    $uid = $redis->get("string:company:email:{$sess[1]}");
                }
                $salt = $redis->hGet('hash:member:' . $uid, 'salt');
                $pass = passencrypt($data['repassword'], $salt);
                $data = $redis->hMSet("hash:member:{$uid}", ['password' => $pass]);
                if ($data) {
                    session('forget-validation', 2);
                    $res['msg'] = 'success';
                    $res['code'] = '200';
                    $res['data']['url'] = '/User/index/forgetPasswordStep3';
                    $this->ajaxReturn($res);
                }
            } else {
                $res['msg'] = $member->getError();
                $res['code'] = '400';
                $this->ajaxReturn($res);
            }
        }
        $this->display('forget-password2');
    }

    /**
     * 忘记密码第三步
     */
    Public function forgetPasswordStep3() {
        $sess = session('forget-validation');
        if (!$sess || $sess != 2) {
            $this->redirect('User/index/forgetPasswordStep');
        }
        session('forget-validation', null);
        $this->display('forget-password3');
    }

    /*登出*/
    Public function logout() {
        unset($_SESSION['memberName']);
        unset($_SESSION['Uid']);
        unset($_SESSION['userHeadImg']);
        cookie('auth_code', null);
        redirect('login');
    }

    /*验证码*/
    Public function verify() {
        ob_end_clean();
        // 实例化Verify对象
        $verify = new \Think\Verify();
        // 配置验证码参数
        $verify->fontSize = 30;     // 验证码字体大小
        $verify->length = 4;        // 验证码位数
        $verify->imageH = 60;       // 验证码高度
        $verify->imageW = 480;       // 验证码宽度
        $verify->useImgBg = false;   // 开启验证码背景
        $verify->useNoise = false;  // 关闭验证码干扰杂点
        $verify->useCurve = false;
        $verify->fontttf = '6.ttf';
        $verify->entry();
    }

    /*获取手机验证码*/
    Public function sendSms() {
        if (IS_AJAX && IS_POST) {
            $data['mobile'] = I('post.phone');
            $data['act'] = I('post.act');
            $data['uv_r'] = I('post.uv_r');
            $ip = get_client_ip();
            $member = D('User/Member');
            if (empty($_SESSION['Send_Code']) || $data['uv_r'] != $_SESSION['Send_Code']) {
                $code['msg'] = 'parameter error';
                $code['code'] = '400';
                $code['data']['error'] = 'parameter error';
                $this->ajaxReturn($code);
            }
            $IpNum = $member->checkIp($ip);
            $num = $member->checkPhone($data['mobile']);

            if ($num < 4 && $IpNum < 100) {
                $code = sendMessage($data['mobile']);
                if ($code['code'] == 200) {
                    $code['data']['uv_r'] = mobileCache();      //每次成功后需要更新,防止并发
                    $this->ajaxReturn($code);
                } else {
                    $this->ajaxReturn($code);
                }
            } else {
                $code['msg'] = 'Three SMS Verification Codes a day only!';
                $code['code'] = '400';
                $code['data']['error'] = 'Three SMS Verification Codes a day only!';
                $this->ajaxReturn($code);
            }
        }
    }

    //注册成功结果页
    public function result() {
        $this->display('register-result');
    }

    public function test() {
        var_dump(PHP_VERSION, function_exists('getallheaders'), PHP_SAPI);
        exit();
    }


    /**
     * 激活邮箱绑定的账号
     */
    public function certifiedMail(){
        $username = I('get.username');
        $time = I('get.time');
        $sign = I('get.sign');
        $redis = \Think\Cache::getInstance('Redis');
        $userId = $redis->get('member:' . $username);
        $bind = $redis->hget('hash:member:'. $userId, 'bind');
        $email = $redis->hget('hash:member:'. $userId, 'email');
        $validTime = 3600*48;
        if(isset($_SESSION['Uid'])){
            //系统提示邮件URL失效，在登陆状态页引导用户进入会员中心
            if(((time()-$time) > $validTime || $username == '' || $sign == '') && ($bind & C('STATUS_BIND')['BIND_EMAIL'])){
                $content['0'] = "Sorry! the validation link failed.<br>You have logined the keywa website.";
                $content['1'] = "Member Center >>";
                $url = U('User/Index/login');
                $this->assign('content',$content);
                $this->assign('url',$url);
                $this->display('bindTips');
                exit();
            }
            //系统提示邮箱已验证过，在登陆状态页引导用户进入会员中心
            if ($bind & C('STATUS_BIND')['BIND_EMAIL']) {
                $content['0'] = "Sorry! your registered email had been validated. <br>You have logined the keywa website.";
                $content['1'] = "Member Center >>";
                $url = U('User/Index/login');
                $this->assign('content',$content);
                $this->assign('url',$url);
                $this->display('bindTips');
                exit();
            }
            //系统提示邮件URL已失效，在登陆状态页引导点击触发邮件来验证
            if(((time()-$time) > $validTime || $username == '' || $sign == '') && !($bind & C('STATUS_BIND')['BIND_EMAIL'])){
                $content['0'] = "Sorry! the validation link failed.<br>You need to validate your registered email.<br>Please sell a confirmation mail to your mailbox,and activate it.";
                $content['1'] = "Send mail again>>";
                $url = U('User/Index/login');
                $this->assign('content',$content);
                $this->assign('url',$url);
                $this->display('bindTips');
                exit();
            }

            $nowSign = hash_hmac('sha1', $username . $time . $userId, strrev($username)).substr(sha1($username), 0, 24);
            if($nowSign !== $sign){
                $content['0'] = "Sorry! the validation link failed.<br>You have logined the keywa website.";
                $content['1'] = "Member Center >>";
                $url = U('User/Index/login');
                $this->assign('content',$content);
                $this->assign('url',$url);
                $this->display('bindTips');
                exit();
            }
            //系统恭喜邮件验证成功！在登陆状态页引导用户进入会员中心
            $content['0'] = "Congratulations to you to validate your registered email.<br>You have logined the keywa website.";
            $content['1'] = "Member Center >>";
            $url = U('User/Index/login');
            $newBind = $bind | C('STATUS_BIND')['BIND_EMAIL'];
            //通过上面的认证后，确认用户的邮箱绑定正常。
            $redis->hset('hash:member:' . $userId, 'bind', $newBind);
            $this->assign('content',$content);
            $this->assign('url',$url);
            $this->display('bindTips');
            exit();
        }else{
            $this->assign('notLogin','1');
            //系统提示邮件URL失效，非登陆状态页面引导用户登陆
            if(((time()-$time) > $validTime || $username == '' || $sign == '') && ($bind & C('STATUS_BIND')['BIND_EMAIL'])){
                $content['0'] = "Sorry! the validation link failed.<br>You can sign in the keywa website now.";
                $content['1'] = "Sign in keywa>>";
                $url = U('User/Index/login');
                $this->assign('content',$content);
                $this->assign('url',$url);
                $this->display('bindTips');
                exit();
            }

            //系统提示邮件已验证过，非登陆状态页面引导用户登陆
            if ($bind & C('STATUS_BIND')['BIND_EMAIL']) {
                $content['0'] = "Sorry! your registered email had been validated. <br>You can sign in the keywa website now.";
                $content['1'] = "Sign in keywa>>";
                $url = U('User/Index/login');
                $this->assign('content',$content);
                $this->assign('url',$url);
                $this->display('bindTips');
                exit();
            }

            //系统提示邮件URL已失效，在登陆状态页引导点击触发邮件来验证
            if(((time()-$time) > $validTime || $username == '' || $sign == '') && !($bind & C('STATUS_BIND')['BIND_EMAIL'])){
                $content['0'] = "Sorry! the validation link failed.<br>You need to sign in the keywa website,and validate your registered email.";
                $content['1'] = "Sign in keywa>>";
                $url = U('User/Index/login');
                $this->assign('content',$content);
                $this->assign('url',$url);
                $this->display('bindTips');
                exit();
            }

            //系统恭喜邮件验证成功！在非登陆状态页引导用户登陆
            $nowSign = hash_hmac('sha1', $username . $time . $userId, strrev($username)).substr(sha1($username), 0, 24);
            if($nowSign !== $sign){
                $content['0'] = "Sorry! the validation link failed.<br>You have logined the keywa website.";
                $content['1'] = "Member Center >>";
                $url = U('User/Index/login');
                $this->assign('content',$content);
                $this->assign('url',$url);
                $this->display('bindTips');
                exit();
            }
            $content['0'] = "Congratulations to you to validate your registered email.<br>You can sign in the keywa website now.";
            $content['1'] = "Sign in keywa>>";
            $url = U('User/Index/login');
            $newBind = $bind | C('STATUS_BIND')['BIND_EMAIL'];
            //通过上面的认证后，确认用户的邮箱绑定正常。
            $redis->hset('hash:member:' . $userId, 'bind', $newBind);
            $this->assign('content',$content);
            $this->assign('url',$url);
            $this->display('bindTips');
        }
    }
    


}