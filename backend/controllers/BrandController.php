<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3 0003
 * Time: 13:13
 */

namespace backend\controllers;


use backend\models\Brand;
use backend\models\Goods;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use backend\controllers\CommonController;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller

{

  public function  behaviors(){
        return [
            'rbac' => [
                'class' =>\backend\filters\RnewFilter::className(),

                    ],
        ];

    }
    public $enableCsrfValidation=false;
    //添加品牌
    public function actionAdd(){

        $model=new Brand();
        $request=new Request();
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
        return $this->render('add',['model'=>$model]);


    }
    //分页列表
    public function actionList(){
        //得到全部数据
        $query=Brand::find()->where(['status'=>1]);
        //分页工具类
        $pager=new Pagination();
        //总条数
        $pager->totalCount=$query->count();
        $pager->pageSize=5;//每业显示2条
        //查询一页的数据
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list',['models'=>$models,'pager'=>$pager]);

    }
    //删除品牌
    public function actionDelete(){


        $request = new Request();
        $id = $request->post('id');
        //根据id查询分组是否有组员
        $goods = Goods::findOne(['goods_category_id'=>$id]);
        if($goods){
            return '该品牌下有商品,不能删除!';
        }else{

            $model= Brand::findOne(['id'=>$id]);
            $model->status=-1;
            $model->save(false);//保存数
            //删除该记录对应
            return 'yes';
        }

    }
    //修改品牌
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=new Request();
        if ($request->isPost){
            //接受数据
            $model->load($request->post());

            if ($model->validate()){
                //通过验证

                $model->save(false);//保存数据
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['list']);
            }

        }
        return $this->render('add',['model'=>$model]);

    }

    //普通ajax上传
    public function actionUpload1()
    {
        if (\Yii::$app->request->isPost) {
            $imgFile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if ($imgFile) {
                $fileName = '/upload/brand/' . uniqid() . '.' . $imgFile->extension;
                $imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, 0);
                return Json::encode(['url' => $fileName]);
            }
        }
    }
    //ajax图片上传到七牛云
    public function actionUpload(){
        if(\Yii::$app->request->isPost) {
            $imgFile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if ($imgFile) {
                $fileName = '/upload/brand/' . uniqid() . '.' . $imgFile->extension;
                $imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, 0);

                //将图片上传到七牛云
                $accessKey = "mJ2OAqOJnx1BAFI2LO6Hsmhs5zFji5KsnVfZopNw";
                $secretKey = "O5ErgT9MLNvsd3TnUMv9bRQLTQwTplC7ayXfiLCq";
                //对象存储 空间名称
                $bucket = "luozong";
                $domian = 'oyxkp4m6t.bkt.clouddn.com';
                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);
                // 生成上传 Token
                $token = $auth->uploadToken($bucket);
                // 要上传文件的本地路径
                $filePath = \Yii::getAlias('@webroot') . $fileName;
                // 上传到七牛后保存的文件名
                $key = $fileName;
                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();
                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
                if ($err !== null) {
                    //上传失败
                    return Json::encode(['error' => $err]);
                } else {
                    //  上传成功返回图片访问路径
                    return Json::encode(['url' => 'http://' . $domian . '/' . $fileName]);
                }

            }
        }
    }
}