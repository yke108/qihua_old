<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 商家工作台控制器
 */

class MemberController extends Controller{
	
	public function index(){
		$this->display('member-index');
	}
	
	
}