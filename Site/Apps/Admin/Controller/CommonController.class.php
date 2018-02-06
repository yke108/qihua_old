<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends  Controller{
    public function _initialize()
    {
        //验证登陆,没有登陆则跳转到登陆页面
        if (empty($_SESSION['username'])) $this->redirect('/');
//        if (empty($_SESSION['username'])) $this->redirect('Admin/Public/login',array('url'=>base64_encode(U())));
//print_r(MODULE_NAME . "/" . CONTROLLER_NAME . "/" . ACTION_NAME);exit;
        //权限验证
        if (!authCheck(MODULE_NAME . "/" . CONTROLLER_NAME . "/" . ACTION_NAME, session('userid'))) {
            //header('HTTP/1.1 404 Not Found');
            $return['message'] = '你没有权限';
            $return['status'] = false;
            $return['msg'] = '你没有权限';
            $return['code'] = 400;
           $this->ajaxReturn($return);
        }
    }

    protected function _empty(){
        $this->error('你请求的页面不存在!');
//            echo "<script>$.messager.alert('错误提示','你请求的页面不存在!!','error');</script>";
    }
}