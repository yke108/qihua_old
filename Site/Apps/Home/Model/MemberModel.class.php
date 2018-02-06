<?php
namespace Home\Model;
use Think\Model;
use App\Admin\Model\UserModel;

class MemberModel extends Model{
    protected $autoCheckFields = false;

    protected $_validate = array(
        array('username', 'require', '用户名不能为空！'),
        array('username', 'CheckUser', '用户名由字母和数字组成!', 0,'callback'),
        array('username', 'IsNumber', '用户名不能为纯数字', 0,'callback'),
        array('username', '6,18', '用户名长度必须为6-18!', 0,'length'),
        array('username', 'CheckUsers', '用户名首位必须为字母!', 0,'callback'),
        array('password','require','密码不能为空！'),
        // 正则验证密码 [需包含字母数字以及@*#中的一种,长度为6-18位]
        array('password', 'passwordRule', '密码格式不正确！', 0 ,'callback'),
        array('password', '6,18', '密码长度必须为6-18!', 0,'length'),
        array('password', 'passwordComplexity', '密码必须由字母、数字、特殊符号中的任意两种组成!', 0 ,'callback'),
        array('newpassword', 'password', '密码不一致', 0, 'confirm'), // 验证确认密码是否和密码一致
        array('phone','require','手机号码不能为空！'),
        array('phone', '/^1[34578]\d{9}$/', '手机号码格式不正确', 0), // 正则表达式验证手机号码
        array('email','require','邮箱不能为空',0),
        array('email','/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/','邮箱格式不对',0),
        array('captcha','require','验证码必须填写'),
        array('captcha','checkverify','验证码错误',0,'callback'),
    );

    /*
     * 只能包含字母数字
     * */
    public function CheckUser($username){
        $pattern = '/[a-zA-Z0-9]$/';
        $ret=preg_match($pattern,$username);
        return $ret;
    }

    //用户名不能为纯数字
    public function IsNumber($username){
        if(is_numeric($username)){
            return false;
        }
    }

    /*
     * 用户名以字母开头
     * */
    public function CheckUsers($username){
        $pattern = '/^[^0-9]{1}[a-zA-Z0-9]{5,17}$/';
        $ret=preg_match($pattern,$username);
        return (bool)$ret;
    }

    //密码验证规则
    public function passwordRule($password){
        $pattern =  "/[a-zA-Z0-9\`\~\!\@\#\$\%\^\&\*\(\)\-\=\_\+\[\]\{\}|;\:\"\,\.\/\<\>\?]$/";
        $ret=preg_match($pattern,$password);
        return (bool)$ret;
    }

    public function passwordComplexity($password){
        //必须包含字母，数字，符号中的两种
        $pattern = '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{6,18}$/';
        $ret=preg_match($pattern,$password);
        return (bool)$ret;
    }
    /*获取某一字段信息*/
    public function getOneFiled($id,$filed){
        $redis= \Think\Cache::getInstance('Redis');
        $phone=$redis->hget("hash:member:{$id}",$filed);
        return $phone;
    }

    public function getString($phone){
    $redis= \Think\Cache::getInstance('Redis');
    $str=$redis->get("string:phone:{$phone}");
    return $str;
}

    //获取邮箱发邮件的次数
    public function getCount($email){
        $redis= \Think\Cache::getInstance('Redis');
        $arr=$redis->get("string:email:{$email}");
        return $arr;
    }

    //添加发邮件的次数
    public function setEmail($email,$count){
        $redis= \Think\Cache::getInstance('Redis');
        $arr=$redis->set("string:email:{$email}",$count);
        $redis->expire("string:email:{$email}",86400);
        return $arr;
    }

    /*删除键*/
    public function del($key){
        $redis= \Think\Cache::getInstance('Redis');
        $res=$redis->del($key);
        return $res;
    }

    /*新建一个键 string:phone:*/
    public function setPhone($string,$id){
        $redis= \Think\Cache::getInstance('Redis');
        $res=$redis->set($string,$id);
        return $res;
    }
    /*取出密码*/
    public function CheckPass($id){
        $redis= \Think\Cache::getInstance('Redis');
        if($id){
            $RedisPass=$redis->hGet("hash:member:{$id}",'password');
        }

        return $RedisPass;
    }

