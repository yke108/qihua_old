<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
//use Think\Controller;
class AuthController extends CommonController {
    //用户列表
    public function index(){
        $this->display();
    }

    public function userList(){
        $user=D('User');

        //搜索条件
        $map=array();
        $username=I('post.username','','string');
        $realname=I('post.realname','','string');
        $department=I('post.department','','int');
        $state=I('post.state');

        !empty($username)?$map['U.username']=array('like','%'.$username.'%'):'';
        !empty($realname)?$map['U.realname']=array('like','%'.$realname.'%'):'';
        !empty($department)?$map['U.department']=array('like','%'.$department.'%'):'';
        //!empty($state)?$state=$map['U.state']: $map['U.status']=1;;

        if(!empty($did)){
            $department = D('Department');
            $childIdArr = $department->getDepartmentChildId($did);
            if($childIdArr)$map['GD.did'] = array('in',implode(',',$childIdArr));
        }

        $page = I('post.page',1,'int');
        $rows=I('post.rows',20,'int');

        //排序
//        $sort=$ck->in('排序','sort','cnennumstr','uid',0,0);
//        $order=$ck->in('规则','order','cnennumstr','desc',0,0);
        //var_dump($map);exit;
        //$map['U.status']=$state;
        $map['U.status']=1;
        if( $state !== '全部状态' && ($state !== '')){
            $map['U.state']=intval( $state );
        }else{
            unset($map['U.state']);
        }
//var_dump($map);exit;
        $field='U.id,U.realname,U.username,U.password,U.sex,U.department,U.group,U.addtime,US.realname as creater,U.state,GA.group_id as gid,GD.did,U.mobile';//查询字段
        $count = $user->getUserCount($map);//读取总行数

        $info = $user->getUserList($map,$field,$page,$rows);//读取列表

        if(!empty($info)){
            $data['total']=$count;
            $data['rows']=$info;
        }else{
            $data['total']=0;
            $data['rows']=0;
        }

        $this->ajaxReturn($data);
    }

    //新增用户
    public function userAdd(){
        $rule=array(
            array('username','require','用户名必须填写！'),
            array('username','','用户名已经存在！',0,'unique',3),
            array('password','require','密码必须填写！'),
            array('realname','require','姓名必须填写！'),
            array('realname','','姓名已经存在！',1,'unique',1),
            array('sex',array(1,0),'性别的值范围不正确！',1,'in'),
            array('department','require','所属部门必须选择！'),

        );
        if(!IS_POST) exit;
        $user = D('User');
        $data = I('post.');
        $gid=$data['gid'];
        $did=$data['did'];
        $data['salt']=rand(1000,9999);
        if (!$data=$user->validate($rule)->create($data)){
            $return['msg']=$user->getError();
            $return['code']=400;
        }else{
            $data['gid']=$gid;
            $data['did']=$did;
            $data['creater']=$_SESSION['userid'];
            $data['password']=passencrypt($data['password'],$data['salt']);
            $addResult = $user->userInsert($data);
            if(!$addResult){
                $return['msg']='添加失败!';
                $return['code']=400;
            }else{
                $return['msg']='添加成功!';
                $return['code']=200;
            }
        }

        $this->ajaxReturn($return);
    }

    //修改用户
    public function userSave(){
        if(!IS_POST) exit;
        $user = D('User');
        $data = I('post.');
        $gid=$data['gid'];
        $did=$data['did'];
        if(empty($data['id']))exit;
        $map['id']=$data['id'];
        $data['salt']=rand(1000,9999);
        if (!$data=$user->create($data)){
            $return['msg']=$user->getError();
            $return['code']=400;
        }else{
            $data['gid']=$gid;
            $data['did']=$did;
            $data['creater']=$_SESSION['userid'];
            $data['password']=passencrypt($data['password'],$data['salt']);
            $editResult = $user->userUpdate($map,$data);
            if(!$editResult){
                $return['msg']='修改失败!';
                $return['code']=400;
            }else{
                $return['msg']='修改成功!';
                $return['code']=200;
            }
        }

        $this->ajaxReturn($return);
    }

