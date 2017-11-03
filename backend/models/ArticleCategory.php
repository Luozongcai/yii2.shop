<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3 0003
 * Time: 15:17
 */

namespace backend\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class ArticleCategory extends ActiveRecord

{
    public static function getItems(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','name');//name是author表的作者
    }

    public function rules()
    {
        return [
            [['name','intro','status','sort'],'required'],
             ];
    }

    public function attributeLabels()
    {

        return [
            'name'=>'文章分类',
            'intro'=>'简介',
            'status'=>'状态',
            'sort'=>'排序',
        ];
    }

}