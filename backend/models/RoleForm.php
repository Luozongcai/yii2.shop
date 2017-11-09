<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9 0009
 * Time: 12:52
 */

namespace backend\models;


use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions;


    public function attributeLabels()
    {
        return [
            'name'=>'角色名',
            'description'=>'描述',
            'permissions'=>'选择权限'
        ];
    }

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
        ];
    }

}