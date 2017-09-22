<?php

namespace app\modules\controllers;
use app\models\Follow;
use Yii;
use yii\web\Controller;
use app\models\YiiUser;
use yii\web\session;
use app\models\UserForm;
use yii\filters\AccessControl;

class IndexController extends Controller{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';

    /**
     * accesscontrol
     */

    /**
     * @用户授权规则
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login','captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','edit','add','del','index','users','modifypwd','upload','cutpic','follow','nofollow'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @验证码独立操作
     */

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                "maxLength"       =>4,
                "minLength"       =>4,  
            ],
        ];
    }

    
    /**
     * @return string|\yii\web\Response 用户登录
     */

    public function actionLogin(){
        $model=new UserForm();
        
        if($model->load(Yii::$app->request->post())){

            if($model->login()){
                return $this->redirect(['boper/index']);
            }else{
                return $this->render('login',['model'=>$model]);
            }
        }

        return $this->render('login',['model'=>$model]);
    }

    /**
     * @uses: modify pwd
     */
    public function actionModifypwd() {
        $pwd = Yii::$app->request->post('pwd');
        if(!$pwd){echo \app\assets\JsonAsset::encode(array("rs"=>"fail","info"=>"pwd cannot empty"));exit;}
        $model =YiiUser::findOne(Yii::$app->user->getId());
        $model->pwd = md5($pwd);
        if($model->save()){
            echo \app\assets\JsonAsset::encode(array("rs"=>"success","info"=>"modify success"));
            exit;
        }else{
            echo \app\assets\JsonAsset::encode(array("rs"=>"fail","info"=>"modify fail"));
            exit;
        }
            
    }

    /**
     * @后台退出页面
     */
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->goHome();

    }


    



}
