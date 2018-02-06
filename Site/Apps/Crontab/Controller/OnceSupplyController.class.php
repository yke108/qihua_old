<?php

namespace Crontab\Controller;

/**
 * 供求模块计划任务
 * 一次性计划任务, 以CLI模式运行.
 * 如果需要多次持久运行的, 请以功能模块的方式增加(eg: XxxController.class.php), 相同模块的方法写在同一个文件内
 * @package Crontab\Controller
 */
class OnceSupplyController extends BaseController {

    /**
     * 同步供求hash.type字段数据到set.type集合
     * @throws \Exception
     */
    public function synHash2SetType() {
        if (!IS_CLI) {
            throw new \Exception('非法请求!');
        }
        $this->redis->del(array('set:supply:type:1', 'set:supply:type:2', 'set:supply:type:3', 'set:supply:type:4'));
        $supplyList = $this->redis->keys('hash:supply:[0-9]*');
        $i = 0;
        if (!empty($supplyList)) {
            foreach ($supplyList as $row) {
                $supplyHash = $this->redis->hMGet($row, array('id', 'type'));
                if (!empty($supplyHash['id'])) {
                    $keyType = 'set:supply:type:'.$supplyHash['type'];
                    $this->redis->sAdd($keyType, $supplyHash['id']);
                    $i++;
                }
            }
        }
        exit('执行完成，影响行数:' . $i);
    }

}