<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3 0003
 * Time: 15:58
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    public function getArticleCategory(){
       //展示列表分类数据
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }

    public function rules()
    {
        return [
            [['name','intro','status','sort','article_category_id'],'required'],

        ];
    }

    public function attributeLabels()
    {

        return [
            'name' => '文章名称',
            'article_category_id'=>'选择分类',
            'intro' => '简介',
            'status' => '状态',
            'sort' => '排序',
        ];
    }

    }