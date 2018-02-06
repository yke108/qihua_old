<?php
namespace Admin\Model;

use Think\Model;

/**
 * 内容管理模型
 */

class ContentsModel extends Model{
	//定义字段映射
	protected $field=array(
			'id',
			'title',
			'content',
			'other'
	);
	protected $redis = null;
    /**
     * 1:佛山总公司, 2:广州分公司
     */
	protected $configCompany = [1 => 'foshan', 2 => 'guangzhou'];
    /**
     * 1:销售合作, 2:采购合作, 3:品牌推广, 4:投资洽谈, 5:客户服务
     */
	protected $configCooperation = [1 => 'sell', 2 => 'purchase', 3 => 'brand', 4 => 'invest', 5 => 'custom'];

	public function __construct() {
	    $this->redis = \Think\Cache::getInstance('Redis');
    }

    /**
     * 获得关于我们 - 平台简介Cache KEY
     */
	public function getAboutUsDescriptionHashKey() {
        return 'hash:aboutUs:description';
    }

    /**
     * 获得关于我们 法律声明Cache KEY
     */
	public function getAboutUsLegalStatementHashKey() {
        return 'hash:aboutUs:legalStatement';
    }

    /**
     * 获得关于我们 - 服务协议Cache KEY
     */
	public function getAboutUsProtocolHashKey() {
        return 'hash:aboutUs:protocol';
    }

    /**
     * 获得关于我们 - 联系我们Cache KEY
     * @param int $type; 1:佛山(default)
     * @return string;
     */
	public function getAboutUsContactHashKey($type=1) {
        return 'hash:aboutUs:company:'.$this->configCompany[$type];
    }

    /**
     * 获得关于我们 - 服务协议Cache KEY
     * @param int $type; 1:销售合作(default)
     * @return string;
     */
	public function getAboutUsCooperationHashKey($type=1) {
        return 'hash:aboutUs:cooperation:'.$this->configCooperation[$type];
    }

    /**
     * 获得关于我们 - 媒体报道Cache KEY
     * @param int $id 对应自增ID
     * @return string
     */
	public function getAboutUsMediaReportHashKey($id) {
        return 'hash:aboutUs:mediaReport:'.$id;
    }

    /**
     * 获得关于我们 - 媒体报道(自增)Cache KEY
     */
	public function getAboutUsMediaReportIncrKey() {
        return 'string:aboutUs:mediaReport';
    }

    /**
     * 获得关于我们 - 媒体报道状态Cache KEY
     * @param int $status; 1:正常(default), -1:删除
     * @return string
     */
	public function getAboutUsMediaReportStatusSetKey($status=1) {
        return 'set:aboutUs:mediaReport:status:'.$status;
    }

    /**
     * 获得关于我们 - 网站公告Cache KEY
     * @param int $id 对应自增ID
     * @return string
     */
	public function getAboutUsNoticeHashKey($id) {
        return 'hash:aboutUs:notice:'.$id;
    }

    /**
     * 获得关于我们 - 网站公告(自增)Cache KEY
     */
	public function getAboutUsNoticeIncrKey() {
        return 'string:aboutUs:notice';
    }

    /**
     * 获得关于我们 - 网站公告状态Cache KEY
     * @param int $status; 1:正常(default), -1:删除
     * @return string
     */
	public function getAboutUsNoticeStatusSetKey($status=1) {
        return 'set:aboutUs:notice:status:'.$status;
    }

    /**
     * 更新关于我们 - 平台简介, 如果不存在, 则添加一条新的
     * @param array $data 入库相关的字段
     * @return bool
     */
    public function editDescription($data) {
        $key = $this->getAboutUsDescriptionHashKey();
        if ($this->redis->exists($key)) {
            $data['editTime'] = time();
        } else {
            $data['addTime'] = $data['editTime'] = time();
        }
        return $this->redis->hMSet($key, $data);
    }

    /**
     * 更新关于我们 - 法律声明, 如果不存在, 则添加一条新的
     * @param array $data 入库相关的字段
     * @return bool
     */
    public function editLegalStatement($data) {
        $key = $this->getAboutUsLegalStatementHashKey();
        if ($this->redis->exists($key)) {
            $data['editTime'] = time();
        } else {
            $data['addTime'] = $data['editTime'] = time();
        }
        return $this->redis->hMSet($key, $data);
    }

