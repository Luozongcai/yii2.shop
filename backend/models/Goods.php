<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 10:44
 */

namespace backend\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Goods extends ActiveRecord
{
    public $imgFile;//保存文件上传对象
    public function getGoodsCategory(){

        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    public function getBrand(){

        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }

    public static function getItems(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','name');//name是分类表的作者
    }

    public function rules()
    {
        return [
            [['name','status','sort','goods_category_id'],'required'],
            [['brand_id','is_on_sale','logo'],'required'],
            [['market_price','shop_price','stock'],'integer'],
           //上传文件验证规则
           // ['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false],
        ];
    }

    public function attributeLabels()
    {

        return [
            'name'=>'商品名称',
            'goods_category_id'=>'商品分类',
            'brand_id'=>'品牌分类',
            'status'=>'状态',
            'sort'=>'排序',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否在售',
            'logo'=>'LOGO图片',

        ];
    }


}