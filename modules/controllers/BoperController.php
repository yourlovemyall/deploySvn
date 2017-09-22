<?php
namespace app\modules\controllers;
use Yii;
use yii\web\Controller;
use app\models\YiiSystem;
use app\models\YiiServer;
use app\models\YiiPbversion;
use app\models\YiiSpublish;
use app\models\YiiServerIps;
use yii\helpers\Cmd;
use yii\web\session;
use yii\filters\AccessControl;

class BoperController extends Controller{
    //put your code here
    public $enableCsrfValidation = false;
    public $layout = 'layout';
    public $active = 2;
    //put your code here
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index',"jsondata","jsonbvsn","newbvsn","jsonversion","publishvn","versionst",'upbvsn',"backvn"],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /*
     * @uses:业务操作系统首页
     */
    public function actionIndex($id=null) {
        header("content-type:text/html;charset=utf-8");
        $res = \app\components\menu\menu::busyNav(Yii::$app->user->identity->gid);
        if($res) 
            $id =  $id ===null ? $res['0']['id'] : intval($id);
        $this->mid = $id;
        return $this->render("index",array("id"=>$id));
    }
    
    /*
     * @uses:return json datas
     */
    public function actionJsondata($id=null){
        $obj = new YiiServer();
//        $sp = new YiiSpublish();
        $id= intval($id);
        
        $res = $obj->findBySql("select ser.*,sp.* ,si.* from {{%server}} as ser left join {{%spublish}} as sp ON sp.ser_id = ser.si_id and sp.sys_id=ser.sys_id"
                . " left join {{%server_ips}} as si ON si.si_id = ser.si_id"
                . " where ser.sys_id= $id  AND sp.sp_status = 1 ")->asArray()->all();
        echo json_encode(array("ret"=>1,"data"=>$res));
        exit;
    }
    
    
    /*
     * @uses:return json datas
     */
    public function actionJsonbvsn($id=null) {
        $obj = new YiiPbversion();
        $attrs = $obj->attr();
        $id= intval($id);
        
        $res = $obj->find()->where(array("sys_id"=>$id))->orderBy("pv_create_time desc")->all();
        $datas = array();
        foreach ($res as $k=> $vs){
            foreach ($attrs as $val){
                $datas[$k][$val]=$vs->$val;
//                $datas[$k]["version"]=0;
            }
        }
        unset($attrs);
        unset($res);
        echo json_encode(array("ret"=>1,"data"=>$datas));
        exit;
    }
    
    /**
     * @uses:return json version datas
     */
    public function actionJsonversion($id=null) {
        $id= intval($id);
        $obj = new YiiPbversion();
        $attrs = $obj->attr();
        $res = $obj->find()->where(array("sys_id"=>$id,"pv_status"=>0))->orderBy("pv_id desc")->all();
        $datas = array();
        foreach ($res as $k=> $vs){
            foreach ($attrs as $val){
                $datas[$k][$val]=$vs->$val;
            }
        }
        unset($attrs);
        unset($res);
        echo json_encode(array("ret"=>1,"data"=>$datas));
        exit;
        
    }
    
    /*
     * @uses:publish new version
     */
    public function actionNewbvsn($id=null) {
        $id = intval($id);
        if ($id ==null) exit("please select system name");
        
        $model = new YiiPbversion();
        YiiSystem::updateAll(array("v_status"=>1), "`id`=$id");
        $sys_obj = YiiSystem::find()->select(array("ename"))->where("id = {$id}")->one();
        $vs_obj = YiiPbversion::findBySql("select pv_v from {{%pbversion}} where sys_id={$id} order by pv_id desc limit 1")->one();
        if (empty($vs_obj)) 
            $version = 1;
        else
            $version = $vs_obj->pv_v +1;
        $ret = Cmd::create_version($sys_obj->ename,$version);
        if($ret['pv_name']==null) {
            YiiSystem::updateAll(array("v_status"=>0), "`id`=$id");
            echo json_encode(array("ret"=>1,"msg"=>$ret['data']));
            exit;
        }
        $comments = Yii::$app->request->post("comment");
        $ret['sys_id']      = $id;
        $ret['pv_creater']  = Yii::$app->user->identity->user;
        $ret['pv_uid']      = Yii::$app->user->getId() ;
        $ret['pv_explain']  = $comments;
        foreach ($ret as $key=>$val) {
            $model->$key = $val;
        }
        if ($model->save()){
            YiiSystem::updateAll(array("v_status"=>0), "`id`=$id");
            echo json_encode(array("ret"=>0,"msg"=>" VERSION {$version} success"));exit;
        }else {
            echo json_encode(array("ret"=>1,"msg"=>"fail"));exit;
        }
    }
    
