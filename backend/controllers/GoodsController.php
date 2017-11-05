<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5 0005
 * Time: 16:36
 */

namespace backend\controllers;


use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class GoodsController extends Controller
{
    //添加商品分类
    public function actionCategoryAdd(){
        $model = new GoodsCategory();
        //parent_id设置默认值
        $model->parent_id = 0;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->parent_id == 0){
                    //创建根节点
                    $model->makeRoot();
                    return $this->redirect(['category-list']);
                }else{
                    //添加子节点
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                    return $this->redirect(['category-list']);
                }

            }
        }

        return $this->render('category-add',['model'=>$model]);
    }
    //展示商品分类
    public function actionCategoryList(){
        //得到全部数据
        $query=GoodsCategory::find();
        //分页工具类
        $pager=new Pagination();
        //总条数
        $pager->totalCount=$query->count();
        $pager->pageSize=5;//每业显示2条
        //查询一页的数据
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('category-list',['models'=>$models,'pager'=>$pager]);
    }
    //删除商品分类
    public function actionCategoryDelete(){
        $request = new Request();
        $id = $request->post('id');
        //如果有子节点则不能删除
        $son= GoodsCategory::find()->where(['parent_id'=>$id])->all();
        if($son){
            return '该节点下有子节点,不能删除!';
        }else {
            $model = GoodsCategory::findOne(['id' => $id]);
            $model->delete();
            return 'yes';
        }
    }
    //修改商品分类
    public function actionCategoryEdit($id)
    {
        $model = GoodsCategory::findOne(['id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->parent_id == 0) {
                    //创建根节点
                    $model->makeRoot();
                    return $this->redirect(['category-list']);
                } else {
                    //添加子节点
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($parent);
                    return $this->redirect(['category-list']);
                }
            }
        }
        return $this->render('category-add', ['model' => $model]);
    }
}