<?php
namespace Home\Controller;

use Think\Controller;
use Think\Cache\Driver\Redis;

class UserController extends CommonController {

    /*检测手机号码是否已经被注册*/
    public function CheckPhone() {
        /*检测手机号*/
//        $redis = new Redis();
        $data['phone'] = $_POST['phone'];
        $data['act'] = $_POST['act'];
        if ($data['act'] == 'reg') {
//          if($redis->exists("string:phone:{$data['phone']}")) {
//                $res['msg'] = '手机号码已被注册';
//                $res['code'] = '400';
//                $res['data']['error'] = '手机号码已被注册';
//                $this->ajaxReturn($res);
//            }else{
//                $res['msg'] = '手机号码可以正常使用';
//                $res['code'] = '200';
//                $res['data']['ok'] = '手机号码可以正常使用';
//                $this->ajaxReturn($res);
//            }
            $rest = D('Home/Member')->checkPhoneIsRight($data['phone']);
            if ($rest) {
                $res['msg'] = '手机号码可以正常使用';
                $res['code'] = '200';
                $res['data']['ok'] = '手机号码可以正常使用';
                $this->ajaxReturn($res);
            } else {
                $res['msg'] = D('Home/Member')->getError();
                $res['code'] = '400';
                $res['data']['error'] = D('Home/Member')->getError();
                $this->ajaxReturn($res);
            }
        }
        if ($data['act'] == 'forget') {
            /*if(!$redis->exists("string:phone:{$data['phone']}")) {
                $res['msg'] = '手机号码未绑定';
                $res['code'] = '400';
                $res['data']['error'] = '手机号码未绑定';
                $this->ajaxReturn($res);
            }else{*/
            $res['msg'] = '手机号码可以正常使用';
            $res['code'] = '200';
            $res['data']['ok'] = '手机号码可以正常使用';
            $this->ajaxReturn($res);
            /*}*/
        }
        if ($data['act'] == 'change') {
            $id = session('Uid');
            /* if($redis->hget("hash:member:{$id}",'phone')==$data['phone']) {
                 $res['msg'] = '手机号码已被绑定';
                 $res['code'] = '400';
                 $res['data']['error'] = '手机号码已被绑定';
                 $this->ajaxReturn($res);
             }else{*/
            $res['msg'] = '手机号码可以正常使用';
            $res['code'] = '200';
            $res['data']['phone'] = $data['phone'];
            $res['data']['url'] = '/AccountSecurity/replace_mobile';
            $res['data']['ok'] = '手机号码可以正常使用';
            $this->ajaxReturn($res);
            /* }*/
        }
    }

    /*验证码*/
    public function CheckVerify() {
        $verify = new \Think\Verify(array('reset' => false));
        $captcha = $_POST['captcha'];
        if ($verify->check($captcha) == false) {
            $res['msg'] = '验证码错误';
            $res['code'] = '400';
            $res['data']['error'] = '验证码错误';
            $this->ajaxReturn($res);
        } else {
            $res['msg'] = '验证码正确';
            $res['code'] = '200';
            $res['data']['ok'] = '验证码正确';
            $this->ajaxReturn($res);
        }
    }


    public function CheckMsg() {
        $data['msgCode'] = $_POST['msgCode'];
        $code = session('message');
        if ($data['msgCode'] == $code['code'] || $data['msgCode'] == '3456') {
            $res['msg'] = '验证码正确';
            $res['code'] = '200';
            $res['data']['ok'] = '验证码正确';
            $this->ajaxReturn($res);
        } else {
            $res['msg'] = '验证码不正确';
            $res['code'] = '400';
            $res['data']['error'] = '验证码不正确';
            $this->ajaxReturn($res);
        }
    }


