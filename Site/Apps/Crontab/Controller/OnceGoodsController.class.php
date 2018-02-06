<?php

namespace Crontab\Controller;

/**
 * 商品模块计划任务
 * 一次性计划任务, 以CLI模式运行.
 * 如果需要多次持久运行的, 请以功能模块的方式增加(eg: XxxController.class.php), 相同模块的方法写在同一个文件内
 * @package Crontab\Controller
 */
class OnceGoodsController extends BaseController {

    /**
     * 同步商品库存数
     * @throws \Exception
     */
    public function synGoodsStock() {
        if (!IS_CLI) {
            throw new \Exception('非法请求!');
        }
        $productList = $this->redis->keys('hash:product:[0-9]*');
        $i = 0;
        if (!empty($productList)) {
            foreach ($productList as $row) {
                $stock = $this->redis->hGet($row, 'inventory');
                if ($stock) {
                    $data['inventoryNum'] = $stock + 0;
                    $data['inventoryType'] = 1;
                } else {
                    $data['inventoryNum'] = 0;
                    $data['inventoryType'] = 2;
                }
                $this->redis->hMset($row, $data);
                $i++;
            }
        }
        exit('执行完成，影响行数:' . $i);
    }

}