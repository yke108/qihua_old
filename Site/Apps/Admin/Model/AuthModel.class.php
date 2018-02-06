<?php
namespace Admin\Model;
use Think\Model;
Class AuthModel extends Model
{
    Protected $autoCheckFields = false;

    public function getuserlist($map){
        $arr = $this->table(C('DB_PREFIX').'auth_group')->where($map)->field('id,title')->select();
        return $arr;
    }

    //读取用户组总数
    public function getgroupcount($map){
        $count=$this->table(C('DB_PREFIX').'auth_group')->where($map)->count();
        return $count;
    }

    //读取用户组数据
    public function getgrouplist($map,$page,$rows){
        $arr=$this->table(C('DB_PREFIX').'auth_group')->where($map)->order('id desc')->page($page,$rows)->select();
        return $arr;
    }

    //读取用户组总数
    public function getauthcount($map){
        $count=$this->table(C('DB_PREFIX').'auth_rule')->where($map)->count();
        return $count;
    }

    //读取用户组数据
    public function getauthlist($field,$map,$page,$rows){
        $arr=$this->table(C('DB_PREFIX').'auth_group')->where($map)->field($field)->order('id desc')->page($page,$rows)->select();
        return $arr;
    }

    public function getrulelist($field,$map){
        $arr = $this->where($map)->field($field)->select();
        return $arr;
    }

    //读取rulelist列表
    public function getrulelistmerge($arr){
        $tree_arr = array();
        if($arr){
            foreach($arr as $k=>$v){
                $tree_arr[$arr[$k]['id']]['id'] = $arr[$k]['id'];
                $tree_arr[$arr[$k]['id']]['text'] = $arr[$k]['name'];
                $tree_arr[$arr[$k]['id']]['attributes']['type'] = $arr[$k]['depth'];
                $tree_arr[$arr[$k]['id']]['attributes']['parentid'] = $arr[$k]['parentid'];
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

    //获取用户组id
    public function getGroupId($field,$map){
        if(!empty($map)){
            $arr=$this->table(C('DB_PREFIX').'group_access')->where($map)->field($field)->find();
            return $arr;
        }else{
            return false;
        }

    }
//查询权限列表
    public function getAuthRules(){
        $map['uid'] = $_SESSION['userid'];;
        $field = 'uid,group_id';
        $arr =$this->getGroupId($field, $map);
        if ($arr['group_id'] == 1) {
            //获取所有的权限列表
            $ret = 'Admin';
        }else{
            $map['id'] = $arr['group_id'];
            $rules = 'id,rules';
            //获取当前的权限id
            $GroupRules = D('AuthGroup')->getGroupRules($map, $rules);
            $ret=explode(',',ltrim($GroupRules['rules'],','));
        }
        return $ret;
    }

    public function getAuthId($rules,$field){
        if(empty($rules)){
            return false;
        }
        $id = $this->table(C('DB_PREFIX').'auth_rule')->where($rules)->field($field)->find();
        return $id;
    }
}