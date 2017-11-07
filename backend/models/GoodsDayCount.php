<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 19:40
 */

namespace backend\models;


use yii\db\ActiveRecord;

class GoodsDayCount extends ActiveRecord

{

    public function rules()
    {
        return [
            [['day','count'],'safe'],
        ];
    }

    public function attributeLabels()
    {

        return [

        ];
    }


}