<?php

namespace Crontab\Controller;

/**
 * 一次性计划任务, 以CLI模式运行.
 * 如果需要多次持久运行的, 请以功能模块的方式增加(eg: XxxController.class.php), 相同模块的方法写在同一个文件内
 * @package Crontab\Controller
 */
class OnceController extends BaseController {
    public function test() {
        if (!IS_CLI) {
            throw new \Exception('非法请求!');
        }
        echo time();
        exit();
    }


	/**
	 * source	来源	pc:电脑, wap:移动端
	 * type	类型	normal:正常, india-show:印度会展, 
	 * 同步这两个字段
	*/

	public function synchronizeMemberInfo(){
//		 if (!IS_CLI) {
//            throw new \Exception('非法请求!');
//        }
		$redis = \Think\Cache::getInstance('Redis');
		$num = $redis->get('string:member');
		if(!empty($num)){
			for ($i=0; $i<$num; $i++){
				$memberData = $redis->hGetAll('hash:member:'.$i);
				if(empty($memberData)){
					continue;
				}
				
				if( empty($memberData['source']) ){
					$redis->hset('hash:member:'.$i,'source','pc');
					$redis->sAdd('set:member:source:normal',$i);
				}
				
				if(empty($memberData['type'])){
					$redis->hset('hash:member:'.$i,'type','normal');
					$redis->sAdd('set:member:type:normal',$i);
				}
			}
		}

		}

}