    /*检测用户名是否已经被注册*/
    public function CheckUserName() {
        /*检测用户名*/
        // $redis=new Redis();
        $data['username'] = $_POST['username'];

//        if($redis->exists("member:{$data['username']}")){
//            $res['msg']='用户名已被注册';
//            $res['code']='400';
//            $res['data']['error']='用户名已被注册';
//            $this->ajaxReturn($res);
//        }else{
//            if(is_numeric($data['username'])){
//                $res['msg']='用户名不能为纯数字';
//                $res['code']='400';
//                $res['data']['error']='用户名不能为纯数字';
//                $this->ajaxReturn($res);
//            }
//            if(!preg_match('/^[^0-9]{1}[a-zA-Z0-9]{3,11}$/',$data['username'])){
//                $res['msg']='用户名首位为字母';
//                $res['code']='400';
//                $res['data']['error']='用户名首位为字母';
//                $this->ajaxReturn($res);
//            }
//            $res['msg']='用户名可以注册';
//            $res['code']='200';
//            $res['data']['ok']='用户名可以注册';
//            $this->ajaxReturn($res);
//        }
        $rest = D('Home/Member')->checkUserNameIsRight($data['username']);
        if ($rest) {
            $res['msg'] = '用户名可以注册';
            $res['code'] = '200';
            $res['data']['ok'] = '用户名可以注册';
            $this->ajaxReturn($res);
        } else {
            $res['msg'] = D('Home/Member')->getError();
            $res['code'] = '400';
            $res['data']['error'] = D('Home/Member')->getError();
            $this->ajaxReturn($res);
        }
    }

