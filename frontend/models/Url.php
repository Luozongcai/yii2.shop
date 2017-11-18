<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13 0013
 * Time: 18:48
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Url extends ActiveRecord
{

    public function rules()
    {
        return [


            [['name','cmbProvince'],'required'],
            [['cmbCity','cmbArea','url','tel'],'required'],
            ['check','safe'],
        ];
    }
    public function attributeLabels()
    {

        return [
            'name'=>'名称',
            'url'=>'详细地址',
            'tel'=>'电话',
            'check'=>'是否默认地址',

        ];
    }



}