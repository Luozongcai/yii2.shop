<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 21:52
 */

namespace backend\models;


use yii\db\ActiveRecord;

class GoodsGallery extends ActiveRecord
{

    public function rules()
    {
        return [
            [['goods_id'],'required'],
            //上传文件验证规则
             ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false],
        ];
    }

    public function attributeLabels()
    {

        return [
            'imgFile'=>'添加图片',

        ];
    }


}