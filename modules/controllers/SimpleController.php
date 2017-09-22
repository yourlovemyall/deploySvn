<?php

/**
 * Description of SimpleController
 * @uses: 快速发版
 * @author yueming.zeng@jinfuzi.com
 */
namespace app\modules\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
class SimpleController extends Controller{
    //put your code here
    public $enableCsrfValidation = false;
    public $layout = 'layout';
    public $active = 9;
    
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
                        'actions' => ["index","push"],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex($id=NULL) {
        header("content-type:text/html;charset=utf-8");
        $res = \app\components\menu\menu::busyNav(Yii::$app->user->identity->gid);
        if($res) {
            $id =  $id ===null ? $res['0']['id'] : intval($id);
            $list = \app\models\YiiPublishSvn::find()->where(array("sys_id"=>$id))->asArray()->all();
        }else{
            $list = array();
        }
        $this->mid = $id;
        return $this->render("index",array("id"=>$id,"list"=>$list));
    }
    
    public function actionPush() {
        $cmd = Yii::$app->request->post('cmd');
        $command = \app\models\YiiPublishSvn::find()->where(array("id"=>$cmd))->asArray()->one();
        
        
        exec($command['ps_cmd'],$output,$return_var);
//        echo \app\assets\JsonAsset::encode(array("rs"=>"success","info"=>$command['ps_cmd']));
//        exit;
        if($return_var!=0) {
            echo \app\assets\JsonAsset::encode(array("rs"=>"success","info"=>"command error"));
            exit;
        }
        $msg = '';
        if(!empty($output)) {
            foreach ($output as $va)    $msg .=$va."\r\n";
        };
        echo \app\assets\JsonAsset::encode(array("rs"=>"success","info"=>$msg));
        exit;
    }
}
