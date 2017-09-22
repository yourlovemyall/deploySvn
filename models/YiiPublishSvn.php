<?php

namespace app\models;

use Yii;

class YiiPublishSvn extends yii\db\ActiveRecord{
    //put your code here
    public static function tableName()
    {
        return '{{%publish_svn}}';
    }
    
    public function relations(){
		return array(
                    //'relationName'=>array(relationType, 'modelName', 'fieldName'),
                    'system'=>array(self::BELONGS_TO, 'YiiSystem', 'id'),
                    
		);
	}
}