    /**
     * 更新关于我们 - 服务协议, 如果不存在, 则添加一条新的
     * @param array $data 入库相关的字段
     * @return bool
     */
    public function editProtocol($data) {
        $key = $this->getAboutUsProtocolHashKey();
        if ($this->redis->exists($key)) {
            $data['editTime'] = time();
        } else {
            $data['addTime'] = $data['editTime'] = time();
        }
        return $this->redis->hMSet($key, $data);
    }

    /**
     * 更新关于我们 - 联系我们(公司), 如果不存在, 则添加一条新的
     * @param array $data 入库相关的字段
     * @param int $type; 对应$configCompany Key值
     * @return bool
     */
    public function editContact($data, $type) {
        if (!array_key_exists($type, $this->configCompany)) {
            return false;
        }
        $key = $this->getAboutUsContactHashKey($type);
        if ($this->redis->exists($key)) {
            $data['editTime'] = time();
        } else {
            $data['addTime'] = $data['editTime'] = time();
        }
        return $this->redis->hMSet($key, $data);
    }

    /**
     * 更新关于我们 - 联系我们(合作), 如果不存在, 则添加一条新的
     * @param array $data 入库相关的字段
     * @param int $type; 对应$configCooperation Key值
     * @return bool
     */
    public function editCooperation($data, $type) {
        if (!array_key_exists($type, $this->configCooperation)) {
            return false;
        }
        $key = $this->getAboutUsCooperationHashKey($type);
        if ($this->redis->exists($key)) {
            $data['editTime'] = time();
        } else {
            $data['addTime'] = $data['editTime'] = time();
        }
        return $this->redis->hMSet($key, $data);
    }

    /**
     * 添加关于我们 - 媒体报道
     * @param $data 入库相关的字段
     * @return bool
     */
    public function addMediaReport($data) {
        $idKey = $this->getAboutUsMediaReportIncrKey();
        $id = $this->redis->incr($idKey);
        $this->redis->multi();
        $mediaReportKey = $this->getAboutUsMediaReportHashKey($id);
        $data['id'] = $id;
        $data['addTime'] = $data['editTime'] = time();
        $this->redis->hMSet($mediaReportKey, $data);
        $mediaReportStatusKey = $this->getAboutUsMediaReportStatusSetKey();
        $this->redis->sAdd($mediaReportStatusKey, $id);
        return $this->redis->exec();
    }

    /**
     * 修改关于我们 - 媒体报道
     * @param int $id 主键ID
     * @param array $data 入库相关的字段
     * @return bool
     */
    public function editMediaReport($id, $data) {
        if (!$id) {
            return false;
        }
//        $this->redis->multi();
        $mediaReportKey = $this->getAboutUsMediaReportHashKey($id);
        $data['editTime'] = time();
        return $this->redis->hMSet($mediaReportKey, $data);
//        $mediaReportStatusKey = $this->getAboutUsMediaReportStatusSetKey();
//        $this->redis->sAdd($mediaReportStatusKey, $id);
//        return $this->redis->exec();
    }

    /**
     * 删除关于我们 - 媒体报道
     * @param int $id 主键ID
     * @return bool
     */
    public function delMediaReport($id) {
        if (!$id) {
            return 0;
        }
        if($this->redis->sRem($this->getAboutUsMediaReportStatusSetKey(), $id)) {
            return $this->redis->sAdd($this->getAboutUsMediaReportStatusSetKey(2), $id);
        }
        return 0;
    }

    /**
     * 添加关于我们 - 网站公告
     * @param $data 入库相关的字段
     * @return bool
     */
    public function addNotice($data) {
        $idKey = $this->getAboutUsNoticeIncrKey();
        $id = $this->redis->incr($idKey);
        $this->redis->multi();
        $mediaReportKey = $this->getAboutUsNoticeHashKey($id);
        $data['id'] = $id;
        $data['addTime'] = $data['editTime'] = time();
        $this->redis->hMSet($mediaReportKey, $data);
        $mediaReportStatusKey = $this->getAboutUsNoticeStatusSetKey();
        $this->redis->sAdd($mediaReportStatusKey, $id);
        return $this->redis->exec();
    }

    /**
     * 修改关于我们 - 网站公告
     * @param int $id 主键ID
     * @param array $data 入库相关的字段
     * @return bool
     */
    public function editNotice($id, $data) {
        if (!$id) {
            return false;
        }
        $mediaReportKey = $this->getAboutUsNoticeHashKey($id);
        $data['editTime'] = time();
        return $this->redis->hMSet($mediaReportKey, $data);
    }

