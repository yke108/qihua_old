<?php

namespace Crontab\Controller;

use Think\Controller;
use Think\Db;

/**
 * 一次性计划任务, 以CLI模式运行.
 * 如果需要多次持久运行的, 请以功能模块的方式增加(eg: XxxController.class.php), 相同模块的方法写在同一个文件内
 * @package Crontab\Controller
 */
class BaseController extends Controller {
    protected $redis = null;    //redis单例对象
    protected $mysql = null;    //mysql单例对象

    public function __construct() {
        $this->redis = new \Redis();
        //从REDIS配置文件读取
        $redisConfig = array(
            'host' => C('REDIS_HOST'),
            'port' => C('REDIS_PORT'),
            'auth' => C('REDIS_AUTH'),
            'db'   => C('REDIS_DB', null, 3),
        );
        $this->redis->connect( $redisConfig['host'], $redisConfig['port'] );
        if( $this->redis !== false ){
            $this->redis->auth( $redisConfig['auth'] );
            $this->redis->select( $redisConfig['db'] );
        }else{
            throw new \Exception('redis连接失败！');
        }
    }

    /**
     * 连接数据库
     * @param array $config 数据库配置
     * @return Object
     */
    public function getDb($config = array()) {
        $dbConfig = array(
            'db_type'    => C('DB_TYPE'), // 数据库类型
            'db_host'    => C('DB_HOST'), // 数据库服务器地址
            'db_name'    => C('DB_NAME'), // 数据库名称
            'db_user'    => C('DB_USER'), // 数据库用户名
            'db_pwd'     => C('DB_PWD'), // 数据库密码
            'db_port'    => C('DB_PORT'), // 数据库端口
            'db_prefix'  => C('DB_PREFIX'), // 数据表前缀
            'db_charset' => C('DB_CHARSET'), // 网站编码
        );
        $config = array_merge($dbConfig, $config);
        return Db::getInstance($config);
    }

    public function test() {
        if (!IS_CLI) {
            throw new \Exception('非法请求!');
        }
        echo time();
        exit();
    }

}