<?php
namespace Admin\Controller;

use Think\Controller;


/**
 * 内容管理控制器
 */
class ContentController extends CommonController {
    public function aboutUs() {
        //获取平台简介信息
        $contentsObj = D('Contents');
        $descriptionList = $contentsObj->getDescription();
        $lgeal = $contentsObj->getLegalStatement();
        $data = array();
        $data['about'] = empty($descriptionList['content']) ? '' : $descriptionList['content'];
        $data['com'] = empty($lgeal['content']) ? '' : $lgeal['content'];
        $this->contactList();
        $this->assign('data', $data);
        $this->display('about-us');
    }

    /*
     * 平台简介&&法律声明&&用户服务协议
     */
    public function about() {
        $arr = array();
        $type = I('post.type');
        $arr['content'] = I('post.content');
//        $title = I('post.title');
        $contentsObj = D('Contents');

        $rs = null;
        if ($type == '平台简介') {
            $rs = $contentsObj->editDescription($arr);
        } else if ($type == '法律声明') {
            $rs = $contentsObj->editLegalStatement($arr);
        }
        if ($rs) {
            $mess = array('code' => 200, 'msg' => '更新成功', 'data' => '');
        } else {
            $mess = array('code' => 400, 'msg' => '更新失败', 'data' => '');
        }
        $this->ajaxReturn($mess);
    }

    /*
     * 网站公告列表
     */

    public function noticeList() {
        $page = I('post.page');
        $pagesize = I('post.rows');

        $contentsObj = D('Contents');
        $sortParams = array(
            'limit' => [($page - 1) * $pagesize, $pagesize],
            'get' => array('hash:aboutUs:notice:*->id', 'hash:aboutUs:notice:*->title',
                'hash:aboutUs:notice:*->addTime', 'hash:aboutUs:notice:*->userId',
                'hash:aboutUs:notice:*->content',
            ),
            'sort' => 'desc',
            'alpha' => true,
            'by' => 'hash:aboutUs:notice:*->id',
        );
        $res = $contentsObj->getNoticeList($sortParams, 'handleNoticeData');
        $this->ajaxReturn(array('total' => empty($res['total']) ? 0 : $res['total'], 'rows' => empty($res['rows']) ? 0 : $res['rows']));
    }


    /*
     * 新增和编辑网站公告
     */
    public function notice() {
        $notice['title'] = I('post.title');
        $notice['content'] = I('post.content');
//        $notice['type'] = '网站公告';
//        $notice['createTime'] = time();
//        $contents = D('Contents');
        foreach ($notice as $key => $value) {
            if (empty($value)) {
                $mess = array('code' => 400, 'msg' => '标题和内容都不可以为空！', 'data' => '');
                $this->ajaxReturn($mess);
            }
        }
        $notice['userId'] = session('userid');
        $contentsObj = D('Contents');
        if (empty($_POST['id'])) {
            $res = $contentsObj->addNotice($notice);
        } else {
            $id = I('post.id') + 0;
            $res = $contentsObj->editNotice($id, $notice);
        }
        if ($res) {
            $mess = array('code' => 200, 'msg' => '操作成功', 'data' => '');
        } else {
            $mess = array('code' => 400, 'msg' => '操作失败', 'data' => '');
        }
        $this->ajaxReturn($mess);
    }

    /*
     * 删除网站公告，包涵批量删除和删除
     */
    public function delnotice() {
        if (IS_AJAX && IS_POST) {
            $id = I('post.id') ;
             $ids = explode(',',$id);
            foreach( $ids as $v){
                $res = D('Contents')->delNotice( $v );
            }
            if ($res) {
                $mess = array('code' => 200, 'msg' => '操作成功', 'data' => '');
            } else {
                $mess = array('code' => 400, 'msg' => '操作失败', 'data' => '');
            }
            $this->ajaxReturn($mess);
        }
    }

    /*
     * 新增和修改媒体报道
     */
    public function news() {
        $new['title'] = I('post.title');
        $new['content'] = I('post.content');
        $new['referer'] = I('post.from');
//        $new['type'] = '媒体报道';
        $new['reportDate'] = I('post.date');
        foreach ($new as $key => $value) {
            if (empty($value)) {
                $mess = array('code' => 400, 'msg' => '标题和内容都不可以为空！', 'data' => '');
                $this->ajaxReturn($mess);
            }
        }
        $new['userId'] = session('userid');
        if (strlen($_POST['img']) > 1000) {
            $base = I('post.img');
            $new['img'] = toImg($base);
        }
        $contentsObj = D('Contents');
        if (empty($_POST['id'])) {
            $res = $contentsObj->addMediaReport($new);
        } else {
            $id = I('post.id') + 0;
            $res = $contentsObj->editMediaReport($id, $new);
        }
        if ($res) {
            $mess = array('code' => 200, 'msg' => '操作成功', 'data' => '');
        } else {
            $mess = array('code' => 400, 'msg' => '操作失败', 'data' => '');
        }
        $this->ajaxReturn($mess);
    }

