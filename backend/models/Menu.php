<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10 0010
 * Time: 14:42
 */

namespace backend\models;


use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name','parent_id','sort'],'required'],
            ['url','safe'],

              ];
    }

    public function attributeLabels()
    {

        return [
            'name'=>'菜单名称',
            'parent_id'=>'上级菜单',
            'url'=>'地址/路由',
            'sort'=>'排序',

        ];
    }


    //一级菜单和二级菜单的关系  1对多
    public function getChildren(){
        //儿子.parent_id  --->  父亲.id
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }


}