    //删除用户
    public function userDel(){
        if(!IS_POST) exit;
        $id = I('post.id');
        if(empty($id))exit;
        $user = D('User');
        $map['id']=array('in',$id);

        //删除操作
        $result = $user->userDelete($map);
        if(!$result){
            $return['msg']='删除失败!';
            $return['code']=400;
        }else{
            $return['msg']='删除成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    //用户批量启用
    public function userBatchActive(){
        if(!IS_POST) exit;
        $id = I('post.id');
        if(empty($id))exit;
        $user = D('User');
        $map['id']=array('in',$id);

        $data['state']=1;

        //启用操作
        $result = $user->userActiveUpdate($map,$data);
        if(!$result){
            $return['msg']='启用失败!';
            $return['code']=400;
        }else{
            $return['msg']='启用成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    //批量禁用
    public function userBatchInactive(){
        if(!IS_POST) exit;
        $id = I('post.id');
        if(empty($id))exit;
        $user = D('User');
        $map['id']=array('in',$id);

        $data['state']=0;

        //启用操作
        $result = $user->userActiveUpdate($map,$data);
        if(!$result){
            $return['msg']='禁用失败!';
            $return['code']=400;
        }else{
            $return['msg']='禁用成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    //根据部门id读取用户组列表
    public function idGetGroupList(){
        if(!IS_POST) exit;
        $id = I('post.id','','int');//读取部门id
        if(empty($id))exit;

        $department = D('Department');
        $info = $department->getConditionGroupList($id);

        if(!empty($info)){
            $return['msg']='读取列表成功!';
            $return['code']=200;
            $return['data']=$info;
        }else{
            $return['msg']='读取列表失败!';
            $return['code']=400;
        }

        $this->ajaxReturn($return);
    }

    //用户组页面
    public function group(){
        $this->display();
    }

    //角色列表
    public function groupList(){
        if(!IS_POST) exit;
        $page = I('post.page',1,'int');
        $rows=I('post.rows',5,'int');
        $title = I('post.title');
        $did = I('post.did','','int');
        $authGroup = D('AuthGroup');

        !empty($title)?$map['AG.title']=array('like','%'.$title.'%'):'';
        if(!empty($did)){
            $department = D('Department');
            $childIdArr = $department->getDepartmentChildId($did);
            if($childIdArr)$map['GD.did'] = array('in',implode(',',$childIdArr));
        }

        $map['AG.status']=array('neq',0);
        $map['D.status']=1;

        $field='AG.id,AG.title,D.name,AG.addtime,A.realname,AG.status,GD.did';//查询字段
        $count = $authGroup->getGroupCount($map);//读取总行数

        $info = $authGroup->getGroupList($map,$field,$page,$rows);//读取列表

        $data['total']=$count;
        $data['rows']=$info;
        $this->ajaxReturn($data);
    }

    //角色增加
    public function groupAdd(){
        if(!IS_POST) exit;
        $authGroup = D('AuthGroup');
        $data = I('post.');
        $groupData['title']=$data['title'];
        if(!empty($data['rules']))$groupData['rules']=$data['rules'];

        if(!empty($data['did'])){
            if (!$groupData=$authGroup->create($groupData)){
                $return['msg']=$authGroup->getError();
                $return['code']=400;
            }else{
                $add_result = $authGroup->groupInsert($groupData,$data['did']);
                if(!$add_result){
                    $return['msg']='添加失败!';
                    $return['code']=400;
                }else{
                    $return['msg']='添加成功!';
                    $return['code']=200;
                }
            }
        }else{
            $return['msg']='所属部门必须选择!';
            $return['code']=400;
        }


        $this->ajaxReturn($return);
    }

    //修改角色
    public function groupSave(){
        if(!IS_POST) exit;
        $data = I('post.');
        if(empty($data['id']))exit;
        $authGroup = D('AuthGroup');
        $groupData['title']=$data['title'];
        if(!empty($data['rules']))$groupData['rules']=$data['rules'];
        $map['id']=$data['id'];//条件

        if(!empty($data['did'])){
            $updateResult = $authGroup->groupUpdate($map,$groupData,$data['did']);
            if (!$updateResult){
                $return['msg']='更新失败!';
                $return['code']=400;
            }else{
                $return['msg']='添加成功!';
                $return['code']=200;
            }
        }else{
            $return['msg']='所属部门必须选择!';
            $return['code']=400;
        }


        $this->ajaxReturn($return);
    }

    //删除角色
    public function groupDel(){
        if(!IS_POST) exit;
        $id = I('post.id');
        if(empty($id))exit;
        $authGroup = D('AuthGroup');
        $map['id']=array('in',$id);
        //删除操作
        $selectResult = $authGroup->getGroupUnion($map);
        if($selectResult>0){
            $return['msg']='删除用户组有存在与用户关联，请先删除用户!';
            $return['code']=400;
        }else{
            $result = $authGroup->groupDelete($map);
            if(!$result){
                $return['msg']='删除失败!';
                $return['code']=400;
            }else{
                $return['msg']='删除成功!';
                $return['code']=200;
            }
        }

        $this->ajaxReturn($return);
    }

    //批量启用
    public function groupBatchActive(){
        if(!IS_POST) exit;
        $id = I('post.id');
        if(empty($id))exit;
        $authGroup = D('AuthGroup');
        $map['id']=array('in',$id);

        $data['status']=1;

        //启用操作
        $result = $authGroup->groupActiveUpdate($map,$data);
        if(!$result){
            $return['msg']='删除失败!';
            $return['code']=400;
        }else{
            $return['msg']='删除成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    //批量禁用
    public function groupBatchInactive(){
        if(!IS_POST) exit;
        $id = I('post.id');
        if(empty($id))exit;
        $authGroup = D('AuthGroup');
        $map['id']=array('in',$id);

        $data['status']=2;

        //启用操作
        $result = $authGroup->groupActiveUpdate($map,$data);
        if(!$result){
            $return['msg']='删除失败!';
            $return['code']=400;
        }else{
            $return['msg']='删除成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    public function department(){
        $this->display();
    }

    //部门列表
    public function departmentList(){
        if(!IS_POST) exit;
        $department=D('Department');
        $map['status']=1;//状态为1
        $field = 'id,name,parentid,parentidlist,depth';

        $gid='';
        if(!empty(I('post.id')))$gid=I('post.id');
        $list = $department->getDepartmentList($map,$field,$gid);

        if(!empty($list)){
            $data['rows']=$list;
        }else{
            $data['rows']=0;
        }

        $this->ajaxReturn($data['rows']);
    }

    //新增部门
    public function departmentAdd(){
        if(!IS_POST) exit;
        $Department = D('Department');
        $data = I('post.');
        $map['id']=$data['parentid'];
        $map['stauts']=1;
        $field='depth,parentidlist';
        $selectData = $Department->getDepartmentInfo($map,$field);//查询父级等级以及所有父路径
        $data['depth']=$selectData['depth']+1;

        if (!$Department->create($data)){
            $return['msg']=$Department->getError();
            $return['code']=400;
        }else{
            $update_result = $Department->departmentInsert($selectData,$data);
            if(!$update_result){
                $return['msg']='添加失败!';
                $return['code']=400;
            }else{
                $return['msg']='添加成功!';
                $return['code']=200;
            }
        }
        $this->ajaxReturn($return);
    }

    //修改部门
    public function departmentSave(){
        if(!IS_POST) exit;
        $Department = D('Department');
        $data = I('post.');
        $map['id']=I('post.id');
        $data['name']=I('post.name');

        $update_result = $Department->departmentUpdate($map,$data);
        if (!$update_result){
            $return['msg']='更新失败!';
            $return['code']=400;
        }else{
            $return['msg']='添加成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    //删除部门
    public function departmentDel(){
        if(!IS_POST) exit;
        $id = I('post.id','','int');
        if(empty($id))exit;
        $Department = D('Department');
        $map['CONCAT(\',\',parentidlist,\',\')']=array('like','%,'.$id.',%');
        $map['stauts']=1;

        //删除操作
        $selectResult = $Department->getDepartmentUnion($map);
        if($selectResult>0){
            $return['msg']='删除部门有存在与用户组关联，请先删除用户组!';
            $return['code']=400;
        }else{
            $result = $Department->departmentDelete($map);
            if(!$result){
                $return['msg']='删除失败!';
                $return['code']=400;
            }else{
                $return['msg']='删除成功!';
                $return['code']=200;
            }
        }

        $this->ajaxReturn($return);
    }

    //权限列表
    public function rule(){
        $this->display();
    }

    //规则列表
    public function ruleList(){
        //if(!IS_POST) exit;
        $rule = D('AuthRule');
        $map['type']=1;//默认规则是没有特定条件
        $map['status']=1;
        $field='id,name,title,parentid,parentidlist,depth';
        $gid='';
        if(!empty(I('post.id')))$gid=I('post.id');
        $list = $rule->getRuleList($map,$field,$gid);

        $info['WEB']=$list;

        if(!empty($info)){
            $data['code']=200;
            $data['msg']='读取成功';
            $data['data']=$info;
        }else{
            $data['code']=400;
            $data['msg']='读取失败';
            $data['data']='';
        }

        $this->ajaxReturn($data);
    }

    //新增权限
    public function ruleAdd(){
        if(!IS_POST) exit;
        $Rule = D('AuthRule');
        $data = I('post.');
        $map['id']=$data['parentid'];
        $map['stauts']=1;
        $field='depth,parentidlist';
        $selectData = $Rule->getRuleInfo($map,$field);//查询父级等级以及所有父路径
        $data['depth']=$selectData['depth']+1;

        //删除数据库中name重复而且status为0的数据。
        $Rule -> where(array('name' => $data['name'], 'status' => '0' )) -> delete();

        if (!$Rule->create($data)){
            $return['msg']=$Rule->getError();
            $return['code']=400;
        }else{
            $update_result = $Rule->ruleInsert($selectData,$data);
            if(!$update_result){
                $return['msg']='添加失败!';
                $return['code']=400;
            }else{
                $return['msg']='添加成功!';
                $return['code']=200;
            }
        }
        $this->ajaxReturn($return);
    }

    //修改权限
    public function ruleSave(){
        $Rule = D('AuthRule');
        $data = I('post.');
        $map['id']=$data['id'];
        $update_result = $Rule->ruleUpdate($map,$data);
        if (!$update_result){
            $return['msg']='更新失败!';
            $return['code']=400;
        }else{
            $return['msg']='添加成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    //删除权限
    public function ruleDel(){
        if(!IS_POST) exit;
        $id = I('post.id','','int');
        if(empty($id))exit;
        $Rule = D('AuthRule');
        $map['CONCAT(\',\',parentidlist,\',\')']=array('like','%,'.$id.',%');
        $map['stauts']=1;

        //删除操作
        $result = $Rule->ruleDelete($map);
        if(!$result){
            $return['msg']='删除失败!';
            $return['code']=400;
        }else{
            $return['msg']='删除成功!';
            $return['code']=200;
        }
        $this->ajaxReturn($return);
    }

    //导出数据--角色列表
    public function expAuth(){
        $authGroup = D('AuthGroup');
        $map['AG.status']=array('neq',0);
        $map['D.status']=1;
        $field='AG.title,D.name,AG.addtime,A.realname,AG.status';//查询字段
        $xlsName  = "角色列表";
        $xlsCell  = array(
            array('title','角色名'),
            array('name','所属部门'),
            array('addtime','创建时间'),
            array('realname','创建人'),
            array('status','状态')
        );

        $xlsData  =  $authGroup->getDataList($map,$field);//读取列表
        exportExcel($xlsName,$xlsCell,$xlsData);
    }

    //导出数据--用户列表
    public function expUser(){
        $user=D('User');
        $map['U.status']=1;
        $field='U.realname,U.username,U.department,U.group,U.addtime,US.realname as creater,U.state';//查询字段

        $xlsName  = "用户列表";
        $xlsCell  = array(
            array('username','用户名'),
            array('realname','姓名'),
            array('department','所在部门'),
            array('group','角色'),
            array('addtime','创建时间'),
            array('creater','创建人'),
            array('state','状态'),
        );
        $xlsData = $user->expUser($map,$field);//读取列表
        exportExcel($xlsName,$xlsCell,$xlsData);
    }

    //后台权限验证
    public function AuthUser()
    {
        $map['uid'] = $_SESSION['userid'];;
        $field = 'uid,group_id';
        $arr = D('Auth')->getGroupId($field, $map);
        if ($arr['group_id'] == 1) {
            //获取所有的权限列表
            $ret = array(
                'code' => 200,
                'msg' => '权限列表',
                'data' => array(
                    'isAdmin' => true,
                    'rules' => array(

                    ),
                ),
            );
        } else {
            $map['id'] = $arr['group_id'];
            $rules = 'id,rules';
            //获取当前的权限id
            $GroupRules = D('AuthGroup')->getGroupRules($map, $rules);
            $AuthRule = D('AuthRule');
            $AuthFiled = 'name,parentidlist';
            $RulesId=explode(',',ltrim($GroupRules['rules'],','));
            foreach($RulesId as $k=>$v){
                $rule['id']=$v;
                $arr[$k]=$AuthRule->getRuleInfo($rule,$AuthFiled);
                $AuthId=explode(',',ltrim($arr[$k]['parentidlist'],','));
                $rs['id']=$AuthId[0];
                $art[$k]=$AuthRule->getRuleInfo($rs,$AuthFiled);
                $rst[trim($art[$k]['name'],'/')][]=trim($arr[$k]['name'],'/');
            }
            $ret = array(
                'code' => 200,
                'msg' => '权限列表',
                'data' => array(
                    'isAdmin' => false,
                    'rules' => array(
                        $rst
                    ),
                ),
            );
        }
        $this->ajaxReturn($ret);
    }

}