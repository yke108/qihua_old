<?php
namespace Home\Model;
use Think\Model;

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
     * @return array
     */
    public function getMediaReportList($params) {
        $mediaReportStatusKey = $this->getAboutUsMediaReportStatusSetKey();
        $rs = $this->redis->sort($mediaReportStatusKey, $params);
        $kFields = array_map(function($v){return ltrim(strstr($v, '->'), '->');}, $params['get']);
        if ($rs) {
            $rs = array_chunk($rs, count($params['get']));
            foreach ($rs as $k => $row) {
                $rs[$k] = array_combine($kFields, $row);
            }

            return ['total' => $this->redis->sCard($mediaReportStatusKey), 'rows' => ($rs)];
        }
    }

    /**
     * 获得关于我们 - 网站公告列表
     * @param array $params
     * @return array
     */
    public function getNoticeList($params) {
        $noticeStatusKey = $this->getAboutUsNoticeStatusSetKey();
        $rs = $this->redis->sort($noticeStatusKey, $params);
        $kFields = array_map(function($v){return ltrim(strstr($v, '->'), '->');}, $params['get']);
        if ($rs) {
            $rs = array_chunk($rs, count($params['get']));
            foreach ($rs as $k => $row) {
                $rs[$k] = array_combine($kFields, $row);
            }

            return ['total' => $this->redis->sCard($noticeStatusKey), 'rows' => ($rs)];
        }
    }

    /**
     * 获得关于我们 - 网站公告 最近公告信息(默认:最近一个月的前三条; 缓存结果, 时间:1800秒)
     * @param int $limit 查询条数
     * @param int $month 查询最近的间隔时间
     * @param int $ttl 缓存时间
     * @return array
     */
    public function getLastestNoticeList($limit=3, $month=1, $ttl=1800) {
        $params = array(
            'limit' => [0, $limit],
            'get' => array('hash:aboutUs:notice:*->id', 'hash:aboutUs:notice:*->title',
                'hash:aboutUs:notice:*->editTime', 'hash:aboutUs:notice:*->userId',
                'hash:aboutUs:notice:*->content',
            ),
            'sort' => 'desc',
            'alpha' => true,
            'by' => 'hash:aboutUs:notice:*->id',
        );

        //暂时缓存KEY
        $tempKey = 'tmp:string:notice:lastest';
        if ($this->redis->exists($tempKey)) {
            return json_decode($this->redis->get($tempKey), 1);
        } else {
            $rs = $this->getNoticeList($params);
            $arr = [];
            if (!empty($rs['rows'])) {
                $time = strtotime('-'.$month.' months');
                foreach ($rs['rows'] as $row) {
                    if ($row['editTime'] >= $time) {
                        $arr[] = $row;
                    }
                }

                if ($arr) {
                    $this->redis->set($tempKey, json_encode($arr), ['EX' => $ttl]);
                    return $arr;
                }
            }
            return;
        }
    }
}