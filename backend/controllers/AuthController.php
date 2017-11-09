<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/9 0009
 * Time: 12:47
 */

namespace backend\controllers;


use backend\models\PermissionsForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class AuthController extends Controller
{
    //添加角色
    public function actionAddRole()
    {
        $auth = \Yii::$app->authManager;
        $models = new RoleForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $models->load($request->post());
            if ($models->validate()) {
                //创建角色
                $role = $auth->createRole($models->name);
                $role->description = $models->description;
                $auth->add($role);//角色添加到数据表
                foreach ($models->permissions as $permissionName) {
                    $permission = $auth->getPermission($permissionName);//根据权限的名称获取权限对象
                    //给角色分配权限
                    $auth->addChild($role, $permission);
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('list-role');
            }


        }

        $permissions = $auth->getPermissions();
        //var_dump($permissions);exit;
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        return $this->render('add-role', ['models' => $models, 'permissions' => $permissions]);
    }
    //角色列表
    public function actionListRole()
    {
        $auth = \Yii::$app->authManager;
        $models= $auth->getRoles();//角色列表
        return $this->render('list-role',['models'=>$models]);

    }
    //删除角色
    public function actionDeleteRole(){
        $auth = \Yii::$app->authManager;
        $request = new Request();
        $name = $request->post('name');
        if($name){
            $role = $auth->getRole($name);
            $auth->remove($role);//删除角色
            return 'yes';

        }else{
            return '该角色已被删除或者不存在';
        }
    }
    //修改角色
    public function actionEditRole()
    {
        $auth = \Yii::$app->authManager;
        $models = new RoleForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $models->load($request->post());
            if ($models->validate()) {
                //创建角色
                $role = $auth->createRole($models->name);
                $role->description = $models->description;
                $auth->add($role);//角色添加到数据表
                foreach ($models->permissions as $permissionName) {
                    $permission = $auth->getPermission($permissionName);//根据权限的名称获取权限对象
                    //给角色分配权限
                    $auth->addChild($role, $permission);
                }
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('list-role');
            }
        }
    }


    //添加权限
    public function actionAddPermissions(){

        $auth = \Yii::$app->authManager;
        $models = new PermissionsForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $models->load($request->post());
            if ($models->validate()) {
                //保存权限
                $permission = $auth->createPermission($models->name);
                $permission->description = $models->description;
                $auth->add($permission);

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('list-permissions');
            }

        }

        return $this->render('add-permissions', ['models' => $models]);
    }
    //权限列表
    public function actionListPermissions(){
        $auth = \Yii::$app->authManager;
        $models=  $auth->getPermissions();// 权限列表
        return $this->render('list-permissions',['models'=>$models]);
    }
    //删除权限
    public function actionDeletePermissions(){
        $auth = \Yii::$app->authManager;
        $request = new Request();
        $name = $request->post('name');

        if($name){
            $permission = $auth->getPermission($name);
            $auth->remove($permission);// 删除权限
            return 'yes';

        }else{
            return '该权限已被删除或者不存在';
        }
    }

    //修改权限
    public function actionEditPermissions(){
        $auth = \Yii::$app->authManager;
        $models = new PermissionsForm();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $models->load($request->post());
            if ($models->validate()) {
                //保存权限
                $permission = $auth->createPermission($models->name);
                $permission->description = $models->description;
                $auth->add($permission);

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('list-permissions');
            }

        }

        return $this->render('add-permissions', ['models' => $models]);
    }

}