<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/11 0011
 * Time: 12:53
 */

namespace backend\models;


use yii\base\Model;

class PwdFome extends Model
{
    public $password1;
    public $password2;
    public $password3;

    public function rules()
    {
        return [
            [['password1','password2','password3'],'required'],
            //compare判断
            //compareAttribute判断前面和后面是否一致
            //新密码和确认新密码一致repassword卸载前面,不一致时错误提示在确认密码下面
            ['password3','compare','compareAttribute'=>'password2','message'=>'两次密码不一致'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password1'=>'旧密码',
            'password2'=>'新密码',
            'password3'=>'确认新密码',
        ];
    }

}