<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 12:54
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    public $address_id;
    public $delivery;
    public $pay;

    public function rules()
    {
        return [

            ['address_id', 'required'],
            ['delivery', 'required'],
            ['pay', 'required'],


        ];
    }

}