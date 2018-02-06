<?php
namespace Admin\Model;
use Think\Model;
class DepartmentModel extends Model{

    protected $_validate = array(
        array('name','require','部门名称必须填写！'),
        array('name','checkUnique','部门名称已经存在！',1,'callback',3),
        array('parentid','require','父id错误！'),
    );

    protected $_auto = array (
        array('status','1'),  // 新增的时候把status字段设置为1
    );

    /**
     * 检查是否唯一
     * @param string $name
     * @return boolean
     */
    public function checkUnique( $name ){
        $ret = FALSE;
        $where = array(
            'name' => $name,
            'status' => array( 'neq', 0 ),
        );
        $count = $this->where( $where )->count();
        if( $count <= 0 ){
            $ret = TRUE;
        }
        return $ret;
    }

    //根据id查询部门信息
    public function getDepartmentInfo($map,$field){
        $arr = $this->where($map)->field($field)->find();
        return $arr;
    }

    //部门添加
    public function departmentInsert($selectData,$data){
        $insert_id = $this->add($data);
        if($insert_id){
            $update_data['parentidlist']=$selectData['parentidlist'].','.$insert_id;
            $update_result = $this->where('id='.$insert_id)->save($update_data);
        }
        return $update_result;
    }

    //部门修改
    public function departmentUpdate($map,$data){
        $result = $this->where($map)->save($data);
        return $result;
    }

    //部门删除
    public function departmentDelete($map){
        $selectData = $this->where($map)->field('id')->select();

        //查询需要删除的所有子id
        if($selectData){
            $id_arr=array();
            foreach($selectData as $key=>$vo){
                $id_arr[]=$vo['id'];
            }
        }

        //删除操作
        if($id_arr){
            $id_str = implode(',',$id_arr);
            $map['id']  = array('in',$id_str);
            $data['status']=0;
            $delete_result = $this->where($map)->save($data);
        }

        return $delete_result;
    }

    //查询是否用绑定用户组
    public function getDepartmentUnion($map){
        $selectData = $this->where($map)->field('id')->select();

        //查询需要删除的所有子id
        if($selectData){
            $id_arr=array();
            foreach($selectData as $key=>$vo){
                $id_arr[]=$vo['id'];
            }
        }

        //删除操作
        if($id_arr){
            $id_str = implode(',',$id_arr);

            //查询是否用绑定用户组
            $selectMap['did']  = array('in',$id_str);
            $count = M('GroupDepartment')->where($selectMap)->count();
            return $count;
        }
    }

    //读取部门列表
    public function getDepartmentList($map,$field,$gid=''){
        $arr = $this->where($map)->field($field)->select();
        $tree_arr = array();
        if(!empty($arr)){
            if(!empty($gid)){
                $editMap['gid']=$gid;
                $defaultData = M('GroupDepartment')->where($editMap)->field('did')->select();
                if($defaultData){
                    $id_arr=array();//整合id数组
                    foreach($defaultData as $key=>$vo){
                        $id_arr[]=$vo['did'];
                    }
                }
                foreach($arr as $k=>$v){
                    $tree_arr[$arr[$k]['id']]['id'] = $arr[$k]['id'];
                    $tree_arr[$arr[$k]['id']]['text'] = $arr[$k]['name'];
                    $tree_arr[$arr[$k]['id']]['attributes']['type'] = $arr[$k]['depth'];
                    $tree_arr[$arr[$k]['id']]['attributes']['parentid'] = $arr[$k]['parentid'];
                    if(in_array($tree_arr[$arr[$k]['id']]['id'] , $id_arr)){
                        $tree_arr[$arr[$k]['id']]['checked']=true;
                    }
                }
            }else {
                foreach ($arr as $k => $v) {
                    $tree_arr[$arr[$k]['id']]['id'] = $arr[$k]['id'];
                    $tree_arr[$arr[$k]['id']]['text'] = $arr[$k]['name'];
                    $tree_arr[$arr[$k]['id']]['attributes']['type'] = $arr[$k]['depth'];
                    $tree_arr[$arr[$k]['id']]['attributes']['parentid'] = $arr[$k]['parentid'];
                }
            }
        }

        $tree = $this->getChild($tree_arr);
        return $tree;
    }

    //递归查询无限分级
    protected function getChild($items)
    {
        $tree = array(); //格式化好的树
        foreach ($items as $item) {

            if (isset($items[$item['attributes']['parentid']]))
                $items[$item['attributes']['parentid']]['children'][] = &$items[$item['id']];
            else
                $tree[] = &$items[$item['id']];
        }
        return $tree;
    }

    //读取部门id读取用户组数据
    public function getConditionGroupList($id){
        $map['CONCAT(\',\',D.parentidlist,\',\')']=array('like','%,'.$id.',%');
        $map['D.status']=1;
        $map['AG.status']=1;
        $arr = $this->alias('D')->join('INNER JOIN __GROUP_DEPARTMENT__ AS GD ON GD.did = D.id')
            ->join('INNER JOIN __AUTH_GROUP__ AS AG ON AG.id = GD.gid')
            ->where($map)->field('AG.id,AG.title as text')->select();

        return $arr;
    }

    //跟读部门id读取子id
    public function getDepartmentChildId($id){
        $map['CONCAT(\',\',D.parentidlist,\',\')']=array('like','%,'.$id.',%');
        $map['D.status']=1;
        $arr = $this->alias('D')
            ->where($map)->field('D.id')->select();
        $id_arr=array();
        if($arr){
            foreach($arr as $key=>$vo){
                $id_arr[]=$vo['id'];
            }
        }
        return $id_arr;
    }
}