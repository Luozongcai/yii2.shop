<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 15:14
 */

namespace backend\controllers;


use backend\models\LoginForm;
use backend\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller

{


    //登录
    public function actionLogin(){
        //显示登录表单
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //接收表单数据
            $model->load($request->post());
            if ($model->validate()) {
                //验证账号密码是否正确
                if ($model->login()) {
                    // 跳转
                    $user = User::findOne(['username'=>$model->username]);
                    $user->last_login_time = time();
                    $user->last_login_ip = \yii::$app->request->userIP;
                    $user->save(false);
                    \Yii::$app->session->setFlash('success', '登录成功');
                    //跳转
                    return $this->redirect(['list']);
                }
            }
        }
        // 调用视图,显示表单
        return $this->render('login', ['model' => $model]);
    }
    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    //添加用户
    public function actionAdd(){
        $model = new User();
        $request = new Request();
        if ($request->isPost) {
            //接受数据
            $model->load($request->post());
            if ($model->validate()) {

                //使用yii 安全组件来(加密密码)生成密码的密文
                $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password);
                $model->save(false);//保存数据
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    //用户列表
    public function actionList(){
        $query=User::find();
        //分页工具类
        $pager=new Pagination();
        //总跳数
        $pager->totalCount=$query->count();
        $pager->pageSize=2;//煤业显示1条
        //查询一页的数据
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list',['models'=>$models,'pager'=>$pager]);
    }
    //删除用户
    public function actionDelete(){
        $request = new Request();
        $id = $request->post('id');
        $model= User::findOne(['id'=>$id]);
        $model->delete();
        //跳转
        return 'yes';
    }
    //修改用户
    public function actionEdit($id){
        $model=User::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost) {
            //接受数据
            $model->load($request->post());
            if ($model->validate()) {

                //使用yii 安全组件来(加密密码)生成密码的密文
                $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password);
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['list']);
            }
        }

        return $this->render('edit',['model'=>$model]);
    }







}