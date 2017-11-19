<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 15:14
 */

namespace backend\controllers;


use backend\models\LoginForm;
use backend\models\Menu;
use backend\models\PwdFome;
use backend\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller

{
    public function  behaviors(){
        return [
            'rbac' => [
                'class' =>\backend\filters\RnewFilter::className(),


            ],
        ];

    }
    //获取用户权限测试
    public function actionRoles()
    {

        $id = \Yii::$app->user->identity->id;//得到登录用户id
        $auth = \yii::$app->authManager;
        //$model=User::findOne(['id'=>$id]);
        $roles = $auth->getRolesByUser($id);
        if ($roles) {
            foreach ($roles as $role) {
                //根据用户ID的到角色遍历
                if ($auth->getPermissionsByRole($role->name)) {
                    //根据角色名字得到权限
                    $pers = $auth->getPermissionsByRole($role->name);
                    foreach ($pers as $v) {
                        $per[] = $v->name;
                    }
                }

            }
            json_encode($pers);
        }
        if ( $auth->getPermissions()){
            $permissions = $auth->getPermissions();
            foreach ($permissions as $v) {
                $pers[] = $v->name;
            }
        }

        var_dump(json_encode($pers));
    }

    public function actionRoo(){

        $menss = [];
        //获取所有一级菜单
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
            //遍历该一级菜单的子菜单
            foreach ($menu->children as $child){
                //根据用户权限来确定是否显示该菜单
                if(\Yii::$app->user->can($child->url)){
                    $menss[] = $child->url;
                }
            }

        }



    }




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
                   // var_dump($model->rememberMe);die;

                    $user = User::findOne(['username'=>$model->username]);

                    if ($model->rememberMe){
                        //记住密码rememberMe=1
                        \Yii::$app->user->login($user,30*24*3600);//参数2是设置cookie有效期
                    }
                    $user->last_login_time = time();//保存最后登录时间
                    //保存最后登录ip
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
    //修改当前登录用户自己密码
    public function actionPwd()

    {
        $model = new PwdFome();
        // var_dump($model);die;
        // 接收表单数据,验证旧密码
        $request = new  Request();

        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){

                $password_hash = \Yii::$app->user->identity->password_hash;//得到登录用户的密码
                //验证旧密码
                if(\Yii::$app->security->validatePassword($model->password1,$password_hash)){
                    //旧密码正确//3 更新当前用户的密码

                    //直接修改数据库密码
                    User::updateAll([
                        'password_hash'=>\Yii::$app->security->generatePasswordHash($model->password2)
                    ],
                        ['id'=>\Yii::$app->user->id]
                    );
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('success','密码修改成功,请重新登录');
                    return $this->redirect(['login']);
                }else{
                    //旧密码不正确
                    $model->addError('password1','旧密码不正确');

                }

            }
        }
        return $this->render('pwd',['model'=>$model]);
    }

    //添加用户
    public function actionAdd(){

        $auth = \yii::$app->authManager;
        $model = new User();
        //设置场景 , 当前场景是SCENARIO_Add场景
        $model->scenario = User::SCENARIO_Add;

        $request = new Request();
        if ($request->isPost) {
            //接受数据
            $model->load($request->post());
            if ($model->validate()) {

                //使用yii 安全组件来(加密密码)生成密码的密文
                $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password);
                $model->auth_key = \Yii::$app->security->generateRandomString();//随机字符串
                $model->save(false);//保存数据

                //分配角色
                if ($model->roles){
                    foreach($model->roles as $roleName){
                        $role = $auth->getRole($roleName);
                        $auth->assign($role,$model->id);
                    }
                }

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['list']);
            }
        }
        //展示表单页面
        $roles = $auth->getRoles();
        $roles = ArrayHelper::map($roles,'name','name');


        return $this->render('add',['model'=>$model,'roles'=>$roles]);
    }
    //用户列表
    public function actionList(){
        $query=User::find();
        //分页工具类
        $pager=new Pagination();
        //总跳数
        $pager->totalCount=$query->count();
        $pager->pageSize=5;//煤业显示1条
        //查询一页的数据
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list',['models'=>$models,'pager'=>$pager]);
    }
    //删除用户
    public function actionDelete(){
        $auth = \yii::$app->authManager;
        $request = new Request();
        $id = $request->post('id');
        if($id){
            $model = User::findOne(['id'=>$id]);
            //删除数据
            $model->delete();
            $auth->revokeAll($id);
            return 'yes';

        }else{
            return '管理员已被删除或者不存在';
        }
    }
    //修改用户
    public function actionEdit($id){
        $auth = \yii::$app->authManager;
        $model=User::findOne(['id'=>$id]);
        $model->roles = $auth->getRolesByUser($id);
        $request = new Request();

        //回显多选遍历为数组赋值给permissionsid
        if ($auth->getRolesByUser($id)){
            $pers = $auth->getRolesByUser($id);
            foreach ($pers as $v){
                $per[] = $v->name;
            }
            $model->roles = $per;
        }
        if ($request->isPost) {
            //接受数据
            $model->load($request->post());
            if ($model->validate()) {

                //使用yii 安全组件来(加密密码)生成密码的密文
               // $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password);
                $model->save();//保存数据
                //删掉原来的角色
                $auth->revokeAll($id);
                //重新分配角色
                if ($model->roles){
                    foreach($model->roles as $roleName){
                        $role = $auth->getRole($roleName);
                        $auth->assign($role,$id);

                    }
                }

                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['list']);
            }
        }
        $roles = $auth->getRoles();
        $roles = ArrayHelper::map($roles,'name','name');
        return $this->render('edit',['model'=>$model,'roles'=>$roles]);
    }







}