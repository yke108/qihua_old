<?php
namespace Admin\Model;
use Think\Model;
class AuthGroupModel extends Model{
    protected $_validate = array(
        array('title','require','角色名必须填写！'),
//        array('title','','角色名已经存在！',1,'unique',3),
    );

    protected $_auto = array (
        array('status','1'),  // 新增的时候把status字段设置为1
        array('addtime','time',1,'function'),
        array('aid','aidAssignment',3,'callback'),
    );

    protected function aidAssignment() {
        return $_SESSION['userid'];
    }

    //查询uid  用户id  filed 字段信息
    public function getOne($where,$field){
        if(!empty($where)){
            $arr=$this->where($where)->field($field)->find();
            return $arr;
        }
    }

    //权限添加
    public function groupInsert($groupData,$did){
        if(!empty($groupData['rules']))$map['id']  = array('in',$groupData['rules']);
        else $map['id']='';
        $map['id']  = array('in',$groupData['rules']);
        $map['depth']=3;
        $map['status']=1;
        $selectArr = M('AuthRule')->where($map)->group('parentid')->field('parentid')->select();
        if(!empty($selectArr)){
            $id_arr=array();
            foreach($selectArr as $key=>$vo){
                $id_arr[]=$vo['parentid'];
            }
            if($id_arr){
                $id_str = implode(',',$id_arr);
                $groupData['rules']  = $groupData['rules'].','.$id_str;
            }
        }

        $insert_id = $this->add($groupData);
        if($insert_id){
            $groupDepartment=D('GroupDepartment');
            $data['gid']=$insert_id;
            $data['did']=$did;
            $insertResult = $groupDepartment->add($data);
        }
        return $insertResult;
    }

    //角色修改
    public function groupUpdate($guMap,$groupData,$did){
        if(!empty($groupData['rules']))$map['id']  = array('in',$groupData['rules']);
        else $map['id']='';
        $map['depth']=3;
        $map['status']=1;
        $selectArr = M('AuthRule')->where($map)->group('parentid')->field('parentid')->select();
        if(!empty($selectArr)){
            $id_arr=array();
            foreach($selectArr as $key=>$vo){
                $id_arr[]=$vo['parentid'];
            }
            if($id_arr){
                $id_str = implode(',',$id_arr);
                $groupData['rules']  = $groupData['rules'].','.$id_str;
            }
        }


        $result = $this->where($guMap)->save($groupData);
        if($result!==false){
            $groupDepartment=D('GroupDepartment');
            $gdMap['gid']=$guMap['id'];
            $data['did']=$did;
            $gdResult = $groupDepartment->where($gdMap)->save($data);
        }

        return $gdResult!==false?true:false;
    }

    //角色删除
    public function groupDelete($map){
        //删除操作
        $data['status']=0;
        $result = $this->where($map)->save($data);
        return $result!==false?true:false;
    }

    //查询是否用绑定用户
    public function getGroupUnion($map){
        //查询是否用绑定用户组
        $selectMap['group_id']  = array('in',$map['id']);
        $count = M('GroupAccess')->where($selectMap)->count();
        return $count;
    }

    //禁用状态修改
    public function groupActiveUpdate($map,$data){
        $result = $this->where($map)->save($data);
        return $result!==false?true:false;
    }

    //读取用户组总数
    public function getGroupCount($map){
        $count=$this->alias('AG')->join('LEFT JOIN __GROUP_DEPARTMENT__ AS GD ON AG.id = GD.gid  ')
            ->join('LEFT JOIN __DEPARTMENT__ AS D ON GD.did = D.id')
            ->join('LEFT JOIN __USER__ AS A ON AG.aid = A.id')->where($map)->count();
        return $count;
    }

    //读取用户组数据
    public function getGroupList($map,$field,$page,$rows){
        $arr=$this->alias('AG')->join('LEFT JOIN __GROUP_DEPARTMENT__ AS GD ON AG.id = GD.gid  ')
            ->join('LEFT JOIN __DEPARTMENT__ AS D ON GD.did = D.id')
            ->join('LEFT JOIN __USER__ AS A ON AG.aid = A.id')->where($map)->field($field)->order('AG.id desc')->page($page,$rows)->select();
        return $arr;
    }

    public function getDataList($map,$field){
        $status=array(
            '0'=>'删除',
            '1'=>'正常',
            '2'=>'禁止'
        );
        $data=$this->alias('AG')->join('LEFT JOIN __GROUP_DEPARTMENT__ AS GD ON AG.id = GD.gid  ')
            ->join('LEFT JOIN __DEPARTMENT__ AS D ON GD.did = D.id')
            ->join('LEFT JOIN __USER__ AS A ON AG.aid = A.id')->where($map)->field($field)->select();
        foreach($data as $k=>$v){
            $data[$k]['addtime']=date('Y-m-d H:i:s',$v['addtime']);
            $data[$k]['status']=$status[$v['status']];
        }
        return $data;
    }

    //对应的权限列表
    public function getGroupRules($map,$field){
        if(!empty($map)){
            $arr=$this->where($map)->field($field)->find();
            return $arr;
        }else{
            return false;
        }
    }

}