    /*
     *媒体报道列表
     */
    public function newsList() {
        $page = I('post.page');
        $pagesize = I('post.rows');

        $contentsObj = D('Contents');
        $sortParams = array(
            'limit' => [($page - 1) * $pagesize, $pagesize],
            'get' => array('hash:aboutUs:mediaReport:*->id', 'hash:aboutUs:mediaReport:*->title',
                    'hash:aboutUs:mediaReport:*->img', 'hash:aboutUs:mediaReport:*->reportDate',
                    'hash:aboutUs:mediaReport:*->referer', 'hash:aboutUs:mediaReport:*->userId',
                    'hash:aboutUs:mediaReport:*->content'
                    ),
            'sort' => 'desc',
            'alpha' => true,
            'by' => 'hash:aboutUs:mediaReport:*->id',
        );
        $res = $contentsObj->getMediaReportList($sortParams, 'handleMediaReportData');
        $this->ajaxReturn(array('total' => empty($res['total']) ? 0 : $res['total'], 'rows' => empty($res['rows']) ? 0 : $res['rows']));
    }

    /**
     * 删除媒体报道
     */
    public function delNews() {
        if (IS_AJAX && IS_POST) {
            $id = I('post.id');
            $ids = explode( ',',$id );
            foreach( $ids as $v ){
                $res = D('Contents')->delMediaReport( $v );
            }
            if ($res) {
                $mess = array('code' => 200, 'msg' => '操作成功', 'data' => '');
            } else {
                $mess = array('code' => 400, 'msg' => '操作失败', 'data' => '');
            }
            $this->ajaxReturn($mess);
        }
    }

    /*
     * 合作伙伴列表
     */
    public function partnerList() {
        $model = D('Home/Partner');
        $data = $model->lists(array());
        $ret = array(
            array(
                'attributes' => array(
                    'img'  => '{"type": 1}',
                    'type' => 1,
                ),
                'children'   => array(),
                'createTime' => null,
                'id'         => 1,
                'parent_id'  => 0,
                'path'       => 0,
                'text'       => '合作伙伴',
                'type'       => 0,
            ),
        );
        if (!empty($data['lists'])) {
            foreach ($data['lists'] as $v) {
                $new = array(
                    'attributes' => array(
                        'img'  => $v['img'],
                        'type' => 2,
                    ),
                    'createTime' => $v['addTime'],
                    'id'         => $v['id'],
                    'parent_id'  => 1,
                    'path'       => "1," . $v['id'],
                    'text'       => $v['text'],
                    'type'       => 0,
                );
                $ret[0]['children'][] = $new;
            }
        }
        echo json_encode($ret);
    }


    public function partner() {
        $this->display();
        exit;

    }

    //新增合作伙伴
    public function addpartner() {
        $data = $_POST;
        if (!$_POST) {
            exit();
        }
        $model = D('Home/Partner');
        $add['text'] = $data['text'];
        if (!empty($data['id'])) {
            if (!empty($data['img'])) {
                $add['img'] = toImg($data['img']);
            }
            $result = $model->edit($data['id'], $add);
            if ($result) {
                $ret = array('code' => 200, 'msg' => '修改成功', 'data' => '');
            } else {
                $ret = array('code' => 400, 'msg' => '修改失败', 'data' => '');
            }
        } else {
            if (!empty($data['img'])) {
                $add['img'] = toImg($data['img']);
            }
            $result = $model->insert($add);
            if ($result) {
                $ret = array('code' => 200, 'msg' => '添加成功', 'data' => '');
            } else {
                $ret = array('code' => 400, 'msg' => '添加失败', 'data' => '');
            }
        }
        $this->ajaxReturn($ret);
    }

    //删除帮助中心
    public function delpartner() {
        if (!$_POST['id']) exit;
        $id = I('post.id');
        $model = D('Home/Partner');
        $result = $model->remove($id);
        if ($result) {
            $ret = array('code' => 200, 'msg' => '删除成功', 'data' => '');
        } else {
            $ret = array('code' => 400, 'msg' => '删除失败', 'data' => '');
        }
        $this->ajaxReturn($ret);
    }

    /*
     * 文件上传
     */
    public function upload() {
        $upload = new \Think\Upload();
        $upload->maxSize = 2097152;
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->savePath = 'Admin/'; // 设置附件上传目录
        $upload->subName = array('date', 'Ymd');
        $upload->saveName = array('time', '');
        $info = $upload->upload();
        if (!$info) {
            echo json_encode(array('error' => 1));
        }
        foreach ($info as $file) {
            $filename = '/Uploads/' . $file['savepath'] . $file['savename'];
        }
        $arr = array();
        //	print_r($filename);exit;
        $arr['error'] = 0;
        $arr['url'] = $filename;
        $this->ajaxReturn($arr);
    }

