<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13 0013
 * Time: 17:37
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\Url;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;

class GoodsController extends Controller
{
    public $enableCsrfValidation=false;
    //首页
    public function actionIndex(){
        $models=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['models'=>$models]);
    }
    //商品列表
    public function actionList($id){
        //商品分类  一级  二级  三级
        $goods_category = GoodsCategory::findOne(['id'=>$id]);
        //三级分类
        if($goods_category->depth == 2){
            $query = Goods::find()->where(['goods_category_id'=>$id]);

        }else{
           //children 查询子节点andWhere筛选子节点层级为2 的叶子节点
            //column返回第一列id
            $ids = $goods_category->children()->andWhere(['depth'=>2])->column();
            //in表示goods_category_id取值属于$ids
            $query = Goods::find()->where(['in','goods_category_id',$ids]);

        }
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->pageSize = 20;

        $lists = $query->limit($pager->limit)->offset($pager->offset)->all();
        $models=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('list',['lists'=>$lists,'pager'=>$pager,'models'=>$models]);
    }
    //用户信息页面
    public function actionUser(){
        if (\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);

        }else{
            $id= \Yii::$app->user->identity->id;
            $model=Member::findOne(['id'=>$id]);
            return $this->render('user',['model'=>$model]);
        }

    }
    //商品详情页
    public function actionGoods($id){
        $goods=Goods::findOne(['id'=>$id]);
        $content=GoodsIntro::findOne(['goods_id'=>$id]);
        $gallery=GoodsGallery::findOne(['goods_id'=>$id]);
        return $this->render('goods',['goods'=>$goods,'content'=>$content,'gallery'=>$gallery]);
    }



    //收货地址页
    public function actionAddress()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else{
            $member_id= \Yii::$app->user->identity->id;
            $models=Url::find()->where(['member_id'=>$member_id])->all();
            return $this->render('address',['models'=>$models]);
        }

    }
    //添加收货地址
    public function actionUrl(){
        $model=new Url();
        $request=new Request();
        if ($request->isPost){
            //接受数据
            $model->load($request->post(),'');
            if ($model->validate()){
                //通过验证
                $model->member_id= \Yii::$app->user->identity->id;
                $model->save();//保存数据

                \Yii::$app->session->setFlash('success','添加成功');
               // return $this->redirect(['list']);
                return $this->redirect('address');
            }
        }
        return $this->render('address',['model'=>$model]);


    }
    //删除收货地址
    public function actionDelete($id){

        $model= Url::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect('address');

        }
    //修改收货地址
    public function actionEdit($id){
        $model= Url::findOne(['id'=>$id]);
        $request=new Request();
        if ($request->isPost){
            //接受数据
            $model->load($request->post(),'');
            if ($model->validate()){
                //通过验证
                $model->save();//保存数据

                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('address');
            }
        }
        return $this->render('edit',['model'=>$model]);


    }


    //购物车列表
    public function actionCars(){
        if (\Yii::$app->user->isGuest){
            //未登录,从cookie获取数据
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if($carts){
                $carts = unserialize($carts);//反序列化
            }else{
                $carts = [];
            }

        }else{
            $member_id = \Yii::$app->user->identity->id;
            $cartss = Cart::find()->where(['member_id' => $member_id])->all();
            $carts = [];
            if ($cartss){
                foreach ($cartss as $cart){
                    $carts[$cart->goods_id]=$cart->amount;
                }
            }
        }

        $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
        return $this->render('cars',['carts'=>$carts,'models'=>$models]);


    }
    //购物车添加商品
    public function actionAddcart($goods_id,$amount){

        if (\Yii::$app->user->isGuest){
                //未登录,保存到cookie
                //1.获取$cooki中购物车数据
                $cookies=\Yii::$app->request->cookies;
                $carts=$cookies->getValue('carts');
                if ($carts){
                    $carts=unserialize($carts);//反序列化
                }else{
                    $carts=[];
                }
                //2.购物车中是否存在该商品,如果存在数量累加 直接添加
                    if (array_key_exists($goods_id,$carts)){
                            //判断$Carts中有没有键值为$goods_id
                        $carts[$goods_id]+=$amount;
                    }else{
                        $carts[$goods_id]=$amount;
                    }
                    //将数据保存到cookie
                    $cookies=\Yii::$app->response->cookies;
                    $cookie=new Cookie();
                    $cookie->name='carts';
                    $cookie->value=serialize($carts);//序列化数组
                    $cookie->expire=time() + 3600*24*30;//过气时间1个月
                    $cookies->add($cookie);

         /*   $cookies->add(new \yii\web\Cookie([
                'name' => 'abc',
                'value' => 'xyz',
                'expire' => time() + 86400 * 365,
            ]));*/

        }else {

                //登录情况保存到数据库
                //获取用户id
                $member_id = \Yii::$app->user->identity->id;
                $goods_old = Cart::findOne(['member_id' => $member_id, 'goods_id' => $goods_id]);
                //->where(['member_id'=>$member_id])->andWhere(['goods_id'=>$id])->all();
                if ($goods_old) {
                    $goods_old->amount += $amount;
                    $goods_old->save();//保存数据
                } else {
                    $model = new Cart();
                    $model->member_id = $member_id;
                    $model->goods_id = $goods_id;
                    $model->amount = $amount;
                    $model->save();//保存数据
                    \Yii::$app->session->setFlash('success', '添加成功');
                }
        }

          return $this->redirect(['cars']);
    }
    //删除购物车商品
    public function actionDelCart($goods_id){
        if (\Yii::$app->user->isGuest){
            //未登录删除cookie的
            //查询cookie
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
             $carts = unserialize($carts);//反序列化
            unset($carts[$goods_id]);//删除一个键值对
            //保存修改后的cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies->add($cookie);

        }else{
            //登录删库
            //$goods_id = \Yii::$app->request->post('goods_id');
            $member_id = \Yii::$app->user->identity->id;
            $model=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            $model->delete();

        }

        return $this->redirect('cars');
    }
    //修改购物车商品数量ajax修改
    public function actionEditcart()
    {

                $goods_id = \Yii::$app->request->post('goods_id');
                $amount = \Yii::$app->request->post('amount');
                //未登录
                if (\Yii::$app->user->isGuest) {
                    //取出cookie中的购物车数据
                    $cookies = \Yii::$app->request->cookies;
                    $carts = $cookies->getValue('carts');
                    if ($carts) {
                        $carts = unserialize($carts);//反序列化
                    } else {
                        $carts = [];
                    }
                    //修改购物车商品数量
                    $carts[$goods_id] = $amount;
                    //保存修改后的cookie
                    $cookies = \Yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value = serialize($carts);
                    $cookies->add($cookie);
                } else {
                    //登录时修改数据库
                    //获取用户id
                    $member_id = \Yii::$app->user->identity->id;
                    $goods_old = Cart::findOne(['member_id' => $member_id, 'goods_id' => $goods_id]);
                    if ($goods_old) {
                        //修改商品数量
                        $goods_old->amount = $amount;
                        $goods_old->save();//保存数据
                    }
                }

        }

    }