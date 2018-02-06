<?php
require_once('redisKeyConfig.php');
$sysConfig=array(
	'DB_TYPE'=>'mysql', // 数据库类型
	'DB_HOST'=>'127.0.0.1', // 数据库服务器地址
	'DB_NAME'=>'keywa_en_test', // 数据库名称
	'DB_USER'=>'kw_en_hongqi', // 数据库用户名
	'DB_PWD'=>'825fa67331590f34', // 数据库密码
	'DB_PORT'=>'3306', // 数据库端口
	'DB_PREFIX'=>'kw_', // 数据表前缀
	'DB_CHARSET'=>'utf8', // 网站编码

	//redis配置
	'REDIS_HOST'=>'127.0.0.1',
	'REDIS_PORT'=>36399,
	'REDIS_AUTH'=>'keywa20181228',
	'REDIS_DB'=>4,
//	'REDIS_DB_PREFIX'=>'keywa:',
	'DATA_CACHE_TIME'       =>  0,             //长连接时间,REDIS_PERSISTENT为1时有效
	'DATA_CACHE_PREFIX'     =>  'keywa:',            // 缓存前缀
	'DATA_CACHE_TYPE'       =>  'Redis',       //数据缓存类型
	'DATA_EXPIRE'           =>  0,               //数据缓存有效期(单位:秒) 0表示永久缓存
//	'DATA_PERSISTENT'      =>  1,               //是否长连接
//	'DATA_REDIS_HOST'            =>  '192.168.1.200,192.168.1.201', //分布式Redis,默认第一个为主服务器
//	'DATA_REDIS_PORT'            =>  '6379',           //端口,如果相同只填一个,用英文逗号分隔
//	'DATA_REDIS_AUTH'            =>  'redis123456',    //Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔
);
$sysConfig = array_merge($sysConfig, $redisKeyConfig);
?>
