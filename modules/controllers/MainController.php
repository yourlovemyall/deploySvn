<?php
namespace app\modules\controllers;
use Yii;
use yii\web\Controller;
use app\models\YiiUser;
use yii\web\session;
use app\models\UserForm;
use app\models\YiiSystem;
use app\models\YiiServer;
use app\models\YiiServerIps;
use app\models\YiiSpublish;
use yii\filters\AccessControl;

class MainController extends Controller{
    public $layout = 'layout';
    public $active = 1;
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
                        'actions' => ['index','jsondata'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex($id=null)
    {
        $res = \app\components\menu\menu::busyNav(Yii::$app->user->identity->gid);
        if($res) {
            $id =  $id ===null ? $res['0']['id'] : intval($id);
        
            $server_ips = YiiServer::findBySql("SELECT ser.*,si.* from {{%server}} as ser left join {{%server_ips}} as si ON ser.si_id = si.si_id"
                . " where ser.sys_id = $id order by ser.si_id asc")->asArray()->all();
        }else{
            $server_ips =array();
        }
        $this->mid = $id;
        return $this->render("index",array("id"=>$id,"servers"=>$server_ips));
    }
    
    public function actionJsondata() {
        $datas = Yii::$app->request->get();
        $id = intval($datas['id']);
        $siid = intval($datas['siid']);
        if ($id ==null && $siid==null)  {
            echo json_encode(array("ret"=>0,"data"=>"param get fail"));
            exit;
        }
        $datas = YiiSpublish::findBySql("select * from {{%spublish}} as sp left join {{%server_ips}} as si ON si.si_id = sp.ser_id"
                . " where sp.ser_id = $siid AND sys_id = $id order by sp.sp_create_time desc")->asArray()->all();
        echo json_encode(array("ret"=>1,"data"=>$datas));
        exit;
    }
}
