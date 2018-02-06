<?php
namespace Admin\Model;
use Think\Model;
class AuthRuleModel extends Model{

    protected $_validate = array(
        array('name','require','规则标识必须填写！'),
        array('name','','部门名称已经存在！',1,'unique',3),
        array('title','require','权限名称必须填写！'),
        array('parentid','require','父id错误！'),
    );

    protected $_auto = array (
        array('status','1'),  // 新增的时候把status字段设置为1
    );

    //根据id查询部门信息
    public function getRuleInfo($map,$field){
        $arr = $this->where($map)->field($field)->find();
        return $arr;
    }

    public function getRuleInfos($map,$field){
        $arr = $this->where($map)->field($field)->select();
        return $arr;
    }

    //权限添加
    public function ruleInsert($selectData,$data){
        $insert_id = $this->add($data);
        if($insert_id){
            $update_data['parentidlist']=$selectData['parentidlist'].','.$insert_id;
            $update_result = $this->where('id='.$insert_id)->save($update_data);
        }
        return $update_result;
    }

    //权限修改
    public function ruleUpdate($map,$data){
        $result = $this->where($map)->save($data);
        return $result;
    }

    //权限删除
    public function ruleDelete($map){
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

    public function getRuleList($map,$field,$gid=''){
        $arr = $this->where($map)->field($field)->select();

        $tree_arr = array();
        if(!empty($arr)){
            if(!empty($gid)){
                $editMap['id']=$gid;
                $defaultData = M('AuthGroup')->where($editMap)->field('rules')->find();
                if(!empty($defaultData['rules'])){
                    $id_arr = explode(',',$defaultData['rules']);
                }
                foreach($arr as $k=>$v){
                    $tree_arr[$arr[$k]['id']]['id'] = $arr[$k]['id'];
                    $tree_arr[$arr[$k]['id']]['name'] = $arr[$k]['name'];
                    $tree_arr[$arr[$k]['id']]['text'] = $arr[$k]['title'];
                    $tree_arr[$arr[$k]['id']]['attributes']['type'] = $arr[$k]['depth'];
                    $tree_arr[$arr[$k]['id']]['attributes']['parentid'] = $arr[$k]['parentid'];
                    $tree_arr[$arr[$k]['id']]['attributes']['parentidlist'] = $arr[$k]['parentidlist'];
                    if(in_array($tree_arr[$arr[$k]['id']]['id'] , $id_arr)){
                        $tree_arr[$arr[$k]['id']]['checked']=true;
                    }
                }
            }else{
                foreach($arr as $k=>$v){
                    $tree_arr[$arr[$k]['id']]['id'] = $arr[$k]['id'];
                    $tree_arr[$arr[$k]['id']]['name'] = $arr[$k]['name'];
                    $tree_arr[$arr[$k]['id']]['text'] = $arr[$k]['title'];
                    $tree_arr[$arr[$k]['id']]['attributes']['type'] = $arr[$k]['depth'];
                    $tree_arr[$arr[$k]['id']]['attributes']['parentid'] = $arr[$k]['parentid'];
                    $tree_arr[$arr[$k]['id']]['attributes']['parentidlist'] = $arr[$k]['parentidlist'];
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
}