    /**
     * 删除关于我们 - 网站公告
     * @param int $id 主键ID
     * @return bool
     */
    public function delNotice($id) {
        if (!$id) {
            return 0;
        }
        if($this->redis->sRem($this->getAboutUsNoticeStatusSetKey(), $id)) {
            return $this->redis->sAdd($this->getAboutUsNoticeStatusSetKey(-1), $id);
        }
        return 0;
    }

    /**
     * 获得关于我们 - 平台简介 具体详情
     * @return mixed
     */
    public function getDescription() {
        return $this->redis->hGetAll($this->getAboutUsDescriptionHashKey());
    }

    /**
     * 获得关于我们 - 法律声明 具体详情
     * @return mixed
     */
    public function getLegalStatement() {
        return $this->redis->hGetAll($this->getAboutUsLegalStatementHashKey());
    }

    /**
     * 获得关于我们 - 服务协议 具体详情
     * @return mixed
     */
    public function getProtocol() {
        return $this->redis->hGetAll($this->getAboutUsProtocolHashKey());
    }

    /**
     * 获得关于我们 - 联系我们(公司)具体详情
     * @param int $type
     * @return array
     */
    public function getContact($type) {
        return $this->redis->hGetAll($this->getAboutUsContactHashKey($type));
    }

    /**
     * 获得关于我们 - 联系我们(合作)具体详情
     * @param int $type
     * @return array
     */
    public function getCooperation($type) {
        return $this->redis->hGetAll($this->getAboutUsCooperationHashKey($type));
    }

    /**
     * 获得关于我们 - 媒体报道 具体详情
     * @param int $id 主键ID
     */
    public function getMediaReport($id) {
        $mediaReportKey = $this->getAboutUsMediaReportHashKey($id);
        if (!$this->redis->exists($mediaReportKey)) {
            return;
        } else {
            return $this->redis->hGetAll($mediaReportKey);
        }
    }

    /**
     * 获得关于我们 - 媒体报道列表
     * @param array $params
     * @param callable $callback 数据加工方法
     * @return array
     */
    public function getMediaReportList($params, $callback=null) {
        $mediaReportStatusKey = $this->getAboutUsMediaReportStatusSetKey();
        $rs = $this->redis->sort($mediaReportStatusKey, $params);
        $kFields = array_map(function($v){return ltrim(strstr($v, '->'), '->');}, $params['get']);
        if ($rs) {
            $rs = array_chunk($rs, count($params['get']));
            foreach ($rs as $k => $row) {
                $rs[$k] = array_combine($kFields, $row);
                $rs[$k]['content'] = html_entity_decode( $rs[$k]['content'] );
                if ($callback && method_exists($this, $callback)) {
                    call_user_func_array([$this, $callback], [&$rs[$k]]);
                }
            }

            return ['total' => $this->redis->sCard($mediaReportStatusKey), 'rows' => ($rs)];
        }
    }

    /**
     * 获得关于我们 - 网站公告列表
     * @param array $params
     * @param callable $callback 数据加工方法
     * @return array
     */
    public function getNoticeList($params, $callback=null) {
        $noticeStatusKey = $this->getAboutUsNoticeStatusSetKey();
        $rs = $this->redis->sort($noticeStatusKey, $params);
        $kFields = array_map(function($v){return ltrim(strstr($v, '->'), '->');}, $params['get']);
        if ($rs) {
            $rs = array_chunk($rs, count($params['get']));
            foreach ($rs as $k => $row) {
                $rs[$k] = array_combine($kFields, $row);
                $rs[$k]['content'] = html_entity_decode( $rs[$k]['content'] );
                if ($callback && method_exists($this, $callback)) {
                    call_user_func_array([$this, $callback], [&$rs[$k]]);
                }
            }

            return ['total' => $this->redis->sCard($noticeStatusKey), 'rows' => ($rs)];
        }
    }

    /**
     * 关于我们 - 网站公告 数据加工方法
     * @param $param
     * @return array
     */
    public function handleNoticeData(&$param) {
        if (isset($param['userId'])) {
            $param['username'] = D('User')->getUserName($param['userId']);
        }
    }

    /**
     * 关于我们 - 媒体报道 数据加工方法
     * @param $param
     * @return array
     */
    public function handleMediaReportData(&$param) {
        if (isset($param['userId'])) {
            $param['username'] = D('User')->getUserName($param['userId']);
        }
    }
}