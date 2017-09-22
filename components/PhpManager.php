<?php
namespace app\components\PhpManager;
 
use Yii;
 
class PhpManager 
{
    public function init()
    {
        parent::init();
        if (!Yii::$app->user->isGuest) {
            
            $this->assign(Yii::$app->user->identity->id, Yii::$app->user->identity->authKey);
        }
    }
}