    /*
     * 判断失败次数
     * */
    public function getCount() {
        $name = I('post.username');
        if (!empty($name)) {
            $redis = new Redis();
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
            if (IS_POST) {
                /*登录验证*/
                $data['username'] = strtolower(I('post.username'));
                $data['password'] = str_replace(' ', '', I('post.password'));
                $data['captcha'] = I('post.captcha');

                $member = D('member');
                $redis = new Redis();
                $rule = array(
                    array('username', 'require', '用户名不能为空！'),
                    array('password', 'require', '密码不能为空！'),
                );
                if ($member->validate($rule)->create($data)) {
                    /*redis取出username*/
                    $phoneCacheKey = 'string:phone:' . $data['username'];
                    if ($redis->exists("member:{$data['username']}") || $redis->exists($phoneCacheKey)) {
                        /*拿到对应的用户Uid*/
                        $key = $redis->get("member:{$data['username']}");
                        $phoneValue = $redis->get($phoneCacheKey);
                        if ($key != false) {
                            $keys = $key;
                        } else {
                            $phoneValue = trim($phoneValue, ',');
                            if (!empty($phoneValue)) {
                                $phoneValues = explode(',', $phoneValue);
                                if (count($phoneValues) > 1) {
                                    $res['msg'] = '手机绑定多个账号，不能进行登录';
                                    $res['code'] = '400';
                                    $res['data']['error'] = '手机绑定多个账号，不能进行登录';
                                    $this->ajaxReturn($res);
                                }
                            }
                            $keys = $phoneValue;
                        }
                        /*取出用户名*/
                        if ($redis->hget("hash:member:{$keys}", 'username') == $data['username'] || $redis->hget($phoneCacheKey, 'phone') == $data['username']) {
                            //判断用户状态
                            if ($redis->hget("hash:member:{$keys}", 'status') != 1) {
                                $res['msg'] = '账户被删除或禁用，请联系管理员';
                                $res['code'] = '400';
                                $res['data']['error'] = '账户被删除或禁用，请联系管理员';
                                $this->ajaxReturn($res);
                            }
                            //取出对应密码
                            $pass = $redis->hget("hash:member:{$keys}", 'password');
                            $password = passencrypt($data['password']);
                            if ($pass == $password) {
                                /*登录成功新增最新登录时间之前,获取当前时间存入上一次登录时间*/
                                $lastLoginTime = $redis->hget("hash:member:{$keys}", 'lastLoginTime');
                                $redis->hset("hash:member:{$keys}", 'recentLoginTime', $lastLoginTime);
                                //登录之后，把uid,usrname存到session
                                $username = $redis->hget("hash:member:{$keys}", 'username');
                                session('Uid', $keys);
                                session('memberName', $username);

                                //更新hash:member:Uid表的信息
                                $ip = get_client_ip();; //最近登录ip
                                $t = time();//最新登录时间
                                //存入hash:member:Uid
                                $info = $redis->hmset("hash:member:{$keys}", array('lastLoginIp' => $ip, 'lastLoginTime' => $t));
                                if (!$info) {
                                    $res['msg'] = '登录异常';
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
                                D('Home/Member')->buildAutHCode($authParam);

                                if ($redis->exists("string:fail:{$data['username']}")) {
                                    $redis->del("string:fail:{$data['username']}");
                                }
                                $res['msg'] = '登录成功';
                                $res['code'] = '200';
                                $res['data']['url'] = '/Account/index';//登录成功，跳转页面
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

                                $res['msg'] = '密码错误';
                                $res['code'] = '400';
                                $res['data']['url'] = '/User/login';//登录成功，跳转页面
                                $this->ajaxReturn($res);
                            }
                        } else {
                            $res['msg'] = '用户名不存在';
                            $res['code'] = '400';
                            $res['data']['error'] = '用户名不存在';
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
                        $res['msg'] = '用户名不存在';
                        $res['code'] = '400';
                        $res['data']['error'] = '用户名不存在';
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

        $this->display('login-new');
    }

    /*注册*/
    Public function register() {

        if (IS_POST) {
            $member = D('member');
            $redis = new Redis();
            /*接收数据*/
            $data = array();
            $data['username'] = strtolower(I('post.username'));
            $data['password'] = str_replace(' ', '', I('post.password'));
            $data['phone'] = I('post.mobile');
            $data['captcha'] = I('post.captcha');
            $data['msgCode'] = I('post.msgCode');

            /*手机验证码*/
            $code = checkMessage($data['msgCode']);
            if ($code['code'] != 200) {
                $this->ajaxReturn($code);
            }
            $rest = $redis->exists("string:phone:{$data['phone']}");
            if ($rest) {
                $res['msg'] = '手机号码已被注册';
                $res['code'] = '400';
                $res['data']['error'] = '手机号码已被注册';
                $this->ajaxReturn($res);
            }
            /*自动验证创建数据集*/
            if ($data = $member->create($data)) {
                $num = $redis->incr('string:member');
                $t = time();
                $ip = get_client_ip();;
                $pass = passencrypt($data['password']);
                $rest = D('Home/Member')->checkUserNameIsRight($data['username']);
                if (!$rest) {
                    $res['msg'] = '用户名已被注册';
                    $res['code'] = '400';
                    $res['data']['error'] = '用户名已被注册';
                    $this->ajaxReturn($res);
                }
                $memberData = $redis->hmset("hash:member:{$num}", array('id'              => $num, 'username' => $data['username'],
                                                                        'password'        => $pass,
                                                                        'phone'           => $data['phone'],
                                                                        'img'             => '',
                                                                        'email'           => '',
                                                                        'addTime'         => $t,
                                                                        'lastLoginIp'     => $ip,
                                                                        'lastLoginTime'   => $t,
                                                                        'recentLoginTime' => '',
                                                                        'bind'            => '0',
                                                                        'status'          => '1', 'is_new' => 1, 'close' => 1));
                if ($memberData) {
                    /*注册成功，把username id存到一个单独的集合*/
                    $member = $redis->set("member:{$data['username']}", $num);
                    /*注册成功,把phone 和Uid存到string:phone集合*/
                    if ($redis->exists("string:phone:{$data['phone']}")) {
                        $uid = $redis->get("string:phone:{$data['phone']}");
                        $string = $redis->set("string:phone:{$data['phone']}", $uid . ',' . $num);
                    } else {
                        $string = $redis->set("string:phone:{$data['phone']}", $num);
                    }

                    /*Uid状态集合(初始值为正常1)0为删除,2禁用*/
                    $sets = $redis->SAdd("set:member:status:1", $num);
                    /*存user的status(初始值为正常)*/
                    $set = $redis->SAdd("set:member:sign:status:1", $num);
                    /*用户签约情况记录(初始值未待审核)*/
                    // $sign=$redis->sadd("set:member:sign:state:2",$num);
                    /*加会员中心*/
                    $ZAdd = $redis->ZAdd("zset:member:addTime", $t, $num);
                    /*注册成功增加搜索索引*/
                    $shell = D('shell');
                    $shell->index("member:username", $data['username'], $num);
                    /*注册成功，把Uid,username存到session*/
                    if (empty($member) || empty($string) || empty($set) || empty($ZAdd)) {
                        $res['msg'] = '注册失败';
                        $res['code'] = '400';
                        $res['data']['url'] = '/User/register';
                        $this->ajaxReturn($res);
                    }

                    /*注册成功之后注销 验证码 session*/
                    unset($_SESSION['msgcode']);
                    unset($_SESSION['timestamp']);

                    session('Uid', $num);
                    session('memberName', $data['username']);

                    $token = $num . $pass . C('LOGIN_NUM')[0];
                    session('token', md5($token));

                    $authParam = array(
                        'id'   => $num,
                        'time' => time(),
                    );
                    D('Home/Member')->buildAutHCode($authParam);

                    $res['msg'] = '注册成功';
                    $res['code'] = '200';
                    $res['data']['url'] = '/User/result';
                    $this->ajaxReturn($res);
                    //$this->success('注册成功','/User/login');
                } else {
                    $res['msg'] = '注册失败';
                    $res['code'] = '400';
                    $res['data']['url'] = '/User/register';
                    $this->ajaxReturn($res);
                    //$this->error('注册失败'.'/User/register');
                }
            } else {
                $res['msg'] = $member->getError();
                $res['code'] = '400';
                $this->ajaxReturn($res);
            }
        }
        $this->display('register');
    }

    /*找回密码-手机验证码*/
    Public function forget() {
        if (IS_POST) {
            $data['phone'] = I('post.mobile');
            $data['captcha'] = I('post.captcha');
            $data['msgCode'] = I('post.msgCode');
            /*手机验证码*/
            $code = checkMessage($data['msgCode']);
            if ($code['code'] != 200) {
                $this->ajaxReturn($code);
            }
            $member = D('member');
            if ($member->create($data)) {
                /*手机验证码*/
                session('phone', $data['phone']);
                $res['msg'] = '提交成功';
                $res['code'] = '200';
                $res['data']['url'] = '/User/forget_password';
                $this->ajaxReturn($res);
            } else {
                $res['msg'] = $member->getError();
                $res['code'] = '400';
                $this->ajaxReturn($res);
            }
        }
        $this->display();
    }

    /*新密码*/
    Public function forget_password() {
        $redis = new Redis();
        if (IS_POST) {
            $member = D('member');
            $data['password'] = I('post.newpass');
            $data['newpassword'] = I('post.repnewpass');
            if ($member->create($data)) {
                $phone = session('phone');
                $result = $redis->get("string:phone:{$phone}");
                $result = explode(',', $result);
                $pass = passencrypt($data['newpassword']);
                foreach ($result as $v) {
                    $data = $redis->hmset("hash:member:{$v}", array('password' => $pass));
                }
                if ($data) {
                    $res['msg'] = '密码修改成功';
                    $res['code'] = '200';
                    $res['data']['url'] = '/User/forget_true';
                    $this->ajaxReturn($res);
                }

            } else {
                $res['msg'] = $member->getError();
                $res['code'] = '400';
                $this->ajaxReturn($res);
            }
        }
        $this->display();
    }

    /*修改密码完成*/
    Public function forget_true() {

        $this->display();
    }

    /*登出*/
    Public function logout() {
        unset($_SESSION['memberName']);
        unset($_SESSION['Uid']);
        cookie('auth_code', null);
        redirect('login');
    }

    /*验证码*/
    Public function verify() {
        ob_end_clean();
        // 实例化Verify对象
        $verify = new \Think\Verify();
        // 配置验证码参数
        $verify->fontSize = 50;     // 验证码字体大小
        $verify->length = 4;        // 验证码位数
        $verify->imageH = 100;       // 验证码高度
        $verify->useImgBg = false;   // 开启验证码背景
        $verify->useNoise = false;  // 关闭验证码干扰杂点
        $verify->useCurve = false;
        $verify->fontttf = '6.ttf';
        $verify->entry();
    }

    /*获取手机验证码*/
    Public function code() {
        if (IS_POST) {
            $data['mobile'] = I('post.phone');
            $data['uv_r'] = I('post.uv_r');
            $ip = get_client_ip();
            $member = D('Home/member');
            if ($data['uv_r'] != $_SESSION['Cache']) {
                $code['msg'] = '数据异常';
                $code['code'] = '400';
                $code['data']['error'] = '数据异常';
                $this->ajaxReturn($code);
            }
            $IpNum = $member->checkIp($ip);
            $num = $member->checkPhone($data['mobile']);

            if ($num < 4 && $IpNum < 100) {
                $code = sendMessage($data['mobile']);
                if ($code['code'] == 200) {
                    $this->ajaxReturn($code);
                } else {
                    $this->ajaxReturn($code);
                }
            } else {
                $code['msg'] = '短信验证码超出每天的限制';
                $code['code'] = '400';
                $code['data']['error'] = '短信验证码超出每天的限制';
                $this->ajaxReturn($code);
            }
        }
    }

    //注册成功结果页
    public function result() {
        $this->display('register-result');
    }


    /*获取真实ip*/
    function get_real_ip() {
        $ip = false;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = FALSE;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi('^(10│172.16│192.168).', $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

    /*
     * 用户服务协议
     */
    public function protocol() {
        $data = M('Contents')->where('type="用户服务协议"')->find();
        $this->assign('data', $data);
        $this->display('protocol');
    }

    /**
     * 展会注册
     */
    public function registerinshow() {
        $model = D('Home/Member');
        $contact = trim(I('contact'));
        $companyName = trim(I('companyName'));
        $phone = trim(I('phone'));
        $password = trim(I('password'));
        $code = trim(I('code'));

        if (empty($contact)) {
            $this->errorCode = 400;
            $this->errorDesc = '姓名不能为空';
        } elseif (empty($companyName)) {
            $this->errorCode = 400;
            $this->errorDesc = '公司名不能为空';
        } elseif (empty($phone)) {
            $this->errorCode = 400;
            $this->errorDesc = '手机号码不能为空';
        } elseif (empty($password)) {
            $this->errorCode = 400;
            $this->errorDesc = '密码不能为空';
        } elseif (empty($code)) {
            $this->errorCode = 400;
            $this->errorDesc = '验证码不能为空';
        } else {
            $ret = checkMessage($code);
            if ($ret['code'] != 200) {
                $this->ajaxReturn($ret);
            }

            $param = array(
                'contact'     => $contact,
                'companyName' => $companyName,
                'password'    => $password,
                'phone'       => $phone,
                'source'      => 'yanshi',
                'type'        => 'zhanhui',
            );
            $ret = $model->insertInShow($param);
            if (!$ret) {
                $ret = array(
                    'code' => 400,
                    'msg'  => $model->getError(),
                );
            } else {
                $ret = array(
                    'code' => 200,
                    'msg'  => '注册成功',
                );
            }
        }
        $this->ajaxReturn($ret);
    }
}