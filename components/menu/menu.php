<?php
namespace app\components\menu;

use app\models\YiiMenus;
use app\models\YiiGmenus;

class menu {
    //put your code here
    
    public static function Nav($gid =NULL){
        header("content-type:text/html;charset=utf-8");
        if ($gid ==1 || $gid==NULL )    return YiiMenus::find()->where(array("m_fid"=>0))->asArray()->all();
        
        $midstr = self::get_string($gid, "menus");
        if(is_array($midstr)) exit($midstr['msg']);
        
        return YiiMenus::find()->where("m_fid=0 and mid in ({$midstr})")->asArray()->all();
    }
    
    public static function leftNav($gid=NULL) {
        header("content-type:text/html;charset=utf-8");
        if ($gid==1 || $gid==NULL)  return YiiMenus::find()->where(array("m_fid"=>3))->asArray()->all();
        
        $midstr = self::get_string($gid, "menus");
        if(is_array($midstr)) exit($midstr['msg']);
        
        return YiiMenus::find()->where("m_fid=3 and mid in ({$midstr})")->asArray()->all();
    }
    
    public static function busyNav($gid=NULL) {
        header("content-type:text/html;charset=utf-8");
        if ($gid==1 || $gid==NULL)  return \app\models\YiiSystem::find()->orderBy('id asc')->asArray()->all();
        $midstr = self::get_string($gid, "sys");
        if(is_array($midstr)) exit($midstr['msg']);
        return \app\models\YiiSystem::find()->where("id in ({$midstr})")->orderBy('id asc')->asArray()->all();
    }
    
    protected static function get_string($gid,$code='menus') {
        
        $gmenus = YiiGmenus::find()->where(array("code"=>"{$code}","g_id"=>$gid))->asArray()->all();
        if(empty($gmenus)) return (array("rs"=>false,"msg"=>"还没有对您所属的角色授权"));
        $strmid = '';
        foreach ($gmenus as $val)   $strmid .= $val['m_id'].",";
        return $midstr = rtrim($strmid,",");
    }
}
