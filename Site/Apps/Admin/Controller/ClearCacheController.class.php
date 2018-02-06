<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/10/24
 * Time: 14:31
 */

namespace Admin\Controller;
use     Think\Controller;

class ClearCacheController extends Controller{

    public function cache_clear() {
        $this->deldir('Runtime');
        $res['msg']='刷新成功';
        $res['code']='200';
        $res['data']['ok']='刷新成功';
        $this->ajaxReturn($res);
    }

    public function deldir($dir) {
      $filename=dirname(dirname(dirname(dirname(__FILE__))));
            $dirs=$filename.'/'.$dir;
        @mkdir($dirs,0777,true);
        $dh = opendir($dirs);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dirs . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                }else{
                   $this->deldir($dir.'/'.$file);
                }
            }
        }
        fopen("Runtime/index.html",'w');
    }

} 