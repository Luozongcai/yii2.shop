<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/5 0005
 * Time: 16:36
 */

namespace backend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends Controller
{

    public function  behaviors(){
        return [
            'rbac' => [
                'class' =>\backend\filters\RnewFilter::className(),

            ],
        ];

    }
    public $enableCsrfValidation=false;
    //添加商品分类
    public function actionCategoryAdd()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $model = new GoodsCategory();
        //parent_id设置默认值
        $model->parent_id = 0;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->parent_id == 0) {
                    //创建根节点
                    $model->makeRoot();

                    $redis->del('goods-category');
                    return $this->redirect(['category-list']);
                } else {
                    //添加子节点
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($parent);
                    $redis->del('goods-category');
                    return $this->redirect(['category-list']);
                }

            }
        }

        return $this->render('category-add', ['model' => $model]);
    }

    //展示商品分类
    public function actionCategoryList()
    {
        //得到全部数据
        $query = GoodsCategory::find();
        //分页工具类
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->count();
        $pager->pageSize = 10;//每业显示2条
        //查询一页的数据
        $models = $query->orderBy('tree ASC', 'lft ASC')->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('category-list', ['models' => $models, 'pager' => $pager]);
    }

    //删除商品分类
    public function actionCategoryDelete()
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1');

        $request = new Request();
        $id = $request->post('id');
        //如果有子节点则不能删除isleaf代表是叶子无子节点
        $son = GoodsCategory::find()->where(['parent_id' => $id])->all();
        if ($son) {
            return '该节点下有子节点,不能删除!';
        } else {
            $model = GoodsCategory::findOne(['id' => $id]);
            $model->deletewithchildren();//删除空节点
            $redis->del('goods-category');
            return 'yes';
        }
    }

    //修改商品分类
    public function actionCategoryEdit($id)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1');

        $model = GoodsCategory::findOne(['id' => $id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->parent_id == 0) {
                    //创建根节点
                    if ($model->getOldAttribute('parent_id') == 0) {
                        //修改根节点
                        $model->save();
                    } else {
                        $model->makeRoot();
                    }
                    $redis->del('goods-category');
                    return $this->redirect(['category-list']);
                } else {
                    //添加子节点
                    $parent = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($parent);
                    $redis->del('goods-category');
                    return $this->redirect(['category-list']);
                }
            }
        }
        return $this->render('category-add', ['model' => $model]);
    }

    //添加商品
    public function actionAdd()
    {
        $model = new Goods();
        //给goods_category_id附一个默认值
        $model->goods_category_id =0;
        $model2 = new GoodsIntro();
        $new_day=new GoodsDayCount();
        $request = new Request();
            if ($request->isPost) {
            //接受数据
                $model->load($request->post());
                //保存goods表数据
            if ($model->validate()) {
                //通过验证
                 $model->create_time=time();
                //判断已经存在当天的添加记录保存goods_day_count表
                $day=GoodsDayCount::findone(['day'=>date('Ymd',time())]);
                if($day){
                    //如果存在day则count+1
                    $day->count =($day->count)+1;
                    $day->save();
                   }else{
                    $new_day->day = date('Ymd',time());
                    $new_day->count =1;
                    $new_day->save();
                }

                //添加货号
                $day=GoodsDayCount::findone(['day'=>date('Ymd',time())]);
                $str=$day->count;
                $model->sn=date('Ymd',time()).sprintf("%05d",$str);
                $model->save();//保存数据
            }
            $id = $model->id;//获取刚保存的id


            //保存goods-intro表数据
            $model2->load($request->post());
            if ($model2->validate()) {
                //通过验证
                $model2->goods_id = $id;
                $model2->save();//保存数据
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', ['model' => $model, 'model2' => $model2]);


    }
    //商品列表
    public function actionList(){

        //接受查询条件
        $name=\Yii::$app->request->get('name');
        $sn=\Yii::$app->request->get('sn');
        $down=\Yii::$app->request->get('down');
        $top=\Yii::$app->request->get('top');
        $query=Goods::find()->where(['status'=>1]);
        if ($name){
            $query->andWhere(['like', 'name', $name]);
        }
        if ($sn){
            $query->andWhere(['like', 'sn', $sn]);
        }
        if ($down){
            //价格下限
            $query->andWhere(['>', 'market_price', $down]);
        }
        if ($top){
            //价格上限
            $query->andWhere(['<', 'market_price', $top]);
        }

        //分页工具类
        $pager=new Pagination();
        //总条数
        $pager->totalCount=$query->count();
        $pager->pageSize=5;//每业显示5条
        //查询一页的数据
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list',['models'=>$models,'pager'=>$pager,'name'=>$name,'sn'=>$sn,'down'=>$down,'top'=>$top]);
    }
    //删除商品
    public function  actionDelete(){
        $request = new Request();
        $id = $request->post('id');
           $model= Goods::findOne(['id'=>$id]);
           $model->status=0;
           $model->save(false);
                return 'yes';
        }
    //修改商品
    public function actionEdit($id){
        $model= Goods::findOne(['id'=>$id]);
        $model2 = GoodsIntro::findOne(['goods_id'=>$id]);
        $request = new Request();
        if ($request->isPost) {
            //接受数据
            $model->load($request->post());

            //保存goods表数据
            if ($model->validate()) {
                //通过验证
                $model->create_time=time();
                $model->save(false);//保存数据
            }

            //保存goods-intro表数据
            $model2->load($request->post());
            if ($model2->validate()) {
                //通过验证
                $model2->goods_id=$id;
                $model2->save(false);//保存数据
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', ['model' => $model, 'model2' => $model2]);

    }
    //相册列表
    public function actionGallery($id){
       $models=GoodsGallery::findAll(['goods_id'=>$id]);
        return $this->render('gallery',['models'=>$models,'id'=>$id]);

    }
    //删除相册图片
    public  function actionGalleryDelete(){
        $request = new Request();
        $id = $request->post('id');
        $model= GoodsGallery::findOne(['id'=>$id]);
        $model->delete();
        return 'yes';
    }
    //ajax添加相册图片
    public function actionUpload1($id)
    {
        if (\Yii::$app->request->isPost) {
            $imgFile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if ($imgFile) {
                $fileName = '/upload/gallery/' . uniqid() . '.' . $imgFile->extension;
                $imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, 0);
            //添加的图片保存数据库
                $model =new  GoodsGallery() ;
                $model->goods_id=$id;
                $model->path=$fileName;
                $model->save();
                //返回图片路径
                return Json::encode(['url' => $fileName]);
            }
        }
    }
    //添加商品图片
    public function actionUpload2()
    {
        if (\Yii::$app->request->isPost) {
            $imgFile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if ($imgFile) {
                $fileName = '/upload/goods/' . uniqid() . '.' . $imgFile->extension;
                $imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, 0);
                return Json::encode(['url' => $fileName]);
            }
        }
    }

    //预览详情
    public function actionPreview($id){
        //得到全部数据
       $model=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('preview',['model'=>$model]);

    }
    //富文本图片上传
    public function actions()
    {
    return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    //"imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
                 "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
               "imageRoot" => \Yii::getAlias("@webroot"),
            ],
        ]
    ];

    }
    public function actionTest(){
        //新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001
        $str=date('Ymd',time());//得到年月日
        $number = 78 ;//sprintf("%05d",$number)得到5位数
        var_dump($str.sprintf("%05d",$number));
    }
}
