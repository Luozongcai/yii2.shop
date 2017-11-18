<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10 0010
 * Time: 14:30
 */

namespace backend\controllers;


use backend\models\Menu;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class MenuController extends Controller
{

    public function  behaviors(){
        return [
            'rbac' => [
                'class' =>\backend\filters\RnewFilter::className(),

            ],
        ];

    }
    //添加菜单
    public function actionAdd(){
        $model=new Menu();
        $request=new Request();



        //获取菜单分类
        $query=Menu::find()->where(['parent_id'=>0])->all();
        $arr = ArrayHelper::merge([0=>['id'=>0,'name'=>'顶级分类','parent_id'=>0]],$query);
        $parents=ArrayHelper::map($arr,'id','name');


        //获取所有路由
        $auth = \Yii::$app->authManager;
        $urls =$auth->getPermissions();// 权限列表
        $urls = ArrayHelper::map($urls, 'name', 'name');
        $url_0 = [''=>'===请选择路由==='];
        $urls = $url_0+$urls;

        if ($request->isPost){
            //接受数据
            $model->load($request->post());
            if ($model->validate()){
                //通过验证
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add',['model'=>$model,'urls'=>$urls,'parents'=>$parents]);


    }
    //展示菜单列表
    public function actionList(){
        //得到全部数据
        $query=Menu::find();
        //分页工具类
        /*$pager=new Pagination();
        //总条数
        $pager->totalCount=$query->count();
        $pager->pageSize=5;//每业显示2条
        *///查询一页的数据
        $models = $query->orderBy( 'id  ASC')/*->limit($pager->limit)->offset($pager->offset)*/->all();
        return $this->render('list',['models'=>$models,/*'pager'=>$pager*/]);

    }
    //删除一个菜单
    public function actionDelete(){
        $request = new Request();
        $id = $request->post('id');
        //如果有子节点则不能删除isleaf代表是叶子无子节点
        $son = Menu::find()->where(['parent_id' => $id])->all();
        if ($son) {
            return '该节点下有子节点,不能删除!';
        } else {
            $model = Menu::findOne(['id' => $id]);
            $model->delete();
            return 'yes';
        }
    }
    //修改菜单
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        $request=new Request();

        //获取菜单分类
        $query=Menu::find()->where(['parent_id'=>0])->all();
        $arr = ArrayHelper::merge([0=>['id'=>0,'name'=>'顶级分类','parent_id'=>0]],$query);
        $parents=ArrayHelper::map($arr,'id','name');


        //获取所有路由
        $auth = \Yii::$app->authManager;
        $urls =$auth->getPermissions();// 权限列表
        $urls = ArrayHelper::map($urls, 'name', 'name');
        $url_0 = [''=>'===请选择路由==='];
        $urls = $url_0+$urls;

        if ($request->isPost){
            //接受数据
            $model->load($request->post());
            if ($model->validate()){
                //通过验证
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add',['model'=>$model,'urls'=>$urls,'parents'=>$parents]);


    }

}