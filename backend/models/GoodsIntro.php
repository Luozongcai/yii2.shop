<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 19:41
 */

namespace backend\models;


use yii\db\ActiveRecord;

class GoodsIntro extends ActiveRecord
{

    public function rules()
    {
        return [
            ['content','safe'],
        ];
    }

    public function attributeLabels()
    {

        return [
            'content'=>'商品详情',

        ];
    }


}