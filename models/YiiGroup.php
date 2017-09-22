<?php

namespace app\models;

use Yii;

class YiiGroup extends yii\db\ActiveRecord{
    //put your code here
    public static function tableName()
    {
        return '{{%group}}';
    }
}