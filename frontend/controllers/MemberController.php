<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/12 0012
 * Time: 13:18
 */

namespace frontend\controllers;


use frontend\components\Sms;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\Request;

class MemberController extends Controller
{
    public $enableCsrfValidation=false;
    //登录
    public function actionLogin(){
        //展示表单
        $model = new LoginForm();

        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                //var_dump($model);die();
                $member = Member::findOne(['username'=>$model->username]);
                if($member && \yii::$app->security->validatePassword($model->password,$member->password_hash)){
                    //提示 跳转
                    $member->last_login_time = time();
                    $member->last_login_ip = \yii::$app->request->userIP;
                    $member->save();
                    if($model->rememberMe){
                        \yii::$app->user->login($member,3600*7*24);
                    }else{
                        \yii::$app->user->login($member);
                    }


                    //登录成功保存cookie里的购物车数据到数据库方法在cart.php
                      Cart::CookieCart();

                    return $this->redirect(['goods/index']);
                }else{
                    \yii::$app->session->setFlash('success','帐号或者密码错误');
                }



            }
        }
        return $this->render('login',['model'=>$model]);
    }

    public function actionLogin1(){

        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                if ($model->login()) {

                   //var_dump($model->rememberMe);die;

                    $user = Member::findOne(['username'=>$model->username]);
                    if ($model->rememberMe){
                        //记住密码rememberMe=1
                        \Yii::$app->user->login($user,30*24*3600);//设置cookie有效期
                    }
                    $user->last_login_time = time();//保存最后登录时间
                    //保存最后登录ip
                    $user->last_login_ip = \yii::$app->request->userIP;
                    $user->save(false);
                    \Yii::$app->session->setFlash('success', '登录成功');
                    //跳转
                    return $this->redirect(['index']);
                }else{
                    \Yii::$app->session->setFlash('success', '用户信息不准确');
                    return $this->redirect(['login']);
                }
            }
        }

        return $this->render('login');
    }

    //注册
    public function actionRegist(){
        $model = new Member();
        $request = \Yii::$app->request;
        if($request->isPost) {
            $model->load($request->post(), '');

            if ($model->validate()) {
               $tel=$model->tel;
               //$checkcode=$model->checkcode;
                //验证售后机验证码
                $redis = new \Redis();
                $redis->connect('127.0.0.1');
                //$code = $redis->get('captcha_'.$tel);
                if (/*$checkcode==$code*/true){
                    //验证码正确
                    //加密密码生
                    $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password_hash);

                    $model->auth_key = \Yii::$app->security->generateRandomString();//随机字符串
                    //var_dump($model);die;
                    $model->save();//保存数据
                    \Yii::$app->session->setFlash('success', '注册成功');
                    // 跳转
                    return $this->redirect(['login']);
                    //var_dump($model);die;
                }


            }
        }
        return $this->render('regist');
    }
    //  public function actionLogout()
    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['goods/index']);
    }




    //验证用户名唯一
    public function actionCheckName($username){
        $model=Member::findOne(['username'=>$username]);
        if($model){
            return 'false';
        }
        return 'true';
    }





    //AJAX发送短信  后台AJAX发送短信功能:
    public function actionAjaxSms($phone){


        //接收请求手机号码
        //$phone = '15881335955';
        $code=rand(1000,9999);
        //发送短信
        $response = Sms::sendSms(
            "老罗爱IT", // 短信签名
            "SMS_109405479", // 短信模板编号
            $phone, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code,
                //"product"=>"dsd"
            )//,
        //"123"   // 流水号,选填
        );

        //保存验证码(SESSION或)REDIS
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('captcha_'.$phone,$code,10*60);
        //根据$response结果判断是否发送成功 $response->Code
       if ($response->Code=='OK'){
            return "yes";
        }else{
            return false;
        }

    }
    //AJAX验证短信
    public function actionCheckSms(){
        $request = new Request();
        $sms= $request->post('captcha');
        $phone= $request->post('tel');

       // var_dump($sms,$phone);

        //从redis获取验证码
        //返回对比结果
        //验证验证码
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $code = $redis->get('captcha_'.$phone);
        if ($code==$sms){
            //输入正确
            return 'true';

        }else{
            return 'false';
        }

    }


    //测试阿里大于短信发送功能
    public function actionSms(){
        //$sms = new Sms();
        $response = Sms::sendSms(
            "老罗爱IT", // 短信签名
            "SMS_109405479", // 短信模板编号
            "15892229801", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>rand(1000,9999)."唐爽爽,你中了500万,高不高兴!",
                //"product"=>"dsd"
            )//,
        //"123"   // 流水号,选填
        );
        echo "发送短信(sendSms)接口返回的结果:\n";
        print_r($response);

        //frontend\components\Sms ---> require '@frontend\components\Sms.php';
        //Aliyun\Core\Config;   ---> require  '@Aliyun\Core\Config.php';
    }

}