<?php
namespace app\models;

use Yii;
/**
 * @uses: 版本分发模型
 */
class YiiSpublish extends yii\db\ActiveRecord{
    //put your code here
    public static function tableName()
    {
        return '{{%spublish}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'ser_id','sys_id','pv_v','sp_uid'], 'required'],
            [['sp_bug'], 'string', 'max' => 256]
        ];
    }
    
}
