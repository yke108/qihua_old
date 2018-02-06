<?php

namespace Crontab\Controller;

/**
 * 求购模块计划任务
 * 一次性计划任务, 以CLI模式运行.
 * 如果需要多次持久运行的, 请以功能模块的方式增加(eg: XxxController.class.php), 相同模块的方法写在同一个文件内
 * @package Crontab\Controller
 */
class OnceBuyOfferController extends BaseController {

    /**
     * 同步求购hash.type字段数据到set.type集合
     * @throws \Exception
     */
    public function synHash2SetType() {
        if (!IS_CLI) {
            throw new \Exception('非法请求!');
        }
        $this->redis->del(array('set:buyoffer:type:1', 'set:buyoffer:type:2', 'set:buyoffer:type:3', 'set:buyoffer:type:4'));
        $buyofferList = $this->redis->keys('hash:buyoffer:[0-9]*');
        $i = 0;
        if (!empty($buyofferList)) {
            foreach ($buyofferList as $row) {
                $buyofferHash = $this->redis->hMGet($row, array('id', 'type'));
                if (!empty($buyofferHash['id'])) {
                    $keyType = 'set:buyoffer:type:'.$buyofferHash['type'];
                    $this->redis->sAdd($keyType, $buyofferHash['id']);
                    $i++;
                }
            }
        }
        exit('执行完成，影响行数:' . $i);
    }

}