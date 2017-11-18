<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15 0015
 * Time: 15:59
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
    public function rules()
    {
        return [

            [['amount'], 'required'],

        ];
    }
    //购物车列表数据
    public static function getListCars(){
         $html='<tbody>';
         $member_id=\Yii::$app->user->identity->id;
        $models=Cart::find()->where(['member_id'=>$member_id])->all();
        $money=0;
        foreach ($models as $k1=>$model){
            $goods_id=$model->goods_id;
            $goods=\backend\models\Goods::findOne(['id'=>$goods_id]);

            $html.='<tr>
                    <td class="col1"><a href=""><img src="http://admin.yii2shop.com/'.$goods->logo.'" alt="" /></a> <strong><a href="">'.$goods->name.'</a></strong></td>
            <td class="col3">￥<span>'.$goods->shop_price.'</span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="amount" value="'.$model->amount.'" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <td class="col5">￥<span>'.$model->amount*$goods->shop_price.'</span></td>
            <td class="col6"><a href="'.\yii\helpers\Url::to(['goods/delcart']).'?id='.$goods->id.'">删除</a></td>
            </tr>
            ';
           $html.='</tbody>';
           $money+=$model->amount*$goods->shop_price;
        };
        $html.=' <tfoot>
				<tr>
					<td colspan="6">购物金额总计： <strong>￥ <span id="total">'.$money.'.00</span></strong></td>
				</tr>
			</tfoot>';

            return $html;
        }
        //登录成功保存cookie里购物车到数据库
    public static function CookieCart(){
        //获取用户id
        $member_id = \Yii::$app->user->identity->id;
        $cookies = \Yii::$app->request->cookies;
        $carts = $cookies->getValue('carts');
        if ($carts){
            //如果cookie存在
            $carts = unserialize($carts);//反序列化
            //遍历购物车数据
            foreach ($carts as $goods_id=>$amount){
                $goods_old = Cart::findOne(['member_id' => $member_id,'goods_id' => $goods_id]);
                if ($goods_old) {
                    $goods_old->amount += $amount;
                    $goods_old->save();//保存数据
                } else {
                    $model = new Cart();
                    $model->member_id = $member_id;
                    $model->goods_id = $goods_id;
                    $model->amount = $amount;
                    $model->save();//保存数据
                }
            }
            //保存到数据库后删除cookie信息
            \yii::$app->response->cookies->remove('carts');

        }

    }


}