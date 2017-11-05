<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5 0005
 * Time: 16:43
 */

namespace backend\models;


use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class GoodsCategoryQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }

}