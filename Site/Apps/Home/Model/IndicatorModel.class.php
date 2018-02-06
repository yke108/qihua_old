<?php

namespace Home\Model;

use Think\Model;


/**
 * 关键指标
 */
class IndicatorModel extends Model {
    protected $redis = null;    //单例化redis
//    protected $shell = null;    //实例化全文索引
    protected $foreignObject = null;    //外键对象
    protected $autoCheckFields = false;

    public function __construct() {
        $this->redis = \Think\Cache::getInstance('Redis');
//        $this->shell = D('shell');
    }

    public function getIncrementIdStringKey() {
        return 'string:product:keyIndex:id';
    }

    /**
     * 关键指标KEY(用做唯一性检测)
     * @param $name
     * @return string
     */
    public function getNameStringKey($name) {
        return 'string:product:keyIndex:' . md5($name);
    }

    /**
     * 关键指标基础数据KEY
     * @param $id
     * @return string
     */
    public function getHashkey($id) {
        return 'hash:product:keyIndex:' . $id;
    }

    /**
     * 集合KEY; 用以排序,分页
     * @param int $status 0:正常状态下的集合
     * @return string
     */
    public function getStatusSetKey($status = 0) {
        return 'set:product:keyIndex:status:' . $status;
    }

    /**
     * 检测关键指标是否存在
     * @param $name
     * @return mixed
     */
    public function checkIndicatorIsExist($name) {
        return $this->redis->exists($this->getNameStringKey($name));
    }

    /**
     * 根据不同的条件返回对应的数据, 以KEY为一个参考单位
     * @param string     $key    对应的key
     * @param array|null $fields 查询的字段名
     * @param array|null $option 附加属性,用于zset,list(eg: min|start, max|stop, withscores)
     *                           array index => 起始区间 array(min, max)
     *                           string group => 分组过滤(withscores)
     *                           string func => 自定义调用方法(主要是调用redis方法)
     * @param string $callback 匿名方法，数据加工，默认(不做任何处理)
     * @return mixed|null
     * @throws \Exception
     */
    public function get($key, array $fields=null, array $option=null, $callback='') {
        if (!$key) {
            throw new \Exception('parameter key is null!');
        }
        if (!is_array($key)) {
            $keys = array($key);
        } else {
            $keys = $key;
        }
        foreach($keys as $k) {
            //$type   对应的类型('tmp', 'string', 'hash', 'list', 'set', 'zset')
            $type = explode(':', $k);
            if ($type[0] === 'tmp') {
                $type = isset($type[1]) ? $type[1] : '';
            } else {
                $type = $type[0];
            }
            if (!in_array($type, array('tmp', 'string', 'hash', 'list', 'set', 'zset'), true)) {
                return;
            }
        }
        $rs = null;
        if ($type === 'string') {
            $rs = count($keys) > 1 ? $this->redis->mGet($key) : $this->redis->get($key);
        } else if ($type === 'hash') {
            $rs = null === $fields ? $this->redis->hGetAll($key) : $this->redis->hMGet($key, $fields);
        } else if ($type === 'list') {
            $rs = $this->redis->lRange($key, $option['index'][0], $option['index'][1]);
        } else if ($type === 'set') {
            $rs = $this->redis->sMembers($key);
        } else if ($type === 'zset') {
            $rs = $this->redis->zRange($key, $option['index'][0], $option['index'][1]);
        }
        //数据加工
        if ($callback && $rs && method_exists($this, $callback)) {
            return $this->$callback([$rs]);
        } else {
            return $rs;
        }
    }

    /**
     * 注入外键关联对象及操作方法
     * @param Object     $obj  具体关联对象
     * @param string     $func 对象操作方法
     * @param string     $foreignKey 对应的外键ID
     * @param array|null $fields 获取需要返回的字段
     */
    public function setForeignKeyRelation($obj, $func, $foreignKey, array $fields = null) {
        $this->foreignObject[] = array(array($obj, $func), $foreignKey, $fields);
    }

    /**
     * 分页列表
     * @param array $params 列表查询条件及分页限制, 主要用到redis->sort, 参数同该方法一致, 其他多余参数无效
     *                  'by' => 'hash:xxx:*->id',
     *                  'limit' => $params['limit'],
     *                  'sort' => 'desc',
     *                  'alpha' => true,
     *                  'get' => array('hash:xxx:*->id', 'hash:xxx:*->name', ...),
     *                  'store' => 'temp_store_key'
     * @param int $isPersist 是否缓存(0:不缓存(默认值); 大于0时表示缓存的时间, 单位秒)
     * @return array (total: 结果总数, rows:当前结果集合)
     */
    public function search(array $params, $isPersist=0) {
        if ($isPersist > 0) {
            $tempPersistKey = 'tmp:string:persist:product:keyIndex:list' . sha1(http_build_query($params));
        }

        if ($isPersist > 0 && $this->redis->exists($tempPersistKey)) {
            return json_decode($this->redis->get($tempPersistKey), true);
        } else {
            $arr = array();
            $arr[] = $this->getStatusSetKey();
            if (count($arr) > 1) {
                $tmpKey = 'tmp:product:keyIndex:filter:id';
                $this->redis->zInter($tmpKey, $arr);
                $this->redis->expire($tmpKey, 30);
            } else {
                $tmpKey = $arr[0];
            }

            $list = $this->redis->sort($tmpKey, $params);
            $rs = array();
            if ($list) {
                //分离出需要展示的字段名
                $showFields = array_map(function ($p) {
                    return ($m = explode('->', $p, 2)) && isset($m[1]) ? $m[1] : $m[0];
                }, $params['get']);
                //切分列表成数组
                $rs = array_chunk($list, count($params['get']));
                foreach ($rs as $k => $row) {
                    $temp = array_combine($showFields, $row);
                    //通过注入外键对象, 返回相关数据
                    if (!empty($this->foreignObject)) {
                        foreach ($this->foreignObject as $obj) {
                            $temp = array_merge($temp, call_user_func_array($obj[0], array($temp[$obj[1]], $obj[2])));
                        }
                    }
                    $rs[$k] = $temp;
                }
                //设置缓存
                if ($isPersist > 0 && $rs) {
                    $this->redis->set($tempPersistKey, json_encode($rs), array('EX' => $isPersist + 0));
                }
            }
            return array('total' => is_array($tmpKey) ? $this->redis->zCard($tmpKey) : $this->redis->sCard($tmpKey),
                         'rows' => $this->processData($rs));
        }
    }

    /**
     * 数据加工处理, 一般查询的数据部分字段需要做一定的转换(eg: 时间, 状态)
     * @param array $data 查询结果集
     * @return array
     */
    protected function processData(array $data) {
        if ($data) {
            foreach ($data as $k => &$row) {
                if (isset($row['addTime'])) {
                    $row['addTime'] = date('Y-m-d H:i:s', $row['addTime']);
                }
                if (isset($row['editTime'])) {
                    $row['editTime'] = date('Y-m-d H:i:s', $row['editTime']);
                }
            }
        }
        return $data;
    }
}