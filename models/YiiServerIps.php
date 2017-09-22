<?php
namespace app\models;

use Yii;
use yii\base\Model;
/*
 * @uses:syetem add del update select
 * @time:2015-04-08
 */
class YiiServerIps extends yii\db\ActiveRecord{
    //put your code here
    
    public static function tableName()
    {
        return '{{%server_ips}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'si_local_ip','si_remote_ip','si_name','si_backup'], 'required','message'=>'{attribute}不能为空！'],
            [['si_name'], 'string', 'max' => 45,'tooLong'=>'{attribute}长度必需在45以内']
        ];
    }
    
    
    public function scenarios()
    {
        return [
            'default' => [ 'si_local_ip','si_remote_ip','si_name','si_backup']
        ];
    }
    /**
     * @
     */
    public function attributeLabels()
    {
        return [
            'si_local_ip' => '内网IP',
            'si_remote_ip' => '外网IP',
            'si_name' =>'salt 名称',
            'si_backup' =>"别称"
        ];
    }
}
