<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model{
    protected $_validate = array(
        array('username','require','用户名必须填写！'),
        array('username','','用户名已经存在！',0,'unique',3),
        array('password','require','密码必须填写！'),
        array('realname','require','姓名必须填写！'),
        array('realname','','姓名已经存在！',1,'unique',1),
        array('sex',array(1,0),'性别的值范围不正确！',1,'in'),
//        array('mobile','require','手机号必须填写！'),
//        array('mobile','number','手机号格式不对！',1),
//        array('email','email','邮箱格式不对！'),
        array('department','require','所属部门必须选择！'),
//        array('department','number','所属部门格式不正确！',1),
        array('group','require','所属角色必须选择！'),
//        array('group','number','所属角色格式不正确！'),
    );

    protected $_auto = array (
        //array('password','passencrypt',3,'callback'),
        array('status','1'),  // 新增的时候把status字段设置为1
        array('addtime','time',1,'function'),
        array('updatetime','time',2,'function'),
        array('creater','getAssignment',3,'function'),
        array('status','1'),
    );

    //用户添加
    public function userInsert($data){
        //根据部门id查询部门名称
        if(!empty($data['did'])){
            $department=M('Department');
            $departmentMap['id']=$data['did'];
            $departmentArr = $department->where($departmentMap)->field('name')->find();
            $data['department']=$departmentArr['name'];
        }

        //根据角色id查询部门名称
        if(!empty($data['gid'])){
            $authGroup=M('AuthGroup');
            $gid=$data['gid'];
            $groupMap['id']=$gid;
            $groupArr = $authGroup->where($groupMap)->field('title')->find();
            $data['group']=$groupArr['title'];
        }
        $insert_id = $this->add($data);
        if($insert_id){
            //新建记录到用户角色关联表
            if(!empty($gid)){
                $groupAccess=D('GroupAccess');
                $groupAccessData['uid']=$insert_id;
                $groupAccessData['group_id']=$gid;
                $groupAccess->add($groupAccessData);
            }
        }
        return $insert_id;
    }


    //用户修改
    public function userUpdate($map,$data){
        //根据部门id查询部门名称
        if(!empty($data['did'])){
            $department=M('Department');
            $departmentMap['id']=$data['did'];
            $departmentArr = $department->where($departmentMap)->field('name')->find();
            $data['department']=$departmentArr['name'];
        }

        //根据角色id查询部门名称
        if(!empty($data['gid'])){
            $authGroup=M('AuthGroup');
            $gid=$data['gid'];
            $groupMap['id']=$gid;
            $groupArr = $authGroup->where($groupMap)->field('title')->find();
            $data['group']=$groupArr['title'];
        }
      /*  if( isset( $data['creater'] ) ){
            unset( $data['creater'] );
        }*/

        $result = $this->where($map)->save($data);
        if($result!==false){
            $groupAccess=D('GroupAccess');
            $groupAccessMap['uid']=$map['id'];//条件

            $groupAccessData['group_id']=$gid;
            $groupAccessResult = $groupAccess->where($groupAccessMap)->save($groupAccessData);
            return $groupAccessResult!==false?true:false;
        }
    }


    //角色删除
    public function userDelete($map){
        //删除操作
        $data['status']=0;
        $result = $this->where($map)->save($data);
        return $result!==false?true:false;
    }

    //用户禁用状态修改
    public function userActiveUpdate($map,$data){
        $result = $this->where($map)->save($data);
        return $result!==false?true:false;
    }

    //检验验证码
    protected function checkverify($captcha){
        $verify=new \Think\Verify();
        if(!$verify->check($captcha)){
            return false;
        }else{
            return true;
        }
    }

    //查询用户信息
    public function getaccountinfo($username,$password,$salt){
        $map['username'] = $username;
        $map['password'] = $this->passencrypt($password,$salt);
        $map['status'] = 1;
        $map['state'] = 1;
        $status=$this->field('id,username,lastlogintime')->where($map)->find();//查询登录状态

        if(false===$status){
            return false;
        }else{
            return $status;
        }
    }

    //修改登錄狀態
    public function loginupdate($username,$password,$salt){
        $map['username'] = $username;
        $map['password'] = $this->passencrypt($password,$salt);
        $map['status'] = 1;
        $map['state'] = 1;

        $data['lastloginip']=get_client_ip();
        $data['lastlogintime']=$_SERVER['REQUEST_TIME'];
        $info=$this->where($map)->save($data);

        if(false===$info){
            return false;
        }else{
            return $info;
        }
    }

    //检验密码
    protected  function checkpasswd($password,$username){
        $map['username'] = $username;
        $where=array('username'=>$username);
        $field='salt';
        $salt=$this->getUserData($field,$where);
        $map['password'] = $this->passencrypt($password,$salt['salt']);
        $map['status'] = 1;
        $map['state'] = 1;
        $result = $this->where($map)->find();
        if(!$result){
            return false;
        }else{
            return true;
        }
    }

    //密码加密
    protected function passencrypt($password,$salt=''){
        return md5(md5($password).$salt);
    }

    //检验密码
    protected  function readinfo($password,$username,$salt){
        $map['username'] = $username;
        $where=array('username'=>$username);
        $field='salt';
        $salt=$this->getUserData($field,$where);
        $map['password'] = $this->passencrypt($password,$salt['salt']);
        $map['status'] = 1;
        $map['state'] = 1;
        $result = $this->where($map)->find();
        if(!$result){
            return false;
        }else{
            return $result;
        }
    }

    //读取用户总数
    public function getUserCount($map){
        $count=$this->alias('U')->join('LEFT JOIN __USER__ AS US ON US.id = U.creater  ')
            ->join('LEFT JOIN __GROUP_ACCESS__ AS GA ON GA.uid = U.id  ')
            ->join('LEFT JOIN __GROUP_DEPARTMENT__ AS GD ON GD.gid = GA.group_id  ')
            ->where($map)->count();
        return $count;
    }

    //读取用户数据
    public function getUserList($map,$field,$page,$rows){
        $arr=$this->alias('U')->join('LEFT JOIN __USER__ AS US ON US.id = U.creater  ')
            ->join('LEFT JOIN __GROUP_ACCESS__ AS GA ON GA.uid = U.id  ')
            ->join('LEFT JOIN __GROUP_DEPARTMENT__ AS GD ON GD.gid = GA.group_id  ')
            ->where($map)->field($field)->order('U.id asc')->page($page,$rows)->select();
        return $arr;
    }

    public function getUserData($field,$where){
        $data=$this->where($where)->field($field)->find();
        if(!empty($data)){
            return $data;
        }
    }

    //跟读uid读取用户名
    public function getUserName($id){
        if(!empty($id)){
            $realname = $this->where('id='.$id)->getField('realname');
            return $realname;
        }else{
            return false;
        }
    }

    //读取用户数据--导出
    public function expUser($map,$field){
        $state=array(
            '0'=>'禁止',
            '1'=>'正常'
        );
        $arr=$this->alias('U')->join('LEFT JOIN __USER__ AS US ON US.id = U.creater  ')
            ->join('LEFT JOIN __GROUP_ACCESS__ AS GA ON GA.uid = U.id  ')
            ->join('LEFT JOIN __GROUP_DEPARTMENT__ AS GD ON GD.gid = GA.group_id  ')
            ->where($map)->field($field)->order('U.id asc')->select();
        foreach($arr as $k=>$v){
            $arr[$k]['addtime']=date('Y-m-d H:i:s',$v['addtime']);
            $arr[$k]['state']=$state[$v['state']];
        }
        return $arr;
    }

}