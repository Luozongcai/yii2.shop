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
use yii\web\NotFoundHttpException;
use yii\web\Request;

class AuthController extends Controller
{


   public function  behaviors(){
        return [
            'rbac' => [
                'class' =>\backend\filters\RnewFilter::className(),

            ],
        ];

    }
    //添加角色
    public function actionAddRole()
    {
        $auth = \Yii::$app->authManager;
        $model= new RoleForm();
        //设置场景 , 当前场景是SCENARIO_Add场景
        $model->scenario = PermissionsForm::SCENARIO_Add;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //创建角色
                $role = $auth->createRole($model->name);
                $role->description = $model->description;

                $auth->add($role);//角色添加到数据表
                if ($model->permissions){
                    foreach ($model->permissions as $permissionName) {
                        $permission = $auth->getPermission($permissionName);//根据权限的名称获取权限对象
                        //给角色分配权限
                        $auth->addChild($role, $permission);
                    }
                }

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('list-role');
            }


        }

        $permissions = $auth->getPermissions();
        //var_dump($permissions);exit;
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        return $this->render('add-role', ['model' => $model, 'permissions' => $permissions]);
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
    public function actionEditRole($name)
    {
        $auth = \Yii::$app->authManager;
        $request = \Yii::$app->request;
        $model = new RoleForm();
        //得到权限数据
        $permissions = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissions,'name','description');
        //得到角色数据
        $role = $auth->getRole($name);
        //如果权限不存在,提示
        if($role == null){
            //404错误
            throw new NotFoundHttpException('权限不存在');
        }

        //设置场景 , SCENARIO_Edit
        $model->scenario = PermissionsForm::SCENARIO_Edit;
        //给oldname赋值到model中验证试验
        $model->oldName = $role->name;
        $model->name = $role->name;
        $model->description = $role->description;

        //回显多选遍历为数组赋值给permissions
        if ($auth->getPermissionsByRole($name)){
            $pers = $auth->getPermissionsByRole($name);
            foreach ($pers as $v){
                $per[] = $v->name;
            }
            $model->permissions = $per;
        }

        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate() && $model->update($name)){


                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('list-role');
            }
        }
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);

    }


    //添加权限
    public function actionAddPermissions(){

        $model= new PermissionsForm();
        //设置场景 , 当前场景是SCENARIO_Add场景
        $model->scenario = PermissionsForm::SCENARIO_Add;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            //验证规则,add方法保存
            if ($model->validate()&& $model->add()) {

            //添加成功
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect('list-permissions');
            }

        }

        return $this->render('add-permissions', ['model' => $model]);
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
    public function actionEditPermissions($name){
        $auth = \Yii::$app->authManager;
        //获取权限
        $permission =  $auth->getPermission($name);
        //如果权限不存在,提示
        if($permission == null){
            //404错误
            throw new NotFoundHttpException('权限不存在');
        }

        $model = new PermissionsForm();
        //设置场景 , SCENARIO_Edit
        $model->scenario = PermissionsForm::SCENARIO_Edit;
        //给oldname赋值到model中验证试验
        $model->oldName = $permission->name;
        $model->name = $permission->name;
        $model->description = $permission->description;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //验证和调用update方法修改保存
            if($model->validate()){

                if ($model->update($name)){

                    \Yii::$app->session->setFlash('success','修改成功');
                    $this->redirect('list-permissions');
                }

            }
        }
        return $this->render('add-permissions',['model'=>$model]);

    }

}