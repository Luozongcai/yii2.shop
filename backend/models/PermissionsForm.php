<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9 0009
 * Time: 12:53
 */

namespace backend\models;


use yii\base\Model;

class PermissionsForm extends Model
{
    public $name;
    public $description;



    public function attributeLabels()
    {
        return [
            'name'=>'权限(路由)',
            'description'=>'描述',

        ];
    }

    public function rules()
    {
        return [
            [['name','description'],'required'],

        ];
    }
}