    /**
     * @uses: back servers version
     */
    public function actionBackvn($id=null) {
        $id = intval($id);
        YiiSystem::updateAll(array("p_status"=>1), "`id`=$id");
        $sys_obj = YiiSystem::find()->where("id = {$id}")->one();
        $datas = Yii::$app->request->post();
        
        if ($datas['serid'] !=null) {
            $serarr = explode(',', $datas['serid']);
            $ips = array();
            foreach ($serarr as $val){
                $ser_obj = YiiServerIps::find()->select(array("si_local_ip","si_name"))->where("si_id = $val")->one();
                if ($ser_obj->si_name)     $ips[] = $ser_obj->si_name;
            }
        }else{
            YiiSystem::updateAll(array("p_status"=>0), "`id`=$id");
            echo json_encode(array("ret"=>"0","msg"=>"back fail"));
            exit;
        }
        $res = Cmd::back_version($sys_obj->ename, $datas['v'], $ips,$sys_obj->ins_name);
        if ($res['ret'] ==null) {
            YiiSystem::updateAll(array("p_status"=>0), "`id`=$id");
            echo json_encode(array("ret"=>0,"msg"=>$res['data']));
            exit;
        }
        $v = $datas['v'];
        foreach ($serarr as $val) {
            $sb_obj = new YiiSpublish();
            $sb_obj->updateAll(array("sp_status"=>0), "sys_id = $id AND ser_id = $val");
            $datas = array(
                "sys_id"=>$id,
                "ser_id"=>$val,
                "pv_v"  =>$v,
                "sp_uid"=>Yii::$app->user->getId(),
                "sp_creater" =>Yii::$app->user->identity->user,
                "sp_status" =>1,
                "sp_create_time" =>date("Y-m-d H:i:s")
            );
            foreach($datas as $key=>$val){
                $sb_obj->$key = $val;
            }
            $sb_obj->save();
            unset($sb_obj);
        }
        YiiSystem::updateAll(array("p_status"=>0), "`id`=$id");
        echo json_encode(array("ret"=>"1","msg"=>"opration success"));
        exit;
    }
    
    /*
     * @uses:get system version status
     */
    public function actionVersionst($id) {
        $id = intval($id);
        if ($id==null)  exit("get param fail!");
        $ret = YiiSystem::find()->where(array("id"=>$id))->asArray()->one();
        echo json_encode($ret);
        exit;
    }
    
    
    /*
     * @uses:put notes
     */
    public function actionUpbvsn($id) {
        $id = intval($id);
        if ($id==null)  exit("get param fail!");
        $datas = Yii::$app->request->post();
        if (empty($datas))     exit("get params fail!");
        
        $ck  = YiiSpublish::find()->where(array("sys_id"=>$id,"pv_v"=>$datas['version'],"sp_status"=>1))->asArray()->one();
        if(!empty($ck)) {
            echo json_encode (array("ret"=>"1","info"=>"当前版本，已作为系统发版版本！"));
            Yii::$app->end();
        }
        $res = YiiPbversion::updateAll(array("pv_explain"=>"{$datas['comment']}","pv_status"=>2), "sys_id = {$id} AND pv_v = {$datas['version']}");
        
        if($res)    
            echo json_encode (array("ret"=>"0","info"=>"success"));
        else
            echo json_encode (array("ret"=>"1","info"=>"oprate fail"));
        exit;
    }
}
