<?php
namespace app\models;

use Yii;
/*
 * @uses:server add del update select
 * @time:2015-04-08
 */
class YiiPbversion extends yii\db\ActiveRecord{
    //put your code here
    public static function tableName()
    {
        return '{{%pbversion}}';
    }

    /*
     * @uses:hasone
     */
    public function getServer(){
        $this->hasOne(YiiServer::tableName(), ['ser_id'=>'ser_id']);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'pv_name', 'pv_size', 'pv_md5','pv_creater','pv_uid'], 'required'],
        ];
    }
    
    /**
     * #uses:return array attributes
     */
    public function attr() {
       $sql = "describe yii_pbversion";
       $res = Yii::$app->db->createCommand($sql)->queryColumn();
       return $res;
    }
}
