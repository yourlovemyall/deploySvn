<?php

namespace app\models;

use Yii;

class YiiMenus extends yii\db\ActiveRecord{
    //put your code here
    public static function tableName()
    {
        return '{{%menus}}';
    }
}