    /*取出邮箱*/
    public function CheckEmail($id){
        $redis= \Think\Cache::getInstance('Redis');
        if($id){
            $RedisEmail=$redis->hGet("hash:member:{$id}",'email');
        }

        return $RedisEmail;
    }

    /*
     * $id  //用户id
     * $filed  //集合filed
     * $value  //集合value
     * 修改字段信息*/
    Public function Modify($id,$filed,$value){
        $redis= \Think\Cache::getInstance('Redis');
        if($id){
            $oldData = $this->detail( array( 'id' => $id ) );
            $result = $redis->hset("hash:member:{$id}",$filed,$value);
            if( $filed == 'email' ){
                $cacheKey = $this->getMemberEmailCacheKey( $value );
                $redis->set( $cacheKey, $id );
                $cacheKey = $this->getMemberEmailCacheKey( $oldData['email'] );
                $redis->del( $cacheKey );
            }
        }

        return $result;
    }


    public function setFields( $id,$filed,$value ){
        $redis= \Think\Cache::getInstance('Redis');
        if(!empty( $id )){
            $result = $redis->hmset("hash:member:{$id}",array($filed=>$value));
        }
        return $result;
    }

    /*
     * 判断商户的状态
     * $id   //用户id
     *  $type //状态
     */
    public function CheckAuth($id,$type){
      $redis= \Think\Cache::getInstance('Redis');
        if($id){
            $res=$redis->sismember("set:member:sign:status:{$type}",$id);
        }
        return $res;
    }

   /*获取字段信息*/
    public function CheckCompanyAuth($id,$filed){
        $redis= \Think\Cache::getInstance('Redis');
        if($id){
            $res=$redis->hget("hash:member:info:{$id}",$filed);
        }
        return $res;
    }

    /*
      * $id   //用户id
      *  $type //状态
      *判断是否为签约商户
      */
    public function CheckSign($id,$type){
        $redis= \Think\Cache::getInstance('Redis');
        if($id){
            $res=$redis->sismember("set:member:sign:state:{$type}",$id);
        }
        return $res;
    }

