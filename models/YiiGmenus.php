<?php

namespace app\models;

use Yii;

class YiiGmenus extends yii\db\ActiveRecord{
    //put your code here
    public static function tableName()
    {
        return '{{%gmenus}}';
    }
    
}