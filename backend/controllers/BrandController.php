<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3 0003
 * Time: 13:13
 */

namespace backend\controllers;


use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller

{
    //添加品牌
    public function actionAdd(){

        $model=new Brand();
        $request=new Request();
        if ($request->isPost){
            //接受数据
            $model->load($request->post());

            //将上上传文件分装成uploadefile对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');

            if ($model->validate()){
                //通过验证
                //设置文件保存路径
                $ext=$model->imgFile->extension;//后缀
                $file='/upload/brand/'.uniqid().'.'.$ext;
                //保存文件
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
                //保存路径到数据库
                $model->logo=$file;
                $model->save(false);//保存数据
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['list']);
            }

        }
        return $this->render('add',['model'=>$model]);


    }
    //分页列表
    public function actionList(){
        //得到全部数据
        $query=Brand::find();
        //分页工具类
        $pager=new Pagination();
        //总条数
        $pager->totalCount=$query->count();
        $pager->pageSize=2;//每业显示2条
        //查询一页的数据
        $models=$query->where(['status'=>1])->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list',['models'=>$models,'pager'=>$pager]);

    }
    //删除品牌
    public function actionDelete(){
        $request = new Request();
        $id = $request->post('id');
       $model= Brand::findOne(['id'=>$id]);
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
    //修改品牌
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=new Request();
        if ($request->isPost){
            //接受数据
            $model->load($request->post());
            //将上上传文件分装成uploadefile对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if ($model->validate()){
                //通过验证
                //设置文件保存路径
                $ext=$model->imgFile->extension;//后缀
                $file='/upload/brand/'.uniqid().'.'.$ext;
                //保存文件
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
                //保存路径到数据库
                $model->logo=$file;
                $model->save(false);//保存数据
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['list']);
            }

        }
        return $this->render('add',['model'=>$model]);

    }
}