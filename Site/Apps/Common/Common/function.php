<?php
/**
 * 公共函数
 */

/**
 * id exist 数组取键值，加默认
 * 
 * @param  array  $arr   数组
 * @param  string $field 键名
 * @param  mix    $value 默认值
 * 
 * @return mix
 */
function idx($arr, $field, $defaultValue='')
{
    //数组存在
    if (is_array($arr)) {
        //键名存在
        if (isset($arr[$field])) {
            return $arr[$field];
        } else {
            //键值不存在返回null,或自定义的默认值
            return $defaultValue;
        }
    } else {
        return $defaultValue;
    }
}

/**
 * 文件上传
 */
function imgUpload($name){
    $upload				= new \Think\Upload();
        $upload->maxSize	= 2097152;
        $upload->exts       = array('jpg', 'gif', 'png', 'jpeg');
        $upload->savePath   = date('Y-m-d',time());//'Admin/'; // 设置附件上传目录
        $upload->subName	='/'. MODULE_NAME.'/'.$name;
        $upload->saveName	=time().rand(100,999);
        $info				= $upload->upload();
        if(!$info){
        //没有文件上传或者上传失败
        return array('error'=>0);
    }else{
        foreach ($info as $file){
            $filename		= '/Uploads/'.$file['savepath'].$file['savename'];
        }
        return $filename;
    }
}



/**
 * base64字符串转换成图片
 * @param $base64
 */
function toImg($base64){
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
		$type = $result[2];
		$path='./Uploads/Admin/base64/'.time().'.'.$type;
		if (file_put_contents($path, base64_decode(str_replace($result[1], '', $base64)))){
			$path=substr($path, 1);
			return $path;
		}else{
			return false;
		}

	}

//	if (preg_match('/(?<=\/)[^\/]+(?=\;)/',$base64,$pregR)) $streamFileType ='.' .$pregR[0];
//	$path='./Uploads/Admin/base64/'.time();
//	$path.=$streamFileType;
//
//	$img = base64_decode($base64);
//	$a=file_put_contents($path, $img);
//	if($a){
//		$path=substr($path, 1);
//		return $path;
//	}else{
//		return false;
//		}

}

/*发邮件*/
function send_mail($to,$title,$content){
    Vendor('PHPMailer.class#phpmailer');
    $mail=new PHPMailer();
    /*服务器相关信息*/
    $mail->IsSMTP();                        //设置使用SMTP服务器发送
    $mail->SMTPAuth   = true;               //开启SMTP认证
    $mail->Host       = 'smtp.exmail.qq.com';   	    //设置 SMTP 服务器,自己注册邮箱服务器地址
    $mail->Username   = 'system@keywa.com';  		//发件人完整的邮箱名称
    $mail->Password   = 'Gdmail09568';          //发信人的邮箱密码
    /*内容信息*/
    $mail->IsHTML(true); 			         //指定邮件内容格式为：html
    $mail->CharSet    ='UTF-8';			     //编码
    $mail->From       = 'system@keywa.com'; //发件人完整的邮箱名称
    $mail->FromName   = 'keywa';			 //发信人署名
    $mail->Subject    = $title;  			 //信的标题
    $mail->MsgHTML($content);  				 //发信主体内容
    /*发送邮件*/
    $mail->AddAddress($to);
    //使用send函数进行发送
    $info=$mail->Send();
    if($info) {
        return $info;
    } else {
        echo $mail->ErrorInfo;//如果发送失败，则返回错误提示
    }

}

//首页商品分类
function getcategory(){
	$cate=D('Category');
	$res=$cate->getcategorycache();
	$product=D('Product');
    $currency = $product->getProductCurrency();
    $weightUnit = $product->getProductWeightUnit();

	//每层楼取出2个商品
	$arr=array();
	foreach ($res as $v){
		$list=$product->lists(array('p'=>0,'page_size'=>2,'state'=>1,'category'=>$v['id']));
		//$list = empty($list['lists']) ? array() : $list['lists'];
		$lists=array();
		if (!empty($list['lists'])) {
		    foreach ($list['lists'] as $l){
		        $a['id']=$l['id'];
		        $a['currency']=(empty($currency[$l['currency']]['character']) ? '' : $currency[$l['currency']]['character']);
		        $a['weightUnit']=$weightUnit[$l['weightUnit']]['enTitle'];
		        $a['title']=$l['title'];
		        $a['price']=$l['price'];
		        $images = unserialize( $l['images'] );
		        $a['thumb']=$images[0];
		        $lists[]=$a;
		        unset($l);
		    }
		    $v['list']=empty($lists) ? array() : $lists;
		    $arr[]=$v;
		}
		
	}
	return $arr;
}

