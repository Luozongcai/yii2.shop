<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3 0003
 * Time: 16:03
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord
{
    public function rules()
    {
        return [
            [['content','article_id'],'safe'],
        ];
    }

    public function attributeLabels()
    {

        return [
            'content' => '文章内容',
        ];
    }

}