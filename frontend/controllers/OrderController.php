<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 23:11
 */

namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Url;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Request;

class OrderController extends Controller
{
    public $enableCsrfValidation=false;
    //订单展示页面
    public function actionOrder()
    {
        if (\Yii::$app->user->isGuest) {
            //未登录
            return $this->redirect(['member/login']);
        } else {
            //登录时显示订单页
            //1.获取收货地址信息
            $member_id = \Yii::$app->user->identity->id;
            $urls = Url::find()->where(['member_id' => $member_id])->all();

            //2.获取购物车商品信息
            $cartss = Cart::find()->where(['member_id' => $member_id])->all();
            $carts = [];
            if ($cartss) {
                foreach ($cartss as $cart) {
                    $carts[$cart->goods_id] = $cart->amount;
                }

                $models = Goods::find()->where(['in', 'id', array_keys($carts)])->all();

                return $this->render('order', ['urls' => $urls, 'carts' => $carts, 'models' => $models]);
            }

        }

    }
    //处理订单保存订单数据
    public  function actionSave()
    {
        $member_id = \Yii::$app->user->identity->id;
        //接受数据
        $model = new Order();
        $request=new Request();
        //快递方式
        $arrdelivery=[
            1=>['name'=>'普通快递送货上门','price'=>10],
            2=>['name'=>'特快专递','price'=>40],
            3=>['name'=>'加急快递送货上门','price'=>40],
            4=>['name'=>'平邮','price'=>10],
        ];
        //付款方法
        $arrpay=[
            1=>'货到付款',
            2=>'在线支付',
            3=>'上门自提',

        ];
        if ($request->isPost){
            //接受数据
            $model->load($request->post(),'');
           // if ($model->validate()) {
                //开启事务
                $transaction = \Yii::$app->db->beginTransaction();
                try {

                $address_id = $model->address_id;
                $delivery = $model->delivery;
                $pay = $model->pay;
                //1.根据地址id保存数据
                $url = Url::findOne(['id' => $address_id]);

                $model->member_id = $member_id;//用户id
                //收货地址信息
                $model->name = $url->name;
                $model->province = $url->cmbProvince;
                $model->city = $url->cmbCity;
                $model->area = $url->cmbCity;
                $model->address = $url->url;
                $model->tel = $url->tel;
                //配送方式信息
                $model->delivery_id = $delivery;
                $model->delivery_name = $arrdelivery[$delivery]['name'];
                $model->delivery_price = $arrdelivery[$delivery]['price'];

                //支付方式信息
                $model->payment_id = $pay;
                $model->payment_name = $arrpay[$pay];
                $model->payment_id = $pay;

                //订单金额

                //总金额=商品金额+快递费
                $model->total =  $arrdelivery[$delivery]['price'];//暂时=邮费
                //订单状态
                $model->status = 1;//待支付
                //第三方交易号
                $model->trade_no = rand(1000000,9999999);

                //创建时间
                $model->create_time = time();


                    ////保存order表
                    if ($model->save()) {
                        //得到order表id继续保存订单商品详情表order_goods
                        $order_id = $model->id;

                        //先查询购物车数据
                        $cartss = Cart::find()->where(['member_id' => $member_id])->all();
                        if ($cartss) {
                            foreach ($cartss as $cart) {
                                $goods_id = $cart->goods_id;
                                $amount = $cart->amount;
                                $goods = Goods::findOne(['id' => $goods_id]);

                                $Order_goods = new OrderGoods();

                                //判断库存是否够充足
                                if ($goods->stock < $amount) {
                                    //库存不足跑出异常
                                    throw new Exception($goods->name . '商品库存不足');
                                    //return $this->redirect(['order/order']);
                                }
                                //库存充足
                                $Order_goods->amount = $amount;

                                $Order_goods->order_id = $order_id;//保存订单号
                                //保存商品数据
                                $Order_goods->goods_id = $goods_id;
                                $Order_goods->goods_name = $goods->name;
                                $Order_goods->logo = $goods->logo;
                                $Order_goods->price = $goods->shop_price;

                                //小计金额
                                $Order_goods->total = $goods->shop_price * $amount;

                                //保存订单商品详情表order_goods
                                $Order_goods->save();
                                //order表总金额累加
                                $model->total += $Order_goods->total;
                                //修改商品库存
                                Goods::updateAllCounters(['stock' => -$amount], ['id' => $goods_id]);

                            }
                            //保存订单表总金额
                            $model->save();
                            //删除购物车
                            Cart::deleteAll('member_id=' . $member_id);

                        }

                        //提交事务
                        $transaction->commit();

                    }
                } catch
                    (Exception $e){
                        //回滚
                        $transaction->rollBack();
                        //下单失败
                        echo $e->getMessage();
                        exit();

                    //}

                }

                return $this->redirect(['ok']);
            }

          }

      //订单成功页面
    public function actionOk(){
        return $this->render('ok');
    }
    //订单列表页面
    public function actionList(){
        if (\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else{
            $member_id = \Yii::$app->user->identity->id;
            //返回订单书籍
            $models=Order::find()->where(['member_id'=>$member_id])->all();
            return  $this->render('list',['models'=>$models]);
        }

    }


  }