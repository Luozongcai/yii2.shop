<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Brand extends ActiveRecord
{
    public $imgFile;//保存文件上传对象


    public static function getItems(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','name');//name是brand表的作者
    }


    public function rules()
    {
        return [
            [['name','intro','status','sort','logo'],'required'],
            //上传文件验证规则
           // ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false],
               ];
    }

    public function attributeLabels()
    {

        return [
            'logo'=>'品牌Logo',
            'name'=>'品牌名称',
            'intro'=>'简介',
            'status'=>'状态',
            'sort'=>'排序',
        ];
    }
}