    /*
     * 用户服务协议
     */
    public function protocol() {
        $contentsObj = D('Contents');
        if (empty($_POST)) {
            $res = $contentsObj->getProtocol();
            if (!empty($res)) {
                $this->assign('data', $res);
            }
            $this->display();
        } else {
            $arr['title'] = I('post.title');
            $arr['content'] = I('post.content');
            $res = $contentsObj->editProtocol($arr);
            if ($res) {
                $mess = array('code' => 200, 'msg' => '保存成功！', 'data' => '');
            } else {
                $mess = array('code' => 400, 'msg' => '保存失败！', 'data' => '');
            }
            $this->ajaxReturn($mess);
        }
    }


    //帮助中心
    public function help() {
        $this->display();
    }

    //帮助中心列表
    public function helpList() {
        if (!$_POST['id']) $id = 0;
        $id = I('post.id');
        $partner = D('Partner');
        $res = $partner->gethelp($id);
        echo json_encode($res);
    }

    //新增帮助中心
    public function addhelp() {
        if (!$_POST) exit;
        $data = I('post.');
        $data['createTime'] = time();
        if (empty($data['text'])) $this->ajaxReturn(array('code' => 400, 'msg' => '标题不能为空！', 'data' => ''));

        //if(empty($data['content']))$this->ajaxReturn(array('code'=>400,'msg'=>'内容不能为空！','data'=>''));
        $partner = D('Partner');
        $this->ajaxReturn($partner->addhelp($data));
    }

    //联系我们
    public function contact() {
        $arr['address'] = I('post.address');
        $arr['companyName'] = I('post.company');
//        $arr['title']=I('post.title');
        $arr['tel'] = I('post.phone');
        $arr['serviceTel'] = I('post.fuwu');
        $arr['email'] = I('post.mail');
        $arr['fax'] = I('post.fax');
        if (I('post.title') == '佛山总公司') {
            $type = 1;
        } else {
            $type = 2;
        }

        $contentsObj = D('Contents');
        $res = $contentsObj->editContact($arr, $type);
        if ($res) {
            $mess = array('code' => 200, 'msg' => '保存成功！', 'data' => '');
        } else {
            $mess = array('code' => 400, 'msg' => '保存失败！', 'data' => '');
        }
        $this->ajaxReturn($mess);
    }

    //商务合作
    public function cooperate() {
        $contentsObj = D('Contents');
        //供应商合作
//		$rec['title']='供应商合作';
        $rec['phone'] = I('post.providephone');
        $rec['mail'] = I('post.providemail');
        $rec['name'] = I('post.provider');
        $rec['qq'] = I('post.provideqq');
        $contentsObj->editCooperation($rec, 1);
        //采购合作
//		$rec['title']='采购合作';
        $rec['phone'] = I('post.buyphone');
        $rec['mail'] = I('post.buymail');
        $rec['name'] = I('post.buyer');
        $rec['qq'] = I('post.buyqq');
        $contentsObj->editCooperation($rec, 2);
        //品牌推广
//		$rec['title']='品牌推广';
        $rec['phone'] = I('post.extendphone');
        $rec['mail'] = I('post.extendmail');
        $rec['name'] = I('post.extender');
        $rec['qq'] = I('post.extendqq');
        $contentsObj->editCooperation($rec, 3);

        //投资洽谈
//		$rec['title']='投资洽谈';
        $rec['phone'] = I('post.investphone');
        $rec['mail'] = I('post.investmail');
        $rec['name'] = I('post.invest');
        $rec['qq'] = I('post.investqq');
        $contentsObj->editCooperation($rec, 4);

        //客户服务
//		$rec['title']='客户服务';
        $rec['phone'] = I('post.kefuphone');
        $rec['mail'] = I('post.kefumail');
        $rec['name'] = I('post.kefu');
        $rec['qq'] = I('post.kefuqq');
        $res = $contentsObj->editCooperation($rec, 5);

        if ($res) {
            $mess = array('code' => 200, 'msg' => '保存成功！', 'data' => '');
        } else {
            $mess = array('code' => 400, 'msg' => '保存失败！', 'data' => '');
        }
        $this->ajaxReturn($mess);
    }

    //联系我们列表
    protected function contactList() {
        $contentsObj = D('Contents');
        //佛山总公司
        $this->assign('fs', $contentsObj->getContact(1));
        //广州分公司
        $this->assign('gz', $contentsObj->getContact(2));
        //供应商合作
        $this->assign('p', $contentsObj->getCooperation(1));
        //采购合作
        $this->assign('b', $contentsObj->getCooperation(2));
        //品牌推广
        $this->assign('e', $contentsObj->getCooperation(3));
        //投资洽谈
        $this->assign('i', $contentsObj->getCooperation(4));
        //客户服务
        $this->assign('k', $contentsObj->getCooperation(5));
        //投诉建议
    }
}