<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3 0003
 * Time: 15:12
 */

namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller

{
    //添加文章分类
    public function actionCategoryAdd(){
        $model=new ArticleCategory();
        $request=new Request();
        if ($request->isPost){
            //接受数据
            $model->load($request->post());
            if ($model->validate()){
                //通过验证
                 $model->save();//保存数据
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['category-list']);
            }

        }
        return $this->render('category-add',['model'=>$model]);



    }
    //文章分类列表
    public function actionCategoryList(){
        //得到全部数据
        $query=ArticleCategory::find();
        //分页工具类
        $pager=new Pagination();
        //总条数
        $pager->totalCount=$query->count();
        $pager->pageSize=2;//每业显示2条
        //查询一页的数据
        $models=$query->where(['status'=>1])->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('category-list',['models'=>$models,'pager'=>$pager]);

    }
    //删除文章分类
    public function actionCategoryDelete(){
        $request = new Request();
        $id = $request->post('id');
        $model= ArticleCategory::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save(false);//保存数据
        //跳转
        return 'yes';


        //根据id查询分组是否有组员
        /*        $admin = Admin::find()->where(['group_id'=>$id])->all();
                if($admin){
                    return '该品牌下有商品,不能删除!';
                }else{

                    $model= Brand::findOne(['id'=>$id]);
                    $model->status=-1;
                    $model->save(false);//保存数
                    //删除该记录对应
                    return 'yes';
                }*/

    }
    //修改文章分类
    public function actionCategoryEdit($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=new Request();
        if ($request->isPost){
            //接受数据
            $model->load($request->post());
            if ($model->validate()){
                //通过验证
                  $model->save();//保存数据
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['category-list']);
            }

        }
        return $this->render('category-add',['model'=>$model]);

    }

    //添加文章
    public function actionAdd(){
        $model=new Article();
        $model2=new ArticleDetail();
        $request=new Request();

        if ($request->isPost){
            //接受数据
            //保存article表数据
            $model->load($request->post());
            if ($model->validate()){
                //通过验证
                $model->save();//保存数据
            }
            $id = \Yii::$app->db->getLastInsertID();//获取刚保存的id


            //保存article_detail表数据
            $model2->load($request->post());
            if ($model2->validate()){
                //通过验证
                $model2->article_id=$id;
                $model2->save();//保存数据
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add',['model'=>$model,'model2'=>$model2]);



    }
    //文章列表
    public function actionList(){
        $query=Article::find();
        //分页工具类
        $pager=new Pagination();
        //总条数
        $pager->totalCount=$query->count();
        $pager->pageSize=2;//每业显示2条
        //查询一页的数据
        $models=$query->where(['status'=>1])->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list',['models'=>$models,'pager'=>$pager]);


    }
    //删除文章
    public function actionDelete(){
        $request = new Request();
        $id = $request->post('id');
        $model= Article::findOne(['id'=>$id]);
        //隐藏该文章
        $model->status=-1;
        $model->save(false);//保存数据
        //跳转
        return 'yes';

    }

}