<?php
namespace app\models;

use Yii;
/*
 * @uses:server add del update select
 * @time:2015-04-08
 */
class YiiServer extends yii\db\ActiveRecord{
    //put your code here
    public static function tableName()
    {
        return '{{%server}}';
    }
    
    /**
     * @uses:has many
     */
    public function getSpublish(){
        $this->hasMany(YiiSpublish::tableName(), ['ser_id'=>'ser_id']);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'sys_id', 'si_id'], 'required'],
        ];
    }
    
    /**
     * #uses:return array attributes
     */
    public function attr() {
       $sql = "describe yii_server";
       $res = Yii::$app->db->createCommand($sql)->queryColumn();
       return $res;
    }
}
