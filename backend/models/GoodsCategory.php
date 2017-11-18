<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5 0005
 * Time: 16:40
 */

namespace backend\models;


use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class GoodsCategory extends ActiveRecord
{

    public static function getItems(){

        return ArrayHelper::map(self::find()->asArray()->all(),'id','name');//name是author表的作者
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),

                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }



    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [

            'name' => '名称',
            'parent_id' => '上级分类',
            'intro' => '简介',
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
    //获取分类需要的数据
    public static function getNodes(){
        return self::find()->select(['id','name','parent_id'])->asArray()->all();
    }



    //前台首页显示商品分类
    public static function getIndexGoodsCategory(){
        //使用redis进行性能优化(后台改变商品分类[添加修改删除],需要清除redis缓存)
        //缓存使用 先读缓存,有就直接用,没有就重写生成
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        //$redis->del('goods-category');
        $html =$redis->get('goods-category');

        if($html == false){

            $html =  '<div class="cat_bd">';
            //遍历一级分类
            $categories = self::find()->where(['parent_id'=>0])->all();
            foreach ($categories as $k1=>$category){
                //第一个一级分类需要加class = item1
                $html .= '<div class="cat '.($k1==0?'item1':'').'">
                    <h3><a href='.Url::to(['goods/list']).'?id='.$category->id.'>'.$category->name.'</a><b></b></h3>
                    <div class="cat_detail">';
                //遍历该一级分类的二级分类
                $categories2 = $category->children(1)->all();
                foreach ($categories2 as $k2=>$category2){
                    $html .= '<dl '.($k2==0?'class="dl_1st"':'').'>
                        
                            <dt><a href='.Url::to(['goods/list']).'?id='.$category2->id.'>'.$category2->name.'</a></dt>
                            <dd>';
                    //遍历该二级分类的三级分类
                    $categories3 = $category2->children(1)->all();
                    foreach ($categories3 as $category3){
                        $html .= '<a href='.Url::to(['goods/list']).'?id='.$category3->id.'>'.$category3->name.'</a>';
                    }
                    $html .= '</dd>
                        </dl>';
                }

                $html .= '</div>
                </div>';
            }
            $html .= '</div>';

              //保存到redis
            $redis->set('goods-category',$html,3600*12);
        }

        return $html;
    }


}