    /*检查验证码*/
    protected function checkverify($captcha){
        $verify=new \Think\Verify();
        if(!$verify->check($captcha)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 获取详情
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return array
     */
    public function detail( $param ){
        $ret 	= array();
        $id     = intval( $param['id'] );
        if( empty( $id ) ){
            return $ret;
        }
        $redis =  \Think\Cache::getInstance('Redis');
        $cacheKey = $this->getDetailCacheKey( $id );
        $data = $redis->hgetall( $cacheKey );
        if( !empty( $data ) ){

        }
        $ret = $data;
        return $ret;
    }

    /**
     * 获取详情缓存 Cachekey
     * @param int $id D
     * @return string
     */
    protected function getDetailCacheKey( $id ){
        return 'hash:member:'.$id;
    }

    /**
     * 获取用户其它信息
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return array
     */
    public function getMemberInfo( $param ){
        $ret 	= array();
        $id 	= intval( $param['id'] );
        if( empty( $id ) ){
            return $ret;
        }
        $redis =  \Think\Cache::getInstance('Redis');
        $cacheKey = $this->getMemberInfoCacheKey( $id );
        $data = $redis->hgetall( $cacheKey );
        if( !empty( $data ) ){

        }
        $ret = $data;
        return $ret;
    }

    /**
     * 获取用户其它信息缓存 Cachekey
     * @param int $id D
     * @return string
     */
    protected function getMemberInfoCacheKey( $id ){
        return 'hash:member:info:'.$id;
    }

    /**
     * 获取用户签约数据
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return array
     */
    public function getMemberSign( $param ){
        $ret 	= array();
        $id 	= intval( $param['id'] );
        if( empty( $id ) ){
            return $ret;
        }
        $redis =  \Think\Cache::getInstance('Redis');
        $cacheKey = $this->getMemberSingCacheKey( $id );
        $data = $redis->hgetall( $cacheKey );
        if( !empty( $data ) ){

        }
        $ret = $data;
        return $ret;
    }

    /**
     * 获取用户签约数据缓存 Cachekey
     * @param int $id D
     * @return string
     */
    protected function getMemberSingCacheKey( $id ){
        return 'hash:member:sign:'.$id;
    }

    /**
     * 获取正在登录的用户ID
     * @return int
     */
    public function getLoginUid(){
        $ret = 0;
        $ret = session( 'Uid' );
        if( !$ret ){
            $autoCode = cookie( 'auth_code' );
            if( !empty( $autoCode ) ){
                $code = unserialize( D( 'Common/SecurityCode' )->securityCode( $autoCode, 'DECODE' ) );

                if( !empty( $code ) ){
                    if( time() - $code['time'] > 2592000 ){
                        cookie( 'auth_code', null );
                    }else{
                        //检测用户是否被删除和被禁用。
                        $redis =  \Think\Cache::getInstance('Redis');
                        if ($redis->hget("hash:member:".$code['id'], 'status') != 1) {
                            cookie( 'auth_code', null );
                        }
                        $param = array(
                            'id' => $code['id'],
                            'time' => $code['time'],
                        );
                        $newCode = $this->buildUserInfoCode( $param );
                        if( $newCode ==  $code['code'] ){
                            $ret = $code['id'];
                            session( 'Uid', $code['id'] );
                            $data = $this->detail( array( 'id' => $code['id'] ) );
                            session( 'memberName', $data['username'] );
                            $token = $code['id'].$data['password'].C('LOGIN_NUM')[0];
                            session( 'token', md5( $token ) );
                            session('country', $data['country']);
                            if (!empty($data['img'])) {
                                session('userHeadImg', $data['img']);   //设置用户头像,以便头部调用
                            }
                        }
                    }
                }
            }
        }
        //vii todo
        //$ret = 1;
        return $ret;
    }

    /*
     * 获取用户的企业认证状态
   */
    public function getLoginAuth($uid){
        $redis =  \Think\Cache::getInstance('Redis');

        if($uid){
          //$member= $this->getMemberInfoCacheKey($uid);
            $ret0=$redis->SMEMBERS('set:company:state:0');
            $ret1=$redis->SMEMBERS('set:company:state:1');
            $ret2=$redis->SMEMBERS('set:company:state:2');
            $ret3=$redis->SMEMBERS('set:company:state:3');
            $ret4=$redis->SMEMBERS('set:company:state:4');
            //$ret=$redis->hGet($member,'state');
        }else{
            return ;
        }
        if(in_array($uid,$ret0)){
            return 1;
        }

        if(in_array($uid,$ret1)){
            return 2;
        }

        if(in_array($uid,$ret2)){
            return 3;
        }
        if(in_array($uid,$ret3)){
            return 4;
        }

        if(in_array($uid,$ret4)){
            return 5;
        }
    }

    public function CheckedPass($uid){
        //vii todo
        //return false;
        if($uid){
            $redis =  \Think\Cache::getInstance('Redis');
            $pass=$redis->hget("hash:member:{$uid}",'password');
            $token=$uid.$pass.C('LOGIN_NUM')['0'];
            $tokens=$_SESSION['token'];
            if(md5($token)!=$tokens){
                return true;
            }
        }
    }

    //添加一个集合
     public function createEmail($email,$uid){
         if($uid){
             $redis =  \Think\Cache::getInstance('Redis');
             $ret=$redis->set("string:company:email:{$email}",$uid);
             return $ret;
         }
     }

    //删除一个集合
    public function delEmail($email){
        if($email){
            $redis =  \Think\Cache::getInstance('Redis');
            $ret= $redis->del("string:company:email:{$email}");
            return $ret;
        }
    }


    /**
     * 获取用户-邮箱关联缓存 Cachekey
     * @param int $email D
     * @return string
     */
    protected function getMemberEmailCacheKey( $email ){
        return 'string:company:email:'.$email;
    }

    /**
     * 获取邮箱是否已绑定数据
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return bool
     */
    public function checkEmailIsExist( $param ){
        $ret 	= false;
        $email 	= trim( $param['email'] );
        if( empty( $email ) ){
            return $ret;
        }
        $redis =  \Think\Cache::getInstance('Redis');
        $cacheKey = $this->getMemberEmailCacheKey( $email );
        $data = $redis->exists( $cacheKey );
        return $data;
    }

    /**
     * 获取用户资料是否完善
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return bool
     */
    public function checkInfoIsComplete( $param ){
        $ret = false;
        if( empty( $param['id'] ) ){
            return $ret;
        }
        $data = $this->getMemberInfo( $param );
        if( !empty( $data ) ){
            $ret = true;
            $requiredKeys = array(
                'companyName', 'trade', 'turnover', 'contact'
            );
            foreach( $requiredKeys as $key ){
                if( empty( trim( $data[$key] ) ) ){
                    $ret = false;
                    break;
                }
            }

            $otherData = unserialize( $data['other'] );
            $requiredKeys = array(
                'tel', 'area_s'
            );
            foreach( $requiredKeys as $key ){
                if( empty( trim( $otherData[$key] ) ) ){
                    $ret = false;
                    break;
                }
            }

          /*  echo '<pre>';
            var_dump($ret);*/
            $data = $this->detail( $param );
            if( empty( trim( $data['phone'] ) ) ){
                $ret = false;
            }
        }
        return $ret;
    }

    /**
     * 注册用户
     * @param array $data <pre> array(
    'id' => '', //ID
    )
     * @return mix
     */
    public function insert( $data ){
        $ret = false;
        $data['username'] = strtolower( $data['username'] );
        $valid = $this->create( $data );
        if( $valid ){
            $redis =  \Think\Cache::getInstance('Redis');
            $cacheKey = $this->getUserNameRelateCacheKey( $data['username'] );
            if( $redis->exists( $cacheKey ) ){
                $this->error = '用户名已被注册';
                return $ret;
            }
            $otherCacheKey = $this->getPhoneRelateCacheKey( $data['phone'] );
            $isPhoneExist = $redis->get( $otherCacheKey );
            if( !empty( $isPhoneExist ) ){
                $this->error = '手机号已被注册';
                return $ret;
            }
            $cacheKey = $this->getIncrementIdCacheKey();
            $num = $redis->incr( $cacheKey );
            $t = time();
            $ip = get_client_ip();
            $pass = passencrypt( $data['password'] );
            $cacheKey = $this->getDetailCacheKey( $num );
            $saveData = array(
                'id' => $num,
                'username' => $data['username'],
                'password' => $pass,
                'phone' => $data['phone'],
                'img' => '',
                'email' => '',
                'addTime' => $t,
                'lastLoginIp' => $ip,
                'lastLoginTime' => '',
                'recentLoginTime' => '',
                'bind' => '0',
                'status' => '1',
                'source' => trim( $data['source'] ),
                'type' => trim( $data['type'] ),
                'isAllowEditUsername' => intval( $data['isAllowEditUsername'] ),
            );

            $memberData = $redis->hmset( $cacheKey, $saveData );
            if( $memberData ){
                $cacheKey = $this->getUserNameRelateCacheKey( $data['username'] );
                $member = $redis->set( $cacheKey, $num );
                $cacheKey = $this->getPhoneRelateCacheKey( $data['phone'] );

                if( $redis->exists( $cacheKey ) ){
                    $uid = $redis->get( $cacheKey );
                    $string = $redis->set( $cacheKey, $uid.','.$num);
                }else{
                    $string = $redis->set( $cacheKey, $num );
                }
                $cacheKey = $this->getStatusCacheKey( 1 );
                $sets = $redis->SAdd( $cacheKey, $num );
                $cacheKey = $this->getSignStatusCacheKey( 1 );
                $set = $redis->SAdd( $cacheKey, $num );
                $cacheKey = $this->getAddTimeCacheKey();
                $ZAdd = $redis->ZAdd( $cacheKey, $t, $num );
                $shell = D( 'Home/shell' );
                $cacheKey = $this->getUsernameSearchCacheKey();
                $shell->index( $cacheKey, $data['username'], $num );
                if( empty( $member ) || empty( $string ) || empty( $set ) ||empty( $ZAdd ) ){
                    return $ret;
                }
                /*注册成功之后注销 验证码 session*/
                unset( $_SESSION['msgcode'] );
                unset( $_SESSION['timestamp'] );
                session( 'Uid', $num );
                session( 'memberName',$data['username'] );
                $token = $num.$pass.C('LOGIN_NUM')[0];
                session( 'token', md5( $token ) );
                $authParam = array(
                    'id' => $num,
                    'time' => $t,
                );
                $this->buildAutHCode( $authParam );

                if( !empty( $data['source'] ) ){
                    $cacheKey = $this->getRegisterSourceCacheKey( $data['source'] );
                    $redis->SAdd( $cacheKey, $num );
                }
                if( !empty( $data['type'] ) ){
                    $cacheKey = $this->getRegisterTypeCacheKey( $data['type'] );
                    $redis->SAdd( $cacheKey, $num );
                }
                $ret = $num;
            }
        }
        return $ret;
    }

    /**
     * 发送验证码
     * @param array $data <pre> array(
    'id' => '', //ID
    )
     * @return mix
     */
    public function sendCode( $data ){
        $ret = false;
        $phone = trim( $data['phone'] );
        if( empty( $phone ) ){
            $this->error = '手机码不能为空';
            return $ret;
        }
        $redis =  \Think\Cache::getInstance('Redis');
        $cacheKey = $this->getTodaySentMsgCodeCacheKey( $phone );
        $num = $redis->get( $cacheKey );
        if( $num == false ){
            $num = 1;
            $redis->set( $cacheKey, $num );
            $redis->expire( $cacheKey, 86400 );
        }
        if( $num < 4 ){
            $code = sendMessage( $phone );
            if( $code['code'] == 200 ){
                $num = $num + 1;
                $redis->set( $cacheKey, $num );
                $redis->expire( $cacheKey, 86400 );
                $ret = true;
            }else{
                $this->error =  $code['msg'];
            }
        }else{
            $this->error = '短信验证码超出每天的限制';
        }
        return $ret;
    }

    //验证IP次数
    public function checkIp( $ip ){
        $redis =  \Think\Cache::getInstance('Redis');
        if(!empty($ip)){
            $cacheKeys=$this->getIpCodeCacheKey( $ip );
            if($redis->exists( $cacheKeys )){
                $num=$redis->get( $cacheKeys );
                $num=$num + 1;
                $redis->set( $cacheKeys,$num );
                $redis->expire( $cacheKeys,86400 );
                $ret= $redis->get($cacheKeys);
            }else{
                $redis->set( $cacheKeys,'1' );
                $redis->expire( $cacheKeys,86400 );
                $ret= $redis->get($cacheKeys);
            }
            return $ret;
        }
    }

    //判断手机号码次数
    public function checkPhone( $phone ){
        $redis =  \Think\Cache::getInstance('Redis');
        if(!empty( $phone )){
            $cacheKey=$this->getTodaySentMsgCodeCacheKey( $phone );
            if($redis->exists( $cacheKey )){
                $num=$redis->get( $cacheKey );
                $num=$num + 1;
                $redis->set( $cacheKey,$num );
                $redis->expire( $cacheKey,86400 );
                $ret= $redis->get($cacheKey);
            }else{
                $redis->set( $cacheKey,'1' );
                $redis->expire( $cacheKey,86400 );
                $ret= $redis->get($cacheKey);
            }
            return $ret;
        }
    }

    /**
     * 获取自增id缓存 Cachekey
     * @return string
     */
    protected function getIncrementIdCacheKey(){
        return 'string:member';
    }

    /**
     * 获取用户名与ID关联缓存 Cachekey
     * @param string $username
     * @return string
     */
    protected function getUserNameRelateCacheKey( $username ){
        return 'member:'.$username;
    }

    /**
     * 获取手机号与ID关联缓存 Cachekey
     * @param string $phone
     * @return string
     */
    protected function getPhoneRelateCacheKey( $phone ){
        return 'string:phone:'.$phone;
    }

    /**
     * 获取用户状态集合缓存 Cachekey
     * @param string $status
     * @return string
     */
    protected function getStatusCacheKey( $status ){
        return 'set:member:status:'.$status;
    }

    /**
     * 获取用户认证状态集合缓存 Cachekey
     * @param string $status
     * @return string
     */
    protected function getSignStatusCacheKey( $status ){
        return 'set:member:sign:status:'.$status;
    }

    /**
     * 获取用户注册时间有序集合缓存 Cachekey
     * @return string
     */
    protected function getAddTimeCacheKey(){
        return 'zset:member:addTime';
    }

    /**
     * 获取用户名搜索缓存 Cachekey
     * @return string
     */
    protected function getUsernameSearchCacheKey(){
        return 'member:username';
    }

    /**
     * 获取公司名搜索缓存 Cachekey
     * @return string
     */
    protected function getCompanyNameSearchCacheKey(){
        return 'member:companyName';
    }

    /**
     * 获取当天手机号已发送的短信数缓存 Cachekey
     * @param string $phone
     * @return string
     */
    protected function getTodaySentMsgCodeCacheKey( $phone ){
        $now = date( 'Ymd', time() );
        return 'string:phone:'.$now.':'.$phone;
    }


    /**
     * 获取当天IP已发送的短信数缓存 Cachekey
     * @param string $phone
     * @return string
     */
    protected function getIpCodeCacheKey( $ip ){
        $now = date( 'Ymd', time() );
        return 'string:ip:'.$now.':'.$ip;
    }


    /**
     * 获取注册来源缓存 Cachekey
     * @param string $source
     * @return string
     */
    public function getRegisterSourceCacheKey( $source ){
        return 'set:member:source:'.$source;
    }

    /**
     * 获取注册类型缓存 Cachekey
     * @param string $type
     * @return string
     */
    public function getRegisterTypeCacheKey( $type ){
        return 'set:member:type:'.$type;
    }

    /**
     * 获取公司名与ID关联缓存 Cachekey
     * @param string $companyName
     * @return string
     */
    protected function getCompanyNameRelateCacheKey( $companyName ){
        return 'string:company:'.$companyName;
    }

    /**
     * 获取公司认证状态集合缓存 Cachekey
     * @param string $state
     * @return string
     */
    protected function getCompanyStateCacheKey( $state ){
        return 'set:company:state:'.$state;
    }

    /**
     * 生成用户登录验证码
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return mix
     */
    public function buildAutHCode( $param ){
        $ret = false;
        if( empty( $param['id'] ) ){
            return $ret;
        }
        $code = $this->buildUserInfoCode( $param );
        $param['code'] = $code;
        $authData = D( 'Common/SecurityCode' )->securityCode( serialize( $param ), 'ENCODE' );
        cookie( 'auth_code', $authData, array( 'expire'=> 2592000 ) );
        $ret = $authData;
        return $ret;
    }

    /**
     * 生成用户信息码
     * @param array $param <pre> array(
    'id' => '', //ID
    )
     * @return mix
     */
    public function buildUserInfoCode( $param ){
        $ret = false;
        if( empty( $param['uid'] ) ){
            return $ret;
        }
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $data = $this->detail( array( 'id' => $param['id'] ) );
        if(empty($data['password'])){
            return  $ret;
        }
        $ret = passencrypt( $param['uid'].$data['password'].$userAgent.$param['time'] );
        return $ret;
    }

    /**
     * 展会注册用户
     * @param array $data <pre> array(
    'id' => '', //ID
    )
     * @return mix
     */
    public function insertInShow( $data ){
        $ret = false;
        $validData = array(
            'phone' => $data['phone'],
            'password' => $data['password'],
        );
        $valid = $this->create( $validData );
        if( $valid ){
            $redis =  \Think\Cache::getInstance('Redis');
            $cacheKey = $this->getCompanyNameRelateCacheKey( $data['companyName'] );
            $isExist = $redis->get( $cacheKey );
            if( !empty( $isExist ) ){
                $this->error = '公司名已被注册';
                return $ret;
            }
            $data['username'] = $this->getNoRegisterUserName();
            $data['isAllowEditUsername'] = 1;
            $uid = $this->insert( $data );
            if( $uid ){
                $cacheKey = $this->getMemberInfoCacheKey( $uid );
                $state = 2;
                $redis->hmset( $cacheKey, array( 'id' => $uid, 'companyName' => $data['companyName'], 'contact' => $data['contact'], 'state' => $state ) );
                $cacheKey = $this->getCompanyStateCacheKey( $state );
                $redis->sadd( $cacheKey, $uid );
                /*
                $cacheKey = $this->getCompanyNameRelateCacheKey( $data['companyName'] );
                $redis->set( $cacheKey, $uid );
                */
                /*
                $shell = D( 'Home/shell' );
                $cacheKey = $this->getUsernameSearchCacheKey();
                $shell->index( $cacheKey, $data['username'], $uid );
                */
                $shell = D( 'Home/shell' );
                $cacheKey = $this->getUsernameSearchCacheKey();
                $shell->index( $cacheKey, $data['username'], $uid );

                $ret = $uid;
            }
        }
        return $ret;
    }

    /**
     * 获取未注册的随机用户名
     * @param array $data <pre> array(
    'length' => '', //ID
    )
     * @return string
     */
    public function getNoRegisterUserName( $param ){
        $ret = '';
        $length = 12;
        $randLetter = 'abcdefghijklmnopqrstuvwxyz';
        $randAll = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randLetterLength = strlen( $randLetter );
        $randLetterAll = strlen( $randAll );
        $redis =  \Think\Cache::getInstance('Redis');
        do{
            $ret = '';
            $ret = $randLetter[rand( 0, $randLetterLength - 1 )];
            for( $i = 0; $i < $length - 1; $i++ ){
                $ret .= $randAll[rand( 0, $randLetterAll - 1 )];
            }
            $cacheKey = $this->getUserNameRelateCacheKey( $ret );
            if( $redis->exists( $cacheKey ) ){
                $isExist = true;
            }else{
                $isExist = false;
            }
        }while( $isExist );

        return $ret;
    }

    /**
     * 检查用户名
     * @param string $username
     * @return bool
     */
    public function checkUserNameIsRight( $username ){
        $ret = false;
        $username = strtolower( $username );
        $result = $this->create( array( 'username' => $username ) );
        if( $result ){
            $redis =  \Think\Cache::getInstance('Redis');
            $cacheKey = $this->getUserNameRelateCacheKey( $username );
            if( $redis->exists( $cacheKey ) ){
                $this->error = '用户名已被注册';
                return $ret;
            }
            $ret = true;
        }
        return $ret;
    }

    /**
     * 检查手机号
     * @param string $phone
     * @return bool
     */
    public function checkPhoneIsRight( $phone ){
        $ret = false;
        $result = $this->create( array( 'phone' => $phone ) );
        if( $result ){
            $redis =  \Think\Cache::getInstance('Redis');
            $otherCacheKey = $this->getPhoneRelateCacheKey( $phone );
            $isPhoneExist = $redis->get( $otherCacheKey );
            if( !empty( $isPhoneExist ) ){
                $this->error = '手机号已被注册';
                return $ret;
            }
            $ret = true;
        }
        return $ret;
    }

    /**
     * 检查公司名
     * @param string $companyName
     * @return bool
     */
    public function checkCompanyNameIsRight( $companyName ){
        $ret = false;
        $redis =  \Think\Cache::getInstance('Redis');
        $cacheKey = $this->getCompanyNameRelateCacheKey( $companyName );
        $isExist = $redis->get( $cacheKey );
        if( !empty( $isExist ) ){
            $this->error = '公司名已被注册';
            return $ret;
        }else{
            $ret = true;
        }
        return $ret;
    }

    /**
     * 公司联营状态
     * @param string $companyName
     * @return bool
     */
    public function getLoginUserCompanySign( $uid ){
        $ret = false;
        $data = $this->getMemberSign( array( 'id' => $uid ) );
        $ret = isset($data['state'])?$data['state']:'';
        return $ret;
    }

    /**
     * 检测用户是否印度展会的和邮箱是否认证。
     * @param  string $uid 用户id
     * @return true/false
     */
    public function checkBindEmail($uid){
        $ret = false;
        $redis =  \Think\Cache::getInstance('Redis');
        $data = $redis->hMget('hash:member:' . $uid, array('bind','type'));
        if($data['type'] == 'india-show'){
            $bindEmail = $data['bind'] & C('STATUS_BIND')['BIND_EMAIL'];
            return $bindEmail;
        }else{
            return true;
        }
    }
} 