//获取网站的logo
function logo(){
	$content=D('Contents');
	$logo=$content->where("type='网站logo'")->field('content')->order('id desc')->find();
	return $logo['content'];
	}


function getAreaName($id){
    $redis = \Think\Cache::getInstance('Redis');
    $title = $redis->hGet('hash:area:'.$id,'title');
    return $title;
}


/**
 * @param $phone 需要发送的信息的手机
 * 
 */
function sendMessage($phone){
	if($data=session('message')){
		$time=time()-$data['addTime'];
		if($time<120){
			return array('code'=>'400','msg'=>'One SMS verification code in 2 minutes only. ');
		}
	}

	$arr=array(
			'cdkey'=>'8SDK-EMY-6699-RISOO',
			'password'=>'105126',
			'phone'=>$phone,
			'addserial'=>''
	);
	//获取随机数
	$code=rand(1000,9999);
    //$code=3456;
    $arr['message']="【奇化网】Dear Customer: The SMS verification code is {$code} and valid for 5 minutes only.
    
    Warming: This is a SMS automatically send by  ".str_replace('http://', '', C('EN_KEYWA_SITE')).", please do not reply directly.";
	$session=array(
			'code'=>$code,
			'addTime'=>time()
	);

	$url="http://hprpt2.eucp.b2m.cn:8080/sdkproxy/sendsms.action";
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT,60);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
    $res=curl_exec($ch);
    curl_close($ch);
    //$res=0;
	//发送成功
	if($res==0){
		session('message',$session);
		return array('code'=>'200','msg'=>'Send success');
	}else{
		return array('code'=>'400','msg'=>'Send failure','data'=>$res);
	}
}

    /**
     * 验证短信验证码接收到一个验证码
     * @param $code 提交过来的验证码
     */
    function checkMessage($code){
        //取出session的数据
        $data=session('message');
        //正式上线后去掉
        if(!$data){
            $data['code']='3456';
            $data['addTime']=time();
        }
        if(!$data){
            return array('code'=>'400','msg'=>'Your verification code has expired, please acquire again. ');
        }
        $time=time()-$data['addTime'];
        if($time>300){
            session('message',null);
            return array('code'=>'400','msg'=>'Your verification code has expired, please acquire again. ');
        }elseif($data['code']==$code || $code=='3456' ){
//            session('message',null);
            return array('code'=>'200','msg'=>'correct SMS Verification Code');
        }else{
            return array('code'=>'400','msg'=>'Sorry! Incorrect SMS Verification Code');
        }
    }

    /**
     * 注册发送邮件验证码
     * @param $to
     * @param $title
     * @param $content
     * @return bool
     */
    function sendEmail($to){
        $title = '【keywa】email verification';
        if($data=session('sendEmail')){
            $time=time()-$data['addTime'];
            if($time<120){
                return array('code'=>'400','msg'=>'One Email verification code in 2 mintues only. ');
            }
        }
        $content = rand(10000,99999);
        $session=array(
            'code'=>$content,
            'addTime'=>time()
        );

        $content = "Dear Customer: The email verification code is {$content} and valid for 2 hours only.
    <br/>
    Warming: This is an email automatically send by ".C('EN_KEYWA_SITE').", please do not reply directly.";

        Vendor('PHPMailer.class#phpmailer');
        $mail=new PHPMailer();
        /*服务器相关信息*/
        $mail->IsSMTP();                        //设置使用SMTP服务器发送
        $mail->SMTPAuth   = true;               //开启SMTP认证
        $mail->Host       = 'smtp.exmail.qq.com';   	    //设置 SMTP 服务器,自己注册邮箱服务器地址
        $mail->Username   = 'system@keywa.com';  		//发件人完整的邮箱名称
        $mail->Password   = 'Gdmail09568';          //发信人的邮箱密码
        /*内容信息*/
        $mail->IsHTML(true); 			         //指定邮件内容格式为：html
        $mail->CharSet    ='UTF-8';			     //编码
        $mail->From       = 'system@keywa.com'; //发件人完整的邮箱名称
        $mail->FromName   = 'keywa';			 //发信人署名
        $mail->Subject    = $title;  			 //信的标题
        $mail->MsgHTML($content);  				 //发信主体内容
        /*发送邮件*/
        $mail->AddAddress($to);
        //使用send函数进行发送
        $info=$mail->Send();
        if($info) {
            session('sendEmail', $session);
            return ['code' => 200, 'msg' => $info];
        } else {
            return ['code' => 400, 'msg' => $mail->ErrorInfo];//如果发送失败，则返回错误提示
        }
    }

    /**
     * 验证邮箱验证码
     * @param $code 提交过来的验证码
     */
    function checkEmail($code){
        //取出session的数据
        $data=session('sendEmail');
        //正式上线后去掉
        if(!$data){
            $data['code']='3456';
            $data['addTime']=time();
        }
        if(!$data){
            return array('code'=>'400','msg'=>'Your verification code has expired, please acquire again. ');
        }
        $time=time()-$data['addTime'];
        if($time>7200){
            session('sendEmail',null);
            return array('code'=>'400','msg'=>'Your verification code has expired, please acquire again. ');
        }elseif($data['code']==$code || $code=='3456' ){
//            session('sendEmail',null);
            return array('code'=>'200','msg'=>'correct Email Verification Code');
        }else{
            return array('code'=>'400','msg'=>'Sorry! Incorrect Email Verification Code');
        }
    }

    //密码加密.dd
    function passencrypt($password, $salt = '') {
        return md5(md5($password) . $salt);
    }

    /*
     * 导出Excel表格
     * $expTitle  表格名称
     * $expCellName  表头名字数组
     * $expTableData 表格数据
     * */
    function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName =date('_YmdHis');// 文件名称
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i].'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
        }

        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j].($i+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=奇化网_$expTitle$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    //短信验证随机token
    function mobileCache() {
        $Cache = md5(rand(10000, 99999));
        session('Send_Code', $Cache);
        return $Cache;
    }

    /**
     * 得到世界国家数据
     */
    function getAllCountry() {
        $redis = \Think\Cache::getInstance('Redis');
        $key = 'hash:country:name';
        if ($redis->exists($key)) {
            return $redis->hGetAll($key);
        } else {
            $c = require_once(APP_PATH.'/Common/Conf/country.php');
            $redis->hmset($key, $c['country']);
            return $c;
        }
    }
    
    /**
     * 获取未读的系统消息
     */
    
    function getUnreadSystem(){
    	$id=session('Uid');
    	$model=D('User/Message');
    	return $model->getUnReadSystem($id);	
    }
    
    /**
     * 获取未读的收件箱
     */
    function getUnReadMessage(){
    	$id=session('Uid');
    	$model=D('User/Message');
    	return $model->getUnReadMessage($id);
    }

    /**
     * 根据路由地址和用户ID, 每分钟频率限制
     * @param string $rkey 具体业务对应的KEY;(KEY前缀 string:limitRate)
     * @param int $limit 限制请求次数(默认:20次/分钟)
     * @return array 结果(200:成功, 400:请求受限)
     */
    function limitRate($rkey='', $limit=20) {
        $redis = \Think\Cache::getInstance('redis');
        if (!$rkey) {
            $rkey = 'string:limitRate:'.MODULE_NAME.':'.CONTROLLER_NAME.':'.ACTION_NAME.':'.session('Uid');
        }
        $random = uniqid();
        //加锁防并发, 产生随机数, 确保客户端只会删除自己产生的锁, 而不会误其他客户端产生的锁
        if ($redis->set($rkey.':lock', $random, array('EX' => 15, 'NX'))) {     //原子操作, 产生值同时生成有效时间
            if ($redis->exists($rkey)){
                $redis->incr($rkey);
            } else {
                $redis->set($rkey, 1, array('EX' => 60));
            }
            if ($redis->get($rkey) > $limit) {
                if ($redis->get($rkey.':lock') == $random) {     //释放锁
                    $redis->del($rkey.':lock');
                }
                //当超过限制时写日志
                $logData = array('ip' => get_client_ip(), 'addtime' => time(), 'uid' => session(Uid), 'count' =>$redis->get($rkey), 'userAgent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
                $redis->hMSet('hash:log:limitRate:'.MODULE_NAME.':'.CONTROLLER_NAME.':'.ACTION_NAME.':'.session('Uid'), $logData);
                return array('code' => '400', 'msg' => 'Request too frequent', 'data' => array('error' => 'Request too frequent'));
            }
            if ($redis->get($rkey.':lock') == $random) {     //释放锁
                $redis->del($rkey.':lock');
            }
            return array('code' => '200', 'msg' => '');
        } else {
            //过滤多余的并发请求
            //当超过限制时写日志
            $logData = array('ip' => get_client_ip(), 'addtime' => time(), 'uid' => session(Uid), 'count' =>$redis->get($rkey), 'userAgent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
            $redis->hMSet('hash:log:limitRate:'.MODULE_NAME.':'.CONTROLLER_NAME.':'.ACTION_NAME.':'.session('Uid'), $logData);
            exit();
        }
    }