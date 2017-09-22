<?php
namespace app\models;

use Yii;
/*
 * @uses:syetem add del update select
 * @time:2015-04-08
 */
class YiiSystem extends yii\db\ActiveRecord{
    //put your code here
    public static function tableName()
    {
        return '{{%system}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'sysname'], 'required'],
            [['sysname'], 'string', 'max' => 128]
        ];
    }
}
